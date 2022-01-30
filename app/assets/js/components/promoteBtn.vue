<template>
  <button type="button" @click="displayModal">Promote </button>
</template>

<script>
export default {
  name: "promoteBtn",
  methods: {
    displayModal: function () {
      this.$root.$data.displayModal ^= true
    }
  },
}
</script>

<style scoped>

</style>