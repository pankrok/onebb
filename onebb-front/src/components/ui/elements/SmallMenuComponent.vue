<script setup lang="ts">
defineProps<{
  transitionMode: 'default' | 'in-out' | 'out-in' | undefined
  transitionName: string
  isActive: boolean
  teleport: string
  top?: string
  right?: string
}>()
</script>
<template>
  <Teleport :to="teleport">
    <Transition :name="transitionName" :mode="transitionMode">
      <div
        v-if="isActive"
        class="position-absolute"
        :style="{ top: top ?? '0px', right: right ?? 0, zIndex: 1 }"
      >
        <slot></slot>
      </div>
    </Transition>
  </Teleport>
</template>
