document.addEventListener('DOMContentLoaded', function () {
    // Mobile menu toggle
    const mobileMenuButton = document.getElementById('mobile-menu-button');
    const mobileMenu = document.getElementById('mobile-menu');
    const menuIconPath = document.getElementById('menu-icon-path');

    if (mobileMenuButton && mobileMenu) {
        mobileMenuButton.addEventListener('click', function () {
            mobileMenu.classList.toggle('hidden');

            // Toggle between hamburger menu and X icon
            if (mobileMenu.classList.contains('hidden')) {
                menuIconPath.setAttribute('d', 'M4 6h16M4 12h16M4 18h16');
            } else {
                menuIconPath.setAttribute('d', 'M6 18L18 6M6 6l12 12');
            }
        });

        // Close mobile menu when clicking a link
        const mobileLinks = mobileMenu.querySelectorAll('a');
        mobileLinks.forEach(link => {
            link.addEventListener('click', () => {
                mobileMenu.classList.add('hidden');
                menuIconPath.setAttribute('d', 'M4 6h16M4 12h16M4 18h16');
            });
        });
    }

    // Optional: add shadow on scroll
    const nav = document.getElementById('main-nav');
    if (nav) {
        window.addEventListener('scroll', () => {
            if (window.scrollY > 10) {
                nav.classList.add('shadow-md', 'bg-white/95');
                nav.classList.remove('bg-white/80', 'shadow-sm');
            } else {
                nav.classList.remove('shadow-md', 'bg-white/95');
                nav.classList.add('bg-white/80', 'shadow-sm');
            }
        });
    }
});
