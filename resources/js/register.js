const fieldValidations = {
    first_name: {
        rules: [{ type: "required", message: "First name is required." }],
    },
    last_name: {
        rules: [{ type: "required", message: "Last name is required." }],
    },
    phone_number: {
        rules: [{ type: "required", message: "Phone number is required." }],
    },
    city_region: {
        rules: [{ type: "required", message: "City/Region is required." }],
    },
    address: {
        rules: [{ type: "required", message: "Full address is required." }],
    },
    email: {
        rules: [
            { type: "required", message: "Email is required." },
            { type: "email", message: "Enter a valid email address." },
        ],
    },
    password: {
        rules: [
            { type: "required", message: "Password is required." },
            { type: "min", value: 6, message: "Password must be at least 6 characters." },
        ],
    },
    password_confirmation: {
        rules: [
            { type: "required", message: "Please confirm your password." },
            { type: "match", target: "password", message: "Passwords do not match." },
        ],
    },
    farm_size: {
        rules: [
            { type: "required", message: "Farm size is required." },
            { type: "number", message: "Enter a valid numeric farm size." },
            { type: "minNumber", value: 0, message: "Farm size cannot be negative." },
        ],
        dependsOnRole: "farmer",
    },
    experience_years: {
        rules: [
            { type: "required", message: "Years of experience are required." },
            { type: "number", message: "Please enter a valid number." },
            { type: "minNumber", value: 0, message: "Experience cannot be negative." },
            { type: "maxNumber", value: 100, message: "Experience must be 100 years or less." },
        ],
        dependsOnRole: "farmer",
    },
    business_type: {
        rules: [{ type: "required", message: "Business type is required for buyers." }],
        dependsOnRole: "buyer",
    },
    preferred_products: {
        rules: [{ type: "required", message: "Preferred products are required." }],
        dependsOnRole: "buyer",
    },
    buyer_address: {
        rules: [{ type: "required", message: "Business address is required." }],
        dependsOnRole: "buyer",
    },
};

