export default {
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
                    alert(response.response.data.message);
                });

            }).catch(function(response) {
                alert(response.response.data.message);
            });
        },
    },

    mounted() {

    },

    components: {}
}