import {combineReducers} from 'redux'
import userReducer from './user';
import categoryReducer from './category';
import { reducer as reduxFormReducer } from 'redux-form';
import { syncHistoryWithStore, routerReducer } from 'react-router-redux'

export default combineReducers({
    user: userReducer,
    category: categoryReducer,
    form: reduxFormReducer,
    routing: routerReducer,
},)
