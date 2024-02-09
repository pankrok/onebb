<script setup lang="ts">
import BoardComponent from '@/components/ui/partials/BoardComponent.vue'
import { useBoard } from '@/hooks/obbClient'
import type { IBoard, IPlot } from '@/interfaces'
import useTimelineStore from '@/stores/useTimelineStore'
import instanceOf from '@/utils/instanceOf'
import { ref } from 'vue'

const data = ref<{
  board: IBoard
  plots: IPlot[]
} | null>(null)

const {setBoardTimeline} = useTimelineStore()

useBoard().then((response) => {
  console.log({ response })
  if (
    instanceOf<{
      board: IBoard
      plots: IPlot[]
    }>(response)
  ) {
    data.value = response
    setBoardTimeline(response.board.id)
  }
})
</script>

<template>
  <board-component v-bind="data" />
</template>
