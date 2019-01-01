export default {
    el: '#app-carrinho',

    data() {
        let carrinho = sessionStorage.getItem('carrinho');
        if (!carrinho) carrinho = '[]';
        return {
            frete: 0,
            prazo: false,
            carrinho: JSON.parse(carrinho),
            total: 0,
            cep: null,
        }
    },

    methods: {

        formatarPreco (valor) {
            let val = (valor/1).toFixed(2).replace('.', ',')
            return val.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".")
        },

        finalizarCompra (e) {
            if (!this.carrinho.length) {
                alert('Seu carrinho está vazio.');
            }
            if (!this.frete) {
                alert('Por favor primeiro calcule o Frete.');
                e.preventDefault();
            }
        },

        refreshTotal (refreshCarrinho) {
            let total = 0;

            this.carrinho.forEach((produto) => {
                total += produto.preco * produto.quantidade;
            });

            this.total = total;

            if (refreshCarrinho) {
                this.carrinho = this.carrinho.filter(p => +p.quantidade);
                console.log("CARRINHO: ", this.carrinho);
                sessionStorage.setItem('carrinho', JSON.stringify(this.carrinho));

                let cep = this.cep.replace(/[^0-9]/g,'');

                if (cep.length === 8) {
                    sessionStorage.setItem('cep', cep);
                    window.axios.post('/api/frete', {cep: cep, carrinho: this.carrinho}).then((response) => {
                        this.frete = response.data.valor;
                        this.prazo = response.data.prazo;
                    })
                }
            }
        }
    },

    watch: {
        cep () {
            let cep = this.cep.replace(/[^0-9]/g,'')

            if (cep.length === 8) {
                sessionStorage.setItem('cep', cep);
                window.axios.post('/api/frete', {cep: cep, carrinho: this.carrinho}).then((response) => {
                    this.frete = response.data.valor;
                    this.prazo = response.data.prazo;
                })
            }
        }
    },

    mounted() {
    this.refreshTotal();
},

    components: {}
}