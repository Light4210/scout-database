import Vue from "vue";
import notification from './components/notification'

export const eventBus = new Vue(); // creating an event bus.

new Vue({
    el: '#alert',
    components: {notification},
});

var toastAlert = function (title, message, notificationType) {
    eventBus.$emit('notify', {
        title,
        message,
        notificationType,
    })
}

export function createNotificationType(changeableElementId, event,title, message, type){
    let element = document.getElementById(changeableElementId)
    if (element) {
        element.addEventListener(event, function (){
            toastAlert(title, message, type);
        })
    } else {
        console.warn('Field with id: "' + changeableElementId + '" was not found')
    }
}

let deleteBtn = document.getElementsByClassName('delete')

for (const btn of deleteBtn) {
    btn.addEventListener('click', function (e){
        if( ! confirm("Do you really want to delete ?") ){
            e.preventDefault(); // ! => don't want to do this
        }
    })
}