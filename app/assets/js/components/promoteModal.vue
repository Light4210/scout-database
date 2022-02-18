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
        <div v-if="this.modalText !== ''"
             v-bind:class="(this.modalTextType === this.errorText)?'error-text':'success-text'"
             class="message-text modal-text">
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
      if (this.selectedStructId != '') {
        axios({
          method: "GET",
          "url": "/ajax/promotion/" + this.selectedStructId + "/" + targetUserId
        }).then((response) => {
          this.modalText = response.data
          this.modalTextType = this.successText
        }).catch((error) => {
          this.modalText = error.response.data
          this.modalTextType = this.errorText
        })
      } else {
        this.modalTextType = this.errorText
        this.modalText = 'Please select struct'
      }
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
@import "/assets/sass/variables.scss";

.crossbar {
  position: absolute;
  top: 5px;
  right: 5px;
  margin-right: 0 !important;
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
  width: 100% !important;
  height: 100% !important;
  background-color: rgba(0, 0, 0, 0.5);
  display: table;
  transition: opacity 0.3s ease;
}

.select-promote {
  display: flex;
}

.modal-content {
  border-radius: 10px;
  position: relative;
  width: 400px !important;
  margin: 200px auto !important;
  padding: 40px 30px;
  background-color: #fff;
  box-shadow: 0 0 8px rgba(0, 0, 0, 0.33);
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
  border: unset;
  border-bottom: 2px solid #7b7c7c;
}

button {
  font-size: 15px;
  display: block;
  width: 100%;
  text-align: center;
  border-radius: 5px;
  cursor: pointer;
  border: 2px solid #7b7c7c;
  min-width: 140px;
  margin-left: 20px;
}

.modal-text {
  width: 100% !important;
  height: auto !important;
}

@media (max-width: $mobile-width) {
  .select-promote {
    width: 100% !important;
    display: block;

    select {
      margin-bottom: 15px;
    }
  }
  .modal-content {
    width: 242px !important;
  }
  button {
    margin-left: 0 !important;
  }
}
</style>