
export const mutations = {
  setUser(state: any, user: any) {
    state.loggedIn = true;
    state.uid = user.uid;
    state.acp = user.acp_enabled;
    state.mcp = user.mcp_enabled;
    state.avatar = user.avatar;
    state.slug = user.slug;
  },

  unsetUser(state:any) {
    state.loggedIn = false;
    state.acp = false;
    state.mcp = false;
    state.uid = undefined;
    state.avatar = undefined;
    state.slug = undefined;
  }
};
