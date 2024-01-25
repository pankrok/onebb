import type { IBoard, IPlot, IHydra } from '@/interfaces'
import { useRoute } from 'vue-router'
import useAxios from '../useAxios'

export async function useBoard() {
  const route = useRoute()
  const {axios} = useAxios()
  const boardEndpoint = `boards/${route.params.id}`
  const plotsEndpoint = `plots?board.id=${route.params.id}&page=${route.params.page}`
  // fixme this shoud be unknown responses
  const bresponse = await axios.get<IBoard>(boardEndpoint)
  const presponse = await axios.get<IHydra<IPlot>>(plotsEndpoint)

  return {
    board: bresponse.data,
    plots: presponse.data ? presponse.data['hydra:member'] : [],
    paginator: presponse.data['hydra:view']
  }
}
