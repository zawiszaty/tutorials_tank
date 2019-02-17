export const notification = (state = [], action) => { // (1)
    switch (action.type) { // (2)
        case 'GET_NOTIFICATION':
            return [
                state.notification = action.notification
            ];
        case 'ADD_NOTIFICATION':
            return [
                state.notification = state.notification + 1
            ];
        case 'REMOVE_NOTIFICATION':
            return [
                state.notification = parseInt(state.notification) - 10
            ];
        default:
            return state
    }
};