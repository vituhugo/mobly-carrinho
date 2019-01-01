import Utils from "../classes/Utils";
import Notify from "../classes/Notify";
import Carrinho from "../classes/Carrinho";
import Api from "../servicos/Api";
import Storage from "../servicos/Storage";
import Logistica from "../servicos/Logistica";

export default {
    el: '#app-finalizar',

    data() {
        let input = {
            nome: null,
            email: null,
            telefone: null,
            endereco: null,
            numero: null,
            cep: Storage.get('cep'),
            entrega_endereco: null,
            entrega_numero: null,
            entrega_cep: Storage.get('cep'),
        };

        input.entrega_cep = Storage.get('cep');

        if (Storage.has('usuario')) {
            let usuario = Storage.get('usuario');

            input.nome = usuario.nome;
            input.email = usuario.email;
            input.telefone = usuario.telefone.replace(/[^0-9]/g,'');
            input.endereco = usuario.endereco.split(",")[0].trim();
            input.numero = usuario.endereco.split(", ")[1].trim();
            input.cep = usuario.cep.replace(/[^0-9]/g,'');
        }

        input.entrega_endereco = null;
        input.entrega_numero = null;

        if (input.cep === Storage.has('cep')) {
            input.entrega_endereco = input.cep === Storage.get('cep') ? input.endereco : null;
            input.entrega_numero = input.cep === Storage.get('cep') ? input.numero : null;
        }

        return {
            frete: null,
            esta_logado: Storage.has('usuario'),
            carrinho: Carrinho.items(),
            total: 0,
            cep: Storage.get('cep'),
            mesmo_endereco: input.cep === Storage.get('cep'),
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
                Storage.set('cep', cep);

                Logistica.calcularFrete(cep, this.carrinho).then((data) => {
                    this.frete = data.valor;
                    this.prazo = data.prazo;
                })
            }
        },

        formatarPreco: Utils.formatarPreco,

        finalizarCompra (e) {
            e.preventDefault();

            if (!this.frete) {
                Notify.warning('Antes de finalizar, é necessário calcular o frete.');
                return false;
            }

            let params =  {
                carrinho: this.carrinho,
                cliente: this.input,
                entrega: {
                    cep: this.mesmo_endereco ? this.input.cep : this.input.entrega_cep,
                    endereco: this.mesmo_endereco
                        ? this.input.endereco + ", " + this.input.numero
                        : this.input.entrega_endereco +", "+ this.input.entrega_numero
                }
            };

            if (this.esta_logado) {
                params.token = Storage.get('token').access_token;
            }

            Api.criarOrdem(params).then(data=> {
                Notify.store('Ordem criada com o id: ' + data.ordem_id, 'success');
                Carrinho.clear();
                window.location.href = '/';
            });
        },

        buscarCep() {
            Api.buscarCep(this.input.entrega_cep).then((data) => {
                if (!data.logradouro) {
                    Notify.warning("Cep inválido.");
                    this.input.entrega_cep = null;
                    return;
                }

                this.input.entrega_endereco = data.logradouro;
            });
        },

        buscarCepUsuario() {
            Api.buscarCep(this.input.cep).then((data) => {
                if (!data.logradouro) {
                    Notify.warning("Cep inválido.");
                    this.input.cep = null;
                    return;
                }

                this.input.endereco = data.logradouro;
            });
        },

        resetCamposEntrega() {
            this.input.entrega_cep = this.input.cep;
            this.input.entrega_endereco = this.input.endereco;
            this.input.entrega_numero = this.input.numero;
        }
    },

    mounted() {
        this.refreshTotal();

        if (this.input.cep) {
            this.buscarCepUsuario()
        }

        if (this.input.entrega_cep) {
            this.buscarCep();
        }
    },

    components: {}
}