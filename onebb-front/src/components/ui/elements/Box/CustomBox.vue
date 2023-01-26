<script setup lang="ts">
import {defineProps } from 'vue'

defineProps<{
  name?: string,
  content?: string,
}>()

</script>

<template>
  <div class="list-complete-item box">
    <div class="box-header">
      <h3 class="text-center">{{ name }}</h3>
    </div>
    <div 
      class="box-content"
      v-html="content">
    </div>
  </div> 
</template>

