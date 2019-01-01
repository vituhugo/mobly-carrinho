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

            if (produto.estoque < produto.quantidade) {
                return alert('quantidade desejada ultrapassa o limite do estoque');
            }

            if (produto.quantidade === 0) {
                return alert('É necessário adicionar pelo menos 1 item.');
            }

            let carrinho = window.sessionStorage.getItem('carrinho') || '[]';
            carrinho = JSON.parse(carrinho);

            let prod = carrinho.find((p) => p.id == produto.id);

            if (!prod) {
                carrinho.push(produto);
            } else {
                prod.quantidade = +prod.quantidade + (+produto.quantidade);
            }

            window.sessionStorage.setItem('carrinho', JSON.stringify(carrinho));

            alert('O Produto foi adicionado ao carrinho!');
        },

        formatarPreco (valor) {
            let val = (valor/1).toFixed(2).replace('.', ',')
            return val.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".")
        },

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