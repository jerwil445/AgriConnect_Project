window.changeMainImage = function(src, btn) {
    const mainImg = document.getElementById('mainImage');
    if (mainImg) mainImg.src = src;
    
    document.querySelectorAll('[onclick^="changeMainImage"]').forEach(b => {
        b.classList.remove('border-green-500');
        b.classList.add('border-transparent');
    });
    
    if (btn) {
        btn.classList.add('border-green-500');
        btn.classList.remove('border-transparent');
    }
};

window.openImageModal = function(src) {
    const modalImg = document.getElementById('modalImage');
    const modal = document.getElementById('imageModal');
    if (modalImg) modalImg.src = src;
    if (modal) {
        modal.classList.remove('hidden');
        document.body.style.overflow = 'hidden';
    }
};

window.closeImageModal = function() {
    const modal = document.getElementById('imageModal');
    if (modal) {
        modal.classList.add('hidden');
        document.body.style.overflow = '';
    }
};

document.addEventListener('keydown', e => {
    if (e.key === 'Escape') window.closeImageModal();
});

document.addEventListener('DOMContentLoaded', () => {
    // Standard initialization if needed
});
