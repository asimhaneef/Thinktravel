import { defineStore } from "pinia";

export const useTenantStore = defineStore("tenantStore", {
    state: () => ({
        sidebarVisible: false,
        editMode: false,
        selectedTenant: null,
    }),
    actions: {
        showSidebar(tenant = null) {
            this.selectedTenant = tenant;
            this.editMode = !!tenant;
            this.sidebarVisible = true;
        },
        closeSidebar() {
            this.sidebarVisible = false;
            this.selectedTenant = null;
            this.editMode = false;
        },
    },
});
