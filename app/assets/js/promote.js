import Vue from 'vue';
import promote from './components/promote'

let promoteModal = new Vue({
    el: '#promote-modal',
    components: {promote},
});

let callModalBtn = document.getElementById('call-modal-promote')
callModalBtn.addEventListener('click', promote.display, false)