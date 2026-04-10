import { defineConfig } from "vite";
import laravel from "laravel-vite-plugin";

export default defineConfig({
    plugins: [
        laravel({
            input: ["resources/css/app.css", "resources/js/app.js", "resources/js/notifications.js", "resources/js/register.js", "resources/js/landing.js", "resources/js/admin/demands/admin-demands-show.js", "resources/js/admin/demands/admin-demands-edit.js", "resources/js/admin/demands/admin-demands-index.js", "resources/js/admin/matches/admin-matches-index.js", "resources/js/admin/products/admin-products-index.js", "resources/js/admin/products/admin-products-edit.js", "resources/js/admin/products/admin-products-show.js", "resources/js/admin/transactions/admin-transactions-index.js", "resources/js/admin/users/admin-users-index.js", "resources/js/admin/users/admin-users-show.js", "resources/js/admin/admin-dashboard.js", "resources/js/admin/admin-profile-edit.js"],
            refresh: true,
        }),
    ],
});