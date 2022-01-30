<template>
  <transition name="fade">
    <div class="modal-mask" v-if="this.$root.$data.displayModal===1">
      <div class="modal-content">
        <div class="select-promote">
          <div @click="closeModal" class="crossbar">
            <div class="cross-1"></div>
            <div class="cross-2"></div>
          </div>
          <select required @change="selectedStructId = $event.target.value">
            <option value="" selected>Select new user struct</option>
            <option v-for="struct in structs" :value="struct.id">
              {{ struct.name }}
            </option>
          </select>
          <button type="button" @click="submitPromotion">Promote</button>
        </div>
        <div class="modal-text" v-if="this.modalText !== ''">
          <p>{{ this.modalText }}</p>
        </div>
      </div>

    </div>
  </transition>
</template>

<script>
import axios from "axios";

export default {
  name: "promoteModal",
  data() {
    return {
      selectedStructId: '',
      structs: "Select new struct for user",
      targetUserUd: window.targetUserId,
      successText: 'success',
      errorText: 'error',
      modalText: '',
      modalTextType: ''
    }
  },
  methods: {
    closeModal: function () {
      this.$root.$data.displayModal = false
      this.modalText = ''
    },
    textType: function () {
      this.$root.$data.displayModal = false
    },
    submitPromotion: function () {
      axios({
        method: "GET",
        "url": "/ajax/promotion/" + this.selectedStructId + "/" + targetUserId
      }).then((response) => {
        console.log(response.data)
        this.modalText = response.data
        this.modalTextType = this.successText
      }).catch((error) => {
        console.log(error.response.data)
        this.modalText = error.response.data
        this.modalTextType = this.errorText
      })
    }
  },
  created() {
    axios({method: "GET", "url": "/ajax/promotion/" + targetUserId + "/structs"}).then(response => {
      this.structs = response.data
    });
  },
}
</script>

<style scoped lang="scss">

.crossbar {
  position: absolute;
  top: 5px;
  right: 5px;
  cursor: pointer;
  height: 25px;
  width: 25px;

  &:hover div {
    background-color: #000;
  }

  div {
    width: 25px;
    height: 3px;
    position: absolute;
    background-color: #525252;
    transition: .3s;
    margin-top: 11px;

    &.cross-1 {
      transform: rotate(45deg);
    }

    &.cross-2 {
      transform: rotate(-45deg);
    }
  }
}

.modal-mask {
  position: fixed;
  z-index: 9998;
  top: 0;
  left: 0;
  width: 100%;
  height: 100%;
  background-color: rgba(0, 0, 0, 0.5);
  display: table;
  transition: opacity 0.3s ease;
}

.select-promote{
  display: flex;
}

.modal-content {
  border-radius: 10px;
  position: relative;
  width: 300px;
  margin: 200px auto;
  padding: 20px 30px;
  background-color: #fff;
  box-shadow: 0 2px 8px rgba(0, 0, 0, 0.33);
  transition: all 0.3s ease;
  font-family: Helvetica, Arial, sans-serif;
}

.fade-enter-active, .fade-leave-active {
  transition: opacity .3s;
}

.fade-enter, .fade-leave-to /* .fade-leave-active below version 2.1.8 */
{
  opacity: 0;
}

select {
  height: 40px;
}
</style>