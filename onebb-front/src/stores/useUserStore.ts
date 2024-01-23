import type { ITokenResponse } from '@/interfaces'
import { defineStore } from 'pinia'
import { computed, ref } from 'vue'

const defaultUser: ITokenResponse = {
  token: null,
  acp_enabled: false,
  mcp_enabled: false,
  avatar: null,
  slug: null,
  uid: null
}

const useUserStore = defineStore('userStore', () => {
  const user = ref<ITokenResponse>(defaultUser)
  const logged = ref(false)

  const getUserId = computed((): number => {
    return user.value.uid ?? 0
  })

  const mod = computed((): boolean => {
    return user.value.mcp_enabled ?? false
  })

  function setLogged(isLogged: boolean) {
    logged.value = isLogged
  }

  function setUserData(data: ITokenResponse | null) {
    if (data === null) {
      user.value = { ...defaultUser }
      logged.value = false;
      return
    }

    user.value = { ...user.value, ...data }
    logged.value = true;
  }

  function $reset() {
    user.value = { ...defaultUser }
    logged.value = false;
  }

  return { user, getUserId, logged, mod, setUserData, setLogged, $reset }
})

export default useUserStore
