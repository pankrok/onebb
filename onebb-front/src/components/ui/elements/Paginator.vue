<script setup lang="ts">
const props = defineProps<{
  active: boolean;
  next: boolean;
  prev: boolean;
  callBack: Function;
}>();
</script>
<template>
  <div v-if="active" class="col-12">
    <div class="d-flex j-c-space-between my-2 mx-2">
      <button v-if="prev" class="btn btn-secondary" @click="callBack('prev')">
        Prev
      </button>
      <span v-else></span>
      <button v-if="next" class="btn btn-secondary" @click="callBack('next')">
        Next
      </button>
    </div>
  </div>
</template>
