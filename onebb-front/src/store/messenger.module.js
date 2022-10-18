const read = localStorage.getItem('msg') ? JSON.parse(localStorage.getItem('msg')) : {};
const longPooling = 60000;
const shortPooling = 5000;
const initialState = {
    lastMessage: null, 
    lastCheck: null,
    readMessages: read,
    cache: null, 
    chatCache: null, 
    pooling: shortPooling, 
    id: null, 
    unread: 0
}   
 
export const messenger = {
  namespaced: true,
  state: initialState,
  actions: {
    setCache({ commit }, data) {
        commit('setCache', data)
    },
    
    setLastMessage({ commit }, data) {
        commit('setMessageTime', data)
    },
    
    setLastCheck({ commit }, data) {
        commit('setCheckTime', data)
    },
    
    setShortPooling({ commit }) {
        commit('shortPooling');
    },

    setLongPooling({ commit }) {
        commit('longPooling');
    },
    
    setChatId({ commit }, id) {
        commit('chatId', id);
    },
    
    incrementUnread({ commit }) {
        commit('incrementUnread');
    },
    
    decrementUnread({ commit }) {
        commit('decrementUnread');
    },
    
    setReadMessage({ commit }, data) {
        commit('readMessage', {id: data.id, updated_at: data.updated_at});
    }
    
  }, 

  mutations: {    
    setCache(state, data) {
        if (state.cache === null) {
            state.cache = {};
            data.forEach(el => {
                state.cache[el.id] = el;
            });
            console.log(state.cache);
            return null;
        }
        
        data.forEach(el => {
            if (typeof(state.cache[el.id]) === 'undefined') {
                state.cache[el.id] = el;
            }
                
            if (state.cache[el.id].updated_at < el.updated_at) {
                state.cache[el.id] = el;
            }
        })
        
    },
    
    setMessageTime(state, data) {
         state.lastMessage = data;
    },
    
    setCheckTime(state, data) {
         state.lastCheck = data;
    },
    
    shortPooling(state) {
        state.pooling = longPooling;
    },
    
    longPooling(state) {
        state.pooling = shortPooling;
    },
    
    chatId(state, id) {
        state.id = id;
    },
    
    incrementUnread(state) {
        state.unread++;
    },
    
    decrementUnread(state) {
        if (state.unread > 0) {
            state.unread--;
        }
    },
    
    readMessage(state, data) {
        state.readMessages[data.id] = data.updated_at;
        localStorage.setItem('msg', JSON.stringify(state.readMessages));
    }
    
  }
};
