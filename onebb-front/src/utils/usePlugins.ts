import useAxios from '@/hooks/useAxios'
import { PLUGIN_DISPATCH } from './apiRoutes'
import { reactive } from 'vue'

const pluginsList = reactive<{ [index: string]: Function[] }>({})
let init = 0
export function initPlugins(page?: string) {
  

  if (page) {
    console.log(`🚀 initPlugins on page ${page} :`, {
      name: page,
      pluginsList: pluginsList[page]
    })

    if (pluginsList[page] === undefined && init < 4) {
      setTimeout(() => {
        console.log('timeout', init)
        init++
        initPlugins(page)
      }, 500)

      return
    }

    if (pluginsList[page]) {
      pluginsList[page].forEach((pluginFn) => {
        pluginFn()
      })
    }
  }
}

export default function usePlugins() {
  const { axios } = useAxios()
  function subscribe(page: string, fn: Function) {
    if (!pluginsList[page]) {
      pluginsList[page] = []
    }

    pluginsList[page].push(fn)
    console.log('subscribe', { page, fn, pluginList: pluginsList[page].length })
  }

  async function dispatch(plugin: string, event: string, data?: any) {
    const response = await axios.post(PLUGIN_DISPATCH, {
      plugin,
      event,
      data
    })

    return response
  }

  return { subscribe, dispatch }
}
