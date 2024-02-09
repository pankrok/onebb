import { defineStore } from 'pinia'
import { reactive } from 'vue'
import moment from 'moment'

const useTimelineStore = defineStore('timelineStore', () => {
  const categoryTimeline: { [index: number]: string } = reactive({})
  const boardTimeline: { [index: number]: string } = reactive({})
  const plotTimeline: { [index: number]: string } = reactive({})

  function getCategoryTimeline(id: number, time: string) {
    if (categoryTimeline[id]) {
      return moment(time).isBefore(categoryTimeline[id])
    }

    return false
  }
  function getBoardTimeline(id: number, time: string) {
    if (boardTimeline[id]) {
      return moment(time).isBefore(boardTimeline[id])
    }

    return false
  }

  function getPlotTimeline(id: number, time: string) {
    if (plotTimeline[id]) {
      return moment(time).isBefore(plotTimeline[id])
    }

    return false
  }

  function setCategoryTimeline(id: number) {
    categoryTimeline[id] = moment().format()
  }

  function setBoardTimeline(id: number) {
    boardTimeline[id] = moment().format()
  }

  function setPlotTimeline(id: number) {
    plotTimeline[id] = moment().format()
  }

  return {
    getCategoryTimeline,
    setCategoryTimeline,
    setBoardTimeline,
    setPlotTimeline,
    getBoardTimeline,
    getPlotTimeline
  }
})

export default useTimelineStore
