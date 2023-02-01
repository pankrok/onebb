<script setup lang="ts">
import { defineProps } from "vue";

const props = defineProps<{
  show: boolean
}>();
</script>
<template>
  <Transition name="fade" mode="out-in">
    <div v-if="props.show" class="modal">
      <slot></slot>
    </div>
  </Transition>
</template>
