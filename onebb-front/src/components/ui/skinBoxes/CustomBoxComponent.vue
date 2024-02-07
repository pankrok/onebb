<script setup lang="ts">
import PanelComponent from '../elements/PanelComponent.vue'

defineProps<{
  name: string
  html: string
}>()
</script>
<template>
  <PanelComponent :header="true">
    <template #header>
        <h4 class="font-size-18 font-weight-400 margin-y-s color-light">{{ name }}</h4>
    </template>
    <template #default>
        <div class="padding-sm-m" v-html="html"></div>
    </template>
  </PanelComponent>
</template>
