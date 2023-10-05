import type { IBoard, IPlot, IHydra } from '@/interfaces'
import useApi from './useApi'
import { useRoute } from 'vue-router'
import { ref } from 'vue'

const useBoard = async () => {
  const board = ref<IBoard>()
  const plots = ref<IPlot[]>()
  const route = useRoute()
  const api = useApi()
  const boardEndpoint = `boards/${route.params.id}`
  const plotsEndpoint = `plots?board.id=${route.params.id}&page=${route.params.page}`
  const bresponse = await api.get<IBoard>(boardEndpoint)
  board.value = bresponse.parsedResponse
  const presponse = await api.get<IHydra<IPlot>>(plotsEndpoint)
  plots.value = presponse.parsedResponse ? presponse.parsedResponse['hydra:member'] : []

  return { board, plots }
}

export default useBoard
