import thunk from 'redux-thunk';
import { createStore, applyMiddleware } from 'redux';
import allReducer from './Reducers';
import logger from 'redux-logger'
import promiseMiddleware from 'redux-promise-middleware'

export const store = createStore(allReducer, applyMiddleware(thunk, logger, promiseMiddleware()));