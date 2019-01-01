import Notify from "../classes/Notify";
import Storage from "../servicos/Storage";

export default {
    el: '#app-header',

    data() {
        return {
            usuario: Storage.get('usuario', null)
        }
    },

    methods: {
        logout (e) {
            e.preventDefault();

            Storage.remove('access_token');
            Storage.remove('usuario');

            Notify.store("Você deslogou do sistema.", "warning");
            window.location.href = "/";
        }
    }
}