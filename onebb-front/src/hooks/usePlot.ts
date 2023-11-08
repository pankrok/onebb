import type { IPlot, IPost, IHydra, ICreatePlot } from '@/interfaces'
import useAxios from './useAxios'
import { useRoute } from 'vue-router'
import { PLOT_URL } from '@/helpers/api';
import { instanceOf } from './helpers';

interface IResponse {
  plot: IPlot|null,
  posts?: IHydra<IPost>
}

export default async function usePlot() {
  const {axios} = useAxios()
  const route = useRoute()
  const response: IResponse = {
    plot: null,
    posts: undefined
  }

  const plotEndpoint = `${PLOT_URL}/${route.params.id}`
  const postsEndpoint = `${PLOT_URL}/${route.params.id}/posts?page=${route.params.page}`
  const plotFullResponse = await axios.get<unknown>(plotEndpoint)
  if(instanceOf<IPlot>(plotFullResponse.data)) {
    response.plot = plotFullResponse.data;
  }

  const postFullResponse = await axios.get<unknown>(postsEndpoint)
  if(instanceOf<IHydra<IPost>>(postFullResponse.data))
    response.posts = postFullResponse.data;

  return response;
}

export async function createPlot(payload: ICreatePlot) {
  console.log(payload);
  // const {axios} = useAxios();
  // const userStore = useUserStore();

 // const {data} = await axios.post()

}