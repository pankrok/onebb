import { defineStore } from 'pinia'
import { reactive } from 'vue'

interface IAlert {
  id: string
  type: 'alert-danger' | 'alert-info' | 'alert-warning' | 'alert-success'
  message: string
  dismiss: Function
  timeout?: number
}

const useAlertStore = defineStore('alertStore', () => {
  const alerts = reactive<IAlert[]>([])

  function setAlert({
    type,
    message,
    timeout
  }: {
    type: 'alert-danger' | 'alert-info' | 'alert-warning' | 'alert-success'
    message: string
    timeout?: number
  }) {
    const id = (Math.random() + 1).toString(36).substring(3)
    alerts.push({
      id,
      type,
      message,
      dismiss: () => {
        dismissAlert(id)
      },
      timeout
    })

    if (timeout) {
        console.log({id, timeout})
      setTimeout(() => {
        dismissAlert(id)
      }, timeout * 1000)
    }
  }

  function dismissAlert(id: string) {
    const index = alerts
      .map(function (item) {
        return item.id
      })
      .indexOf(id)

      if (index === -1) {
        return;
      }
      
    alerts.splice(index, 1)
  }

  return { alerts, setAlert }
})

export default useAlertStore
