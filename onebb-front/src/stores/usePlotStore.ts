import usePlot from '@/hooks/usePlot';
import type { IPlot, IPost, IHydraView } from '@/interfaces';
import {defineStore} from 'pinia';
import { ref } from 'vue';

const useBoardStore = defineStore('boardStore', ()=>{
    const posts = ref<IPost[]|null>(null);
    const plot = ref<IPlot|null>(null)
    const hydraView = ref<IHydraView>(null);
    
    async function getBoard() {
        const response = await usePlot();
        posts.value = response.posts?['hydra:member'];
        plot.value = response.plot;
    }

    function $reset() {
        plot.value = null;
        posts.value = [];
    }

    return {plot, posts, getBoard, $reset}
})

export default useBoardStore;