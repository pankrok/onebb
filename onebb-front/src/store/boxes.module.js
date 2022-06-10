export const boxes = {
  namespaced: true,
  state: () => ({ 
    Home: {
        top: null,
        bottom: null,
        left: null,
        right: null,   
    },
    Category: {
        top: null,
        bottom: null,
        left: null,
        right: null,   
    },
    Board: {
        top: null,
        bottom: null,
        left: null,
        right: null,   
    },
    Plot: {
        top: null,
        bottom: null,
        left: null,
        right: null,   
    },
    NewPlot: {
        top: null,
        bottom: null,
        left: null,
        right: null,   
    },
    SignUp: {
        top: null,
        bottom: null,
        left: null,
        right: null,   
    },
    Profile: {
        top: null,
        bottom: null,
        left: null,
        right: null,   
    },
    Userlist: {
        top: null,
        bottom: null,
        left: null,
        right: null,   
    },
    Validation: {
        top: null,
        bottom: null,
        left: null,
        right: null,   
    },
    
  }),
  actions: {
    init({commit}, boxlist)  {
        commit('addBoxes', boxlist);
    },
  },
  mutations: {
    addBoxes(state, boxlist) {
        boxlist.forEach((val) => {
            if (val.page && val.position && val.id) {
                
                if (state[val.page][val.position] === null) {
                    state[val.page][val.position] = {};
                }
                
                state[val.page][val.position][val.id] = 
                { 
                    name: val.box.name, 
                    engine: val.box.engine,
                    html: val.box.html
                }
            }
        })
    },
  }
};