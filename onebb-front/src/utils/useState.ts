import { ref, type Ref, type UnwrapRef } from 'vue'

type TUseStateResponse<T> = [
  Ref<UnwrapRef<T> | undefined>,
  (newState: UnwrapRef<T> | undefined) => void
]

export default function useState<T = any>(defaultState?: T): TUseStateResponse<T> {
  const state = ref<T | undefined>(defaultState)
  function setState(newState: UnwrapRef<T> | undefined) {
    state.value = newState
  }
  return [state, setState]
}
