import useAxios from './useAxios'
import { instanceOf } from './helpers'
import type { SkinBox, ISkinHydra, ISkinConfiguration } from '@/interfaces/config'

function parseBoxes(boxlist: SkinBox[]) {
  const boxes: ISkinConfiguration = {
    Home: {
      top: null,
      bottom: null,
      left: null,
      right: null
    },
    Category: {
      top: null,
      bottom: null,
      left: null,
      right: null
    },
    Board: {
      top: null,
      bottom: null,
      left: null,
      right: null
    },
    CreatePlot: {
      top: null,
      bottom: null,
      left: null,
      right: null
    },
    Plot: {
      top: null,
      bottom: null,
      left: null,
      right: null
    },
    Profile: {
      top: null,
      bottom: null,
      left: null,
      right: null
    },
    Page: {
      top: null,
      bottom: null,
      left: null,
      right: null
    },
    UserConfiguration: {
      top: null,
      bottom: null,
      left: null,
      right: null
    }
  }
  boxlist.forEach((val) => {
    if (val.page && val.position && val.id) {
      if (boxes[val.page][val.position] === null) {
        boxes[val.page][val.position] = []
      }

      boxes[val.page][val.position]?.push({
        id: val.id,
        name: val.box.name,
        engine: val.box.engine,
        html: val.box.html
      })
    }
  })

  return boxes
}

export default async function useSkin() {
  const { axios } = useAxios()
  const { data } = await axios.get<unknown>('skins?active=1')
  if (instanceOf<ISkinHydra>(data)) {
    const handler = data['hydra:member'][0].skinBoxes
    return await parseBoxes(handler)
  }

  return null
}
