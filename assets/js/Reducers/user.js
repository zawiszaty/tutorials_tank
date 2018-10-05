import {UPDATE_USER} from './../actions/user-action'
import {LOGIN_USER} from './../actions/user-action'
import {LOGOUT_USER} from './../actions/user-action'
import {REGISTER_USER} from './../actions/user-action'
import {REGISTER_USER_SUCCESS} from './../actions/user-action'
import axios from './../axios';

export default function userReducer(state = [], {type, payload}) {
    switch (type) {
        case LOGIN_USER:
            state.user = payload.user;
            return payload.user;
        case LOGOUT_USER:
            state.user = [];
            return payload.user;
        case REGISTER_USER:
            return {
                isLoading: true,
            };
        case REGISTER_USER_SUCCESS:
            console.log(payload);
            return {
                isLoading: false,
            };
        default:
            return state;
    }
}