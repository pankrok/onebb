import type { IPlot, IPost, IHydra } from "@/interfaces/OnebbInterfaces";
import useApi from "./useApi"

const useBoard = async (id: string|string[]|number, page:string|string[]|number) => {
    const api = useApi()
    const plotEndpoint = `plots/${id}`;
    const postsEndpoint = `plots/${id}/posts?page=${page}`;
    const plotFullResponse = await api.get<IPlot>(plotEndpoint);
    const plotResponse: IPlot|undefined = plotFullResponse.parsedResponse
    const postFillResponse = await api.get<IHydra<IPost>>(postsEndpoint);
    const postsResponse: IHydra<IPost>|undefined = postFillResponse.parsedResponse

    return {plotResponse, postsResponse}
}

export default useBoard;