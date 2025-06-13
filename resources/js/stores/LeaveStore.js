import { defineStore } from "pinia";
import axios from "axios";
import Form from "vform";
import { showToast } from "@/utils"; // Adjust path based on your project structure

export const useLeaveStore = defineStore("leaveStore", {
    state: () => ({
        sidebarVisible: false,
        editMode: false,
        selectedLeave: null,
        leaveList: [],
        departmentDesignation: [], // Added department designations array
        form: new Form({
            department_id: "",
            role_id: "",
            medical_leave: "",
            casual_leave: "",
            annual_leave: "",
            effected_from: "",
        }),
    }),

    actions: {
        // 🟢 Fetch leave settings
        async fetchLeaveSettings() {
            try {
                const response = await axios.get("/api/leave-setting");
                this.leaveList = response.data;
            } catch (error) {
                console.error("Error fetching leave settings:", error);
            }
        },

        // 🟡 Submit form (Create or Update Leave Setting)
        async submitForm(toast) {
            try {
                let response;
                if (this.editMode) {
                    // Update existing leave setting
                    response = await this.form.put(
                        `/api/leave-setting/${this.form.id}`
                    );
                } else {
                    // Create new leave setting
                    response = await this.form.post("/api/leave-setting");
                }

                showToast(toast, response.data);
                this.fetchLeaveSettings(); // Refresh data
                this.closeSidebar(); // Close the sidebar
            } catch (error) {
                console.error("Error submitting form:", error);
            }
        },

        // 🟡 Show Sidebar for Adding/Editing Leave Setting
        async showSidebar(leave = null) {
            console.log(leave); // Log the `leave` object for debugging

            this.selectedLeave = leave;
            this.editMode = !!leave;
            this.sidebarVisible = true;

            if (this.editMode && leave) {
                // Fill the form with the existing leave setting data
                this.form.fill({
                    id: leave.id,
                    department_id: leave.department_id, // Pre-select department
                    role_id: leave.role_id, // Pre-select role/designation
                    medical_leave: leave.medical_leave,
                    casual_leave: leave.casual_leave,
                    annual_leave: leave.annual_leave,
                    effected_from: leave.effected_from, // Pre-select effective date
                });

                // Check if department_id is defined before making the API call
                if (leave.department_id) {
                    await this.getDepartmentDesignation(leave.department_id);
                } else {
                    console.error("Department ID is missing or undefined.");
                    // Optionally, show a user-friendly error message here if needed
                }
            } else {
                // If in add mode (no leave), reset the form
                this.form.reset();
            }
        },
        // 🟡 Get Designations for a Department
        async getDepartmentDesignation(departmentId) {
            try {
                const response = await axios.get(
                    `/api/department-roles?department_id=${departmentId}`
                );
                this.departmentDesignation = response.data.data; // Save department roles
            } catch (error) {
                console.error("Error fetching department designations:", error);
                this.departmentDesignation = [];
            }
        },

        // 🔴 Close Sidebar and Reset Data
        closeSidebar() {
            this.sidebarVisible = false;
            this.selectedLeave = null;
            this.editMode = false;

            // Reset the form completely
            this.form.reset();
            this.departmentDesignation = []; // Clear the department designations
        },
    },
});
