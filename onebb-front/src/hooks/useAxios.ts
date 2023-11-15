//import type { ITokenResponse } from '@/interfaces'
import axios, { type AxiosResponse } from 'axios'
import useLoadingStore from '@/stores/useLoadingStore'
import type { AxiosError } from 'axios'
import type { ITokenResponse } from '@/interfaces'
import { instanceOf } from './helpers'
import useUserStore from '@/stores/useUserStore'
import { REFRESH_URL } from '@/helpers/api'

let numberOfAjaxCAllPending = 0

const instance = axios.create({
  withCredentials: true,
  baseURL: import.meta.env.VITE_BASE_URL_API,
  timeout: import.meta.env.MODE === 'development' ? 30000 : 3000,
  
})

export default function useAxios() {
  const loadingStore = useLoadingStore()
  instance.interceptors.request.use(
    function (config) {
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
      numberOfAjaxCAllPending--
      if (numberOfAjaxCAllPending == 0) {
        loadingStore.isLoaded()
      }
      return response
    },
    async (error: AxiosError) => {
      numberOfAjaxCAllPending--
      if (numberOfAjaxCAllPending == 0) {
        loadingStore.isLoaded()
      }
      console.log('err ', { error })
      const { response } = error

      if (
        response &&
        response.data &&
        response.data &&
        response.status === 401 && // @ts-ignore
        response.data.message === 'Expired JWT Token'
      ) {
        if (instance.defaults.headers.common['Authorization']) {
          const {data} = await instance.post<{}, AxiosResponse<unknown>>(REFRESH_URL, {})
          if (instanceOf<ITokenResponse>(data)) {
            const userStore = useUserStore()
            userStore.setUserData(data)
            
            if (error.config) {
              error.config.headers.Authorization = `Bearer ${data.token}`;
              return instance.request(error.config)
            }
          }
        }
      }

      return Promise.reject(error)
    }
  )

  function setToken(token: string) {
    console.log('setting token')
    instance.defaults.headers.common['Authorization'] = `Bearer ${token}`
    console.log({ axiosHeaders: instance.defaults.headers })
  }

  function removeToken() {
    instance.defaults.headers.common['Authorization'] = null
  }

  // async function refreshToken() {
  //   const { data, status, statusText } = await instance.post<{}, AxiosResponse<ITokenResponse>>(
  //     'refresh',
  //     {}
  //   )
  // }

  return {
    setToken,
    removeToken,
    //  refreshToken,
    axios: instance
  }
}
