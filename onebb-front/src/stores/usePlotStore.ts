import { instanceOf } from '@/hooks/helpers'
import usePlot from '@/hooks/usePlot'
import type { IPlot, IPost, IHydraView } from '@/interfaces'
import { defineStore } from 'pinia'
import { ref } from 'vue'

const useBoardStore = defineStore('boardStore', () => {
  const posts = ref<IPost[]>([])
  const plot = ref<IPlot | null>(null)
  const hydraView = ref<IHydraView | null>(null)
  const { getPlot, addPostToPlot } = usePlot()

  async function getPlotData() {
    const response = await getPlot()
    plot.value = response.plot
    if (response.posts !== null) {
      posts.value = response.posts['hydra:member']
      hydraView.value = response.posts['hydra:view'] ?? null
    }
  }

  async function addPost(content: string) {
    if (plot.value === null) {
      console.error('ERR CODE 1.1')
      return
    }

    const data = await addPostToPlot(content, plot.value['@id'])

    if (instanceOf<IPost>(data)) {
      posts.value = [...posts.value, data]
      return
    }

    console.error('ERR CODE 1.2')
  }

  function $reset() {
    plot.value = null
    hydraView.value = null
    posts.value = []
  }

  return { plot, posts, hydraView, getPlotData, addPost, $reset }
})

export default useBoardStore
