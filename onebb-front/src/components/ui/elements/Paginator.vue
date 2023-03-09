<script setup lang="ts">

const props = defineProps<{
  active: boolean,
  next: boolean,
  prev: boolean,
  callBack: Function,
}>();

</script>
<template>
<div v-if="active" class="col-12 display-flex j-c-space-between">
<button v-if="prev" class="btn btn-primary" @click="callBack('prev')">
    Prev
</button>
<button v-if="next " class="btn btn-primary" @click="callBack('next')">
    Next
</button>
</div>


</template>