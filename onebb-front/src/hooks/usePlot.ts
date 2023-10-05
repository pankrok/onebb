import type { IPlot, IPost, IHydra } from '@/interfaces'
import useApi from './useApi'
import { useRoute } from 'vue-router'

export default async function usePlot() {
  const api = useApi()
  const route = useRoute()
  const plotEndpoint = `plots/${route.params.id}`
  const postsEndpoint = `plots/${route.params.id}/posts?page=${route.params.page}`
  const plotFullResponse = await api.get<IPlot>(plotEndpoint)
  const plotResponse: IPlot | undefined = plotFullResponse.parsedResponse
  const postFillResponse = await api.get<IHydra<IPost>>(postsEndpoint)
  const postsResponse: IHydra<IPost> | undefined = postFillResponse.parsedResponse

  return { plotResponse, postsResponse }
}
