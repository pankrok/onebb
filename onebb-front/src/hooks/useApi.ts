import useLoading from './useLoading'
import type { ITokenResponse } from '@/interfaces'

enum Method {
  POST = 'POST',
  GET = 'GET',
  PUT = 'PUT',
  DELETE = 'DELETE'
}

interface ICfg {
  retry: number
  timeout: number
  fetch: RequestInit
}

// FIXME
const baseUrl = import.meta.env.VITE_BASE_URL_TMP
const { loading, loaded } = useLoading()
let url = ''
let body: any = {}
let token: string | null = null
//const queue: any[] = []

let configuration: ICfg = {
  retry: 3,
  timeout: 1000,
  fetch: {
    method: 'GET',
    credentials: 'include',
    mode: 'cors',
    cache: 'no-cache',
    redirect: 'follow',
    referrerPolicy: 'no-referrer',
    headers: {
      'Content-Type': 'application/ld+json',
      Accept: 'application/ld+json'
    },
    body: null
  }
}

const defaultConfiguration = { ...configuration }
let isRefreshing = false

class ObbFetch {
  #ret: number
  #request: RequestInit
  prepareRequest: <T>() => Promise<{ request: RequestInit; response: Response; parsedResponse: T|null }>
  #url: string

  constructor(request: RequestInit) {
    this.#ret = configuration.retry
    this.#url = url
    this.#request = request
    this.prepareRequest = () => {
      if (this.#ret < 0) {
        this.#ret = configuration.retry
        throw new Error('Retry beyond limit')
      }

      if (token) {
        if (request.headers) {
          request.headers = { ...request.headers, Authorization: `Bearer ${token}` }
        } else {
          request.headers = {
            Authorization: `Bearer ${token}`
          }
        }
      }

      return new Promise((reslove) => {
        fetch(baseUrl + this.#url, this.#request).then((response) => {
          if ((response.status >= 429 || response.status === 401) && this.#ret !== 0) {
            if (response.status === 401 && isRefreshing === false) {
              isRefreshing = true
              //const { refresh } = useUser()
              refresh().then(() => {
                isRefreshing = false
              })
            }
            setTimeout(async () => {
              this.#ret--
              reslove(await this.prepareRequest())
            }, configuration.timeout)
          }

          if (this.#ret === 0 || (response.status < 429 && response.status !== 401)) {
            const contentType = response.headers.get('content-type')
            if (
              contentType &&
              (contentType.indexOf('application/ld+json') !== -1 ||
                contentType.indexOf('application/json') !== -1)
            ) {
              response.json().then((parsedResponse) => {
                reslove({ request, response, parsedResponse })
              })
            } else {
              reslove({ request, response, parsedResponse: null })
            }
          }
        })
      })
    }
  }
}

export const refresh = async () => {
  const endpoint = 'refresh'
  try {
    const { parsedResponse } = await useApi().post<ITokenResponse>(endpoint, {})
    if (parsedResponse?.code) {
      throw new Error(parsedResponse.message)
    }
    if (!parsedResponse) {
      throw new Error('Data fetch error!')
    }

    useApi().setToken(parsedResponse.token)
    return parsedResponse
  } catch (e) {
    if (e === 'Missing JWT Refresh Token') {
      localStorage.removeItem('obbLogged')
    }
    console.error(e)
    return null
  }
}

const factory = <T>(method?: string) => {
  loading()
  const request = { ...configuration.fetch }
  if (method) {
    request.method = method
  }

  if (method !== Method.GET && method !== Method.DELETE) {
    request.body = JSON.stringify(body)
  }

  const obbFetch = new ObbFetch(request)
  url = ''
  body = {}
  configuration = defaultConfiguration
  const response = obbFetch.prepareRequest<T>()
  loaded()
  return response
}

const useApi = () => {
  console.warn('[OneBB warn]: useApi is deprecetad!')
  return {
    setHeaders: (headers: HeadersInit) => {
      for (const key of Object.keys(headers)) {
        ;(configuration.fetch.headers as { [key: string]: string })[key] = (
          headers as { [key: string]: string }
        )[key]
      }
    },

    get: async <T>(setUrl: string) => {
      url = setUrl
      const { request, response, parsedResponse } = await factory<T>(Method.GET)
      return { request, response, parsedResponse }
    },

    post: async <T>(setUrl: string, bodyInit: object) => {
      url = setUrl
      body = bodyInit

      const { request, response, parsedResponse } = await factory<T>(Method.POST)

      return { request, response, parsedResponse }
    },

    put: async <T>(setUrl: string, bodyInit: object) => {
      url = setUrl
      body = bodyInit

      const { request, response, parsedResponse } = await factory<T>(Method.PUT)

      return { request, response, parsedResponse }
    },

    retry: async <T>(requestInit: RequestInit) => {
      configuration.fetch = { ...requestInit }
      return await (<T>factory())
    },

    setToken: (newToken: string | null) => {
      token = newToken
    },

    unsetToken: () => {
      token = null
    }
  }
}

export default useApi
