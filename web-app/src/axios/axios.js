import axios from 'axios';
import {baseUrl} from "./env";

const instance = axios.create({
    baseURL: baseUrl
});

export default instance;