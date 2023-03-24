import {
  HeadersInterface,
  ConfigInterface,
  ApiClientInterface,
  ResponseInterface,
  RequestInterface,
  HydraInterface,
} from "./apiInterface";

const GET = "GET";
const POST = "POST";
const PUT = "PUT";
const DELETE = "DELETE";

class Api {
  #defaultHeaders: HeadersInterface = {
    "Content-Type": "application/ld+json",
    Accept: "application/ld+json",
    "X-ONEBB-ADMIN": "true",
    Authorization: "",
  };

  #defaultConfig: ConfigInterface = {
    method: "GET",
    credentials: "include",
    mode: "cors",
    cache: "no-cache",
    redirect: "follow",
    referrerPolicy: "no-referrer",
    // @ts-ignore
    headers: this.#defaultHeaders,
    body: null,
  };

  #client: ApiClientInterface = {
    token: undefined,
    resource: "",
    params: [],
    config: this.#defaultConfig,
  };

  #request: RequestInterface<unknown> = {
    url: "",
    config: this.#defaultConfig,
  };

  #storedRequest = this.#request;

  #intercept: Function | null = null;
  maxRetry: number = 3;
  retryMsDelay = 1000;
  #retryHandler = 0;

  async saveRequest(): Promise<boolean> {
    return new Promise(reslove => {
      this.#storedRequest = this.#request;
      reslove(true);
    })
    
  }

  getPreviosRequest() {
    return this.#storedRequest;
  }

  setRequestToken() {
    if (this.#client.token) {
      this.#request.config.headers.Authorization = `Bearer ${
        this.#client.token
      }`;
    }
  }

  #prepareRequest(request: RequestInterface<unknown> | null = null) {
    if (request) {
      this.#request = request;
      this.setRequestToken();
      return null;
    }

    if (this.#client.resource == "") {
      throw "Resourece must be specified before method!";
    }

    // @ts-ignore
    this.#request.url = this.#client.resource;
    if (this.#client.params.length) {
      this.#request.url += () => {
        let handler = "?";
        for (const [key, value] of Object.entries(this.#client.params)) {
          handler += `${key}=${value}&`;
        }

        return handler.slice(0, -1);
      };
    }
    this.#request.config = this.#client.config;
    this.setRequestToken();
  }

  #fetchRequest<T>(
    req: null | RequestInterface<unknown> = null
  ): Promise<ResponseInterface<T>> {
    this.#prepareRequest(req);

    return new Promise(async (reslove) => {
      const response: ResponseInterface<T> = {
        code: 0,
        count: undefined,
        next: false,
        prev: false,
        body: undefined,
      };
      // @ts-ignore
      let res = await fetch(this.#request.url, this.#request.config);
      if (this.#intercept) {
        res = await this.#intercept(this.#request, res);
      }
      //console.log({res})
      if (res.status > 399 && res.status < 500) {
        if (this.#retryHandler < this.maxRetry) {
          this.#retryHandler++;
          setTimeout(() => {
            reslove(this.#fetchRequest());
          }, this.retryMsDelay);
        }
        console.error("API ERROR");
        //console.error(res);
      }

      if (res.status < 200 || res.status > 299) {
        console.warn("API ERROR");
        //console.warn({ res });
      }
      if (req) {
        // TODO: fixe interface
        // @ts-ignore
        reslove(res);
        return null;
      }

      response.code = res.status;
      if (
        res.headers.get("content-type") === "application/json" ||
        res.headers.get("content-type") === "application/json; charset=utf-8" ||
        res.headers.get("content-type") === "application/ld+json; charset=utf-8"
      ) {
        res.json().then((data) => {
          response.count = data["hydra:totalItems"]
            ? data["hydra:totalItems"]
            : undefined;
          if (data["hydra:view"]) {
            response.next = data["hydra:view"]["hydra:next"] ? true : false;
            response.prev = data["hydra:view"]["hydra:previous"] ? true : false;
          }
          response.body = data["hydra:member"] ? data["hydra:member"] : data;
          reslove(response);
        });
      } else {
        throw new Error("Not a json response");
      }
    });
    //.catch((error) => {
    //   console.error("API Error:", error);
    //  });
  }

  intercept(cb: Function) {
    this.#intercept = cb;
  }

  async retry(req: RequestInterface<unknown>) {
    const response = await this.#fetchRequest(req);
    return response;
  }

  setResource(resource: string) {
    console.warn("[OBB API] setResource method is depreceted!");
    this.#client.resource = resource;
    return this;
  }

  async setToken(token: string): Promise<boolean> {
    return new Promise(reslove => {
      this.#client.token = token;
      reslove(true);
    })
  }

  async get<T>(cfg: {
    resource: string;
    id?: number | null;
    query?: string | null;
  }): Promise<ResponseInterface<T>> {
    const { id, resource, query } = cfg;
    this.#client.config.method = GET;

    if (resource) {
      this.#client.resource = resource;
    }

    if (id) {
      this.#client.resource += `/${id}`;
    }

    if (query) {
      this.#client.resource += `/${query}`;
    }

    if (this.#client.config.body !== null) {
      this.#client.config.body = null;
    }

    const response: ResponseInterface<T> = await this.#fetchRequest();
    return response;
  }

  async post<ReqT, ResT>(cfg: {
    resource: string;
    body?: ReqT;
    id?: number | null;
  }): Promise<ResponseInterface<ResT>> {
    const { resource, body, id } = cfg;
    this.#client.config.method = POST;

    if (resource) {
      this.#client.resource = resource;
    }

    if (id) {
      this.#client.resource += `/${id}`;
    }

    if (body) {
      this.#client.config.body = JSON.stringify(body);
    }

    const response: ResponseInterface<ResT> = await this.#fetchRequest();
    return response;
  }

  async put<T>(body?: any, id?: number | null): Promise<ResponseInterface<T>> {
    this.#client.config.method = PUT;
    if (id) {
      this.#client.resource += `/${id}`;
    }

    if (body) {
      this.#client.config.body = JSON.stringify(body);
    }

    const response: ResponseInterface<T> = await this.#fetchRequest();
    return response;
  }

  async delete<T>(id: number | null): Promise<ResponseInterface<T>> {
    this.#client.config.method = DELETE;
    this.#client.resource += `/${id}`;

    const response: ResponseInterface<T> = await this.#fetchRequest();
    return response;
  }
}
const api = new Api();
export default api;
