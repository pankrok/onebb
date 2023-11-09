import type { ITokenResponse } from '@/interfaces';
import {defineStore} from 'pinia';
import { computed, ref } from 'vue';
import useAuthStore from './useAuthStore';

const defaultUser: ITokenResponse = {
    token: null,
    acp_enabled: false,
    mcp_enabled: false,
    avatar: null,
    slug: null,
    uid: null,
}

const useUserStore = defineStore('userStore', ()=>{
    const user = ref<ITokenResponse>(defaultUser);
    
    const authStore = useAuthStore();

    const getUserId = computed((): number  =>{
        return user.value.uid ?? 0;
    })

    function setUserData(data: ITokenResponse|null) {
        
        if (data === null) {
            user.value = {...defaultUser}
            authStore.setLogged(false);
            return;
        }

        user.value = {...user.value, ...data};
        authStore.setLogged(true)
    }

    function $reset() {
        user.value = {...defaultUser};
        authStore.setLogged(false);
    }

    return {user, setUserData, getUserId, $reset};
})

export default useUserStore