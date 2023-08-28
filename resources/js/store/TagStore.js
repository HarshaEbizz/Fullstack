import { defineStore } from 'pinia'

export const TagStore = defineStore('tags', {
    state: () => ({
        sucess_message: null,
        tags: null,
        error: null,
        data: null,
        isAuthenticated: true,
    }),
    actions: {
        async getTags() {
            const res = await axios.get("/tags/all",this.data);
            this.tags = res.data;
        },
    }
})