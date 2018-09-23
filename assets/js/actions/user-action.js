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