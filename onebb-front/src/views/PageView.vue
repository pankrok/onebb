<script setup lang="ts">
import usePageStore from '@/stores/usePageStore'
import Box from '@/components/box/BoxComponent.vue';
import { storeToRefs } from 'pinia'

const plotClassBox = [
  'row',
  'margin-m',
  'border-1',
  'color-white',
  'border-color-primary',
  'border-radius-5',
  'padding-m'
]

const headerClassBox = [
  'color-white',
  'margin-m',
  'border-1',
  'border-color-primary',
  'border-radius-5',
  'padding-m',
  'font-size-14'
]

const pageStore = usePageStore()
pageStore.fetchPage()
const { page } = storeToRefs(pageStore)
</script>

<template>
  <div v-if="page">
    <Box :boxClass="headerClassBox" key="plot-header">
      <h2 class="font-size-16 font-weight-600 padding-bottom-m margin-none padding-x-xl padding-y-none">{{ page.name }}</h2>
    </Box>
    <Box :box-class="plotClassBox">
      <div v-html="page.content"></div>
    </Box>
  </div>
</template>
