// Smooth scroll, Scroll Reveal, and tab functionality using pure Tailwind manipulation
document.addEventListener("DOMContentLoaded", () => {
    // Intersection Observer for scroll animations (Tailwind version)
    const reveals = document.querySelectorAll(".reveal-group");
    const observer = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                entry.target.classList.remove("opacity-0", "translate-y-8");
                entry.target.classList.add("opacity-100", "translate-y-0");
                observer.unobserve(entry.target); // Optional: animate only once
            }
        });
    }, { threshold: 0.1, rootMargin: "0px 0px -50px 0px" });
    
    reveals.forEach(reveal => observer.observe(reveal));

    // Smooth scrolling for anchor links
    document.querySelectorAll("a[href^='#']").forEach(link => {
        link.addEventListener("click", function(e) {
            e.preventDefault();
            const targetId = this.getAttribute("href");
            if (targetId === '#') return;
            
            const targetElement = document.querySelector(targetId);
            if (targetElement) {
                targetElement.scrollIntoView({ behavior: "smooth" });
            }
        });

        // Close mobile menu when clicking nav link
        const mobileMenu = document.getElementById('mobile-menu');
        if (mobileMenu && !mobileMenu.classList.contains('hidden')) {
            mobileMenu.classList.add('hidden');
            document.body.classList.remove('overflow-hidden');
        }
    });

    // Tab switching functionality
    const farmerBtn = document.getElementById('for-farmer-btn');
    const buyerBtn = document.getElementById('for-buyers-btn');
    const farmersSection = document.getElementById('for-farmers');
    const buyersSection = document.getElementById('for-buyers');
    const pillIndicator = document.getElementById('pill-indicator');

    if (farmerBtn && buyerBtn && farmersSection && buyersSection && pillIndicator) {
        // Initialize text colors
        farmerBtn.classList.add('text-green-900', 'font-bold');
        buyerBtn.classList.add('text-gray-500', 'font-medium');

        buyerBtn.addEventListener('click', () => {
            // Hide farmers, show buyers
            farmersSection.classList.add('hidden', 'opacity-0');
            buyersSection.classList.remove('hidden');
            
            setTimeout(() => {
                buyersSection.classList.remove('opacity-0');
                buyersSection.classList.add('opacity-100');
            }, 10);
            
            // Move indicator
            pillIndicator.style.transform = 'translateX(100%)';
            
            // Update text styles
            farmerBtn.classList.remove('text-green-900', 'font-bold');
            farmerBtn.classList.add('text-gray-500', 'font-medium');
            
            buyerBtn.classList.remove('text-gray-500', 'font-medium');
            buyerBtn.classList.add('text-green-900', 'font-bold');
        });

        farmerBtn.addEventListener('click', () => {
            // Hide buyers, show farmers
            buyersSection.classList.add('hidden', 'opacity-0');
            farmersSection.classList.remove('hidden');
            
            setTimeout(() => {
                farmersSection.classList.remove('opacity-0');
                farmersSection.classList.add('opacity-100');
            }, 10);
            
            // Move indicator back
            pillIndicator.style.transform = 'translateX(0)';
            
            // Update text styles
            buyerBtn.classList.remove('text-green-900', 'font-bold');
            buyerBtn.classList.add('text-gray-500', 'font-medium');
            
            farmerBtn.classList.remove('text-gray-500', 'font-medium');
            farmerBtn.classList.add('text-green-900', 'font-bold');
        });
    }
});
