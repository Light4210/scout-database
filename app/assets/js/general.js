// assets/js/app.js
import Vue from 'vue';
import Example from './components/Example'
/**
 * Create a fresh Vue Application instance
 */
var app = new Vue({
    delimiters: ['{', '}'],
    el: '#app',
    data: {
        product: "Socks"
    }
});