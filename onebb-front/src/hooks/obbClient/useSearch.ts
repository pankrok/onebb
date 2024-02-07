import type { IPost, IHydra } from '@/interfaces'
import useAxios from '../useAxios'
import { useRoute, useRouter } from 'vue-router'
import { PLOT_URL, POST_URL } from '@/utils/apiRoutes'
import instanceOf from '@/utils/instanceOf'


export function useSearch() {
  const { axios } = useAxios()
  async function findPosts(query: string) {
    const { data } = await axios.get<unknown>(`${POST_URL}?page=1&limit=20&content=${query}`)
    //&user.username=grzyb
    if (instanceOf<IHydra<IPost>>(data)) {
      return data
    }
  }

  return {
    findPosts
  }
}
