export const plugins = {
  namespaced: true,
  state: () => ({ plugins: {}, firstRun: true}),
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
        console.log('%c PLUGIN MODULE || initialize plugin', 'color: #bada55');
        console.log({initPlugin: plugin.name()});
        if (typeof(plugin.name()) === 'string') {
            state.plugins[plugin.name()] = plugin;
            state.plugins[plugin.name()].init();
            console.log({pluginStore: 'initing plugin : ' + plugin.name});
        }
    },
    
    plugin(state, name) {
        return state.plugins[name];
    },
    
    reload(state) {
        if (state.firstRun === true) {
            state.firstRun = false;
            return;
        }
        
        if (Object.keys(state.plugins).length === 0) 
            return;
       
        Object.keys(state.plugins).forEach((plugin) => {
            state.plugins[plugin].init();            
        })
    }
      
  }
};