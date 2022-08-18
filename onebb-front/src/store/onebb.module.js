import OneBB from '../services/onebb.service';

const user = localStorage.getItem('user');
const initialState = user
  ? { status: { 
        loggedIn: true,  
        acp: false,
        mcp: false,
        errors: null, 
        uid: null,
        avatar: null,
        slug: '-',
      }, 
      errors: null
    }
  : { status: { loggedIn: false, acp: false, mcp: false, errors: null, uid: null }, errors: null};
  
export const onebb = {
  namespaced: true,
  state: initialState,
  actions: {
      get({ commit }, api) {
          
        return new Promise((resolve) => {
            let res = api.resource;
            let id = api.id ? parseInt(api.id) : null;
            let subresource = api.subresource ? (api.subresource) : null;
            let page = api.page ? parseInt(api.page) : null;
            let limit = api.limit ? parseInt(api.limit) : null;
            
            OneBB[res]()
            .setPage(page)
            .setLimit(limit)
            .get(id, subresource).then(res => {
              if ( res.status < 200 || res.status > 399 ) {
                  commit('errors', res);
                  resolve(res.response);
              } else {
                resolve(res.response);  
              }
          })
        })
          
      },
      
      post({ commit }, api) {
         return new Promise((resolve) => {
         let res = api.resource;
         let data = api.data;
 
         OneBB[res]().post(data).then(res => {
              if ( res.status < 200 || res.status > 399 ) {
                commit('errors', res);
                resolve(res.response);
              } else {
                resolve(res.response);  
              }
          })  
        })
      },
      
      put({ commit }, api) {
        return new Promise((resolve) => {
             let res    = api.resource;
             let id     = api.id ? api.id : null;
             let data   = api.data;
 
            OneBB[res]().put(id, data).then(res => {
              if ( res.status < 200 || res.status > 399 ) {
                commit('errors', res);
                resolve(res.response);
              } else {
                resolve(res.response);  
              }
          })  
        })
      },
      
      login({ commit }, user) {
        return new Promise((resolve) => {
          OneBB.login(user).then(response => {
            if(response.success == true) {
                commit('loginSuccess', response.response);
            } else {
                commit('loginError');                   
            }
            resolve(response);
          });
        });
      },
      
      logout({ commit }) {
          OneBB.logout();
          commit('logout');
      },
      refresh({ commit }) {
        return new Promise((resolve) => {
          OneBB.refresh().then(response => {
            if(response.success == true) {
                commit('loginSuccess', response.response);
            } else {
                commit('loginError');                   
            }
            resolve(response);
          });
        });
      },
      newAvatar({ commit }, avatarUrl) {
          commit('setAvatar', avatarUrl);
      }
      
  },
  mutations: {
    loginSuccess(state, data) {
        console.log({loginSuccessData: data});
      state.status.loggedIn = true;
      state.status.errors = false;
      state.status.acp = data.acp_enabled;
      state.status.mcp = data.mcp_enabled;
      state.status.uid = data.uid;
      state.status.avatar = data.avatar;
      state.status.slug = data.slug;
    },
    loginError(state) {
      state.status.loggedIn = false;
      state.status.errors = true;
    },
    logout(state) {
        state.status.loggedIn = false;
          state.status.errors = false;
          state.status.acp = false;
          state.status.mcp = false;
          state.status.uid = null;
          state.status.avatar = null;
          state.status.slug = null;
    },
    errors(state, err) {
        state.errors = err;
    },
    setAvatar(state, avatar) {
        state.status.avatar = avatar;
    }
  },

};
