document.addEventListener('DOMContentLoaded', function () {
    // Mobile sidebar functionality
    const menuToggle = document.getElementById('mobileSidebarToggle');
    const sidebar = document.getElementById('mobileSidebar');

    // User dropdown functionality
    const userMenuButton = document.getElementById('user-menu-button');
    const userDropdown = document.getElementById('user-dropdown-menu');

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
            sidebar.classList.toggle('translate-x-0');
        });

        // Close sidebar when clicking outside
        document.addEventListener('click', function (event) {
            const isClickInsideSidebar = sidebar.contains(event.target);
            const isClickOnMenuToggle = menuToggle.contains(event.target);

            if (!isClickInsideSidebar && !isClickOnMenuToggle && !sidebar.classList.contains('-translate-x-full')) {
                sidebar.classList.add('-translate-x-full');
                sidebar.classList.remove('translate-x-0');
            }
        });
    }
});
