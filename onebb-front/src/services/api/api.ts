import {
  HeadersInterface,
  ConfigInterface,
  ApiClientInterface,
  ResponseInterface,
  RequestInterface,
} from "./apiInterface";

const GET = "GET";
const POST = "POST";
const PUT = "PUT";
const DELETE = "DELETE";

class Api {
  #defaultHeaders: HeadersInterface = {
    "Content-Type": "application/json",
    Accept: "application/json",
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

  #request: RequestInterface = {
    url: "",
    config: this.#defaultConfig,
  };

  #prepareRequest() {
    if (this.#client.token) {
      this.#client.config.headers.Authorization = `Bearer ${
        this.#client.token
      }`;
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
  }

  #fetchRequest(): Promise<ResponseInterface> {
    this.#prepareRequest();

    return new Promise((reslove) => {
      const response: ResponseInterface = {
        code: 0,
        body: "",
      };
      // @ts-ignore
      fetch(this.#request.url, this.#request.config)
        .then((res) => {
          if (res.status < 200 || res.status > 299) {
            throw new Error("Something bad happened with API");
          }

          response.code = res.status;
          if (
            res.headers.get("content-type") ===
              "application/json" ||
            res.headers.get("content-type") ===
              "application/json; charset=utf-8" ||
            res.headers.get("content-type") ===
              "application/ld+json; charset=utf-8"
          ) {
            res.json().then((data) => {
              response.body = data;
              reslove(response);
            });
          } else {
            throw new Error("Not a json response");
          }
        })
        .catch((error) => {
          console.error("API Error:", error);
        });
    });
  }

  setResource(resource: string) {
    this.#client.resource = resource;
    return this;
  }

  setToken(token: string) {
    this.#client.token = token;
  }

  async get(id?: string | number | null): Promise<ResponseInterface> {
    this.#client.config.method = GET;
    if (id) {
      this.#client.resource += `/${id}`;
    }

    if (this.#client.config.body !== null) {
      this.#client.config.body = null;
    }

    const response: ResponseInterface = await this.#fetchRequest();
    return response;
  }

  async post(body?: any, id?: string | null): Promise<ResponseInterface> {
    this.#client.config.method = POST;
    if (id) {
      this.#client.resource += `/${id}`;
    }

    if (body) {
      this.#client.config.body = JSON.stringify(body);
    }

    const response: ResponseInterface = await this.#fetchRequest();
    return response;
  }

  async put(body?: any, id?: string | null): Promise<ResponseInterface> {
    this.#client.config.method = PUT;
    if (id) {
      this.#client.resource += `/${id}`;
    }

    if (body) {
      this.#client.config.body = JSON.stringify(body);
    }

    const response: ResponseInterface = await this.#fetchRequest();
    return response;
  }

  async delete(id: string | null): Promise<ResponseInterface> {
    this.#client.config.method = DELETE;
    this.#client.resource += `/${id}`;

    const response: ResponseInterface = await this.#fetchRequest();
    return response;
  }
}
const api = new Api();
export default api;