document.addEventListener('DOMContentLoaded', function() {
    console.log('Buyer Demands Index JS Loaded');
    
    // Initialize product mapping from script tag
    const mappingDataElement = document.getElementById('product-mapping-data');
    const productMapping = mappingDataElement ? JSON.parse(mappingDataElement.textContent) : {};
    
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

    // Dynamic Product Loading logic
    function populateProducts(categorySelectId, productSelectId, oldValue = null) {
        const categorySelect = document.getElementById(categorySelectId);
        const productSelect = document.getElementById(productSelectId);
        
        if (!categorySelect || !productSelect) return;

        const category = categorySelect.value;
        let allProducts = [];

        // Flatten nested products if the category mapping is an object of subcategories
        if (category && productMapping[category]) {
            const subCategories = productMapping[category];
            
            if (Array.isArray(subCategories)) {
                allProducts = subCategories;
            } else {
                Object.values(subCategories).forEach(products => {
                    if (Array.isArray(products)) {
                        allProducts = allProducts.concat(products);
                    }
                });
            }
        }

        // Remove duplicates and sort
        allProducts = [...new Set(allProducts)].sort();

        // Clear existing options
        productSelect.innerHTML = '<option value="" disabled selected>Select a product</option>';

        // Add products from mapping
        allProducts.forEach(product => {
            const option = document.createElement('option');
            option.value = product;
            option.textContent = product;
            if (oldValue && product === oldValue) {
                option.selected = true;
            }
            productSelect.appendChild(option);
        });

        // Add "Others" option
        const othersOption = document.createElement('option');
        othersOption.value = 'Others';
        othersOption.textContent = 'Others';
        if (oldValue && oldValue === 'Others') {
            othersOption.selected = true;
        }
        productSelect.appendChild(othersOption);
    }

    const createCategorySelect = document.getElementById('modal_category');
    if (createCategorySelect) {
        createCategorySelect.addEventListener('change', () => populateProducts('modal_category', 'modal_product_name'));
        
        // If there's an old value (e.g. after validation error), populate it
        const createProductSelect = document.getElementById('modal_product_name');
        if (createProductSelect && createProductSelect.dataset.oldValue) {
            populateProducts('modal_category', 'modal_product_name', createProductSelect.dataset.oldValue);
        }
    }

    const editCategorySelect = document.getElementById('edit_category');
    if (editCategorySelect) {
        editCategorySelect.addEventListener('change', () => populateProducts('edit_category', 'edit_product_name'));
    }

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

        // Handle category and dynamic product population for Edit
        const editCategorySelect = document.getElementById('edit_category');
        if (editCategorySelect && d.category) {
            editCategorySelect.value = d.category;
            populateProducts('edit_category', 'edit_product_name', d.productName);
        } else {
            // Fallback if category is missing in dataset
            populateProducts('edit_category', 'edit_product_name', d.productName);
        }

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

    // Disable scrolling on number inputs to prevent accidental value changes
    document.addEventListener('wheel', function (event) {
        if (document.activeElement.type === 'number') {
            document.activeElement.blur();
        }
    });
});
