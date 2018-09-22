import axios from './../axios';

export const UPDATE_USER = 'user:updateUser';
export const LOGIN_USER = 'user:loginUser';
export const REGISTER_USER = 'user:registerUser';
export const REGISTER_USER_SUCCESS = 'user:registerUserSuccess';

export function updateUser(newUser) {
    return {
        type: UPDATE_USER,
        payload: {
            user: newUser
        }
    }
}

export function loginUser(user) {
    return {
        type: LOGIN_USER,
        payload: {
            user: user
        }
    }
}

export function registerUserSuccess(user) {
    return {
        type: REGISTER_USER_SUCCESS,
        payload: {
            user: user
        }
    }
}

export function registerUser(user) {
    return {
        type: 'REGISTER_USER',
        promise: axios.post('api/v1/user/register', {
            username: user.username,
            email: user.email,
            plainPassword: {
                first: user.password_first,
                second: user.password_second
            }
        })
    }
}