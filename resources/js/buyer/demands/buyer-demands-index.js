document.addEventListener('DOMContentLoaded', function() {
    const modal = document.getElementById('demandModal');
    const backdrop = document.getElementById('modalBackdrop');
    const openBtns = [
        document.getElementById('openDemandModal'),
        document.getElementById('openDemandModalEmpty'),
    ].filter(Boolean);

    function openModal() {
        if (modal) {
            modal.classList.remove('hidden');
            document.body.style.overflow = 'hidden';
        }
    }

    function closeModal() {
        if (modal) {
            modal.classList.add('hidden');
            document.body.style.overflow = '';
        }
    }

    openBtns.forEach(btn => btn.addEventListener('click', openModal));
    document.getElementById('closeModal')?.addEventListener('click', closeModal);
    document.getElementById('cancelModal')?.addEventListener('click', closeModal);
    backdrop?.addEventListener('click', closeModal);
    
    // Handle Loading State on Submit
    const demandForm = document.getElementById('demandForm');
    const loadingOverlay = document.getElementById('loadingOverlay');
    const submitBtn = document.getElementById('submitBtn');

    demandForm?.addEventListener('submit', function() {
        if (loadingOverlay) loadingOverlay.classList.remove('hidden');
        if (submitBtn) {
            submitBtn.disabled = true;
            submitBtn.classList.add('opacity-75', 'cursor-not-allowed');
        }
    });

    document.addEventListener('keydown', e => {
        if (e.key === 'Escape' && modal && !modal.classList.contains('hidden') && loadingOverlay && loadingOverlay.classList.contains('hidden')) {
            closeModal();
        }
    });

    document.querySelectorAll('.delete-demand-form').forEach(form => {
        form.addEventListener('submit', e => {
            if (!confirm('Are you sure you want to delete this demand?')) {
                e.preventDefault();
            }
        });
    });

    // Re-open modal if validation errors exist
    if (window.hasValidationErrors) {
        openModal();
    }
});
