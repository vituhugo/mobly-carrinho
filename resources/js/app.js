
/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

require('./bootstrap');

window.Vue = require('vue');

/**
 * The following block of code may be used to automatically register your
 * Vue components. It will recursively scan this directory for the Vue
 * components and automatically register them with their "basename".
 *
 * Eg. ./components/ExampleComponent.vue -> <example-component></example-component>
 */

// const files = require.context('./', true, /\.vue$/i)
// files.keys().map(key => Vue.component(key.split('/').pop().split('.')[0], files(key).default))

/**
 * Next, we will create a fresh Vue application instance and attach it to
 * the page. Then, you may begin adding components to this application
 * or customize the JavaScript scaffolding to fit your unique needs.
 */
if (document.getElementById('app')) {
    const app = new Vue({
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
    });
}

if (document.getElementById('app-cadastro')) {
    const app_cadastro = new Vue({
        el: '#app-cadastro',

        data () {
            return {
                input: {
                    nome: null,
                    email: null,
                    senha: null,
                    cep: null,
                    endereco: null,
                    telefone: null,
                }
            }
        },

        methods: {
            onSubmit (e) {
                e.preventDefault();
                let params = this.input;

                window.axios.post('api/registrar', params).then((response) => {

                    if (response.status !== 201) {
                        alert(response.data.message);
                    }

                    alert('Cadastro realizado com sucesso!');
                    sessionStorage.setItem('access_token', response.data.access_token);
                    sessionStorage.setItem('usuario', JSON.stringify(response.data.user));
                    window.location.href = "/";
                }).catch(function(response) {
                    alert(response);
                });
            },
        },

        mounted() {

        },

        components: {

        }
    });
}

const app_header = new Vue({
    el: '#app-header',

    data() {
        return {
            usuario: sessionStorage.getItem('usuario') ? JSON.parse(sessionStorage.getItem('usuario')) : null
        }
    },

    methods: {
        logout (e) {
            e.preventDefault();
            sessionStorage.removeItem('access_token');
            sessionStorage.removeItem('usuario');

            window.location.href = "/";
        }
    }
})

if (document.getElementById('app-entrar')) {
    const app_entrar = new Vue({
        el: '#app-entrar',

        data() {
            return {
                input: {
                    nome: null,
                    senha: null,
                }
            }
        },

        methods: {
            onSubmit (e) {
                e.preventDefault();

                let params = this.input;

                window.axios.post('api/auth/get-token', params).then((response) => {

                    if (response.status !== 200) {
                        return alert(response.data.message);
                    }

                    sessionStorage.setItem('access_token', response.data.access_token);

                    window.axios.get('api/clientes', {params: {token: sessionStorage.getItem('access_token')}}).then(function(response) {
                        sessionStorage.setItem('usuario', JSON.stringify(response.data));

                        alert('Login realizado com sucesso!');
                        window.location.href = "/";
                    }).catch(function(response) {
                        alert(response);
                    });
                });
            },
        },

        mounted() {

        },

        components: {}
    });
}

if (document.getElementById('app-carrinho')) {
    const app_carrinho = new Vue({
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

                    let cep = apenasNumeros(this.cep);

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
                let cep = apenasNumeros(this.cep)

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
    });
}

if (document.getElementById('app-finalizar')) {
    const app_finalizar = new Vue({
        el: '#app-finalizar',

        data() {
            let input = {
                nome: null,
                email: null,
                telefone: null,
                endereco: null,
                cep: sessionStorage.getItem('cep'),
                entrega_endereco: null,
                entrega_cep: sessionStorage.getItem('cep'),
            };

            input.entrega_cep = sessionStorage.getItem('cep');

            if (sessionStorage.getItem('usuario')) {
                let usuario = JSON.parse(sessionStorage.getItem('usuario'));
                input.nome = usuario.nome;
                input.email = usuario.email;
                input.telefone = usuario.telefone;
                input.endereco = usuario.endereco;
                input.cep = apenasNumeros(usuario.cep);
            }

            input.entrega_endereco = input.cep == sessionStorage.getItem('cep') ? input.endereco : null;
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

                let cep = apenasNumeros(this.mesmo_endereco ? this.input.cep : this.entrega_cep);

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
        },

        mounted() {
            this.refreshTotal();
        },

        components: {}
    });
}

function apenasNumeros(string) {
    string = ""+string;

    const pattern = /\d+/g;

    let result = string.match( pattern );

    if (result) {
        result = result.join('');
    }

    return ""+result;
}