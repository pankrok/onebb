import useAxios from '../useAxios'
import { RESPECT } from '@/utils/apiRoutes'
import useUserStore from '@/stores/useUserStore'
import instanceOf from '@/utils/instanceOf'
import type { IRespect, IHydra } from '@/interfaces'
import { storeToRefs } from 'pinia'
interface IAddRespect {
  postId: number
  respectTo: number
  type: 'smile' | 'thumb' | 'heart'
}
const { getUserId } = storeToRefs(useUserStore())
const { axios } = useAxios()
export default function useRespect() {
  async function manageRespect({ postId, respectTo, type }: IAddRespect) {
    const { data } = await axios.get<unknown>(
      `${RESPECT}?page=1&limit=1&post.id=${postId}&respectFrom.id=${getUserId.value}&respectTo.id=${respectTo}`
    )
    if (instanceOf<IHydra<IRespect>>(data)) {
      console.log({ data })
      if (data['hydra:member'].length > 0 && type !== data['hydra:member'][0].type) {
        const { id } = data['hydra:member'][0]
        const { status } = await axios.put(`${RESPECT}/${id}`, {
          post: `/api/posts/${postId}`,
          respectFrom: `/api/users/${getUserId.value}`,
          respectTo: `/api/users/${respectTo}`,
          type
        })
        return {status, type: 'put'}
      }

      if (data['hydra:member'].length > 0 && type === data['hydra:member'][0].type) {
        const { id } = data['hydra:member'][0]
        const { status } = await axios.delete(`${RESPECT}/${id}`)
        return {status, type: 'delete'}
      }
    }

    const { status } = await axios.post(`${RESPECT}`, {
      post: `/api/posts/${postId}`,
      respectFrom: `/api/users/${getUserId.value}`,
      respectTo: `/api/users/${respectTo}`,
      type
    })
    return {status, type: 'post'}
  }

  async function showPostRespect(postId: number) {
    const { data } = await axios.get<unknown>(`${RESPECT}?page=1&limit=60&post.id=${postId}`)

    if (instanceOf<IHydra<IRespect>>(data)) {
      return data['hydra:member']
    }
  }

  return {
    manageRespect,
    showPostRespect
  }
}
