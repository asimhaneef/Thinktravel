import { defineStore } from "pinia";
import axios from "axios";
import Form from "vform";
import { showToast } from "@/utils"; // Adjust path based on your project structure

export const useUserStore = defineStore("userInfo", {
    state: () => ({
        user: "", // Stores the user data
        sidebarVisible: false,
        editMode: false,
        skeleton: true,
        myProfile: [],
        form: new Form({}),
    }),
    actions: {
        async fetchUserProfile() {
            try {
                const response = await axios.post("/api/user-profile");
                this.myProfile = response.data;
                this.skeleton = false;
            } catch (error) {
                console.error("Error fetching user profile:", error);
            }
        },
        setUser(userData) {
            this.user = userData; // Save user data to store
        },
        clearUser() {
            this.user = null; // Clear user data
        },
    },
});
