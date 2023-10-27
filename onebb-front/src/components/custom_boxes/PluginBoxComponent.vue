<script setup lang="ts">
defineProps<{
  innerHtml: string
}>()
</script>
<template>
    <div v-html="innerHtml"></div>
</template>
