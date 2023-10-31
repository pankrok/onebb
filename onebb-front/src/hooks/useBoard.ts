import type { IBoard, IPlot, IHydra } from '@/interfaces'
import useApi from './useApi'
import { useRoute } from 'vue-router'
import useAxios from './useAxios'

const useBoard = async () => {
  const route = useRoute()
  const {axios} = useAxios()
  const boardEndpoint = `boards/${route.params.id}`
  const plotsEndpoint = `plots?board.id=${route.params.id}&page=${route.params.page}`
  // fixme this shoud be unknown responses
  const bresponse = await axios.get<IBoard>(boardEndpoint)
  const presponse = await axios.get<IHydra<IPlot>>(plotsEndpoint)

  return {
    board: bresponse.data,
    plots: presponse.data ? presponse.data['hydra:member'] : []
  }
}

export default useBoard
