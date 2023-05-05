import type { IUser } from "@/interfaces/OnebbInterfaces"

export const useUser = () => {
    const parseUsername = (user: IUser) => {
        return user.user_group.html_code.replace("{{username}}", user.username);
    }

    const getId = () => {
        // return int or null
    }

    const login = (user: IUser) => {
        // store data
    }

    const logout = () => {
        // logout
    }

    return {
        parseUsername,
    }
}