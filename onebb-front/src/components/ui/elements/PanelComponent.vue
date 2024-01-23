<script setup lang="ts">
defineProps<{
    header: boolean
}>();
</script>
<template>
  <div class="col-12 border-1 background-primary border-color-dark margin-sm-y-m margin-y-l">
    <div v-if="header" class="row padding-m padding-sm-s border-bottom-1 background-stripes border-color-dark">
        <slot name="header"></slot>
    </div>
    <slot></slot>
  </div>
</template>
