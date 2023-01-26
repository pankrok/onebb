export const getData = (state: any) => {
        if (state.data) {
            return state.data.body
        }

        return state.data;
    };