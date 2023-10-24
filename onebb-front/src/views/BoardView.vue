<script setup lang="ts">
import useBoard from '@/hooks/useBoard'
import Box from '@/components/box/BoxComponent.vue'
import PlotComponent from '@/components/ui/PlotComponent.vue'
import { reactive } from 'vue';
import type { IBoard, IPlot } from '@/interfaces';

interface IBoardData {
  board: IBoard|null,
  plots: IPlot[]|null,
}

const boxStyles = [
  'background-background',
  'border-radius-5',
  'color-white',
  'font-size-18',
  'font-weight-500',
  'padding-l',
  'margin-x-l',
  'margin-top-l'
]

const data = reactive<IBoardData>({
  board: null,
  plots: null,
})

async function init() {
  const { board, plots } = await useBoard();

  data.board = board;
  data.plots = plots;
}

init();

</script>
<template>
  <div class="column">
    <Box v-if="data.board" :boxClass="boxStyles">
      {{ data.board.name }}
    </Box>
    <Box v-for="plot in data.plots" :key="plot.id + plot.name">
      <PlotComponent :plot="plot" />
    </Box>
  </div>

</template>
