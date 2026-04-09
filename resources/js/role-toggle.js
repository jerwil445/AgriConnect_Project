document.addEventListener("DOMContentLoaded", () => {
    const farmerBtn = document.getElementById("farmerBtn");
    const buyerBtn = document.getElementById("buyerBtn");
    const highlight = document.getElementById("highlight");

    const farmerForm = document.getElementById("farm-info");
    const buyerForm = document.getElementById("buyer-info");

    const farmH4 = document.querySelector(".farm-h4 ");
    const buyerH4 = document.querySelector(".buyer-h4 ");

    // Switch between forms
    farmerBtn.addEventListener("click", () => {
        highlight.style.left = "10px";
        farmerBtn.classList.add("text-white");
        buyerBtn.classList.remove("text-white");
        buyerBtn.classList.add("text-gray-700");
        farmerForm.style.display = "flex";
        buyerForm.style.display = "none";
        buyerH4.style.display = "none";
        farmH4.style.display = "flex";
    });

    buyerBtn.addEventListener("click", () => {
        highlight.style.left = "-10px";
        buyerBtn.classList.add("text-white");
        farmerBtn.classList.remove("text-white");
        farmerBtn.classList.add("text-gray-700");
        buyerForm.style.display = "flex";
        farmerForm.style.display = "none";
        buyerH4.style.display = "flex";
        farmH4.style.display = "none";
    });
});
