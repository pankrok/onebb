# useApi()

## setHeaders

```ts
(headers: HeadersInit) => void
```

allow set all fetch headers parameters.

## get

```ts
async <T>(url: string) => { request: RequestInit, response: Response, parsedResponse: T|undefined }
```

function send GET http request on specified url, you can set hat Type should have response object.

## post

```ts
async <T>(url: string, bodyInit: object) => { request: RequestInit, response: Response, parsedResponse: T|undefined }
```

function send POST http request on specified url, you can set hat Type should have response object.

## put

```ts
async <T>(url: string, bodyInit: object) => { request: RequestInit, response: Response, parsedResponse: T|undefined }
```

function send PUT http request on specified url, you can set hat Type should have response object.

## retry

```ts
async <T>(requestInit: RequestInit) => { request: RequestInit, response: Response, parsedResponse: T|undefined }
```

function retry last http request

## setToken

```ts
(newToken: string | null) => void
```

setting OneBB access token

## unsetToken

```ts
() => void
```

remove OneBB access token
