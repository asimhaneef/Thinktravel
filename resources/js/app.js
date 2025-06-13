import "./bootstrap";
import "admin-lte/plugins/jquery/jquery.min.js";
import "admin-lte/plugins/bootstrap/js/bootstrap.bundle.min.js";
import "admin-lte/dist/js/adminlte.min.js";
import { createApp } from "vue/dist/vue.esm-bundler.js";
import { createRouter, createWebHistory } from "vue-router";
import Routes from "./routes";
import PrimeVue from "primevue/config";
import Lara from "@/presets/lara"; //import preset
import Aura from "@/presets/aura"; //import preset
import registerPrimeVueComponents from './primevue-components';
import { createPinia } from 'pinia'
import VueApexCharts from "vue3-apexcharts";
import ConfirmationService from 'primevue/confirmationservice';
import ToastService from 'primevue/toastservice';
import vCan from "./directives/can"; 
import { usePermissionStore } from "@/stores/Permissions";
const app = createApp({});
app.directive("can", vCan);
const router = createRouter({
    routes: Routes,
    history: createWebHistory(),
});
const pinia = createPinia()

// Global route guard for permission checking
router.beforeEach(async (to, from, next) => {
    const permissionStore = usePermissionStore(pinia);

    // Ensure permissions are loaded
    if (permissionStore.permissions.length === 0) {
        await permissionStore.fetchPermissions(); // Fetch permissions if not already loaded
    }

    // Check if the route requires a specific permission
    if (to.meta.requiresPermission) {
        const hasPermission = permissionStore.hasPermission(
            to.meta.requiresPermission
        );

        if (hasPermission) {
            next(); // Allow navigation
        } else {
            next({ name: "admin.dashboard" }); // Redirect to dashboard if no permission
        }
    } else {
        next(); // Proceed to the route if no permission is required
    }
});
app.use(router);
app.use(pinia);
app.use(VueApexCharts);
app.use(PrimeVue, {
    unstyled: true,
    pt: Aura, //apply preset
});
app.use(ConfirmationService);
app.use(ToastService)
registerPrimeVueComponents(app);
app.mount("#app");
