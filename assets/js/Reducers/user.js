import {UPDATE_USER} from './../actions/user-action'
import {LOGIN_USER} from './../actions/user-action'
import {REGISTER_USER} from './../actions/user-action'
import {REGISTER_USER_SUCCESS} from './../actions/user-action'
import axios from './../axios';

export default function userReducer(state = [], {type, payload}) {
    switch (type) {
        case UPDATE_USER:
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