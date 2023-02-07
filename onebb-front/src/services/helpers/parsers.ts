import moment from "moment";
import { useStore } from "vuex";
import { IUser } from '@/interfaces/obbApiInterface'

export const parseUsername = (user: string|IUser, groupe?: string): string => {
  
  if (typeof user === 'object') {
    return user.user_group.html_code.replace("{{username}}", user.username);
  }
  
  if (groupe) { 
    return groupe.replace("{{username}}", user);
  }

  return '';
};

export const parseDate = (value: Date|string): string => {
  return moment(String(value)).format("YYYY-MM-DD");
};

export const parseAvatar = (img?: string): string => {
  const store = useStore();
  if(img) {
    return store.state.baseUrl + img;
  }

  return store.state.baseUrl + '/'  
}