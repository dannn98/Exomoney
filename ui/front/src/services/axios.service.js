import axios from 'axios'
import nprogress from 'nprogress'

export const customAxios = axios.create({
    baseURL: `http://localhost:8081/api/v1`,
    headers: {},
    withCredentials: true
})

export const NProgress = nprogress

customAxios.interceptors.request.use(config => {
    nprogress.start();
    return config;
})

customAxios.interceptors.response.use(response => {
    nprogress.done();
    return response;
})