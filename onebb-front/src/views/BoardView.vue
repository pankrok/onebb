<script setup lang="ts">
import { ref } from 'vue';
import { useRoute } from 'vue-router';
import useBoard from '@/hooks/useBoard';
import Box from '@/components/box/BoxComponent.vue';
import type { IBoard, IPlot } from '@/interfaces/OnebbInterfaces';
import PlotComponent from '@/components/ui/PlotComponent.vue';

const boxStyles = ['background-blue', 'border-radius-5', 'color-white', 'font-size-18', 'font-weight-500', 'padding-l', 'margin-x-l', 'margin-top-l'];
const route = useRoute();
const board = ref<IBoard>();
const plots = ref<IPlot[]>();
useBoard(route.params.id, route.params.page ?? 1).then(response => {
    console.log({response})
    const { boardResponse, plotsResponse } = response;
    console.log({ boardResponse, plotsResponse })
    if (boardResponse && plotsResponse) {
        board.value = boardResponse;
        plots.value = plotsResponse['hydra:member'];
    }
})

</script>
<template>
    <div class="column" v-if="board">
        <Box :boxClass="boxStyles">
            {{ board.name }}
        </Box>
        <Box v-if="plots" v-for="plot in plots">
            <PlotComponent :plot="plot" />
        </Box>
    </div>
</template>