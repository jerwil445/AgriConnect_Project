/** @type {import('tailwindcss').Config} */
module.exports = {
    content: [
        "./resources/**/*.blade.php",
        "./resources/**/*.js",
        "./resources/**/*.vue",
    ],
    theme: {
        extend: {
            colors: {
                primary: "#16a34a",
                secondary: "#2563eb",
            },
        },
    },
    plugins: [
        require("@tailwindcss/forms"), // optional
    ],
};
// Trigger rebuild
