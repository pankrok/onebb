import type {
  ITokenResponse,
  ILoginCreditionals,
  ILogoutResponse,
  IRegisterCreditionals,
  IUser,
  IViolations
} from '@/interfaces'
import useAxios from './useAxios'
import { AUTH_URL, LOGOUT_URL, USER_URL } from '@/helpers/api'
import { instanceOf } from './helpers'
import type { AxiosResponse } from 'axios'
import useUserStore from '@/stores/useUserStore'

export default function useAuth() {
  const { axios, setToken } = useAxios()
  async function signIn(payload: ILoginCreditionals) {
    const { data } = await axios.post<ILoginCreditionals, AxiosResponse<unknown>>(AUTH_URL, payload)
    if (instanceOf<ITokenResponse>(data)) {
      const userStore = useUserStore()
      console.log({userStore})
      userStore.setUserData(data);
      if (data.token) setToken(data.token)

      return true
    }

    return false
  }

  async function signUp(payload: IRegisterCreditionals) {
    try {
      const { data } = await axios.post<unknown>(USER_URL, payload)
      if (instanceOf<IUser>(data)) return true;
      if (instanceOf<IViolations>(data)) return data
       
      return false
    } catch (e) {
      return false
    }
  }

  async function signOut() {
    const { data } = await axios.get<{}, AxiosResponse<unknown>>(LOGOUT_URL)
    if (instanceOf<ILogoutResponse>(data)) {
      const userStore = useUserStore()
      userStore.$reset()
      return true
    }
    return false
  }

  return { signIn, signOut, signUp }
}
