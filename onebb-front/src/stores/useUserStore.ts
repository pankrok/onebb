import type { ITokenResponse } from '@/interfaces';
import {defineStore, storeToRefs} from 'pinia';
import { ref } from 'vue';
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
    const {logged} = storeToRefs(authStore)

    function setUserDate(data: ITokenResponse|null) {
        if (data === null) {
            user.value = {...defaultUser}
            authStore.setLogged(false);
            return;
        }

        user.value = {...user.value, ...data};
        authStore.setLogged(true)
    }

    return {user, logged, setUserDate};
})

export default useUserStore