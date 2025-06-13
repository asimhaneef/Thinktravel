import { defineStore } from "pinia";
import axios from "axios";

export const usePermissionStore = defineStore("permissions", {
    state: () => ({
        permissions: [], // ✅ Must be an array
        permissionsLoaded: false, // To prevent multiple API calls
    }),
    actions: {
        async fetchPermissions() {
            if (this.permissionsLoaded) return;

            try {
                const res = await axios.get("/api/user-permissions");

                // ✅ Make sure it's always an array
                this.permissions = Array.isArray(res.data.permissions)
                    ? res.data.permissions
                    : [];

                this.permissionsLoaded = true;
            } catch (err) {
                console.error("Failed to load permissions:", err);
                this.permissions = []; // ✅ fallback to empty array
            }
        },
    },
    getters: {
        hasPermission: (state) => (permission) => {
            return (
                Array.isArray(state.permissions) &&
                state.permissions.includes(permission)
            );
        },
    },
});
