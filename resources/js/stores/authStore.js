import { defineStore } from 'pinia';
import axios from 'axios';

export const useAuthStore = defineStore('auth', {
    state: () => ({
        isImpersonating: false,
    }),
    actions: {
        async checkImpersonation() {
            try {
                const res = await axios.get('/api/check-impersonation');
                this.isImpersonating = res.data.isImpersonating;
            } catch (e) {
                console.error('Error checking impersonation:', e);
            }
        },
    },
});
