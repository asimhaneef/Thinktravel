import { defineStore } from "pinia";
import axios from "axios";
import Form from "vform";
import { showToast } from "@/utils"; // Adjust this if needed

export const usePayrollStore = defineStore("payrollStore", {
    state: () => ({
        sidebarVisible: false,
        editMode: false,
        selectedPayroll: null,
        payrolls: [],
        departmentDesignation: [],
        form: new Form({
            department_id: "",
            role_id: "",
            salary: "",
            payment_date: "",
            basic_salary: "",
            effected_from: "",
            housing: "",
            fuel: "",
            education: "",
            meal: "",
            travel: "",
            internet: "",
            provident_fund: "",
            other: "",
            id: "",
        }),
    }),

    actions: {
        // 🟢 Fetch all payroll records
        async fetchPayrolls() {
            try {
                const response = await axios.get("/api/payroll-setting");
                this.payrolls = response.data;
            } catch (error) {
                console.error("Error fetching payrolls:", error);
            }
        },

        // 🟢 Open Sidebar for Add/Edit
        async showSidebar(payroll = null) {
            console.log(payroll);
            this.editMode = !!payroll;
            this.sidebarVisible = true;

            if (this.editMode) {
                this.selectedPayroll = payroll;

                // Fill everything except role_id for now
                this.form.fill({
                    id: payroll.id,
                    department_id: payroll.role.department_id,
                    salary: payroll.salary,
                    payment_date: payroll.payment_date,
                    basic_salary: payroll.basic_salary,
                    effected_from: payroll.effected_from,
                    housing: payroll.housing,
                    fuel: payroll.fuel,
                    education: payroll.education,
                    meal: payroll.meal,
                    travel: payroll.travel,
                    internet: payroll.internet,
                    provident_fund: payroll.provident_fund,
                    other: payroll.other,
                });

                // Fetch department roles then assign role_id after designations are loaded
                await this.getDepartmentDesignation(payroll.role.department_id);
                this.form.role_id = payroll.role_id;
            } else {
                this.form.reset();
            }
        },

        // 🔴 Close Sidebar
        closeSidebar() {
            this.sidebarVisible = false;
            this.selectedPayroll = null;
            this.editMode = false;
            this.form.reset();
        },

        // 🟡 Create or Update Payroll
        async submitForm(toast) {
            try {
                let response;
                if (this.editMode) {
                    response = await this.form.put(
                        `/api/payroll-setting/${this.selectedPayroll.id}`
                    );
                } else {
                    response = await this.form.post("/api/payroll-setting");
                }

                showToast(toast, response.data);
                this.fetchPayrolls();
                this.closeSidebar();
            } catch (error) {
                console.error("Error saving payroll:", error);
            }
        },

        // 🔴 Delete Payroll
        async deletePayroll(id, toast) {
            try {
                const response = await axios.delete(
                    `/api/payroll-setting/${id}`
                );
                showToast(toast, response.data);
                this.fetchPayrolls();
            } catch (error) {
                console.error("Error deleting payroll:", error);
            }
        },

        // 🟡 Get Designations for a Department
        async getDepartmentDesignation(departmentId) {
            try {
                const response = await axios.get(
                    `/api/department-roles?department_id=${departmentId}`
                );
                this.departmentDesignation = response.data.data;
            } catch (error) {
                console.error("Error fetching department designations:", error);
                this.departmentDesignation = [];
            }
        },
    },
});
