import type { IUser } from '@/interfaces'
import useAxios from './useAxios'
import { USER_URL } from '@/helpers/api'
export function instanceOf<T>(object: any): object is T {
  return object
}

export function parseUsername(user: IUser) {
  return user.user_group.html_code.replace('{{username}}', user.username)
}

export async function getUserById(id: number) {
  const { axios } = useAxios()

  try {
    const { data } = await axios.get<unknown>(`${USER_URL}/${id}`)
    if (instanceOf<IUser>(data)) return data
  } catch (e) {
    console.error(e)
  }
}
