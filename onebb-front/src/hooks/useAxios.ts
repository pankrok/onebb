import type { ITokenResponse } from '@/interfaces'
import axios from 'axios'
import type { AxiosError, AxiosResponse } from 'axios'


const instance = axios.create({
  baseURL: 'https://bdev.s89.eu/api/',
  timeout: 1000
})

export default function useAxios() {
  instance.interceptors.response.use(
    (response) => {
      return response
    },
    async (error: AxiosError) => {
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
    instance.defaults.headers.common['Authorization'] = token
  }

  function removeToken() {
    instance.defaults.headers.common['Authorization'] = null
  }

  async function refreshToken() {
    const { data, status, statusText } = await instance.post<{}, AxiosResponse<ITokenResponse>>(
      'refresh',
      {}
    )
  }

  return {
    setToken,
    removeToken,
    refreshToken,
    axios: instance
  }
}
