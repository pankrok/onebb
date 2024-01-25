import type { IPlot, IPost, IHydra, IHydraView } from '@/interfaces'
import useAxios from '../useAxios'
import { useRoute, useRouter } from 'vue-router'
import { PLOT_URL, POST_URL } from '@/utils/apiRoutes'
import instanceOf from '@/utils/instanceOf'

interface IResponse {
  plot: IPlot | null
  posts: IPost[] | null
  paginator: IHydraView | null
}

export function usePlot() {
  const { axios } = useAxios()
  const route = useRoute()

  async function getPlot() {
    
    const response: IResponse = {
      plot: null,
      posts: null,
      paginator: null,
    }

    const plotEndpoint = `${PLOT_URL}/${route.params.id}`
    const postsEndpoint = `${PLOT_URL}/${route.params.id}/posts?page=${route.params.page}`
    const plotFullResponse = await axios.get<unknown>(plotEndpoint)
    if (instanceOf<IPlot>(plotFullResponse.data)) {
      response.plot = plotFullResponse.data
    }

    const postFullResponse = await axios.get<unknown>(postsEndpoint)
    if (instanceOf<IHydra<IPost>>(postFullResponse.data)) {
      response.posts = postFullResponse.data['hydra:member']
      response.paginator = postFullResponse.data['hydra:view'] ?? null
    }

    return response
  }

  async function addPostToPlot(content: string, plot: string) {
    const { data } = await axios.post<unknown>(POST_URL, {
        content,
        plot,
    })

    return data;
  }

  async function createPlot(payload: any) {
     
     const {name, content} = payload;
     const {data} = await axios.post<unknown>(PLOT_URL, {
        name,
        board: `/api/boards/${route.params.id}`,
     })
     console.log({data})
     if(instanceOf<IPlot>(data)) {
        const post = await addPostToPlot(content, data['@id']);
        if(instanceOf<IPost>(post)) {
          const router = useRouter();
          console.log({data, post, params: { id: data.id, slug: data.slug, page: 1 }, router})
          
          router.push({ name: 'Plot', params: { id: data.id, slug: data.slug, page: 1 } });
        }
     }
    
  }

  async function updatePost(id: number, payload: {content?: string, hidden?: boolean}) {
    const {data} = await axios.put<unknown>(`${POST_URL}/${id}`, payload)

    console.log('updatePost', {data})
   
 }

  return {
    getPlot,
    addPostToPlot,
    createPlot,
    updatePost,
  }
}


