<script setup lang="ts">
import BoxComponent from '../box/BoxComponent.vue'

defineProps<{
  name: string
  innerHtml: string
}>()
</script>
<template>
  <BoxComponent>
    <template v-slot:header>
      {{ name }}
    </template>
    <div v-html="innerHtml"></div>
  </BoxComponent>
</template>
