import type { IPlot, IPost, IHydra } from '@/interfaces'
import useAxios from './useAxios'
import { useRoute } from 'vue-router'

export default async function usePlot() {
  const {axios} = useAxios()
  const route = useRoute()
  const plotEndpoint = `plots/${route.params.id}`
  const postsEndpoint = `plots/${route.params.id}/posts?page=${route.params.page}`
  const plotFullResponse = await axios.get<IPlot>(plotEndpoint)
  const plotResponse: IPlot | null = plotFullResponse.data
  const postFillResponse = await axios.get<IHydra<IPost>>(postsEndpoint)
  const postsResponse: IHydra<IPost> | null = postFillResponse.data

  return { plotResponse, postsResponse }
}
