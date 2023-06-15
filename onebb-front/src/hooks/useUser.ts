import type { IUser, ILoginCreditionals, ITokenResponse } from "@/interfaces/OnebbInterfaces"
import useApi from "./useApi";

    
const initialState: ITokenResponse = {
    token: '',
    acp_enabled: false,
    mcp_enabled: false,
    avatar: '',
    slug: '',
    uid: 0,
}

const data: ITokenResponse = {...initialState};
let isRefreshing = 0;    

export const useUser = () => {
    const api = useApi();

    const setData = (newData: ITokenResponse) => {
        for (const key of Object.keys(data)) {
            (data as {[key: string]:any })[key] = (newData as {[key: string]:any })[key];
        }

        localStorage.setItem('obbLogged', '1');
    }

    const parseUsername = (user: IUser) => {
        return user.user_group.html_code.replace("{{username}}", user.username);
    }

    const getUser = () => {
        return {...data}
    }

    const getToken = () => {
        return data.token;
    }

    const login = async (creditionals: ILoginCreditionals) => {
        const endpoint = 'login'
        console.log({creditionals});
        
        try {
            const {parsedResponse} = await api.post<ITokenResponse>(endpoint, {username: creditionals.username, password: creditionals.password});
            if (parsedResponse?.code) {
                throw new Error(parsedResponse.message)
            }
            if (!parsedResponse) {
                throw new Error('Data fetch error!')
            }
            setData(parsedResponse);
            console.log({parsedResponse})
            api.setToken(parsedResponse.token);
            
            return true;
        } catch(e) {
            console.error(e);
            return false;
        }
    }

    const refresh = async () => {
        const endpoint = 'refresh'
        
        try {
            const {parsedResponse} = await api.post<ITokenResponse>(endpoint,{});
            if (parsedResponse?.code) {
                throw new Error(parsedResponse.message)
            }
            if (!parsedResponse) {
                throw new Error('Data fetch error!')
            }
            setData(parsedResponse);
            console.log({parsedResponse})
            api.setToken(parsedResponse.token);
            isRefreshing = 0;
            return true;
        } catch(e) {
            console.error(e);
            return false;
        }
    }

    const logout = () => {
        setData(initialState);
    }

    if (localStorage.getItem('obbLogged') === '1' && data.token === '' && isRefreshing === 0) {
        isRefreshing = 1;
        refresh();
    }

    return {
        parseUsername,
        getUser,
        login,
        logout,
        getToken,
    }
}