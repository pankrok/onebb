import { createStore, GetterTree } from "vuex";
import { ResponseInterface } from "@/services/api/apiInterface";
import { user } from "@/store/user/user.module";
import { boxes } from "@/store/boxes/boxes.module";
import { IUser } from "@/interfaces/obbApiInterface";
import api from "@/services/api/api";

interface IBaseUrl {
  url: string;
  obb: string;
}

// @ts-ignore
let cfg: IBaseUrl = document.getElementById("app").dataset;
const API_URL = cfg.url + cfg.obb;

export interface State {
  user: any; //fixme
  loading: boolean;
  defaultTitle: string;
  baseUrl: string;
}

const initialState: State = {
  user: {
    name: "",
    id: 0,
  },
  loading: true,
  defaultTitle: document.title,
  baseUrl: API_URL,
};

const actions = {
  setTitle({ state }: any, title?: string) {
    document.title = title
      ? `${state.defaultTitle} - ${title}`
      : state.defaultTitle;
  },
  loading({ commit }: any) {
    commit("loading");
  },
  loaded({ commit }: any) {
    commit("loaded");
  },
};

const mutations = {
  loading(state: State) {
    state.loading = true;
  },
  loaded(state: State) {
    state.loading = false;
  },
  setUser(state: State, res: ResponseInterface<IUser>) {
    if (res?.body) {
      state.user = {
        name: res.body.username,
        id: res.body.id,
      };
    }
  },
};

const getters = {
  getUser(state: State) {
    return `user id: ${state.user.id} || username: ${state.user.name}`;
  },
};

const store = createStore<State>({
  state: initialState,
  actions: actions,
  mutations: mutations,
  getters: getters,
  modules: {
    user,
    boxes,
  },
});
// TODO: fix types
api.intercept(async (req, res) => {
  
  let response = res.clone();
  response = await response.json();

  if (
    response.code === 401 &&
    response.message == "Expired JWT Token"
  ) {
    const reqClone = JSON.stringify(req);
    const save = await api.saveRequest();
    
    if(save === true) {
      await store.dispatch("user/refresh");
    }
    
    return await api.retry(JSON.parse(reqClone));
  }
  return res;
});

export default store;
