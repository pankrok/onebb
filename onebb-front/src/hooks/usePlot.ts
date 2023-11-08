import type { IPlot, IPost, IHydra, ICreatePlot } from '@/interfaces'
import useAxios from './useAxios'
import { useRoute } from 'vue-router'
import { PLOT_URL, POST_URL } from '@/helpers/api'
import { instanceOf } from './helpers'

interface IResponse {
  plot: IPlot | null
  posts: IHydra<IPost> | null
}

export default function usePlot() {
  const { axios } = useAxios()

  async function getPlot() {
    const route = useRoute()
    const response: IResponse = {
      plot: null,
      posts: null
    }

    const plotEndpoint = `${PLOT_URL}/${route.params.id}`
    const postsEndpoint = `${PLOT_URL}/${route.params.id}/posts?page=${route.params.page}`
    const plotFullResponse = await axios.get<unknown>(plotEndpoint)
    if (instanceOf<IPlot>(plotFullResponse.data)) {
      response.plot = plotFullResponse.data
    }

    const postFullResponse = await axios.get<unknown>(postsEndpoint)
    if (instanceOf<IHydra<IPost>>(postFullResponse.data)) response.posts = postFullResponse.data

    return response
  }

  async function addPostToPlot(content: string, plot: string) {
    const { data } = await axios.post<unknown>(POST_URL, {
        content,
        plot,
    })

    return data;
  }

  async function createPlot(payload: ICreatePlot) {
    console.log(payload)
    // const {axios} = useAxios();
    // const userStore = useUserStore();
  
    // const {data} = await axios.post()
  }

  return {
    getPlot,
    addPostToPlot,
    createPlot,
  }
}


