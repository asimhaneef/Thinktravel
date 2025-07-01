import { defineConfig } from "vite";
import laravel from "laravel-vite-plugin";
import vue from "@vitejs/plugin-vue";
export default defineConfig({
    plugins: [
        laravel({
            input: ["resources/css/app.css", 
                "resources/js/app.js",

                "resources/js/core.min.js",
                "resources/css/web.css",
                "resources/js/web.js",
                "resources/css/jquery-ui.css",
                "resources/js/jquery-ui.js",
                "resources/js/jquery-1.12.4.js",
                "resources/js/script.js"],
            refresh: true,
        }),
        vue(),
    ],
});
