import type { ITokenResponse } from "@/interfaces";
import axios, { type AxiosResponse } from "axios";

export default function useAxios() {
    const instance = axios.create({
        baseURL: 'https://bdev.s89.eu/api/',
        timeout: 1000,
        headers: {'X-OBB-Client': '1'}
    });

    instance.interceptors.response.use(function (response) {
        // Any status code that lie within the range of 2xx cause this function to trigger
        // Do something with response data
        return response;
      }, function (error) {
        
        return Promise.reject(error);
      });

    function setToken(token: string) {
        instance.defaults.headers.common['Authorization'] = token;
    };

    function removeToken() {
        instance.defaults.headers.common['Authorization'] = null;
    };

    async function refreshToken() {
        const {data, status, statusText} = await instance.post<{}, AxiosResponse<ITokenResponse>>('refresh', {});
    }

    return {
        setToken,
        removeToken,
        refreshToken,
        axios: instance,
    }
}