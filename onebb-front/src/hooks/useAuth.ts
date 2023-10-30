import type { ITokenResponse, ILoginCreditionals } from "@/interfaces";
import useAxios from "./useAxios";
import { AUTH_URL } from "@/helpers/api";
import { instanceOf } from "./helpers";
import type { AxiosResponse } from "axios";
import useUserStore from "@/stores/useUserStore";

export default function useAuth() {
    const { axios } = useAxios();
    async function signIn(payload: ILoginCreditionals) {
        const {data} = await axios.post<ILoginCreditionals, AxiosResponse<unknown>>(AUTH_URL, payload);
        if (instanceOf<ITokenResponse>(data)) {
            const userStore = useUserStore();
            userStore.setUserDate(data);
            return true;
        }

        return false;
    }

    return {signIn}
}