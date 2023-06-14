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
    

export const useUser = () => {
    const api = useApi();

    const setData = (newData: ITokenResponse) => {
        for (const key of Object.keys(data)) {
            (data as {[key: string]:any })[key] = (newData as {[key: string]:any })[key];
        }
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
            const {parsedResponse} = await api.post<ITokenResponse>(endpoint, creditionals);
            if (parsedResponse?.code) {
                throw new Error(parsedResponse.message)
            }
            if (!parsedResponse) {
                throw new Error('Data fetch error!')
            }
            setData(parsedResponse);
            api.setHeaders({Authorization: `Bearer ${parsedResponse.token}`});
            
            return true;
        } catch(e) {
            console.error(e);
            return false;
        }
    }

    const logout = () => {
        setData(initialState);
    }

    return {
        parseUsername,
        getUser,
        login,
        logout,
        getToken,
    }
}