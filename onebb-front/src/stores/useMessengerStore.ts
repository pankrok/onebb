import type { IMessage, IOneMessenger, IUser } from '@/interfaces'
import { defineStore } from 'pinia'
import { ref } from 'vue'

const useMessengerStore = defineStore('messengerStore', () => {
  const showMessenger = ref(false)
  const lastReadMessageTime = ref(null)
  const chats = ref<IOneMessenger[]>([])
  const currentChat = ref<IOneMessenger | null>(null)
  const messages = ref<IMessage[]>([])
  const userList = ref<IUser[]>([])
  const chatUsers = ref<IUser[]>([])
  const component = ref(0)

  function toggleMessenger() {
    showMessenger.value = !showMessenger.value
  }

  function setChats(payload: IOneMessenger[]) {
    chats.value = payload
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

  return {
    showMessenger,
    userList,
    chatUsers,
    component,
    messages,
    currentChat,
    toggleMessenger,
    setChats,
    updateUserList,
    setChatUsers,
    setComponent,
    setCurrentChat
  }
})

export default useMessengerStore
