import { state } from './user.state';
import { actions } from './user.actions';
import { mutations } from './user.mutations';

export const user = {
    namespaced: true,
    state,
    actions,
    mutations
}
