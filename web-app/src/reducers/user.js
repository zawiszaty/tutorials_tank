export const user = (state = [], action) => { // (1)
    switch (action.type) { // (2)
        case 'USER_LOGIN':
            return [
                state.user = action.user
            ];
        case  'userClear':
            return [];
        default:
            return state
    }
};