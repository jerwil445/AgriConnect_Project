document.addEventListener('DOMContentLoaded', function() {
    console.log('Buyer Demands Index JS Loaded');
    
    const modal = document.getElementById('demandModal');
    const backdrop = document.getElementById('modalBackdrop');
    const openBtns = [
        document.getElementById('openDemandModal'),
        document.getElementById('openDemandModalEmpty'),
    ].filter(Boolean);

    function openModal() {
        console.log('Opening Create Modal');
        if (modal) {
            modal.classList.remove('hidden');
            document.body.style.overflow = 'hidden';
        }
    }

    function closeModal() {
        console.log('Closing Create Modal');
        if (modal) {
            modal.classList.add('hidden');
            document.body.style.overflow = '';
        }
    }

    openBtns.forEach(btn => btn.addEventListener('click', openModal));
    document.getElementById('closeModal')?.addEventListener('click', closeModal);
    document.getElementById('cancelModal')?.addEventListener('click', closeModal);
    backdrop?.addEventListener('click', closeModal);

    // ══ EDIT DEMAND MODAL LOGIC ══
    const editModal = document.getElementById('editDemandModal');
    const editBackdrop = document.getElementById('editModalBackdrop');
    const editForm = document.getElementById('editDemandForm');
    const editLoadingOverlay = document.getElementById('editLoadingOverlay');
    const submitEditBtn = document.getElementById('submitEditBtn');

    function openEditModal() {
        console.log('Opening Edit Modal');
        if (editModal) {
            editModal.classList.remove('hidden');
            document.body.style.overflow = 'hidden';
        } else {
            console.error('Edit Modal element not found!');
        }
    }

    function closeEditModal() {
        console.log('Closing Edit Modal');
        if (editModal) {
            editModal.classList.add('hidden');
            document.body.style.overflow = '';
        }
    }

    document.getElementById('closeEditModal')?.addEventListener('click', closeEditModal);
    document.getElementById('cancelEditModal')?.addEventListener('click', closeEditModal);
    editBackdrop?.addEventListener('click', closeEditModal);

    // Handle Edit Button Clicks (reads data from HTML attributes — instant, no network request)
    document.addEventListener('click', function(e) {
        const btn = e.target.closest('.edit-demand-btn');
        if (!btn) return;

        const d = btn.dataset;

        // Set form action
        if (editForm) editForm.action = `/demands/${d.id}`;

        // Populate all fields instantly from data attributes
        const setVal = (id, val) => {
            const el = document.getElementById(id);
            if (el) el.value = val || '';
        };

        setVal('edit_product_name', d.productName);
        setVal('edit_variety_size', d.varietySize);
        setVal('edit_quantity', d.quantity);
        setVal('edit_unit', d.unit);
        setVal('edit_delivery_date', d.deliveryDate);
        setVal('edit_deadline', d.deadline);
        setVal('edit_purok_street', d.purokStreet);
        setVal('edit_barangay', d.barangay);
        setVal('edit_municipality_city', d.municipalityCity);
        setVal('edit_province', d.province);

        openEditModal();
    });

    // Handle Update Submission
    editForm?.addEventListener('submit', function() {
        console.log('Submitting Edit Form');
        if (editLoadingOverlay) editLoadingOverlay.classList.remove('hidden');
        if (submitEditBtn) {
            submitEditBtn.disabled = true;
            submitEditBtn.classList.add('opacity-75', 'cursor-not-allowed');
        }
    });

    // Handle Create Submission
    const demandForm = document.getElementById('demandForm');
    const loadingOverlay = document.getElementById('loadingOverlay');
    const submitBtn = document.getElementById('submitBtn');

    demandForm?.addEventListener('submit', function() {
        console.log('Submitting Create Form');
        if (loadingOverlay) loadingOverlay.classList.remove('hidden');
        if (submitBtn) {
            submitBtn.disabled = true;
            submitBtn.classList.add('opacity-75', 'cursor-not-allowed');
        }
    });

    document.addEventListener('keydown', e => {
        if (e.key === 'Escape') {
            closeModal();
            closeEditModal();
        }
    });

    document.querySelectorAll('.delete-demand-form').forEach(form => {
        form.addEventListener('submit', e => {
            if (!confirm('Are you sure you want to delete this demand?')) {
                e.preventDefault();
            }
        });
    });

    if (window.hasValidationErrors) {
        openModal();
    }

    // ══ QUANTITY VALIDATION ══
    function validateQuantity(inputId, errorId, submitId) {
        const input = document.getElementById(inputId);
        const error = document.getElementById(errorId);
        const submitBtn = document.getElementById(submitId);

        if (input && error) {
            input.addEventListener('input', function() {
                const val = parseFloat(this.value);
                if (this.value !== '' && val < 0) {
                    error.classList.remove('hidden');
                    if (submitBtn) {
                        submitBtn.disabled = true;
                        submitBtn.classList.add('opacity-75', 'cursor-not-allowed');
                    }
                } else {
                    error.classList.add('hidden');
                    if (submitBtn) {
                        submitBtn.disabled = false;
                        submitBtn.classList.remove('opacity-75', 'cursor-not-allowed');
                    }
                }
            });
        }
    }

    validateQuantity('modal_quantity', 'modal_quantity_error', 'submitBtn');
    validateQuantity('edit_quantity', 'edit_quantity_error', 'submitEditBtn');
});
