import { defineStore } from "pinia";
import axios from "axios";
import Form from "vform";
import { showToast } from "@/utils"; // Adjust path based on your project structure

export const useDepartmentStore = defineStore("departmentStore", {
    state: () => ({
        sidebarVisible: false,
        editMode: false,
        selectedDepartment: null,
        departmentList: [],
        form: new Form({
            parent_id: "",
            name: "",
            short_name: "",
        }),
    }),

    actions: {
        // Fetch all departments
        async fetchDepartments() {
            try {
                const response = await axios.get("/api/department");
                this.departmentList = response.data.data;
            } catch (error) {
                console.error("Error fetching departments:", error);
            }
        },

        // Show Sidebar and optionally prefill form if editing
        showSidebar(department = null) {
            this.selectedDepartment = department;
            this.editMode = !!department;
            this.sidebarVisible = true;

            if (!this.departmentList.length) {
                this.fetchDepartments();
            }

            this.form = new Form({
                id: department?.id ?? null,
                parent_id: department?.parent_id ?? "",
                name: department?.name ?? "",
                short_name: department?.short_name ?? "",
            });
        },

        // Close Sidebar and reset form
        closeSidebar() {
            this.sidebarVisible = false;
            this.selectedDepartment = null;
            this.editMode = false;

            this.form = new Form({
                parent_id: "",
                name: "",
                short_name: "",
            });
        },

        // Submit Form (Create or Update)
        async submitForm(toast) {
            try {
                let response;
                if (this.editMode) {
                    response = await this.form.put(
                        `/api/department/${this.form.id}`
                    );
                } else {
                    response = await this.form.post("/api/department");
                }

                showToast(toast, response.data);
                this.fetchDepartments();
                this.closeSidebar();
            } catch (error) {
                console.error("Error saving department:", error);
            }
        },

        // Delete Department
        async deleteDepartment(id, toast) {
            try {
                const response = await axios.delete(`/api/department/${id}`);
                showToast(toast, response.data);
                this.fetchDepartments();
            } catch (error) {
                console.error("Error deleting department:", error);
            }
        },
    },
});
