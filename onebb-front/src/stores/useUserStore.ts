import type { ITokenResponse } from '@/interfaces';
import {defineStore} from 'pinia';
import { computed, ref } from 'vue';

const defaultUser: ITokenResponse = {
    token: null,
    acp_enabled: false,
    mcp_enabled: false,
    avatar: null,
    slug: null,
    uid: null,
}

export const useUserStore = defineStore('userStore', ()=>{
    const user = ref<ITokenResponse>(defaultUser);

    const logged = computed(()=> user.value.uid)

    function setUserDate(data: ITokenResponse|null) {
        if (data === null) {
            user.value = {...defaultUser}
        }

        user.value = {...user.value, ...data};
    }

    return {user, logged, setUserDate};
})