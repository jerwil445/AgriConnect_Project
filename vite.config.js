import { defineConfig } from "vite";
import laravel from "laravel-vite-plugin";
import fs from "fs";
import path from "path";

export default defineConfig({
    plugins: [
        {
            name: "security-hider",
            enforce: "pre",
            configureServer(server) {
                server.middlewares.use((req, res, next) => {
                    const rawUrl = req.url || "";
                    const url = rawUrl.split("?")[0];

                    // Detect requests for JS/CSS in the resources folder
                    const isSourcePath =
                        url.includes("/resources/") &&
                        (url.endsWith(".js") || url.endsWith(".css"));

                    // Detection: Browsers send 'text/html' for direct visits, View Source, and 'Open in new tab'
                    const isBrowserViewing =
                        req.headers["accept"] &&
                        req.headers["accept"].includes("text/html");

                    if (isSourcePath && isBrowserViewing) {
                        const filePath = path.resolve(
                            process.cwd(),
                            "resources/js/404.html"
                        );
                        if (fs.existsSync(filePath)) {
                            res.statusCode = 404;
                            res.setHeader("Content-Type", "text/html");
                            res.end(fs.readFileSync(filePath, "utf-8"));
                            return;
                        }
                    }
                    next();
                });
            },
        },
        laravel({
            input: [
                "resources/css/app.css",
                "resources/js/app.js",
                "resources/js/register.js",
                "resources/js/landing.js",
                "resources/js/admin/demands/admin-demands-show.js",
                "resources/js/admin/demands/admin-demands-edit.js",
                "resources/js/admin/demands/admin-demands-index.js",
                "resources/js/admin/matches/admin-matches-index.js",
                "resources/js/admin/products/admin-products-index.js",
                "resources/js/admin/products/admin-products-edit.js",
                "resources/js/admin/products/admin-products-show.js",
                "resources/js/admin/transactions/admin-transactions-index.js",
                "resources/js/admin/users/admin-users-index.js",
                "resources/js/admin/users/admin-users-show.js",
                "resources/js/admin/admin-dashboard.js",
                "resources/js/admin/admin-profile-edit.js",
                "resources/js/buyer/demands/buyer-demands-index.js",
                "resources/js/buyer/demands/buyer-demands-show.js",
                "resources/js/buyer/orders/buyer-orders-index.js",
                "resources/js/buyer/products/buyer-products-show.js",
                "resources/js/buyer/buyer-analytics.js",
                "resources/js/buyer/buyer-dashboard.js",
                "resources/js/buyer/buyer-dashboard-charts.js",
                "resources/js/buyer/buyer-profile-edit.js",
                "resources/js/farmer/farmer-analytics.js",
                "resources/js/farmer/farmer-dashboard.js",
                "resources/js/farmer/farmer-profile-edit.js",
                "resources/js/farmer/products/farmer-products-show.js",
                "resources/js/farmer/products/farmer-products-index.js",
                "resources/js/farmer/products/farmer-products-edit.js",
                "resources/js/farmer/products/farmer-products-create.js",
                "resources/js/farmer/orders/farmer-orders-index.js",
                "resources/js/farmer/matches/farmer-matches-product_matches.js",
                "resources/js/farmer/farmer-layout.js",
                "resources/js/farmer/farmer-notifications.js",
                "resources/js/layouts/layout-messages.js",
                "resources/js/layouts/layout-buyers-page.js",
                "resources/js/messages/messages-index.js",
                "resources/js/orders/orders-show.js",
                "resources/js/partials/partial-navbar.js",
                "resources/js/partials/buyers/partial-buyers-header.js",
                "resources/js/partials/admin/partial-admin-sidebar-header.js",
                "resources/js/partials/farmers/partial-farmers-header.js",
            ],

            refresh: true,
        }),
    ],
});