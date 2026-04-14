import "../css/app.css"; // imports Tailwind
import "./role-toggle.js";
import "./register.js";
import './mark_as_done.js';
import './order-modal.js';


document.addEventListener("DOMContentLoaded", () => {
    window.openModal = function (id) {
        const modal = document.getElementById(id);
        if (modal) {
            modal.classList.remove(
                "opacity-0",
                "scale-95",
                "pointer-events-none"
            );
            modal.classList.add("opacity-100", "scale-100");
        }
    };

    window.closeModal = function (id) {
        const modal = document.getElementById(id);
        const inputs = modal ? modal.querySelectorAll("input") : [];
        const select = modal ? modal.querySelectorAll("select") : [];
        select.forEach((select) => (select.value = ""));
        inputs.forEach((input) => (input.value = ""));
        if (modal) {
            modal.classList.remove("opacity-100", "scale-100");
            modal.classList.add("opacity-0", "scale-95");

            setTimeout(() => {
                modal.classList.add("pointer-events-none");
            }, 300); // match the duration-300 transition
        }
    };

    const registerBtn = document.getElementById("registerBtn");
    const loginModal = document.getElementById("loginModal");
    const loginBtn = document.getElementById("loginBtn");
    const registerModal = document.getElementById("registerModal");
    if (registerBtn) {
        registerBtn.addEventListener("click", () => {
            openModal("registerModal");
            loginModal.classList.remove("opacity-100", "scale-100");
            loginModal.classList.add("opacity-0", "scale-95");
            setTimeout(() => {
                loginModal.classList.add("pointer-events-none");
            }, 300);
        });
    }

    if (loginBtn) {
        loginBtn.addEventListener("click", () => {
            openModal("loginModal");
            registerModal.classList.remove("opacity-100", "scale-100");
            registerModal.classList.add("opacity-0", "scale-95");
            setTimeout(() => {
                registerModal.classList.add("pointer-events-none");
            }, 300);
        });
    }

});

// Global function to toggle password visibility
window.togglePasswordVisibility = function (inputId, iconElement) {
    const passwordInput = document.getElementById(inputId);
    const icon = iconElement.querySelector('i');

    if (passwordInput.type === 'password') {
        passwordInput.type = 'text';
        icon.classList.remove('fa-eye-slash');
        icon.classList.add('fa-eye');
    } else {
        passwordInput.type = 'password';
        icon.classList.remove('fa-eye');
        icon.classList.add('fa-eye-slash');
    }
};