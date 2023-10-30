import {defineStore} from 'pinia';
import { ref } from 'vue';

const useAuthStore = defineStore('authStore', ()=>{
    const logged = ref(false);
    
    function setLogged(payload: boolean) {
        logged.value = payload;
    }

    return {logged, setLogged}
})

export default useAuthStore;