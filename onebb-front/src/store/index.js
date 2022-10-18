import { createStore } from 'vuex'
import { onebb } from './onebb.module';
import { plugins } from './plugins.module';
import { boxes } from './boxes.module';
import { messenger } from './messenger.module';

let cfg = document.getElementById('app').dataset;
const API_URL = cfg.url + cfg.obb;
 
export default createStore({
  state: {
    baseUrl: API_URL,
    defaultMeta: document.querySelector('meta[name="description"]').getAttribute("content"),
    defaultTitle: document.title,
  },
  mutations: {
  },
  actions: {
  },
  modules: {
    onebb,
    plugins,
    boxes,
    messenger,
  }
})
