import "../css/app.css"; // imports Tailwind
import "./role-toggle";

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

document.addEventListener("DOMContentLoaded", () => {
    const highlight = document.getElementById("highlight");
    const farmerBtn = document.getElementById("farmerBtn");
    const buyerBtn = document.getElementById("buyerBtn");

    farmerBtn.addEventListener("click", () => {
        highlight.style.left = "0"; // move to Farmer
    });

    buyerBtn.addEventListener("click", () => {
        highlight.style.left = "50%"; // move to Buyer
    });
});
