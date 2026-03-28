@extends('layouts.admin_page')

@section('content')
<div class="bg-white rounded-xl shadow-sm border border-gray-100 ml-72 mr-5 mt-20">
    <main class="flex-1 p-6">
        <div class="max-w-6xl mx-auto">
            <div class="bg-white rounded-lg shadow p-6">
                <div class="flex items-center justify-between mb-6">
                    <div>
                        <h2 class="text-2xl font-bold text-gray-800">Edit Product</h2>
                        <p class="text-sm text-gray-500 mt-1">Update product details using the same structure as the create-product form.</p>
                    </div>
                    <a href="{{ route('admin.products.index') }}" class="rounded-md bg-gray-500 px-4 py-2 text-white hover:bg-gray-600">
                        Back to Products
                    </a>
                </div>

                <form action="{{ route('admin.products.update', $product) }}" method="POST" enctype="multipart/form-data" class="space-y-8">
                    @csrf
                    @method('PUT')

                    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                        <div class="space-y-6">
                            <div>
                                <label for="product_name" class="block text-sm font-medium text-gray-700 mb-1">Product Name</label>
                                <input type="text" name="product_name" id="product_name" value="{{ old('product_name', $product->product_name) }}"
                                    class="w-full rounded-md border-gray-300 shadow-sm focus:border-green-500 focus:ring-green-500" required>
                                @error('product_name')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label for="variety_size" class="block text-sm font-medium text-gray-700 mb-1">Variety/Size</label>
                                <input type="text" name="variety_size" id="variety_size" value="{{ old('variety_size', $product->variety_size) }}"
                                    placeholder="Large, Medium, Grade A, Bundle, etc."
                                    class="w-full rounded-md border-gray-300 shadow-sm focus:border-green-500 focus:ring-green-500">
                                @error('variety_size')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label for="description" class="block text-sm font-medium text-gray-700 mb-1">Description</label>
                                <textarea name="description" id="description" rows="5"
                                    class="w-full rounded-md border-gray-300 shadow-sm focus:border-green-500 focus:ring-green-500"
                                    placeholder="Describe the product quality, packaging, or harvest notes.">{{ old('description', $product->description) }}</textarea>
                                @error('description')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label for="images" class="block text-sm font-medium text-gray-700 mb-1">Replace Images</label>
                                <div class="mt-2 rounded-2xl border border-gray-200 bg-gradient-to-br from-green-50 via-white to-emerald-50 p-4">
                                    <div id="image-upload-dropzone"
                                        class="relative overflow-hidden rounded-2xl border-2 border-dashed border-green-200 bg-white/90 p-6 transition duration-200 hover:border-green-400 hover:bg-green-50/60">
                                        <input type="file" name="images[]" id="images" multiple accept="image/*"
                                            class="sr-only" onchange="previewImages(this)">

                                        <label for="images" class="block cursor-pointer">
                                            <div class="mx-auto flex max-w-lg flex-col items-center text-center">
                                                <div class="flex h-16 w-16 items-center justify-center rounded-2xl bg-green-100 text-green-700 shadow-sm">
                                                    <svg class="h-8 w-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M3 15.75V16.5A2.25 2.25 0 005.25 18.75h13.5A2.25 2.25 0 0021 16.5v-.75M7.5 10.5L12 6m0 0l4.5 4.5M12 6v9"></path>
                                                    </svg>
                                                </div>
                                                <h3 class="mt-4 text-base font-semibold text-gray-900">Drop replacement images here</h3>
                                                <p class="mt-1 text-sm text-gray-500">or click to browse from your device</p>
                                                <div class="mt-4 inline-flex items-center rounded-full bg-green-600 px-4 py-2 text-sm font-semibold text-white shadow-sm">
                                                    Choose New Images
                                                </div>
                                                <div class="mt-4 flex flex-wrap items-center justify-center gap-2 text-xs text-gray-500">
                                                    <span class="rounded-full bg-white px-3 py-1 shadow-sm ring-1 ring-gray-200">Replaces current gallery</span>
                                                    <span class="rounded-full bg-white px-3 py-1 shadow-sm ring-1 ring-gray-200">Up to 10 images</span>
                                                    <span class="rounded-full bg-white px-3 py-1 shadow-sm ring-1 ring-gray-200">First image = cover</span>
                                                </div>
                                            </div>
                                        </label>
                                    </div>

                                    <div id="image-selection-meta" class="mt-4 hidden items-center justify-between rounded-xl border border-gray-200 bg-white px-4 py-3">
                                        <div>
                                            <p id="selected-image-count" class="text-sm font-semibold text-gray-900">0 images selected</p>
                                            <p id="image-limit-note" class="text-xs text-gray-500">Uploading new images will replace the current gallery.</p>
                                        </div>
                                        <button type="button" id="clear-selected-images"
                                            class="inline-flex items-center rounded-lg border border-gray-300 px-3 py-2 text-sm font-medium text-gray-700 transition hover:bg-gray-50">
                                            Clear All
                                        </button>
                                    </div>
                                </div>
                                @error('images')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                                @error('images.*')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror

                                <div id="image-preview" class="mt-4 hidden">
                                    <div class="mb-3 flex items-center justify-between">
                                        <div>
                                            <p class="text-sm font-semibold text-gray-800">New Images</p>
                                            <p class="text-xs text-gray-500">Saving will replace the current gallery with the images below.</p>
                                        </div>
                                    </div>
                                    <div id="preview-container" class="grid grid-cols-1 gap-3 sm:grid-cols-2 xl:grid-cols-3"></div>
                                </div>

                                @if($product->images->count() > 0)
                                    <div class="mt-4">
                                        <div class="mb-3 flex items-center justify-between">
                                            <div>
                                                <p class="text-sm font-semibold text-gray-800">Current Images</p>
                                                <p class="text-xs text-gray-500">These stay in place unless you upload a replacement gallery.</p>
                                            </div>
                                            <span class="rounded-full bg-gray-100 px-3 py-1 text-xs font-medium text-gray-600">
                                                {{ $product->images->count() }} saved
                                            </span>
                                        </div>
                                        <div class="grid grid-cols-2 gap-3 sm:grid-cols-3">
                                            @foreach($product->images as $image)
                                                <div class="overflow-hidden rounded-2xl border border-gray-200 bg-white shadow-sm">
                                                    <div class="relative h-28 overflow-hidden bg-gray-100">
                                                        <img src="{{ asset('storage/' . $image->image_path) }}" alt="{{ $product->product_name }}"
                                                            class="h-full w-full object-cover">
                                                        @if($loop->first)
                                                            <span class="absolute bottom-2 left-2 rounded-full bg-gray-900 px-3 py-1 text-xs font-semibold text-white shadow">
                                                                Current Cover
                                                            </span>
                                                        @endif
                                                    </div>
                                                </div>
                                            @endforeach
                                        </div>
                                    </div>
                                @endif
                            </div>
                        </div>

                        <div class="space-y-6">
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div>
                                    <label for="quantity" class="block text-sm font-medium text-gray-700 mb-1">Quantity</label>
                                    <input type="number" name="quantity" id="quantity" value="{{ old('quantity', $product->quantity) }}" min="1"
                                        class="w-full rounded-md border-gray-300 shadow-sm focus:border-green-500 focus:ring-green-500" required>
                                    @error('quantity')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>

                                <div>
                                    <label for="unit" class="block text-sm font-medium text-gray-700 mb-1">Unit</label>
                                    <select name="unit" id="unit"
                                        class="w-full rounded-md border-gray-300 shadow-sm focus:border-green-500 focus:ring-green-500" required>
                                        @foreach(['pieces', 'trays', 'dozen', 'kilos', 'boxes', 'bunches', 'sacks'] as $unit)
                                            <option value="{{ $unit }}" {{ old('unit', $product->unit) === $unit ? 'selected' : '' }}>
                                                {{ ucfirst($unit) }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('unit')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div>
                                    <label for="price" class="block text-sm font-medium text-gray-700 mb-1">Price per Unit</label>
                                    <input type="number" name="price" id="price" value="{{ old('price', $product->price) }}" step="0.01" min="0"
                                        class="w-full rounded-md border-gray-300 shadow-sm focus:border-green-500 focus:ring-green-500" required>
                                    @error('price')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>

                                <div>
                                    <label for="total_amount_display" class="block text-sm font-medium text-gray-700 mb-1">Total Amount</label>
                                    <input type="text" id="total_amount_display"
                                        value="{{ number_format((float) old('quantity', $product->quantity) * (float) old('price', $product->price), 2) }}"
                                        class="w-full rounded-md border-gray-300 bg-gray-50 shadow-sm" readonly>
                                </div>
                            </div>

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div>
                                    <label for="harvest_date" class="block text-sm font-medium text-gray-700 mb-1">Harvest Date</label>
                                    <input type="date" name="harvest_date" id="harvest_date"
                                        value="{{ old('harvest_date', optional($product->harvest_date)->format('Y-m-d')) }}"
                                        class="w-full rounded-md border-gray-300 shadow-sm focus:border-green-500 focus:ring-green-500">
                                    @error('harvest_date')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>

                                <div>
                                    <label for="status" class="block text-sm font-medium text-gray-700 mb-1">Status</label>
                                    <select name="status" id="status"
                                        class="w-full rounded-md border-gray-300 shadow-sm focus:border-green-500 focus:ring-green-500">
                                        @foreach(['Available', 'Pending', 'Sold Out'] as $status)
                                            <option value="{{ $status }}" {{ old('status', $product->status) === $status ? 'selected' : '' }}>
                                                {{ $status }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('status')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-3">Address</label>
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                    <div>
                                        <label for="purok_street" class="block text-xs text-gray-500 mb-1">Purok/Street</label>
                                        <input type="text" name="purok_street" id="purok_street" value="{{ old('purok_street', $product->purok_street) }}"
                                            class="w-full rounded-md border-gray-300 shadow-sm focus:border-green-500 focus:ring-green-500">
                                        @error('purok_street')
                                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                        @enderror
                                    </div>

                                    <div>
                                        <label for="barangay" class="block text-xs text-gray-500 mb-1">Barangay</label>
                                        <input type="text" name="barangay" id="barangay" value="{{ old('barangay', $product->barangay) }}"
                                            class="w-full rounded-md border-gray-300 shadow-sm focus:border-green-500 focus:ring-green-500">
                                        @error('barangay')
                                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                        @enderror
                                    </div>

                                    <div>
                                        <label for="municipality_city" class="block text-xs text-gray-500 mb-1">Municipality/City</label>
                                        <input type="text" name="municipality_city" id="municipality_city" value="{{ old('municipality_city', $product->municipality_city) }}"
                                            class="w-full rounded-md border-gray-300 shadow-sm focus:border-green-500 focus:ring-green-500">
                                        @error('municipality_city')
                                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                        @enderror
                                    </div>

                                    <div>
                                        <label for="province" class="block text-xs text-gray-500 mb-1">Province</label>
                                        <input type="text" name="province" id="province" value="{{ old('province', $product->province) }}"
                                            class="w-full rounded-md border-gray-300 shadow-sm focus:border-green-500 focus:ring-green-500">
                                        @error('province')
                                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="flex justify-end gap-3 border-t border-gray-100 pt-6">
                        <a href="{{ route('admin.products.index') }}"
                            class="rounded-md border border-gray-300 bg-white px-4 py-2 text-sm font-semibold text-gray-700 shadow-sm hover:bg-gray-50">
                            Cancel
                        </a>
                        <button type="submit"
                            class="rounded-md bg-green-600 px-4 py-2 text-sm font-semibold text-white shadow-sm hover:bg-green-500">
                            Save Changes
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </main>
</div>

<script>
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
        document.getElementById('total_amount_display').value = (quantity * price).toFixed(2);
    }

    function syncImageInput() {
        const dataTransfer = new DataTransfer();

        selectedImageFiles.forEach((file) => {
            dataTransfer.items.add(file);
        });

        productImageInput.files = dataTransfer.files;
    }

    function formatFileSize(bytes) {
        if (bytes < 1024) {
            return bytes + ' B';
        }

        if (bytes < 1024 * 1024) {
            return (bytes / 1024).toFixed(1) + ' KB';
        }

        return (bytes / (1024 * 1024)).toFixed(1) + ' MB';
    }

    function updateImageSelectionMeta() {
        if (selectedImageFiles.length === 0) {
            imageSelectionMeta.classList.add('hidden');
            imageSelectionMeta.classList.remove('flex');
            imageLimitNote.textContent = 'Uploading new images will replace the current gallery.';
            return;
        }

        imageSelectionMeta.classList.remove('hidden');
        imageSelectionMeta.classList.add('flex');
        selectedImageCount.textContent = `${selectedImageFiles.length} image${selectedImageFiles.length === 1 ? '' : 's'} selected`;
        imageLimitNote.textContent = imageSelectionWasTrimmed
            ? 'Only the first 10 images were kept. Saving will replace the current gallery.'
            : 'Cover image is highlighted in green. Saving this form will replace the current gallery.';
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
            previewCard.className = 'group overflow-hidden rounded-2xl border border-gray-200 bg-white shadow-sm';

            const imageWrapper = document.createElement('div');
            imageWrapper.className = 'relative h-44 overflow-hidden bg-gray-100';

            const img = document.createElement('img');
            img.src = URL.createObjectURL(file);
            img.className = 'h-full w-full object-cover transition duration-300 group-hover:scale-105';
            img.alt = 'Preview';
            img.onload = function() {
                URL.revokeObjectURL(img.src);
            };

            const removeButton = document.createElement('button');
            removeButton.type = 'button';
            removeButton.className = 'absolute right-2 top-2 flex h-8 w-8 items-center justify-center rounded-full bg-white/95 text-base font-bold text-red-600 shadow-md transition hover:bg-red-600 hover:text-white';
            removeButton.setAttribute('aria-label', `Remove ${file.name}`);
            removeButton.innerHTML = '&times;';
            removeButton.addEventListener('click', function() {
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

        imagePreview.classList.remove('hidden');
    }

    function handleSelectedFiles(files) {
        const imageFiles = Array.from(files).filter((file) => file.type.match('image.*'));

        imageSelectionWasTrimmed = imageFiles.length > 10;
        selectedImageFiles = imageFiles.slice(0, 10);

        syncImageInput();
        renderImagePreviews();
    }

    function previewImages(input) {
        handleSelectedFiles(input.files);
    }

    if (clearSelectedImagesButton) {
        clearSelectedImagesButton.addEventListener('click', function() {
            selectedImageFiles = [];
            imageSelectionWasTrimmed = false;
            syncImageInput();
            renderImagePreviews();
        });
    }

    ['dragenter', 'dragover'].forEach((eventName) => {
        imageUploadDropzone.addEventListener(eventName, function(event) {
            event.preventDefault();
            event.stopPropagation();
            setDropzoneActive(true);
        });
    });

    ['dragleave', 'drop'].forEach((eventName) => {
        imageUploadDropzone.addEventListener(eventName, function(event) {
            event.preventDefault();
            event.stopPropagation();
            setDropzoneActive(false);
        });
    });

    imageUploadDropzone.addEventListener('drop', function(event) {
        handleSelectedFiles(event.dataTransfer.files);
    });

    document.getElementById('quantity').addEventListener('input', updateTotalAmount);
    document.getElementById('price').addEventListener('input', updateTotalAmount);
    updateTotalAmount();
</script>
@endsection
