document.addEventListener('DOMContentLoaded', function () {
    // Farmer sidebar functionality
    const menuToggle = document.getElementById('menu-toggle');
    const closeBtn = document.getElementById('close-btn');
    const sidebar = document.getElementById('sidebar');

    // User dropdown functionality - fixed to work with both farmer and buyer headers
    const userMenuButton = document.getElementById('user-menu-button');
    // Try both possible dropdown IDs
    const userDropdown = document.getElementById('user-dropdown-menu') || document.getElementById('user-dropdown');

    if (userMenuButton && userDropdown) {
        userMenuButton.addEventListener('click', function () {
            userDropdown.classList.toggle('hidden');
        });

        // Close dropdown when clicking outside
        document.addEventListener('click', function (event) {
            if (!userMenuButton.contains(event.target) && !userDropdown.contains(event.target)) {
                userDropdown.classList.add('hidden');
            }
        });
    }

    if (menuToggle && sidebar) {
        menuToggle.addEventListener('click', function () {
            sidebar.classList.toggle('-translate-x-full');
        });
    }

    if (closeBtn && sidebar) {
        closeBtn.addEventListener('click', function () {
            sidebar.classList.add('-translate-x-full');
        });
    }

    // Close sidebar when clicking outside on mobile
    document.addEventListener('click', function (event) {
        if (!sidebar) return;
        
        const isClickInsideSidebar = sidebar.contains(event.target);
        const isClickOnMenuToggle = menuToggle && menuToggle.contains(event.target);

        if (!isClickInsideSidebar && !isClickOnMenuToggle && !sidebar.classList.contains('-translate-x-full')) {
            sidebar.classList.add('-translate-x-full');
        }
    });
});
