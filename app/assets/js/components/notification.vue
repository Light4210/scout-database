<template>
  <div class="notifications">
    <transition-group name="fade">
      <div :class=notification.color class="notification" v-for="notification in notifications"
           :key="notification.title">
        <div class="title-message">
          <div class="title">
            <p>{{ notification.title }}</p>
          </div>
          <div class="message">
            <p>{{ notification.message }}</p>
          </div>
        </div>
      </div>
    </transition-group>
  </div>
</template>

<script>
import {eventBus} from "../general";

export default {
  name: "notification",
  data() {
    return {
      notifications: [],
      existingTime: 2000,
      types: {'error': 'red', 'warning': 'yellow', 'success': 'green', 'info': 'blue'},
      error: 'error',
      warning: 'warning',
      success: 'success',
      info: 'info'
    }
  },
  methods: {
    notification: function (title, message, notificationType) {
      let typeColor = this.types[notificationType]
      return {
        title: title,
        message: message,
        color: typeColor
      };
    },
  },
  mounted() {
    eventBus.$on('notify', data => {
      let notification = this.notification(data.title, data.message, data.notificationType);
      this.notifications.push(notification)
      setTimeout(function () {
        this.notifications.splice(0, 1)
      }.bind(this), this.existingTime)
    })
  }
}
</script>

<style scoped lang="scss">
.notifications {
  position: absolute;
}
</style>