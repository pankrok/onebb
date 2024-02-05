import {useSkin} from '@/hooks/obbClient';
import type { ISkinConfiguration } from '@/interfaces/config';
import {defineStore} from 'pinia';
import { computed, ref } from 'vue';
import { useRoute } from 'vue-router';

const useConfigStore = defineStore('configStore', ()=>{
    const route = useRoute();
    const boxes = ref<ISkinConfiguration|null>(null);
    //const pages = ref([]);

    async function init() {
        boxes.value = await useSkin()
    }
    
    const pageBoxes = computed(()=>{
        if(route.name && boxes.value)
            return boxes.value[route.name.toString()]

        return null;
    })

    return {boxes, pageBoxes, init}
})

export default useConfigStore;