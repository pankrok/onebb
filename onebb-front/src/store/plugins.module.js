export const plugins = {
  namespaced: true,
  state: () => ({ plugins: {}}),
  actions: {
    addPlugin({commit}, plugin)  {
        commit('init', plugin);
    },
    reloadPlugins({commit}) {
      commit('reload');  
    }
  },
  mutations: {
    init(state, plugin) {
        if (typeof(plugin.name) === 'string') {
            state.plugins[plugin.name] = plugin.app;
            state.plugins[plugin.name].init();
        }
    },
    
    plugin(state, name) {
        return state.plugins[name];
    },
    
    reload(state) {
        if (Object.keys(state.plugins).length === 0) 
            return;
       
        Object.keys(state.plugins).forEach((plugin) => {
            state.plugins[plugin].init();            
        })
    }
      
  }
};