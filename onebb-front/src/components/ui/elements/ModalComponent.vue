<script setup lang="ts">
    defineProps<{
        onClose: ((payload: MouseEvent) => void) | undefined
        noBox?: boolean
        wrapperClass?: string[]
    }>()
</script>
<template>
  <Teleport to="#app">
    <aside
      @click.self="onClose"
      class="position-fixed display-sm-flex align-items-sm-center justify-sm-content-center col-sm-12 height-sm-12 background-mask"
    >
      <div :class="wrapperClass ?? []">
        <div
          :class="noBox ? 'col-12' : 'column-sm margin-sm-y-l border-1 background-primary border-color-dark box-shadow-light padding-sm-m padding-l height-sm-auto'"
        >
          <slot>test</slot>
        </div>
      </div>
    </aside>
  </Teleport>
</template>
