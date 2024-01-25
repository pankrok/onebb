import type { IUser } from "@/interfaces";

export default function parseUsername(user: IUser) {
    return user.user_group.html_code.replace('{{username}}', user.username)
  }