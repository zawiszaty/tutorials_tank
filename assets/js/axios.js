import axios from 'axios';

axios.create({
    baseURL: 'http://localhost:8080'
});

export default axios;