const bells = {
    updates: {},
    moderations: {},
    updatesLoaded: false,
};
  
export const obbBell = {
  namespaced: true,
  state: bells,
  actions: {  
    setUpdates({ commit }, data) {
      commit('updates', data);
    },  
    setModerations({ commit }, data) {
      commit('moderations', data);
    },
  },
  mutations: {
    updates(state, data) {
        state.updates = data;
        state.updatesLoaded = true;
    },
    moderations(state, data) {
        state.moderations = data;
    },
  },

};
    