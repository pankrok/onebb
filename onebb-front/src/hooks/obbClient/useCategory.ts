import { useRoute } from 'vue-router'
import type { ICategory, IHydra } from '@/interfaces'
import instanceOf from '@/utils/instanceOf'
import useAxios from '../useAxios'
import { CATEGORY_URL } from '@/utils/apiRoutes'

export async function useCategory() {
  const route = useRoute()
  const {axios} = useAxios()
  const endpoint = CATEGORY_URL + (route.params.id ? `/${route.params.id}` : '')
  const { data } = await axios.get<IHydra<ICategory> | ICategory>(endpoint)
  
  if (instanceOf<IHydra<ICategory>>(data)) {
    return typeof data['hydra:member'] !== 'undefined'
      ? data['hydra:member']
      : [data]
  }

  return null;
}
