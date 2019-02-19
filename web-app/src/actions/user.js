export const login = (user) => ({
    type: 'USER_LOGIN',
    user
});

export const userClear = () => ({
    type: 'userClear'
});
