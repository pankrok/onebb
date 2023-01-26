<script setup lang="ts">
import {defineProps } from 'vue'

defineProps<{
  name?: string,
  content?: string,
}>()

</script>
<template>
    <div :data-box="name" v-html="content">
    </div>
</template>