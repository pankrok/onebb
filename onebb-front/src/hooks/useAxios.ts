//import type { ITokenResponse } from '@/interfaces'
import axios, { type AxiosResponse } from 'axios'
import useLoadingStore from '@/stores/useLoadingStore'
import type { AxiosError } from 'axios'
import type {
  ILoginCreditionals,
  ILogoutResponse,
  IRegisterCreditionals,
  ITokenResponse,
  IUser,
  IViolations
} from '@/interfaces'
import instanceOf from '@/utils/instanceOf'
import { AUTH_URL, LOGOUT_URL, REFRESH_URL, USER_URL } from '@/utils/apiRoutes'
import useUserStore from '@/stores/useUserStore'

let numberOfAjaxCAllPending = 0
let initAxios = false;

const baseURL = // @ts-ignore
  document.getElementById('app')?.getAttribute('data-url') +
  // @ts-ignore
  document.getElementById('app')?.getAttribute('data-obb') +
  'api'

const instance = axios.create({
  baseURL,
  withCredentials: true, //@ts-ignore
  timeout: import.meta.env.MODE === 'development' ? 30000 : 3000
})

export function init() {
  if (initAxios) {
    return;
  }

  const loadingStore = useLoadingStore()
  instance.interceptors.request.use(
    function (config) {
      if (config.url?.includes('one_messengers')) {
        return config
      }

      console.log('withCredentials ', instance.defaults.withCredentials)
      numberOfAjaxCAllPending++
      loadingStore.isLoading()
      return config
    },
    function (error) {
      return Promise.reject(error)
    }
  )

  instance.interceptors.response.use(
    (response) => {
      console.log({ response })
      if (numberOfAjaxCAllPending > 0) {
        numberOfAjaxCAllPending--
      }

      if (numberOfAjaxCAllPending <= 0) {
        numberOfAjaxCAllPending = 0;
        loadingStore.isLoaded()
      }
      return response
    },
    async (error: AxiosError) => {
      console.log({ numberOfAjaxCAllPending })
      if (numberOfAjaxCAllPending > 0) {
        numberOfAjaxCAllPending--
      }
      if (numberOfAjaxCAllPending == 0) {
        loadingStore.isLoaded()
      }
      console.log('err ', { error })
      const { response } = error

      if (
        response &&
        response.data &&
        response.status === 401 && // @ts-ignore
        response.data.message === 'Expired JWT Token'
      ) {
        if (instance.defaults.headers.common['Authorization']) {
          const { data } = await instance.post<{}, AxiosResponse<unknown>>(REFRESH_URL, {})
          if (instanceOf<ITokenResponse>(data)) {
            const userStore = useUserStore()
            userStore.setUserData(data)

            if (error.config) {
              error.config.headers.Authorization = `Bearer ${data.token}`
              return instance.request(error.config)
            }
          }
        }
      }

      return Promise.reject(error)
    }
  )

  initAxios = true;
}

export default function useAxios() {
  function setToken(token: string) {
    console.log('setting token')
    instance.defaults.headers.common['Authorization'] = `Bearer ${token}`
    console.log({ axiosHeaders: instance.defaults.headers })
  }

  function removeToken() {
    instance.defaults.headers.common['Authorization'] = null
  }

  async function refreshToken() {
    const { data } = await instance.post<{}, AxiosResponse<unknown>>(REFRESH_URL, {})
    if (instanceOf<ITokenResponse>(data)) {
      const userStore = useUserStore()
      userStore.setUserData(data)
      if (data.token) setToken(data.token)
    }
  }

  async function signIn(payload: ILoginCreditionals) {
    const { data } = await instance.post<ILoginCreditionals, AxiosResponse<unknown>>(
      AUTH_URL,
      payload
    )
    if (instanceOf<ITokenResponse>(data)) {
      const userStore = useUserStore()
      console.log({ userStore })
      userStore.setUserData(data)
      if (data.token) {
        setToken(data.token)
        localStorage.setItem('logged', 'true')
        return true
      }
    }

    return false
  }

  async function signUp(payload: IRegisterCreditionals) {
    try {
      const { data } = await instance.post<unknown>(USER_URL, payload)
      if (instanceOf<IUser>(data)) return true
      if (instanceOf<IViolations>(data)) return data

      return false
    } catch (e) {
      return false
    }
  }

  async function signOut() {
    const { data } = await instance.get<{}, AxiosResponse<unknown>>(LOGOUT_URL)
    if (instanceOf<ILogoutResponse>(data)) {
      const userStore = useUserStore()
      userStore.$reset()
      localStorage.removeItem('logged')
      return true
    }
    return false
  }

  if (localStorage.getItem('logged') && !instance.defaults.headers.common['Authorization']) {
    refreshToken()
  }

  return {
    setToken,
    removeToken,
    refreshToken,
    signIn,
    signUp,
    signOut,
    axios: instance
  }
}
