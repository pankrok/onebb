import { reactive } from 'vue'
import type { IAlert } from '@/interfaces'

const alerts = reactive<IAlert[]>([])
const defaultTimeout = 300000

export function useToast() {
  const setAlert = (props: IAlert, msTimeout?: number) => {
    alerts.push(props)
    setTimeout(() => {
      alerts.shift()
    }, msTimeout ?? defaultTimeout)
  }

  const getAlerts = () => {
    return alerts
  }

  return {
    setAlert,
    getAlerts
  }
}