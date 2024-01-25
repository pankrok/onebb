import useMessengerStore from "@/stores/useMessengerStore";
import useAxios from "../useAxios";
import { ONE_MESSENGER, USER_URL } from "@/utils/apiRoutes";
import instanceOf from "@/utils/instanceOf";
import type { IOneMessenger, IHydra, IUser } from "@/interfaces";
import useUserStore from "@/stores/useUserStore";

export enum MessengerComponentEnum {
 List,
 Chat,
 Add
}


export function useMessenger() {
    const {axios} = useAxios();
    const messagnerStore = useMessengerStore();
    const userStore = useUserStore()

    async function getChats() 
    {
        const {data} = await axios.get<unknown>(`ONE_MESSENGER?users.id=${userStore.getUserId}`);
        if (instanceOf<IHydra<IOneMessenger>>(data))
            messagnerStore.setChats(data['hydra:member']);
    }

    async function findUser(payload: string) {
        if (payload.length < 4) {
            messagnerStore.updateUserList([])
            return;
        }

        const {data} = await axios.get<unknown>(`${USER_URL}?page=1&limit=20&username=${payload}`)
        console.log({data})
        if (instanceOf<IHydra<IUser>>(data)) { // FIXME
            const handler = data['hydra:member'].map((u:IUser) => {
                if(u.id != userStore.getUserId)
                    return u;
            })

             messagnerStore.updateUserList(handler);
        }
    }

    async function isChatExist()
    {
        let users = `&users.id=${userStore.getUserId}`;
       
        messagnerStore.chatUsers.forEach(u => {
            users += `&users.id=${u.id}`;
        })

        const {data} = await axios.get<unknown>(`${ONE_MESSENGER}?limit=1${users}`)

        if (instanceOf<IHydra<IOneMessenger>>(data)) {
            messagnerStore.setCurrentChat(data['hydra:member'][0]);
            return;
        }


    }

    async function sendMessage(payload: string) {
        
      
        console.log({data})
    }

    return {
        getChats,
        findUser,
        sendMessage,
    }
}