import { instanceOf } from '@/hooks/helpers'
import usePlot from '@/hooks/usePlot'
import type { IPlot, IPost, IHydraView } from '@/interfaces'
import { defineStore } from 'pinia'
import { ref } from 'vue'

const usePlotStore = defineStore('plotStore', () => {
  const isLoading = ref(true);
  const posts = ref<IPost[]>([])
  const plot = ref<IPlot | null>(null)
  const hydraView = ref<IHydraView | null>(null)
  const { getPlot, addPostToPlot } = usePlot()

  function addPostToArray(postsHandler: IPost[]) {
    let i = 0;
    const lenght = postsHandler.length;

    const myInterval = setInterval(() => {
      i++;
      if (i < lenght) {
        posts.value.push(postsHandler[i]);
      } else {
        clearInterval(myInterval);
        isLoading.value = false;
      }
    }, 100);
  } 

  async function getPlotData() {
    isLoading.value = true;
    posts.value = [];
    const response = await getPlot()
    plot.value = response.plot
    if (response.posts !== null) {
      hydraView.value = response.posts['hydra:view'] ?? null
      addPostToArray(response.posts['hydra:member'])
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

  return { plot, posts, hydraView, isLoading, getPlotData, addPost, $reset }
})

export default usePlotStore
