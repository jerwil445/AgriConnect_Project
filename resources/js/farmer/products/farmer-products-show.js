document.addEventListener('DOMContentLoaded', function () {
    // Gallery Image Swapping
    window.changeMainImage = function (src, btn) {
        const mainImg = document.getElementById('mainImage');
        if (!mainImg) return;

        mainImg.classList.add('opacity-0');

        // Handle active state for thumbnails
        document.querySelectorAll('[onclick^="changeMainImage"]').forEach(b => {
            b.classList.remove('border-green-500', 'ring-4', 'ring-green-500/10');
            b.classList.add('border-white', 'opacity-60');
        });

        if (btn) {
            btn.classList.add('border-green-500', 'ring-4', 'ring-green-500/10');
            btn.classList.remove('border-white', 'opacity-60');
        }

        setTimeout(() => {
            mainImg.src = src;
            mainImg.classList.remove('opacity-0');
        }, 300);
    };

    // Image Modal Logic
    const imageModal = document.getElementById('imageModal');
    const modalImage = document.getElementById('modalImage');

    window.openImageModal = function (src) {
        if (!imageModal || !modalImage) return;

        modalImage.src = src;
        imageModal.classList.remove('hidden');
        document.body.style.overflow = 'hidden';

        // Animation
        if (typeof anime !== 'undefined') {
            anime({
                targets: '#imageModal',
                opacity: [0, 1],
                duration: 400,
                easing: 'easeOutQuart'
            });
        }
    };

    window.closeImageModal = function () {
        if (!imageModal) return;

        imageModal.classList.add('hidden');
        document.body.style.overflow = '';
    };

    // Close modal on escape
    document.addEventListener('keydown', e => {
        if (e.key === 'Escape') {
            if (typeof closeImageModal === 'function') {
                closeImageModal();
            }
        }
    });

    if (imageModal) {
        imageModal.addEventListener('click', function (e) {
            if (e.target === imageModal) closeImageModal();
        });
    }
});
