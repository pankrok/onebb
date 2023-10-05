import { ref } from 'vue'
import { useRoute } from 'vue-router'
import useApi from './useApi'
import type { ICategory, IHydra } from '@/interfaces'
import { instanceOf } from '@/hooks/helpers'

export default async function useCategory() {
  const route = useRoute()
  const api = useApi()
  const endpoint = 'categories' + (route.params.id ? `/${route.params.id}` : '')
  const { parsedResponse } = await api.get<IHydra<ICategory> | ICategory>(endpoint)
  console.log({ parsedResponse })
  if (typeof parsedResponse === 'undefined') {
    return null
  }

  if (instanceOf<IHydra<ICategory>>(parsedResponse)) {
    return typeof parsedResponse['hydra:member'] !== 'undefined'
      ? parsedResponse['hydra:member']
      : [parsedResponse]
  }
}
