export default {
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
                numero: null
            }
        }
    },

    methods: {
        onSubmit (e) {
            e.preventDefault();

            if (!this.validaForm(this.input)) return;

            let params = this.formataForm(this.input);

            window.axios.post('api/registrar', params).then((response) => {

                if (response.status !== 201) {
                    alert(response.data.message);
                }

                alert('Cadastro realizado com sucesso!');
                sessionStorage.setItem('access_token', response.data.access_token);
                sessionStorage.setItem('usuario', JSON.stringify(response.data.user));
                window.location.href = "/";
            }).catch(function(response) {
                alert(response.response.data.message);
            });
        },

        validaForm(form) {
            let error = [];

            if (form.nome.trim().match(new RegExp(" +", 'g')).length === 0) {
                error.push("Digite o nome completo.");
            }

            if (form.senha.length < 6) {
                error.push("A senha precisa de no mínimo 6 digitos.");
            }

            if (form.cep.replace(/[^0-9]/g,'').length !== 8) {
                error.push("Cep inválido.")
            }

            if ([10,11].indexOf(form.telefone.replace(/[^0-9]/g,'').length) === -1) {
                error.push("telefone, informar o DDD")
            }

            if (error.length) {
                alert("Foram encontrados os seguintes erros: " + error.join("\r\n"));
                return false;
            }

            return true;
        },

        formataForm(form) {
            return {
                nome: form.nome,
                email: form.email,
                senha: form.senha,
                cep: form.cep.replace(/[^0-9]/g, ''),
                endereco: form.endereco + ", " + form.numero,
                telefone: form.telefone.replace(/[^0-9]/g, ''),
            }
        },

        buscarCep() {
            if (this.input.cep.replace(/[^0-9]/g, '').length !== 8) return;

            window.axios.get('/api/busca-cep/'+this.input.cep.replace(/[^0-9]/g, '')).then((response) => {
                if (!response.data.logradouro) {
                    alert("Cep inválido.")
                    this.input.cep = null;
                    return;
                }

                this.input.endereco = response.data.logradouro;
            });
        }
    },
}