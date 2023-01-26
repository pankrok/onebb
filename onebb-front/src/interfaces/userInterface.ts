export interface IUser {
    loggedIn: boolean,  
    acp: boolean,
    mcp: boolean,
    uid?: number | null,
    avatar?: string,
    slug: string,
}