const validators = {
    required(value) {
        return value.trim().length > 0;
    },
    email(value) {
        if (!value.trim()) return true;
        const emailPattern =
            /^[a-zA-Z0-9.!#$%&’*+/=?^_`{|}~-]+@([a-zA-Z0-9-]+\.)+[a-zA-Z]{2,}$/;
        return emailPattern.test(value.trim());
    },
    min(value, ruleValue) {
        return value.length >= ruleValue;
    },
    match(value, _, { field, form }) {
        const target = form.querySelector(`[name="${field.target}"]`);
        return target ? value === target.value : true;
    },
    number(value) {
        if (!value.trim()) return true;
        return !Number.isNaN(Number(value));
    },
    minNumber(value, ruleValue) {
        if (!value.trim()) return true;
        return Number(value) >= ruleValue;
    },
    maxNumber(value, ruleValue) {
        if (!value.trim()) return true;
        return Number(value) <= ruleValue;
    },
};

function handleValidation(field, context) {
    const { input, errorEl, rules = [], dependsOnRole } = field;

    if (!input || !errorEl) return true;

    if (dependsOnRole && context.roleInput?.value !== dependsOnRole) {
        updateError(errorEl, "");
        return true;
    }

    const value = input.value ?? "";

    for (const rule of rules) {
        const { type, value: ruleValue, message, target } =
            typeof rule === "string" ? { type: rule } : rule;

        const validator = validators[type];
        if (!validator) {
            continue;
        }

        const isValid = validator(value, ruleValue, {
            field: { target },
            form: context.form,
            input,
        });

        if (!isValid) {
            updateError(errorEl, message);
            return false;
        }
    }

    updateError(errorEl, "");
    return true;
}

function updateError(errorEl, message) {
    if (!errorEl) return;

    if (message) {
        errorEl.textContent = message;
        errorEl.classList.remove("hidden");
    } else {
        errorEl.textContent = "";
        errorEl.classList.add("hidden");
    }
}

document.addEventListener("DOMContentLoaded", () => {
    const form = document.getElementById("farmerForm");
    if (!form) return;

    const farmerBtn = document.getElementById("farmerBtn");
    const buyerBtn = document.getElementById("buyerBtn");
    const highlight = document.getElementById("highlight");
    const roleInput = document.getElementById("roleInput");
    const farmInfo = document.getElementById("farm-info");
    const buyerInfo = document.getElementById("buyer-info");
    const farmH4 = document.querySelector(".farm-h4");
    const buyerH4 = document.querySelector(".buyer-h4");
    const businessTypeSelect = document.getElementById("business_type");

    const roleScopedFields = form.querySelectorAll("[data-role-field]");

    const fieldEntries = Object.entries(fieldValidations).map(([name, config]) => ({
        name,
        input: form.querySelector(`[name="${name}"]`),
        errorEl: form.querySelector(`[data-error-for="${name}"]`),
        ...config,
    }));

    function applyRoleFieldState(role) {
        roleScopedFields.forEach((field) => {
            const targetRole = field.dataset.roleField;
            if (!targetRole) {
                return;
            }

            if (targetRole === role) {
                field.removeAttribute("disabled");
            } else {
                field.setAttribute("disabled", "disabled");
                const errorEl = form.querySelector(
                    `[data-error-for="${field.name}"]`
                );
                updateError(errorEl, "");
            }
        });
    }

    function syncRoleRequiredFields(role) {
        fieldEntries.forEach((field) => {
            const { input, dependsOnRole } = field;
            if (!input || !dependsOnRole) {
                return;
            }

            if (dependsOnRole === role) {
                input.setAttribute("required", "required");
            } else {
                input.removeAttribute("required");
                updateError(field.errorEl, "");
            }
        });
    }

    function switchToFarmer() {
        roleInput.value = "farmer";
        highlight && (highlight.style.transform = "translateX(0)");
        farmInfo?.classList.remove("hidden");
        buyerInfo?.classList.add("hidden");
        farmH4?.classList.remove("hidden");
        buyerH4?.classList.add("hidden");
        businessTypeSelect?.removeAttribute("required");
        syncRoleRequiredFields("farmer");
        applyRoleFieldState("farmer");
    }

    function switchToBuyer() {
        roleInput.value = "buyer";
        highlight && (highlight.style.transform = "translateX(100%)");
        farmInfo?.classList.add("hidden");
        buyerInfo?.classList.remove("hidden");
        farmH4?.classList.add("hidden");
        buyerH4?.classList.remove("hidden");
        businessTypeSelect?.setAttribute("required", "required");
        syncRoleRequiredFields("buyer");
        applyRoleFieldState("buyer");
        const businessField = fieldEntries.find((field) => field.name === "business_type");
        businessField && handleValidation(businessField, { form, roleInput });
    }

    farmerBtn?.addEventListener("click", switchToFarmer);
    buyerBtn?.addEventListener("click", switchToBuyer);

    if (roleInput.value === "buyer") {
        switchToBuyer();
    } else {
        switchToFarmer();
    }

    fieldEntries.forEach((field) => {
        if (!field.input) return;
        const eventName = field.input.tagName === "SELECT" ? "change" : "input";
        field.input.addEventListener(eventName, () =>
            handleValidation(field, { form, roleInput })
        );
        field.input.addEventListener("blur", () =>
            handleValidation(field, { form, roleInput })
        );
    });

    form.addEventListener("submit", (event) => {
        let formIsValid = true;

        fieldEntries.forEach((field) => {
            const isValid = handleValidation(field, { form, roleInput });
            if (!isValid) {
                formIsValid = false;
            }
        });

        if (!formIsValid) {
            event.preventDefault();
            const firstInvalid = fieldEntries.find((field) => {
                const errorEl = field.errorEl;
                return errorEl && !errorEl.classList.contains("hidden");
            });
            firstInvalid?.input?.focus();
        }
    });
});

