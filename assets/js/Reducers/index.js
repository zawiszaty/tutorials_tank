import {combineReducers} from 'redux'
import userReducer from './user';
import { reducer as reduxFormReducer } from 'redux-form';

export default combineReducers({
    user: userReducer,
    form: reduxFormReducer
})
