import type { IUser, ILoginCreditionals, ITokenResponse } from "@/interfaces/OnebbInterfaces"
import useApi from "./useApi";

class OnebbUser {

    #data: ITokenResponse;
    #initialState: ITokenResponse;
    
    constructor() {
        this.#initialState = {
            token: '',
            acp_enabled: false,
            mcp_enabled: false,
            avatar: '',
            slug: '',
            uid: 0,
        }
        this.#data = this.#initialState;
    }

    getData = () => {
        return this.#data;
    }

    setData = (data: ITokenResponse) => {
        this.#data = data;
    }

    logout = () => {
        this.#data = this.#initialState;
    }
}

const obbUser = new OnebbUser();

export const useUser = () => {
    const api = useApi();
    const parseUsername = (user: IUser) => {
        return user.user_group.html_code.replace("{{username}}", user.username);
    }

    const getUser = () => {
        return obbUser.getData();
    }

    const login = async (creditionals: ILoginCreditionals) => {
        const endpoint = 'login'
        try {
            const response = await api.post<ITokenResponse, ILoginCreditionals>(endpoint, creditionals);
            if (response?.code) {
                throw new Error(response.message)
            }
            obbUser.setData(response);
            return true;
        } catch(e) {
            console.error(e);
            return false;
        }
    }

    const logout = () => {
        obbUser.logout();
    }

    return {
        parseUsername,
        getUser,
        login,
        logout,
    }
}