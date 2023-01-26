import { state } from './user.state';
import { actions } from './user.actions';

export const user = {
    namespaced: true,
    state: state,
    actions: actions,
    mutations: {}
}
