export interface HeadersInterface {
  'Content-Type': string,
  Accept: string,
  'X-ONEBB-ADMIN': string,
  Authorization?: string | null,
}

export interface ConfigInterface {
  method?: string,
  mode: string,
  cache?: RequestCache | undefined,
  credentials?: RequestCredentials | undefined,
  redirect?: string,
  referrerPolicy?: string,
  headers: HeadersInterface,
  body?: string | null,
}

export interface ApiClientInterface {
  config: ConfigInterface
  token?: string,
  resource?: string,
  params: Array<{ key: string, value: string }>,
  refresh?: {
    calls: number,
    maxCalls: number,
    timeout: number
  }
}

export interface ResponseInterface<T> {
  code: number,
  body?: T,
}

export interface RequestInterface<T> {
  url: string,
  config: ConfigInterface,
  body?: T
}

