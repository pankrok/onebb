//import type { ITokenResponse } from '@/interfaces'
import axios from 'axios'
import useLoadingStore from '@/stores/useLoadingStore';
import type { AxiosError } from 'axios'

let numberOfAjaxCAllPending = 0;
console.log({axiosEnv: import.meta.env.MODE})
const instance = axios.create({
  baseURL: import.meta.env.VITE_BASE_URL_API,
  timeout: import.meta.env.MODE === 'development' ? 30000 : 3000
})

export default function useAxios() {
  const loadingStore = useLoadingStore();
  instance.interceptors.request.use(
    function (config) {
      numberOfAjaxCAllPending++
      loadingStore.isLoading();
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
        loadingStore.isLoaded();
      }
      return response
    },
    async (error: AxiosError) => {
      numberOfAjaxCAllPending--
      if (numberOfAjaxCAllPending == 0) {
        loadingStore.isLoaded();
      }

      console.log({ error })
      // if (error.code === '401' && error.)
      // if(instance.defaults.headers.common['Authorization']) {
      //     const data = await instance.post<{}, AxiosResponse<unknown>>(REFRESH_URL, {});
      //     if (instanceOf<ITokenResponse>(data)) {
      //         const userStore = useUserStore();
      //         userStore.setUserDate(data);
      //     }
      // }
      return Promise.reject(error)
    }
  )

  function setToken(token: string) {
    console.log('setting token');
    instance.defaults.headers.common['Authorization'] = `Bearer ${token}`;
    console.log({axiosHeaders: instance.defaults.headers})
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
