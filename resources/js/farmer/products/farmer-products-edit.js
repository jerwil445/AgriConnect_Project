document.addEventListener('DOMContentLoaded', function () {
    const productImageInput = document.getElementById('images');
    const imageUploadDropzone = document.getElementById('image-upload-dropzone');
    const imageSelectionMeta = document.getElementById('image-selection-meta');
    const selectedImageCount = document.getElementById('selected-image-count');
    const imageLimitNote = document.getElementById('image-limit-note');
    const clearSelectedImagesButton = document.getElementById('clear-selected-images');
    const previewContainer = document.getElementById('preview-container');
    const imagePreviewArea = document.getElementById('image-preview');

    let selectedImageFiles = [];
    let imageSelectionWasTrimmed = false;

    // Total Amount Calculation
    window.updateTotalAmount = function () {
        const quantityInput = document.getElementById('quantity');
        const priceInput = document.getElementById('price');
        const totalDisplay = document.getElementById('total_amount_display');
        const quantityError = document.getElementById('quantity-error');
        const priceError = document.getElementById('price-error');

        if (quantityInput && priceInput && totalDisplay) {
            const quantityVal = quantityInput.value;
            const priceVal = priceInput.value;
            
            const quantity = parseFloat(quantityVal) || 0;
            const price = parseFloat(priceVal) || 0;

            let hasError = false;

            // Validate Quantity
            if (quantityVal !== '' && quantity < 0) {
                if (quantityError) quantityError.classList.remove('hidden');
                hasError = true;
            } else {
                if (quantityError) quantityError.classList.add('hidden');
            }

            // Validate Price
            if (priceVal !== '' && price < 0) {
                if (priceError) priceError.classList.remove('hidden');
                hasError = true;
            } else {
                if (priceError) priceError.classList.add('hidden');
            }

            if (hasError) {
                totalDisplay.value = "0.00";
            } else {
                totalDisplay.value = (quantity * price).toFixed(2);
            }
        }
    };

    // Image Upload Logic
    function syncImageInput() {
        if (!productImageInput) return;
        const dataTransfer = new DataTransfer();
        selectedImageFiles.forEach((file) => {
            dataTransfer.items.add(file);
        });
        productImageInput.files = dataTransfer.files;
    }

    function formatFileSize(bytes) {
        if (bytes < 1024) return bytes + ' B';
        if (bytes < 1024 * 1024) return (bytes / 1024).toFixed(1) + ' KB';
        return (bytes / (1024 * 1024)).toFixed(1) + ' MB';
    }

    function updateImageSelectionMeta() {
        if (!imageSelectionMeta || !selectedImageCount || !imageLimitNote) return;

        if (selectedImageFiles.length === 0) {
            imageSelectionMeta.classList.add('hidden');
            imageSelectionMeta.classList.remove('flex');
            imageLimitNote.textContent = 'Up to 10 images. First image becomes the cover.';
            return;
        }

        imageSelectionMeta.classList.remove('hidden');
        imageSelectionMeta.classList.add('flex');
        selectedImageCount.textContent = `${selectedImageFiles.length} image${selectedImageFiles.length === 1 ? '' : 's'} selected`;
        imageLimitNote.textContent = imageSelectionWasTrimmed
            ? 'Only the first 10 images were kept. Cover image is highlighted in green.'
            : 'Cover image is highlighted in green. Remove any image before saving if needed.';
    }

    function setDropzoneActive(isActive) {
        if (!imageUploadDropzone) return;
        imageUploadDropzone.classList.toggle('border-green-500', isActive);
        imageUploadDropzone.classList.toggle('bg-green-50', isActive);
        imageUploadDropzone.classList.toggle('shadow-inner', isActive);
    }

    function renderImagePreviews() {
        if (!previewContainer || !imagePreviewArea || !productImageInput) return;

        previewContainer.innerHTML = '';
        updateImageSelectionMeta();

        if (selectedImageFiles.length === 0) {
            imagePreviewArea.classList.add('hidden');
            productImageInput.value = '';
            return;
        }

        selectedImageFiles.forEach((file, index) => {
            const previewCard = document.createElement('div');
            previewCard.className = 'group overflow-hidden rounded-2xl border border-gray-200 bg-white shadow-sm';

            const imageWrapper = document.createElement('div');
            imageWrapper.className = 'relative h-44 overflow-hidden bg-gray-100';

            const img = document.createElement('img');
            img.src = URL.createObjectURL(file);
            img.className = 'h-full w-full object-cover transition duration-300 group-hover:scale-105';
            img.alt = 'Preview';
            img.onload = function () {
                URL.revokeObjectURL(this.src);
            };

            const removeButton = document.createElement('button');
            removeButton.type = 'button';
            removeButton.className = 'absolute right-2 top-2 flex h-8 w-8 items-center justify-center rounded-full bg-white/95 text-base font-bold text-red-600 shadow-md transition hover:bg-red-600 hover:text-white';
            removeButton.setAttribute('aria-label', `Remove ${file.name}`);
            removeButton.innerHTML = '&times;';
            removeButton.addEventListener('click', function () {
                selectedImageFiles = selectedImageFiles.filter((_, fileIndex) => fileIndex !== index);
                syncImageInput();
                renderImagePreviews();
            });

            imageWrapper.appendChild(img);
            imageWrapper.appendChild(removeButton);

            if (index === 0) {
                const coverBadge = document.createElement('span');
                coverBadge.className = 'absolute bottom-2 left-2 rounded-full bg-green-600 px-3 py-1 text-xs font-semibold text-white shadow';
                coverBadge.textContent = 'Cover';
                imageWrapper.appendChild(coverBadge);
            }

            const details = document.createElement('div');
            details.className = 'border-t border-gray-100 p-3';

            const fileName = document.createElement('p');
            fileName.className = 'truncate text-sm font-semibold text-gray-800';
            fileName.textContent = file.name;

            const fileSize = document.createElement('p');
            fileSize.className = 'mt-1 text-xs text-gray-500';
            fileSize.textContent = formatFileSize(file.size);

            details.appendChild(fileName);
            details.appendChild(fileSize);

            previewCard.appendChild(imageWrapper);
            previewCard.appendChild(details);
            previewContainer.appendChild(previewCard);
        });

        imagePreviewArea.classList.remove('hidden');
    }

    function handleSelectedFiles(files) {
        const imageFiles = Array.from(files).filter((file) => file.type.match('image.*'));

        imageSelectionWasTrimmed = imageFiles.length > 10;
        selectedImageFiles = imageFiles.slice(0, 10);

        syncImageInput();
        renderImagePreviews();
    }

    window.previewImages = function (input) {
        handleSelectedFiles(input.files);
    };

    if (clearSelectedImagesButton) {
        clearSelectedImagesButton.addEventListener('click', function () {
            selectedImageFiles = [];
            imageSelectionWasTrimmed = false;
            syncImageInput();
            renderImagePreviews();
        });
    }

    if (imageUploadDropzone) {
        ['dragenter', 'dragover'].forEach((eventName) => {
            imageUploadDropzone.addEventListener(eventName, function (event) {
                event.preventDefault();
                event.stopPropagation();
                setDropzoneActive(true);
            });
        });

        ['dragleave', 'drop'].forEach((eventName) => {
            imageUploadDropzone.addEventListener(eventName, function (event) {
                event.preventDefault();
                event.stopPropagation();
                setDropzoneActive(false);
            });
        });

        imageUploadDropzone.addEventListener('drop', function (event) {
            handleSelectedFiles(event.dataTransfer.files);
        });
    }

    // Initial calculation
    if (typeof updateTotalAmount === 'function') {
        updateTotalAmount();
    }

    // Event listeners for total amount
    const quantityEl = document.getElementById('quantity');
    const priceEl = document.getElementById('price');
    if (quantityEl) quantityEl.addEventListener('input', updateTotalAmount);
    if (priceEl) priceEl.addEventListener('input', updateTotalAmount);
    
    // Dynamic Category and Product Logic
    const categorySelect = document.getElementById('category');
    const productNameSelect = document.getElementById('product_name');
    const otherProductContainer = document.getElementById('other_product_container');
    const otherProductInput = document.getElementById('other_product_name');
    const mappingDataEl = document.getElementById('product-mapping-data');

    if (categorySelect && productNameSelect && mappingDataEl) {
        const productMapping = JSON.parse(mappingDataEl.textContent);

        function updateProductOptions(selectedCategory, selectedProduct = null) {
            // Clear current options
            productNameSelect.innerHTML = '<option value="" disabled selected>Select a product</option>';
            
            if (selectedCategory && productMapping[selectedCategory]) {
                const subCategories = productMapping[selectedCategory];
                let allProducts = [];
                
                // Flatten all products under the category
                Object.values(subCategories).forEach(products => {
                    allProducts = allProducts.concat(products);
                });
                
                // Sort and add options
                [...new Set(allProducts)].sort().forEach(product => {
                    const option = document.createElement('option');
                    option.value = product;
                    option.textContent = product;
                    if (selectedProduct === product) option.selected = true;
                    productNameSelect.appendChild(option);
                });

                // Add "Others" option
                const othersOption = document.createElement('option');
                othersOption.value = 'Others';
                othersOption.textContent = 'Others (Custom)';
                
                // Check if current selectedProduct is "Others" or custom
                const isCustom = selectedProduct && selectedProduct !== '' && !allProducts.includes(selectedProduct);
                if (selectedProduct === 'Others' || isCustom) othersOption.selected = true;
                
                productNameSelect.appendChild(othersOption);
            }
        }

        categorySelect.addEventListener('change', function() {
            updateProductOptions(this.value);
            // Hide other product name if category changes
            otherProductContainer.classList.add('hidden');
            otherProductInput.removeAttribute('required');
            otherProductInput.value = '';
        });

        productNameSelect.addEventListener('change', function() {
            if (this.value === 'Others') {
                otherProductContainer.classList.remove('hidden');
                otherProductInput.setAttribute('required', 'required');
                otherProductInput.focus();
            } else {
                otherProductContainer.classList.add('hidden');
                otherProductInput.removeAttribute('required');
                otherProductInput.value = '';
            }
        });

        // Initialize with current product
        if (categorySelect.value) {
            const oldProduct = productNameSelect.getAttribute('data-old-value') || '';
            updateProductOptions(categorySelect.value, oldProduct);
        }
    }
});
