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
const app = createApp({});
const router = createRouter({
    routes: Routes,
    history: createWebHistory(),
});
const pinia = createPinia()
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
