<script setup lang="ts">
import useBoard from '@/hooks/useBoard'
import Box from '@/components/box/BoxComponent.vue'
import PlotComponent from '@/components/ui/PlotComponent.vue'
import { onUnmounted } from 'vue';
import useBoardStore from '@/stores/useBoardStore';
import { storeToRefs } from 'pinia';

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

const boardStore = useBoardStore();
const {board, plots} = storeToRefs(boardStore);

boardStore.getBoard();

onUnmounted(()=>{
  boardStore.$reset();
})

</script>
<template>
  <div class="column">
    <Box v-if="board" :boxClass="boxStyles">
      {{ board.name }}
    </Box>
    <Box v-for="plot in plots" :key="plot.id + plot.name">
      <PlotComponent :plot="plot" />
    </Box>
  </div>

</template>
