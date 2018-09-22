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
        case LOGIN_USER:
            axios.post('oauth/v2/token', {
                "grant_type": "password",
                "client_id": "1_17b58gtuwp40gg8s4g800kg8gsc4gg04w8cwooko8go484sws4",
                "client_secret": "bw3gxccdi5w8w8owwggs8gsg8kw0088gk0wc48cwossokk0s0",
                "username": payload.user.username,
                "password": payload.user.password
            }).then((response) => {
                localStorage.setItem('access_token', response.data.access_token);
                localStorage.setItem('refresh_token', response.data.refresh_token);
                // this.state.onPresentSnackbar('success', 'Successfully Login');
            }).catch((e) => {
                // this.state.onPresentSnackbar('error', 'Złe hasło albo nazwa użytkownika');
            });
            return true;
        default:
            return state;
    }
}