window.addEventListener('DOMContentLoaded', function() {
    const sizes = ['small', 'medium', 'large', 'extra_large', 'jumbo'];
    const eggSizeHidden = document.getElementById('egg_size_hidden');
    
    sizes.forEach(size => {
        const checkbox = document.getElementById(`${size}_checkbox`);
        const trayContainer = document.getElementById(`${size}_tray_container`);
        const trayInput = document.getElementById(`${size}_trays`);
        
        if (checkbox) {
            checkbox.addEventListener('change', function() {
                if (this.checked) {
                    trayContainer.classList.remove('hidden');
                    trayContainer.classList.add('animate-in', 'fade-in', 'slide-in-from-top-2', 'duration-200');
                } else {
                    trayContainer.classList.add('hidden');
                    if (trayInput) trayInput.value = '';
                }
                updateEggSizeHidden();
            });
        }
        
        if (trayInput) {
            trayInput.addEventListener('input', updateEggSizeHidden);
        }
    });
    
    function updateEggSizeHidden() {
        const selectedValues = [];
        sizes.forEach(size => {
            const checkbox = document.getElementById(`${size}_checkbox`);
            if (checkbox && checkbox.checked) {
                const trayInput = document.getElementById(`${size}_trays`);
                const trayCount = trayInput ? trayInput.value : '';
                
                if (trayCount) {
                    selectedValues.push(`${size} (${trayCount} tray${trayCount > 1 ? 's' : ''})`);
                } else {
                    selectedValues.push(size);
                }
            }
        });
        
        if (eggSizeHidden) {
            eggSizeHidden.value = selectedValues.join(', ');
        }
    }

    // --- Dynamic Category and Product Logic ---
    const categorySelect = document.getElementById('category');
    const productSelect = document.getElementById('product_name');
    const otherProductContainer = document.getElementById('other_product_container');
    const otherProductInput = document.getElementById('other_product_name');
    
    // Read the mapping data from the script tag
    const mappingDataTag = document.getElementById('product-mapping-data');
    if (categorySelect && productSelect && mappingDataTag) {
        const productMapping = JSON.parse(mappingDataTag.textContent);

        function updateProductOptions() {
            const selectedCategory = categorySelect.value;
            const oldValue = productSelect.getAttribute('data-old-value');
            
            let allProducts = [];
            if (selectedCategory && productMapping[selectedCategory]) {
                const categoryData = productMapping[selectedCategory];
                
                // Handle both flat arrays and nested objects
                if (Array.isArray(categoryData)) {
                    allProducts = categoryData;
                } else {
                    // Flatten all products under the category
                    Object.values(categoryData).forEach(products => {
                        if (Array.isArray(products)) {
                            allProducts = allProducts.concat(products);
                        }
                    });
                }
            }

            // Clear current options
            productSelect.innerHTML = '<option value="" disabled>Select a product</option>';

            // Add new options
            [...new Set(allProducts)].sort().forEach(product => {
                const option = document.createElement('option');
                option.value = product;
                option.textContent = product;
                if (product === oldValue) {
                    option.selected = true;
                }
                productSelect.appendChild(option);
            });

            // Always add "Others"
            const othersOption = document.createElement('option');
            othersOption.value = 'Others';
            othersOption.textContent = 'Others (Custom)';
            
            // Check if oldValue is NOT in the list of products for this category
            // and it's not empty, then it must be an "Others" custom value
            if (oldValue && oldValue !== '' && !allProducts.includes(oldValue)) {
                othersOption.selected = true;
            } else if (oldValue === 'Others') {
                othersOption.selected = true;
            }

            productSelect.appendChild(othersOption);
            handleProductChange();
        }

        function handleProductChange() {
            if (productSelect.value === 'Others') {
                otherProductContainer.classList.remove('hidden');
                otherProductInput.setAttribute('required', 'required');
                otherProductInput.focus();
            } else {
                otherProductContainer.classList.add('hidden');
                otherProductInput.removeAttribute('required');
            }
        }

        categorySelect.addEventListener('change', updateProductOptions);
        productSelect.addEventListener('change', handleProductChange);

        // Initial trigger
        if (categorySelect.value) {
            updateProductOptions();
        }
    }
});
