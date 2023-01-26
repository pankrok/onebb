import { state } from './api.state';
import * as actions from './api.actions';
import * as mutations from './api.mutations';
import * as getters from './api.getters'

export const obb = {
    namespaced: true,
    state,
    actions,
    mutations,
    getters
}
