import {defineStore} from 'pinia';
import { ref } from 'vue';

const useLoadingStore = defineStore('loadingStore', ()=>{
    const loading = ref(false);
    
    function isLoading() {
        loading.value = true;
    }

    function isLoaded() {
        loading.value = false;
    }

    return {loading, isLoading, isLoaded}
})

export default useLoadingStore;