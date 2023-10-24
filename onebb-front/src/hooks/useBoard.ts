import type { IBoard, IPlot, IHydra } from '@/interfaces'
import useApi from './useApi'
import { useRoute } from 'vue-router'
import { ref } from 'vue'

const useBoard = async () => {
  const route = useRoute()
  const api = useApi()
  const boardEndpoint = `boards/${route.params.id}`
  const plotsEndpoint = `plots?board.id=${route.params.id}&page=${route.params.page}`
  const bresponse = await api.get<IBoard>(boardEndpoint)
  const presponse = await api.get<IHydra<IPlot>>(plotsEndpoint)

  return {
    board: bresponse.parsedResponse,
    plots: presponse.parsedResponse ? presponse.parsedResponse['hydra:member'] : []
  }
}

export default useBoard
