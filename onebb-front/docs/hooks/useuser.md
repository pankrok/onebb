# useUser

## parseUsername

```ts
;(user: IUser) => string
```

returns parsed username

## getUser

```ts
;() => ITokenResponse
```

return user data obteined from token

## getUserId

```ts
;() => number
```

return 0 if user is not logged in or user id

## getUserById

```ts
async (id: number) => { request: RequestInit, response: Response, parsedResponse: IUser | undefined}
```

return user data specified by user id

## login

```ts
;async (creditionals: ILoginCreditionals) => Promise<boolean>
```

## register

```ts
;async (creditionals: IRegister) =>
  Promise<
    | {
        request: RequestInit
        response: Response
        parsedResponse: IUser | IViolations | undefined
      }
    | undefined
  >
```

sending register request, return user data or violations

## logout

```ts
;() => Promise<boolean>
```

logging out user, return true for success logout

## getToken

```ts
;() => string
```

return empty string or user token

## isLogged

```ts
;() => boolean
```

return information is user logged in
