import { defineConfig } from "vite";
import laravel from "laravel-vite-plugin";
import vue from "@vitejs/plugin-vue";

export default defineConfig({
    plugins: [
        laravel({
            input: ["resources/css/app.css", "resources/js/app.js"],
            refresh: true,
        }),
        vue({
            template: {
                transformAssetUrls: {
                    base: null,
                    includeAbsolute: false,
                },
            },
        }),
    ],
    server: {
        host: "0.0.0.0", // Allow access from Docker network
        port: 5173, // Ensure this matches the exposed port
        hmr: {
            host: "localhost",
            port: 5173,
        },
        watch: {
            usePolling: true
          },
    },
});
