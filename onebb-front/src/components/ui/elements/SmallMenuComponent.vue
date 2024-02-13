<script setup lang="ts">
defineProps<{
  isActive?: boolean
  top?: string
  right?: string
}>()
</script>
<template>
    <Transition name="slide-fade" mode="in-out">
      <div
        v-if="isActive"
        class="position-absolute"
        :style="{ top: top ?? '0px', right: right ?? 0, zIndex: 1 }"
      >
        <slot></slot>
      </div>
    </Transition>
</template>
