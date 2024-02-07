import type { IHydra, IPost, IUser } from '@/interfaces'
import { useRoute } from 'vue-router'
import useAxios from '../useAxios'
import { FORGET_PASSWORD, POST_URL, RESET_PASSWORD, USER_URL } from '@/utils/apiRoutes'
import instanceOf from '@/utils/instanceOf'
import { ref } from 'vue'

export function useUser() {
  const route = useRoute()
  const { axios } = useAxios()
  const userPosts = ref<IPost[]>([])
  const lastPage = ref(false)

  async function getUser(id?: number) {
    const { data } = await axios.get<unknown>(`${USER_URL}/${id ?? route.params.id}`)
    console.log(data)

    if (instanceOf<IUser>(data)) return data
  }

  async function getUserPosts(page?: string) {
    if (lastPage.value) {
      return false
    }

    const { data } = await axios.get<unknown>(
      //`${USER_URL}/${route.params.id}/posts?limit=20&page=${page ?? 1}`
      `${POST_URL}?limit=20&page=${page ?? 1}&user.id=${route.params.id}`
    )
    if (instanceOf<IHydra<IPost>>(data)) {
        console.log({data})
      userPosts.value.push(...data['hydra:member'])
      if (data['hydra:view']) {
        if (!data['hydra:view']['hydra:next']) {
          lastPage.value = true
          return false;
        }
      }

      return true;
    }
  }

  async function forgetPassword(email: string) {
    const {data} = await axios.post(FORGET_PASSWORD, {
      email
    })

    console.log('forgetPassword',{data})
  }

  async function resetPassword(password: string, hash: string) {
    const {data} = await axios.post(RESET_PASSWORD, {
      password,
      hash
    })

    console.log('forgetPassword',{data})
  }

  return {
    getUser,
    getUserPosts,
    userPosts,
    forgetPassword,
    resetPassword
  }
}
