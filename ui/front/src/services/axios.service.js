import axios from 'axios'

export const customAxios = axios.create({
    baseURL: `http://localhost:8081/api/v1`,
    headers: {},
    withCredentials: true
})

customAxios.interceptors.request.use(config => {
    return config;
})

customAxios.interceptors.response.use(response => {
    return response;
})