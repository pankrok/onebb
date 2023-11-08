import useBoard from '@/hooks/useBoard';
import type { IBoard, IPlot } from '@/interfaces';
import {defineStore} from 'pinia';
import { ref, watch } from 'vue';

const useBoardStore = defineStore('boardStore', ()=>{
    const board = ref<IBoard|null>(null);
    const plots = ref<IPlot[]>([])
    
    async function getBoard() {
        const response = await useBoard();
        board.value = response.board;
        plots.value = response.plots;
    }

    function $reset() {
        board.value = null;
        plots.value = [];
    }

    return {board, plots, getBoard, $reset}
})

export default useBoardStore;