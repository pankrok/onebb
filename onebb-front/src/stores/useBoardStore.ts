import {defineStore} from 'pinia';
import { ref } from 'vue';

const useBoardStore = defineStore('boardStore', ()=>{
    const loading = ref(true);
    const board = ref(null);
    const plots = ref([])
    
    function getBoard(id: number) {
        
    }

    return {loading, board, plots, getBoard}
})

export default useBoardStore;