enum Method {
    POST = "POST",
    GET = "GET",
    PUT = "PUT",
    DELETE = "DELETE",
}
 
interface ICfg {
    retry: number,
    timeout: number,
    fetch: RequestInit
}
 
// FIXME
const baseUrl = 'https://bdev.s89.eu/api/';
let url = '';
let body: any = {};
 
let configuration: ICfg = {
    retry: 3,
    timeout: 3000,
    fetch: {
        method: 'GET',
        //redirect: "follow",
        headers: {
            'Content-Type': 'application/json+ld',
          //  'Accept': 'application/ld+json',
          //  Authentication: ''
        },
        body: null,
    }
}
 
const defaultConfiguration = {...configuration}
 
 
class ObbFetch {
    #ret: number;
    #request: RequestInit;
    prepareRequest: <T>() => Promise<{request: RequestInit, response: Response, parsedResponse?: T}>
    #url: string;
 
    constructor(request: RequestInit) {
        this.#ret = configuration.retry;
        this.#url = url;
        this.#request = request;
        this.prepareRequest = () => {
            if (this.#ret < 0) {
                this.#ret = configuration.retry;
                throw new Error('Retry beyond limit')
            }
 
            return new Promise(async (reslove) => {
                console.log(baseUrl + this.#url, this.#request)
                let response = await fetch(baseUrl + this.#url, this.#request)
                if (response.status >= 429 && this.#ret !== 0) {
                    setTimeout(async () => {
                        this.#ret--;
                        reslove(await this.prepareRequest())
                    }, configuration.timeout)
                }
                
                if (this.#ret === 0 || response.status < 404) {
                    const contentType = response.headers.get("content-type");
                    if (contentType && contentType.indexOf("application/ld+json") !== -1) {
                        const parsedResponse = await response.json()
                        reslove({request, response, parsedResponse});
                    } 
                    
                    reslove({request, response});
                }
            });
        }
    }
}
 
const factory = async <T>(method?: string) => {
    const request = {...configuration.fetch}
    if(method)
      request.method = method;
 
    if (method !== Method.GET && method !== Method.DELETE) {
        request.body = JSON.stringify(body);
    }
 
    const obbFetch = new ObbFetch(request);
    url = '';
    body = {};
    configuration = defaultConfiguration;
 
    return await obbFetch.prepareRequest<T>()
}
 
const useApi = () => {
    return {
        setHeaders: (headers: HeadersInit) => {
            for (const key of Object.keys(headers)) {
                (configuration.fetch.headers as {[key: string]:string })[key] = (headers as {[key: string]:string })[key];
            }
        },
 
        get: async <T>(setUrl: string) => {
            url = setUrl;
            const {request, response, parsedResponse} = await factory<T>(Method.GET);
            return {request, response, parsedResponse} 
        },
 
        post: async <T>(setUrl: string, bodyInit: object) => {
            url = setUrl;
            body = bodyInit
            console.log({body})
            const {request, response, parsedResponse} = await factory<T>(Method.POST);
            return {request, response, parsedResponse} 
            
        },
 
        retry: async <T>(requestInit: RequestInit) => {
            configuration.fetch = {...requestInit}
            return await <T>factory();
        }
    }
}

export default useApi;