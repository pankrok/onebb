import { createStore } from 'vuex'
import { onebb } from './onebb.module';
import { obbPlugins } from './plugins.module';
import { obbBell } from './bell.module';

export default createStore({
  state: {
    loading: true,
    mobileMenu: false
  },
  mutations: {
    setLoading(state, data) {
        state.loading = data;
    },
    toggle(state) {
        state.mobileMenu = !state.mobileMenu;
    },
    hide(state) {
        state.mobileMenu = false;
    }
  },
  actions: {
    loading({ commit }) {
         commit('setLoading', true);
    },
    loaded({ commit }) {
        commit('setLoading', false);
    },
    toggleMenu({ commit }) {
        commit('toggle');
    },
    hideMenu({ commit }) {
        commit('hide');
    }
  },
  modules: {
    onebb,
    obbPlugins,
    obbBell,
  }
})
