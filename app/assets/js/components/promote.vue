<template>
  <div>
    <select></select>
    <button>Promote</button>
  </div>
</template>

<script>
function getModal() {
  return document.getElementById('promote-modal')
}

export default {
  name: 'promote',
  data() {
    return {
      showModal: false
    }
  }
}
</script>

<style lang="scss">

</style>