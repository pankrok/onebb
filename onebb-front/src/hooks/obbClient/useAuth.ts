import type {
  ITokenResponse,
  ILoginCreditionals,
  ILogoutResponse,
  IRegisterCreditionals,
  IUser,
  IViolations
} from '@/interfaces'
import useAxios from '../useAxios'
import { AUTH_URL, LOGOUT_URL, REFRESH_URL, USER_URL } from '@/utils/apiRoutes'
import instanceOf from '@/utils/instanceOf'
import type { AxiosResponse } from 'axios'
import useUserStore from '@/stores/useUserStore'

export function useAuth() {
  const { axios, setToken } = useAxios()
  async function signIn(payload: ILoginCreditionals) {
    const { data } = await axios.post<ILoginCreditionals, AxiosResponse<unknown>>(AUTH_URL, payload)
    if (instanceOf<ITokenResponse>(data)) {
      const userStore = useUserStore()
      console.log({userStore})
      userStore.setUserData(data);
      if (data.token) {
        setToken(data.token)
        localStorage.setItem('logged', 'true');
        return true
      }
      
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
      localStorage.removeItem('logged');
      return true
    }
    return false
  }

  async function stayLogged() {
    const { data } = await axios.post<{}, AxiosResponse<unknown>>(REFRESH_URL, {})
    if (instanceOf<ITokenResponse>(data)) {
      const userStore = useUserStore()
      userStore.setUserData(data);
      if (data.token) {
        setToken(data.token)
        localStorage.setItem('logged', 'true');
        return true
      }
    }

    return false
  }

  return { signIn, signOut, signUp, stayLogged }
}
