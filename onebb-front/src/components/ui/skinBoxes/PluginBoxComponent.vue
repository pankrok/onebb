<script setup lang="ts">

defineProps<{
  name: string
  html: string
}>()
</script>
<template>
        <div v-html="html"></div>
</template>
