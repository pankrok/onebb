import type {
  IUser,
  ILoginCreditionals,
  ITokenResponse,
  IRegister,
  IViolations
} from '@/interfaces'
import useApi, { refresh } from './useApi'
import { reactive } from 'vue'

const initialState: ITokenResponse = {
  token: '',
  acp_enabled: false,
  mcp_enabled: false,
  avatar: '',
  slug: '',
  uid: 0
}

const data: ITokenResponse = reactive({ ...initialState })
let isRefreshing = 0

export const useUser = () => {
  const api = useApi()

  const setData = (newData: ITokenResponse) => {
    for (const key of Object.keys(data)) {
      ;(data as { [key: string]: any })[key] = (newData as { [key: string]: any })[key]
    }

    localStorage.setItem('obbLogged', '1')
  }

  const parseUsername = (user: IUser) => {
    return user.user_group.html_code.replace('{{username}}', user.username)
  }

  const getUser = () => {
    return data
  }

  const getUserId = () => {
    return data.uid ?? 0
  }

  const getToken = () => {
    return data.token
  }

  const login = async (creditionals: ILoginCreditionals) => {
    const endpoint = 'login'
    console.log({ creditionals })

    try {
      const { parsedResponse } = await api.post<ITokenResponse>(endpoint, {
        username: creditionals.username,
        password: creditionals.password
      })
      if (parsedResponse?.code) {
        throw new Error(parsedResponse.message)
      }
      if (!parsedResponse) {
        throw new Error('Data fetch error!')
      }
      setData(parsedResponse)
      console.log({ parsedResponse })
      api.setToken(parsedResponse.token)

      return true
    } catch (e) {
      console.error(e)
      return false
    }
  }

  const logout = async () => {
    const endpoint = 'logout'

    try {
      await api.get<{ logged_out: boolean }>(endpoint)
      setData(initialState)
      api.setToken(null)
      localStorage.removeItem('obbLogged')
      return true
    } catch (e) {
      console.error(e)
      return false
    }
  }

  if (localStorage.getItem('obbLogged') === '1' && data.token === '' && isRefreshing === 0) {
    isRefreshing = 1
    refresh().then((res) => {
      if (res) {
        setData(res)
      }

      isRefreshing = 0
    })
  }

  const isLogged = () => {
    return data.uid > 0
  }

  const register = async (creditionals: IRegister) => {
    const endpoint = 'users'

    try {
      const response = await api.post<IUser | IViolations>(endpoint, creditionals)

      return response
    } catch (e) {
      console.error(e)
    }
  }

  const getUserById = async (id: number) => {
    const endpoint = `users/${id}`

    try {
      const response = await api.get<IUser>(endpoint)

      return response
    } catch (e) {
      console.error(e)
    }
  }
  console.warn('[OneBB warn]: useUser is deprecated')
  return {
    parseUsername,
    getUser,
    getUserId,
    getUserById,
    login,
    refresh,
    register,
    logout,
    getToken,
    isLogged
  }
}
