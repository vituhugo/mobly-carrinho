import Carrinho from "./../classes/Carrinho";
import Notify from "./../classes/Notify";
import Utils from "./../classes/Utils";
import Storage from '../servicos/Storage';
import Logistica from "../servicos/Logistica";
export default {
    el: '#app-carrinho',

    data() {
        console.log(Carrinho.items());
        return {
            carrinho: Carrinho.items(),

            total: 0,
            frete: 0,
            prazo: false,
            cep: null,
        }
    },

    methods: {

        formatarPreco: Utils.formatarPreco,

        finalizarCompra (e) {

            if (!this.carrinho.length) {
                Notify.danger('Seu carrinho está vazio.');
                e.preventDefault();
            }

            if (!this.frete) {
                Notify.danger('É necessário calcular o frete primeiro.');
                e.preventDefault();
            }
        },

        atualizarProduto (produto, quantidade) {
            Carrinho.atualizarProduto(produto, quantidade);

            this.carrinho = Carrinho.items();

            this.refreshTotal()
        },

        refreshTotal () {
            let total = 0;

            Carrinho.items().forEach(produto => {
                total += produto.preco * produto.quantidade;
            });

            this.total = total + this.frete;
        }
    },

    watch: {
        cep () {
            let cep = this.cep.replace(/[^0-9]/g,'')

            if (cep.length === 8) {
                Storage.set('cep', cep);
                Logistica.calcularFrete(cep, this.carrinho).then((data) => {
                    this.frete = data.valor;
                    this.prazo = data.prazo;

                    this.refreshTotal ()
                }).catch((e) => console.error(e));
            }
        }
    },

    mounted() {
        this.refreshTotal();
    },

    components: {}
}