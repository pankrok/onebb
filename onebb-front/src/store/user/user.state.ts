import { IUser}  from '@/interfaces/userInterface';

export const state: IUser =  {
    loggedIn: false,  
    uid: null,
    acp: false,
    mcp: false,
    avatar: undefined,
    slug: '',
};
