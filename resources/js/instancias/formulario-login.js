import Notify from "../classes/Notify";
import Api from "../servicos/Api";
import Storage from "../servicos/Storage";
export default {
    el: '#app-entrar',

    data() {
        return {
            input: {
                email: null,
                senha: null,
            }
        }
    },

    methods: {
        onSubmit (e) {  e.preventDefault();
            Api.token(this.input.email, this.input.senha).then(token => {
                Storage.set('token', token);

                Api.perfil(token.access_token).then(data => {
                    Storage.set('usuario', data);

                    Notify.store('Login realizado com sucesso!', 'success');
                    window.location.href = "/";

                }).catch(Notify.danger);

            }).catch(Notify.danger)
        },
    },

    mounted() {

    },

    components: {}
}