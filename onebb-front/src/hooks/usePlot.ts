import type { IPlot, IPost } from "@/interfaces/OnebbInterfaces";
import useApi from "./useApi"

const useBoard = async (id: string|string[]|number, page:string|string[]|number) => {
    const api = useApi()
    const plotEndpoint = `plots/${id}`;
    const postsEndpoint = `plots/${id}/posts?page=${page}`;
    const plotResponse: IPlot = await api.get(plotEndpoint);
    const postsResponse:{'hydra:member': IPost[]} = await api.get(postsEndpoint);

    return {plotResponse, postsResponse: postsResponse['hydra:member']}
}

export default useBoard;