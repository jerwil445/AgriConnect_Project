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

    // Handle Edit Button Clicks (Using Event Delegation for better reliability)
    document.addEventListener('click', async function(e) {
        const btn = e.target.closest('.edit-demand-btn');
        if (!btn) return;

        console.log('Edit Button Clicked, ID:', btn.dataset.id);
        const demandId = btn.dataset.id;
        
        // Show some temporary loading state
        const originalContent = btn.innerHTML;
        btn.disabled = true;
        btn.innerHTML = '<i class="fas fa-spinner fa-spin text-xs"></i>...';

        try {
            console.log(`Fetching data for demand ${demandId}...`);
            const response = await fetch(`/demands/${demandId}/data`);
            if (!response.ok) throw new Error(`HTTP error! status: ${response.status}`);
            
            const demand = await response.json();
            console.log('Demand data received:', demand);
            
            // Populate Edit Form
            if (editForm) editForm.action = `/demands/${demandId}`;
            
            const setVal = (id, val) => {
                const el = document.getElementById(id);
                if (el) el.value = val || '';
            };

            setVal('edit_product_name', demand.product_name);
            setVal('edit_variety_size', demand.variety_size);
            setVal('edit_quantity', demand.quantity);
            setVal('edit_unit', demand.unit);
            
            // Format dates for input (YYYY-MM-DD)
            if (demand.delivery_date) {
                const d = new Date(demand.delivery_date);
                if (!isNaN(d)) {
                    // Adjust for local timezone to get correct YYYY-MM-DD
                    const offset = d.getTimezoneOffset() * 60000;
                    const localDate = new Date(d.getTime() - offset);
                    setVal('edit_delivery_date', localDate.toISOString().split('T')[0]);
                }
            }
            
            if (demand.deadline) {
                const d = new Date(demand.deadline);
                if (!isNaN(d)) {
                    const offset = d.getTimezoneOffset() * 60000;
                    const localDate = new Date(d.getTime() - offset);
                    setVal('edit_deadline', localDate.toISOString().split('T')[0]);
                }
            }

            setVal('edit_purok_street', demand.purok_street);
            setVal('edit_barangay', demand.barangay);
            setVal('edit_municipality_city', demand.municipality_city);
            setVal('edit_province', demand.province);

            openEditModal();
        } catch (error) {
            console.error('Error fetching demand:', error);
            alert('An error occurred while fetching demand data. Please check the console.');
        } finally {
            btn.disabled = false;
            btn.innerHTML = originalContent;
        }
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
});
