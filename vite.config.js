import { defineConfig } from "vite";
import laravel from "laravel-vite-plugin";

export default defineConfig({
    plugins: [
        laravel({
            input: ["resources/css/app.css", "resources/js/app.js"],
            refresh: true,
        }),
    ],
    server: {
        host: "0.0.0.0", // <-- importante
        port: 5173, // coincide con el mapeo anterior
        hmr: {
            host: process.env.HOSTNAME || "3.87.79.207",
            protocol: "ws",
            port: 5173,
        },
    },
});
