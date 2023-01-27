import { createStore, GetterTree } from 'vuex'
import api from '@/services/api/api'
import { ResponseInterface } from '@/services/api/apiInterface';
import * as resources from '@/services/api/obbResources';
import { user } from '@/store/user/user.module';
import { obb } from '@/store/api/api.module';
import { boxes } from '@/store/boxes/boxes.module';

interface IBaseUrl {
  url: string,
  obb: string,
}

// @ts-ignore
let cfg: IBaseUrl = document.getElementById('app').dataset;
const API_URL = cfg.url + cfg.obb;

export interface State {
  user: any,
  loading: boolean,
  defaultTitle: string,
  baseUrl: string
}

const initialState: State = {
  user: {
    name: '',
    id: 0,
  },
  loading: false,
  defaultTitle: document.title,
  baseUrl: API_URL
}

const actions = {
  async fetchUser({ commit }: any, id?: string) {
    
    api.setResource(resources.USER).get(id)
    .then((res) => { 
      console.log(res);
      commit('setUser', res)
    })
  },
  setTitle({ state }: any, title?: string) {
    document.title = title ? `${state.defaultTitle} - ${title}` : state.defaultTitle;
  }
}

const mutations = {
  loading(state: State) {
    state.loading = true;
  },
  loaded(state: State) {
    state.loading = false;
  },
  setUser(state: State, res: ResponseInterface) {
    state.user = {
      name: res.body.username,
      id: res.body.id
    }
  }
}

const getters = {
  getUser(state: State) {
    return `user id: ${state.user.id} || username: ${state.user.name}`
  }
}

const store = createStore<State>({
  state: initialState,
  actions: actions,
  mutations: mutations,
  getters:  getters,
  modules: {
    user,
    obb,
    boxes
  }
})
export default store