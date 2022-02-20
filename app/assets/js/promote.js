import Vue from 'vue/dist/vue.common.js';
import promoteBtn from "./components/promoteBtn";
import promoteModal from "./components/promoteModal";

new Vue({
    el: '#promotion',
    components: {promoteModal, promoteBtn},
    data() {
        return {
            displayModal: false,
        }
    },
});