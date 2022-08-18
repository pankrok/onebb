const plugins = {};
  
export const obbPlugins = {
  namespaced: true,
  state: plugins,
  actions: {  
      set({ commit }, plugin) {
            commit('setPlugin', plugin);
      },      
  },
  mutations: {
    setPlugin(state, data) {
        state[data.plugin] = data.data;
    },
  },

};
    