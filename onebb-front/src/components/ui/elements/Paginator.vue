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
    <div class="box my-1">
      <div class="box-content list">
        <div class="d-flex j-c-space-between mx-2">
          <button
            v-if="prev"
            class="btn btn-secondary"
            @click="callBack('prev')"
          >
            Prev
          </button>
          <span v-else></span>
          <button
            v-if="next"
            class="btn btn-secondary"
            @click="callBack('next')"
          >
            Next
          </button>
        </div>
      </div>
    </div>
  </div>
</template>
