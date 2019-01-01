export default {

    get(key, defaultValue) {
        let item = sessionStorage.getItem(key);
        if (!item) return defaultValue;
        return JSON.parse(item);
    },

    set(key, value) {
        sessionStorage.setItem(key, JSON.stringify(value));
    },

    push(key, value) {

        let item = JSON.parse(sessionStorage.getItem(key));

        if (!item instanceof Array) {
            console.error("O item que está tentando dar push não é um array.", e);
            return;
        }

        item.push(value);

        this.set(key, item);
    },

    remove(key) {
        sessionStorage.removeItem(key);
    },

    has(key) {
        return !!sessionStorage.getItem(key);
    }
}