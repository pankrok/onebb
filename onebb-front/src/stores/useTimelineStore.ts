import { defineStore } from 'pinia'
import { reactive } from 'vue'
import moment from 'moment'

let onInit = true

const useTimelineStore = defineStore('timelineStore', () => {
  let initData = {
    categoryTimeline: null,
    boardTimeline: null,
    plotTimeline: null,
  };
  if (localStorage.getItem('timeline')) { // @ts-ignore
    initData = JSON.parse(localStorage.getItem('timeline'))
  }

  const categoryTimeline: { [index: number]: string } = reactive(initData.categoryTimeline ?? {})
  const boardTimeline: { [index: number]: string } = reactive(initData.boardTimeline ?? {})
  const plotTimeline: { [index: number]: string } = reactive(initData.plotTimeline ?? {})

  function setLocalTimeline() {
    localStorage.setItem('timeline', JSON.stringify({
      categoryTimeline,
      boardTimeline,
      plotTimeline
    }))
  }

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
    setLocalTimeline();
  }

  function setBoardTimeline(id: number) {
    boardTimeline[id] = moment().format()
    setLocalTimeline();
  }

  function setPlotTimeline(id: number) {
    plotTimeline[id] = moment().format()
    setLocalTimeline();
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
