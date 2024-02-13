<script setup lang="ts">
import randomString from '@/utils/randomString'
import { onMounted, ref } from 'vue'

defineProps<{
  onClose: ((payload: MouseEvent) => void) | undefined
  isActive?: boolean
  noBox?: boolean
  wrapperClass?: string[]
}>()

const modalKey = ref('modal')

onMounted(() => {
  modalKey.value = randomString()
})
</script>
<template>
  <Teleport to="#app">
    <Transition name="fade" mode="in-out">
      <aside
        v-if="isActive"
        :key="modalKey"
        @click.self="onClose"
        class="position-fixed display-sm-flex align-items-sm-center justify-sm-content-center col-sm-12 height-sm-12 background-mask"
      >
        <div :class="wrapperClass ?? []">
          <div
            :class="
              noBox
                ? 'col-12'
                : 'column-sm margin-sm-y-l border-1 background-primary border-color-dark box-shadow-light padding-sm-m padding-l height-sm-auto'
            "
          >
            <slot>test</slot>
          </div>
        </div>
      </aside>
    </Transition>
  </Teleport>
</template>
