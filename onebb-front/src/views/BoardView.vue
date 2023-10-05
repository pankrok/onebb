<script setup lang="ts">
import useBoard from '@/hooks/useBoard'
import Box from '@/components/box/BoxComponent.vue'
import PlotComponent from '@/components/ui/PlotComponent.vue'

const boxStyles = [
  'background-blue',
  'border-radius-5',
  'color-white',
  'font-size-18',
  'font-weight-500',
  'padding-l',
  'margin-x-l',
  'margin-top-l'
]

const { board, plots } = await useBoard()
</script>
<template>
  <div class="column" v-if="board && plots">
    <Box :boxClass="boxStyles">
      {{ board.name }}
    </Box>
    <Box v-for="plot in plots" :key="plot.id + plot.name">
      <PlotComponent :plot="plot" />
    </Box>
  </div>
</template>
