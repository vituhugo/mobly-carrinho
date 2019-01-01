
/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

require('./bootstrap');

require('bootstrap-notify');

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

[

    require("./instancias/cabecalho").default,
    require('./instancias/carrinho').default,
    require('./instancias/formulario-cadastro').default,
    require('./instancias/formulario-login').default,
    require('./instancias/home').default,
    require('./instancias/finalizar').default

].forEach(function(instancia) {
    if (document.querySelector(instancia.el)) {
        new Vue(instancia);
    }
});