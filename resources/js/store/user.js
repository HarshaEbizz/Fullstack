
import { defineStore } from "pinia";
import axios from "axios";

export const useUserStore = defineStore("user", {
    state: () => ({
        sucess_message: null,
        user: null,
        error: null,
        token: null,
        data: null,
        isAuthenticated: false,
    }),

    actions: {
        async register(data) {
            return axios.post('register', data)
                .then((reponse) => {
                    this.isAuthenticated = true;
                    this.sucess_message = reponse.data.message;
                    this.token = reponse.data.token;
                }).catch((error) => {
                    this.error = error.response.data.message
                })
        },
        async login(data) {
            return axios.post('login', data).
                then((reponse) => {
                    this.user = reponse.data.user;
                    this.token = reponse.data.token;
                    this.sucess_message = reponse.data.message;
                    this.isAuthenticated = true;
                }).catch((error) => {
                    this.error = error.response.data.message
                })
        },
        async fetch_user() {
            return axios.get('user')
                .then((reponse) => {
                    this.user = reponse.data;
                    this.isAuthenticated = true;
                }).catch((error) => {
                    this.error = error.response.data.message
                })
        },
        async logout() {
            return axios.get('logout')
                .then((reponse) => {
                    this.sucess_message = reponse.data.message;
                    this.isAuthenticated = false;
                    this.user = null;
                    this.token = null;
                }).catch((error) => {
                    this.error = error.response.data.message
                })
        },
        async profile(data) {
            return axios.post('profile', data)
                .then((reponse) => {
                    this.sucess_message = reponse.data.message;
                }).catch((error) => {
                    this.error = error.response.data.message
                })
        },
        async list() {
            return await axios.get('users')
                .then((reponse) => {
                    this.data = reponse.data
                }).catch((error) => {
                    this.error = error.response.data.message
                })
        },
    },
});