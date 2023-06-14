import type { IBoard, IPlot,IHydra } from "@/interfaces/OnebbInterfaces";
import useApi from "./useApi"

const useBoard = async (id: string|string[]|number, page:string|string[]|number) => {
    const api = useApi()
    const boardEndpoint = `boards/${id}`;
    const plotsEndpoint = `plots?board.id=${id}&page=${page}`;
    let bresponse = await api.get<IBoard>(boardEndpoint);
    const boardResponse: IBoard|undefined = bresponse.parsedResponse;
    let presponse =  await api.get<IHydra<IPlot>>(plotsEndpoint);
    const plotsResponse:IHydra<IPlot>|undefined = presponse.parsedResponse

    return {boardResponse, plotsResponse}
}

export default useBoard;