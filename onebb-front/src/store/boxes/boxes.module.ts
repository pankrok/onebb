import { state } from './boxes.state';
import { actions } from './boxes.actions';
import { mutations } from './boxes.mutations';

export const boxes = {
    namespaced: true,
    state: state,
    actions: actions,
    mutations: mutations,
}
