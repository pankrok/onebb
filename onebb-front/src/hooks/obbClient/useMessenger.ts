import useMessengerStore from '@/stores/useMessengerStore'
import useAxios from '../useAxios'
import { MESSAGES, ONE_MESSENGER, USER_URL } from '@/utils/apiRoutes'
import instanceOf from '@/utils/instanceOf'
import type { IOneMessenger, IHydra, IUser, IMessage } from '@/interfaces'
import useUserStore from '@/stores/useUserStore'
import moment from 'moment'

export enum MessengerComponentEnum {
  List,
  Chat,
  Add
}

export function useMessenger() {
  const { axios } = useAxios()
  const messagnerStore = useMessengerStore()
  const userStore = useUserStore()

  async function getChats() {
    const { data } = await axios.get<unknown>(`${ONE_MESSENGER}?users.id=${userStore.getUserId}`)
    if (instanceOf<IHydra<IOneMessenger>>(data)) messagnerStore.setChats(data['hydra:member'])
  }

  async function getNewChats() {
    const { data } = await axios.get<unknown>(
      `${ONE_MESSENGER}?users.id=${userStore.getUserId}&created_at[strictly_after]=${moment(
        messagnerStore.lastReadChat
      ).format('YYYY-MM-DD HH:mm:ss')}`
    )

    if (instanceOf<IHydra<IOneMessenger>>(data) && data['hydra:member'].length > 0) {
        messagnerStore.setChats(data['hydra:member'])
    }
  }

  async function getMessages() {
    if (instanceOf<IOneMessenger>(messagnerStore.currentChat)) {
      const { data } = await axios.get<unknown>(
        `${MESSAGES}?page=1&limit=20&order[created_at]=desc&om=${messagnerStore.currentChat['@id']}`
      )
      if (instanceOf<IHydra<IMessage>>(data))
        messagnerStore.setMessages(data['hydra:member'].reverse())
    }
  }

  async function getNewMessages() {
    if (instanceOf<IOneMessenger>(messagnerStore.currentChat)) {
      const { data } = await axios.get<unknown>(
        `${MESSAGES}?page=1&limit=20&created_at[strictly_after]=${moment(
          messagnerStore.lastReadMessageTime
        ).format('YYYY-MM-DD HH:mm:ss')}&order[created_at]=desc&om=${
          messagnerStore.currentChat['@id']
        }`
      )
      if (instanceOf<IHydra<IMessage>>(data)) {
        if (data['hydra:member'].length === 0) {
          return false
        }

        const handler = data['hydra:member'].reverse()
        handler.forEach((msg) => {
          messagnerStore.pushMessage(msg)
        })
        return true
      }
      return false
    }
    return false
  }

  async function findUser(payload: string) {
    if (payload.length < 4) {
      messagnerStore.updateUserList([])
      return
    }

    const { data } = await axios.get<unknown>(`${USER_URL}?page=1&limit=20&username=${payload}`)
    console.log({ data })
    if (instanceOf<IHydra<IUser>>(data)) {
      // FIXME
      const handler = data['hydra:member'].map((u: IUser) => {
        if (u.id != userStore.getUserId) return u
      })

      // @ts-ignore FIXME
      messagnerStore.updateUserList(handler)
    }
  }

  async function isChatExist() {
    let users = `&users.id=${userStore.getUserId}`

    messagnerStore.chatUsers.forEach((u) => {
      users += `&users.id=${u.id}`
    })

    const { data } = await axios.get<unknown>(`${ONE_MESSENGER}?limit=1${users}`)

    if (instanceOf<IHydra<IOneMessenger>>(data)) {
      messagnerStore.setCurrentChat(data['hydra:member'][0])
      return
    }

    let newUsers = [`/api/users/${userStore.getUserId}`]
    messagnerStore.chatUsers.forEach((u) => {
      newUsers.push(u['@id'])
    })

    const response = await axios.post<unknown>(ONE_MESSENGER, {
      users: newUsers
    })

    if (instanceOf<IOneMessenger>(response.data)) {
      messagnerStore.setCurrentChat(response.data)
      return
    }
  }

  async function sendMessage(payload: string) {
    if (messagnerStore.currentChat === null) {
      isChatExist()
    }

    if (instanceOf<IOneMessenger>(messagnerStore.currentChat)) {
      const body = {
        om: messagnerStore.currentChat['@id'],
        message: payload,
        createdAt: null,
        sender: null
      }

      const { data } = await axios.post<unknown>(MESSAGES, body)
      console.log({ data })

      if (instanceOf<IMessage>(data)) {
        messagnerStore.pushMessage(data)
      }
    }
  }

  return {
    getChats,
    getMessages,
    findUser,
    sendMessage,
    getNewMessages,
    getNewChats
  }
}
