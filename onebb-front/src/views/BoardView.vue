<script setup lang="ts">
import BoardComponent from '@/components/ui/partials/BoardComponent.vue'
import { useBoard } from '@/hooks/obbClient'
import type { IBoard, IPlot } from '@/interfaces'
import instanceOf from '@/utils/instanceOf'
import { ref } from 'vue'
console.log('BOARD')
const data = ref<{
  board: IBoard
  plots: IPlot[]
} | null>(null)

useBoard().then((response) => {
  console.log({ response })
  if (
    instanceOf<{
      board: IBoard
      plots: IPlot[]
    }>(response)
  ) {
    data.value = response
  }
})
</script>

<template>
  <board-component v-bind="data" />
</template>
