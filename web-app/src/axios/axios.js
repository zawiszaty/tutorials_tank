import axios from 'axios';

const instance = axios.create({
    baseURL: 'http://localhost'
});

export default instance;