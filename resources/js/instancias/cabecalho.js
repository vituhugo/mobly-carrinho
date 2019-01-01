export default {
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
}