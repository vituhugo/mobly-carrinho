import Storage from "../servicos/Storage";

if (!Storage.get('notifies')) {
    Storage.set('notifies', []);
}

export default {
    dispatch() {
        let notifies = Storage.get('notifies');

        notifies.forEach(notify => {
            this.send(notify.message, {type: notify.type});
        });

        Storage.set('notifies', []);
    },

    warning (message) {
        return $.notify(message,{type: 'warning'});
    },

    success (message) {
        return $.notify(message,{type: 'success'});
    },

    info (message) {
        return $.notify(message,{type: 'info'});
    },

    danger (message) {
        return $.notify(message,{type: 'danger'});
    },

    send (message, settings) {
        return $.notify({message},settings);
    },

    store(message, type) {
        Storage.push('notifies', {message, type});
    }
}