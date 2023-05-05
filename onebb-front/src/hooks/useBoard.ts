import type { IBoard, IPlot } from "@/interfaces/OnebbInterfaces";
import useApi from "./useApi"

const useBoard = async (id: string|string[]|number, page:string|string[]|number) => {
    const api = useApi()
    const boardEndpoint = `boards/${id}`;
    const plotsEndpoint = `plots?board.id=${id}&page=${page}`;
    const boardResponse: IBoard = await api.get(boardEndpoint);
    const plotsResponse:{'hydra:member': IPlot[]} = await api.get(plotsEndpoint);

    return {boardResponse, plotsResponse: plotsResponse['hydra:member']}
}

export default useBoard;