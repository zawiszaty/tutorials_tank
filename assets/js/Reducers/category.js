import {GET_CATEGORY} from './../actions/category-action'

import axios from './../axios';

export default function categoryReducers(state = [], {type, payload}) {
    switch (type) {
        case GET_CATEGORY:
            state.category = payload.category;
            return payload.category;
        default:
            return state;
    }
}