export default {
    el: '#app-finalizar',

    data() {
        let input = {
            nome: null,
            email: null,
            telefone: null,
            endereco: null,
            numero: null,
            cep: sessionStorage.getItem('cep'),
            entrega_endereco: null,
            entrega_numero: null,
            entrega_cep: sessionStorage.getItem('cep'),
        };

        input.entrega_cep = sessionStorage.getItem('cep');

        if (sessionStorage.getItem('usuario')) {
            let usuario = JSON.parse(sessionStorage.getItem('usuario'));
            input.nome = usuario.nome;
            input.email = usuario.email;
            input.telefone = usuario.telefone.replace(/[^0-9]/g,'');
            input.endereco = usuario.endereco.split(",")[0].trim();
            input.numero = usuario.endereco.split(", ")[1].trim();
            input.cep = usuario.cep.replace(/[^0-9]/g,'');
        }

        input.entrega_endereco = input.cep == sessionStorage.getItem('cep') ? input.endereco : null;
        input.entrega_numero = input.cep == sessionStorage.getItem('cep') ? input.numero : null;

        return {
            frete: null,
            esta_logado: !!sessionStorage.getItem('usuario'),
            carrinho: JSON.parse(sessionStorage.getItem('carrinho')),
            total: 0,
            cep: sessionStorage.getItem('cep'),
            mesmo_endereco: input.cep == sessionStorage.getItem('cep'),
            input: input
        }
    },

    methods: {

        refreshTotal () {
            let total = 0;

            this.carrinho.forEach((produto) => {
                total += produto.preco * produto.quantidade;
            });

            this.total = total;

            let cep = (this.mesmo_endereco ? this.input.cep : this.entrega_cep).replace(/[^0-9]/g,'');

            if (cep.length === 8) {
                sessionStorage.setItem('cep', cep);
                window.axios.post('/api/frete', {cep: cep, carrinho: this.carrinho}).then((response) => {
                    this.frete = response.data.valor;
                    this.prazo = response.data.prazo;
                })
            }
        },

        formatarPreco (valor) {
            let val = (valor/1).toFixed(2).replace('.', ',')
            return val.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".")
        },

        finalizarCompra (e) {
            e.preventDefault();

            if (!this.frete) {
                alert('Por favor primeiro calcule o Frete.');
                return false;
            }

            let params =  {
                carrinho: this.carrinho,
                cliente: this.input,
                entrega: {
                    cep: this.input.entrega_cep,
                    endereco: this.input.entrega_endereco
                }
            };

            if (this.esta_logado) {
                params.token = sessionStorage.getItem('access_token');
            }

            window.axios.post('api/ordem', params).then((response) => {
                if (response.status !== 201) {
                    return alert(response.data.message);
                }

                alert('Ordem criada com o id: ' + response.data.ordem_id);
                sessionStorage.removeItem('carrinho');
                window.location.href = '/';
            });
        },

        buscarCep() {
            window.axios.get('/api/busca-cep/'+this.input.entrega_cep.replace(/[^0-9]/g, '')).then((response) => {
                if (!response.data.logradouro) {
                    alert("Cep inválido.")
                    this.input.entrega_cep = null;
                    return;
                }

                this.input.entrega_endereco = response.data.logradouro;
            });
        },
        resetCamposEntrega() {
            this.input.entrega_cep = this.input.cep;
            this.input.entrega_endereco = this.input.endereco;
            this.input.numero = this.input.numero;
        }
    },

    mounted() {
        this.refreshTotal();
    },

    components: {}
}