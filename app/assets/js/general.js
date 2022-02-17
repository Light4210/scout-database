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

function createNotificationType(changeableElementId, event,title, message, type){
    let element = document.getElementById(changeableElementId)
    if (element) {
        element.addEventListener(event, function (){
            console.log(message)
            alert(title, message, type);
        })
    }
}
createNotificationType('user_edit_dealScan', 'change', 'Success', 'Deal scan was successfully uploaded', notification.data().success)




