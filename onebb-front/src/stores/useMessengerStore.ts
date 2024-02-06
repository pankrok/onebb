import type { IMessage, IOneMessenger, IUser } from '@/interfaces'
import useAlertStore from './useAlertStore'
import { defineStore } from 'pinia'
import { ref, watch } from 'vue'
import moment from 'moment'

const useMessengerStore = defineStore('messengerStore', () => {
  const showMessenger = ref(false)
  const lastReadMessageTime = ref('')
  const chats = ref<IOneMessenger[]>([])
  const currentChat = ref<IOneMessenger | null>(null)
  const messages = ref<IMessage[]>([])
  const userList = ref<IUser[]>([])
  const chatUsers = ref<IUser[]>([])
  const component = ref(0)
  const lastReadChat = ref(localStorage.getItem('lastReadChat') ?? '')
  const newMessages = ref(false)
  const alertStore = useAlertStore()
  watch(newMessages, (msg) => {
    if (msg === true) {
      alertStore.setAlert({
        type: 'alert-info',
        message: 'you have new message',
        timeout: 5
      })
    }
  })

  function setLastMessageTime(time: string) {
    lastReadMessageTime.value = time
    lastReadChat.value = time
    localStorage.setItem('lastReadChat', time)
    newMessages.value = false
  }

  function toggleMessenger() {
    showMessenger.value = !showMessenger.value
  }

  function setChats(payload: IOneMessenger[]) {
    chats.value = payload
    const firstItem = payload[0]
    if (moment(firstItem.updated_at) > moment(lastReadChat.value)) {
      newMessages.value = true
    }
  }

  function updateUserList(payload: IUser[]) {
    userList.value = payload
  }

  function setChatUsers(payload: IUser[]) {
    chatUsers.value = payload
  }

  function setCurrentChat(chat: IOneMessenger) {
    currentChat.value = chat
  }

  function setComponent(id: 0 | 1 | 2) {
    component.value = id
  }

  function setMessages(msgs: IMessage[]) {
    messages.value = msgs
    const [lastItem] = msgs.slice(-1)
    setLastMessageTime(lastItem.created_at)
  }

  function pushMessage(msg: IMessage) {
    messages.value.push(msg)
    setLastMessageTime(msg.created_at)
  }

  return {
    showMessenger,
    userList,
    chatUsers,
    component,
    messages,
    chats,
    currentChat,
    lastReadMessageTime,
    newMessages,
    lastReadChat,
    toggleMessenger,
    setChats,
    updateUserList,
    setChatUsers,
    setComponent,
    setCurrentChat,
    setMessages,
    pushMessage
  }
})

export default useMessengerStore
