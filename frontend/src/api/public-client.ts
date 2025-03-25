import axios from "axios";
import {isSsr} from "../utilites/helpers";
// import {getConfig} from "../utilites/config";

export const publicApi = axios.create();

const existingToken = typeof window !== "undefined" ? window?.localStorage?.getItem('token') : undefined;

if (existingToken) {
    publicApi.defaults.headers.common['Authorization'] = `Bearer ${existingToken}`;
}

publicApi.interceptors.request.use((config) => {
    const baseUrl = isSsr()
        ? process.env.VITE_API_URL_SERVER
        : process.env.VITE_API_URL_CLIENT;
    console.log(baseUrl);

    config.baseURL = `${baseUrl}/public`;
    return config;
}, (error) => {
    return Promise.reject(error);
});

axios.defaults.withCredentials = true;
