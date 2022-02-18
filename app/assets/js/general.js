import Vue from "vue";
import notification from './components/notification'

export const eventBus = new Vue(); // creating an event bus.

new Vue({
    el: '#alert',
    components: {notification},
});

var alert = function (title, message, notificationType) {
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
            alert(title, message, type);
        })
    } else {
        console.warn('Field with id: "' + changeableElementId + '" was not found')
    }
}






