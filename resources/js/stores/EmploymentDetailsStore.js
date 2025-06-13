import { defineStore } from "pinia";

export const useEmploymentDetailsStore = defineStore("EmploymentDetailsInfo", {
    state: () => ({
        EmploymentDetails: "", // Stores the EmploymentDetails data
        EmploymentID: "", // Stores the EmploymentID data
    }),
    actions: {
        setEmploymentDetails(EmploymentDetailsData) {
            this.EmploymentDetails = EmploymentDetailsData; // Save EmploymentDetails data to store
        },
        setEmploymentID(EmploymentIDData) {
            this.EmploymentID = EmploymentIDData; // Save EmploymentID data to store        
        },
        clearEmploymentDetails() {
            this.EmploymentDetails = null; // Clear EmploymentDetails data
        },
        clearEmploymentID() {
            this.EmploymentID = null; // Clear EmploymentDetails data
        },
    },
});
