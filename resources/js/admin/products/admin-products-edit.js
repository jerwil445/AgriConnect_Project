document.addEventListener('DOMContentLoaded', () => {
    const productImageInput = document.getElementById('images');
    const imageUploadDropzone = document.getElementById('image-upload-dropzone');
    const imageSelectionMeta = document.getElementById('image-selection-meta');
    const selectedImageCount = document.getElementById('selected-image-count');
    const imageLimitNote = document.getElementById('image-limit-note');
    const clearSelectedImagesButton = document.getElementById('clear-selected-images');
    let selectedImageFiles = [];
    let imageSelectionWasTrimmed = false;

    function updateTotalAmount() {
        const quantity = parseFloat(document.getElementById('quantity').value) || 0;
        const price = parseFloat(document.getElementById('price').value) || 0;
        const display = document.getElementById('total_amount_display');
        if (display) display.value = (quantity * price).toFixed(2);
    }

    function syncImageInput() {
        const dataTransfer = new DataTransfer();
        selectedImageFiles.forEach((file) => dataTransfer.items.add(file));
        if (productImageInput) productImageInput.files = dataTransfer.files;
    }

    function formatFileSize(bytes) {
        if (bytes < 1024) return bytes + ' B';
        if (bytes < 1024 * 1024) return (bytes / 1024).toFixed(1) + ' KB';
        return (bytes / (1024 * 1024)).toFixed(1) + ' MB';
    }

    function updateImageSelectionMeta() {
        if (selectedImageFiles.length === 0) {
            imageSelectionMeta.classList.add('hidden');
            imageSelectionMeta.classList.remove('flex');
            imageLimitNote.textContent = 'Uploading new images replaces current gallery.';
            return;
        }

        imageSelectionMeta.classList.remove('hidden');
        imageSelectionMeta.classList.add('flex');
        selectedImageCount.textContent = `${selectedImageFiles.length} image${selectedImageFiles.length === 1 ? '' : 's'} selected`;
        imageLimitNote.textContent = imageSelectionWasTrimmed
            ? 'Only first 10 kept. Saving replaces current gallery.'
            : 'Cover is highlighted. Saving replaces current gallery.';
    }

    function setDropzoneActive(isActive) {
        imageUploadDropzone.classList.toggle('border-green-500', isActive);
        imageUploadDropzone.classList.toggle('bg-green-50', isActive);
        imageUploadDropzone.classList.toggle('shadow-inner', isActive);
    }

    function renderImagePreviews() {
        const imagePreview = document.getElementById('image-preview');
        const previewContainer = document.getElementById('preview-container');
        previewContainer.innerHTML = '';
        updateImageSelectionMeta();

        if (selectedImageFiles.length === 0) {
            imagePreview.classList.add('hidden');
            productImageInput.value = '';
            return;
        }

        selectedImageFiles.forEach((file, index) => {
            const previewCard = document.createElement('div');
            previewCard.className = 'group overflow-hidden rounded-xl border border-gray-200 bg-white shadow-sm';

            const imageWrapper = document.createElement('div');
            imageWrapper.className = 'relative h-28 overflow-hidden bg-gray-100';

            const img = document.createElement('img');
            img.src = URL.createObjectURL(file);
            img.className = 'h-full w-full object-cover transition duration-300 group-hover:scale-110';
            img.onload = function () { URL.revokeObjectURL(img.src); };

            const removeButton = document.createElement('button');
            removeButton.type = 'button';
            removeButton.className = 'absolute right-1.5 top-1.5 flex h-6 w-6 items-center justify-center rounded-full bg-white/90 backdrop-blur text-xs font-bold text-red-600 shadow-sm transition hover:bg-red-600 hover:text-white';
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
                coverBadge.className = 'absolute bottom-1.5 left-1.5 rounded-full bg-green-600/90 backdrop-blur px-2 py-0.5 text-[9px] font-bold text-white shadow';
                coverBadge.textContent = 'Cover';
                imageWrapper.appendChild(coverBadge);
            }

            const details = document.createElement('div');
            details.className = 'border-t border-gray-100 p-2';

            const fileName = document.createElement('p');
            fileName.className = 'truncate text-[10px] font-semibold text-gray-800';
            fileName.textContent = file.name;

            const fileSize = document.createElement('p');
            fileSize.className = 'text-[9px] text-gray-500';
            fileSize.textContent = formatFileSize(file.size);

            details.appendChild(fileName);
            details.appendChild(fileSize);

            previewCard.appendChild(imageWrapper);
            previewCard.appendChild(details);
            previewContainer.appendChild(previewCard);
        });

        imagePreview.classList.remove('hidden');
    }

    function handleSelectedFiles(files) {
        const imageFiles = Array.from(files).filter((file) => file.type.match('image.*'));
        imageSelectionWasTrimmed = imageFiles.length > 10;
        selectedImageFiles = imageFiles.slice(0, 10);
        syncImageInput();
        renderImagePreviews();
    }

    window.previewImages = function(input) { handleSelectedFiles(input.files); };

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

    const quantityInput = document.getElementById('quantity');
    const priceInput = document.getElementById('price');
    if (quantityInput) quantityInput.addEventListener('input', updateTotalAmount);
    if (priceInput) priceInput.addEventListener('input', updateTotalAmount);
    updateTotalAmount();

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
