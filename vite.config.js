import { defineConfig } from "vite";
import laravel from "laravel-vite-plugin";

export default defineConfig({
    plugins: [
        laravel({
            input: ["resources/css/app.css", "resources/js/app.js", "resources/js/notifications.js", "resources/js/register.js", "resources/js/landing.js", "resources/js/admin/demands/admin-demands-show.js", "resources/js/admin/demands/admin-demands-edit.js", "resources/js/admin/demands/admin-demands-index.js"],
            refresh: true,
        }),
    ],
});