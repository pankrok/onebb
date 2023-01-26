import { LOGIN } from '@/services/api/obbResources'
import api from '@/services/api/api';

export const actions = {
  async login({ commit }: any) {
    
    api.setResource(LOGIN).post({
        username: "grzyb",
        password: 'grzyb@s89.eu'
    })
    .then((res) => { 
      console.log(res);
      api.setToken(res.body.token)
    })
  }
}