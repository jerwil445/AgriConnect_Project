document.addEventListener("DOMContentLoaded", () => {
    const farmerBtn = document.getElementById("farmerBtn");
    const buyerBtn = document.getElementById("buyerBtn");
    const highlight = document.getElementById("highlight");

    const farmerForm = document.getElementById("farm-info");
    const buyerForm = document.getElementById("buyer-info");

    // Switch between forms
    farmerBtn.addEventListener("click", () => {
        highlight.style.left = "0";
        farmerBtn.classList.add("text-white");
        buyerBtn.classList.remove("text-white");
        buyerBtn.classList.add("text-gray-700");
        farmerForm.classList.remove("opacity-0", "pointer-events-none");
        buyerForm.classList.add("opacity-0", "pointer-events-none");
    });

    buyerBtn.addEventListener("click", () => {
        highlight.style.left = "50%";
        buyerBtn.classList.add("text-white");
        farmerBtn.classList.remove("text-white");
        farmerBtn.classList.add("text-gray-700");
        buyerForm.classList.remove("opacity-0", "pointer-events-none");
        farmerForm.classList.add("opacity-0", "pointer-events-none");
    });
});


