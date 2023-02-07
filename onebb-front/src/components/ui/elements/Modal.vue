<script setup lang="ts">
import { defineProps } from "vue";

const props = defineProps<{
  show: boolean
  callback: Function,
}>();
</script>
<template>
  <Transition name="fade" mode="out-in">
    <div v-if="props.show" class="modal" @click.self="callback()" >
      <slot></slot>
    </div>
  </Transition>
</template>
