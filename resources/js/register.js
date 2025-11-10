// document.addEventListener("DOMContentLoaded", function () {
//     const farmerBtn = document.getElementById("farmerBtn");
//     const buyerBtn = document.getElementById("buyerBtn");
//     const farmerForm = document.getElementById("farmerForm");
//     const buyerForm = document.getElementById("buyerForm");
//     const highlight = document.getElementById("highlight");

//     // Function to show farmer form and hide buyer form
//     function showFarmerForm() {
//         farmerForm.style.opacity = "1";
//         farmerForm.style.pointerEvents = "auto";
//         buyerForm.style.opacity = "0";
//         buyerForm.style.pointerEvents = "none";
//         highlight.style.transform = "translateX(0)";
//     }

//     // Function to show buyer form and hide farmer form
//     function showBuyerForm() {
//         farmerForm.style.opacity = "0";
//         farmerForm.style.pointerEvents = "none";
//         buyerForm.style.opacity = "1";
//         buyerForm.style.pointerEvents = "auto";
//         highlight.style.transform = "translateX(100%)";
//     }

//     // Add event listeners to the buttons
//     farmerBtn.addEventListener("click", showFarmerForm);
//     buyerBtn.addEventListener("click", showBuyerForm);

//     // Initially show the farmer form
//     showFarmerForm();
// });
