document.addEventListener("DOMContentLoaded", () => {
    // Handle notification dropdown for both farmer and buyer
    const notificationButton = document.getElementById("notification-button");
    const notificationDropdown = document.getElementById("notification-dropdown");
    
    if (notificationButton && notificationDropdown) {
        // Toggle notification dropdown
        notificationButton.addEventListener("click", (event) => {
            event.stopPropagation();
            notificationDropdown.classList.toggle("hidden");
        });
        
        // Close dropdown when clicking outside
        document.addEventListener("click", (event) => {
            if (!notificationDropdown.contains(event.target) && 
                !notificationButton.contains(event.target)) {
                notificationDropdown.classList.add("hidden");
            }
        });
        
        // Close dropdown when pressing Escape key
        document.addEventListener("keydown", (event) => {
            if (event.key === "Escape") {
                notificationDropdown.classList.add("hidden");
            }
        });
    }
});