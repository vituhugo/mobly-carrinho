import Utils from "../classes/Utils";
import Carrinho from "../classes/Carrinho";
import Notify from "../classes/Notify";

export default {
    el: '#app',
        data () {
    return {
        busca: null,
        ipp: null,
        order_by: null,
        order_type: null,

        produtos: [],
        pagina_atual: 1,
        paginate_info: {},

        input: {
            busca: null,
            itens_por_pagina: 10,
            ordenado_por: 'nome',
            ordenado_tipo: 'asc'
        }
    }
},
    methods: {

        addCarrinho (produto) {
            return Carrinho.addCarrinho(produto)
        },

        formatarPreco: Utils.formatarPreco,

        atualizarProdutos () {

            let params = {
                page: this.pagina_atual,
                q: this.busca,
                ipp: this.ipp,
                order_by: this.order_by,
                order_type: this.order_type
            };

            window.axios.get("/api/produto", {params}).then((response) => {
                this.paginate_info = response.data;
                this.produtos = response.data.data;

                console.log("response:", response);
            });
        },

        atualizarOrdenacao () {
            this.order_by = this.input.ordenado_por;
            this.order_type = this.input.ordenado_tipo;
            this.ipp = this.input.itens_por_pagina;

            this.atualizarProdutos();
        }
    },

    mounted() {
        this.atualizarProdutos();
        Notify.dispatch();
    },

    watch: {
        pagina_atual () {
            this.atualizarProdutos();
        },

        busca () {
            this.atualizarProdutos();
        }
    },
    components: {

    }
}