<template>
<Transition name="fade">
<div v-if="show" @click.self="$emit('update:show', false)" class="modal">
    <div class="alert column" :class="{ success: data.success, danger: data.error }">
        <div class="alert-body">
            <strong> {{ data.message }} </strong>
         </div>
    </div>
</div>
</Transition>
</template>

<script>
export default {
  name: 'Modal',
  props: {
    show: Boolean,
    data: Object
  },
  emits: ['update:show']
}
</script>
<style scoped>
   @import '../assets/modal.css';
</style>
