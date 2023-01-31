import { LOGIN } from '@/services/api/obbResources'
import api from '@/services/api/api';

export const actions = {
  async login({ commit }: any) {
    
    api.setResource(LOGIN).post({
        username: "",
        password: ''
    })
    .then((res) => {
      // FIXME  
      // @ts-ignore
      api.setToken(res.body.token)
    })
  }
}