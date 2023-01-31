<script setup lang="ts">
import { defineProps } from "vue";
import type { Component } from "vue";

const props = defineProps<{
  component: Component,
  show: boolean,
  properties?: Object
}>();
</script>
<template>
  <Transition name="fade" mode="out-in">
    <div v-if="props.show" class="modal">
      <component :is="props.component" v-bind="props.properties" />
    </div>
  </Transition>
</template>
