@extends('layouts.admin_page')

@section('content')
<div class="ml-72 mr-5 mt-20 relative bg-gradient-to-br from-emerald-50/50 via-white to-green-50/50 rounded-[2rem] shadow-[0_8px_30px_rgb(0,0,0,0.04)] border border-white overflow-hidden">
    
    <!-- Subtle Background Elements -->
    <div class="absolute top-0 right-0 w-96 h-96 bg-green-200/20 rounded-full blur-3xl -mt-20 -mr-20 pointer-events-none"></div>
    <div class="absolute bottom-0 left-0 w-72 h-72 bg-emerald-200/20 rounded-full blur-3xl -mb-10 -ml-10 pointer-events-none"></div>

    <main class="relative z-10 flex-1 p-8 lg:p-10">
        <div class="max-w-6xl mx-auto">
            
            <div class="flex justify-between items-center mb-8 pb-4 border-b border-green-100">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 bg-green-100 rounded-xl flex items-center justify-center text-green-600 shadow-inner">
                        <i class="fas fa-box-open"></i>
                    </div>
                    <div>
                        <h2 class="text-2xl font-black text-gray-800 tracking-tight">Edit Product</h2>
                        <p class="text-sm text-gray-500 font-medium mt-0.5">Update product listing details and imagery</p>
                    </div>
                </div>
                
                <a href="{{ route('admin.products.index') }}" 
                   class="bg-white hover:bg-gray-50 text-gray-700 text-sm font-semibold px-4 py-2.5 rounded-xl border border-gray-200 shadow-sm transition-all duration-200 flex items-center group">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1.5 text-gray-400 group-hover:text-gray-600 transition-colors" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M9.707 16.707a1 1 0 01-1.414 0l-6-6a1 1 0 010-1.414l6-6a1 1 0 011.414 1.414L5.414 9H17a1 1 0 110 2H5.414l4.293 4.293a1 1 0 010 1.414z" clip-rule="evenodd" />
                    </svg>
                    Back to Products
                </a>
            </div>

            @if ($errors->any())
                <div class="bg-red-50/80 backdrop-blur-sm border-l-4 border-red-500 text-red-800 px-5 py-4 rounded-r-xl shadow-sm mb-8">
                    <div class="flex items-center gap-2 mb-2">
                        <i class="fas fa-exclamation-circle text-red-500"></i>
                        <strong class="font-bold text-red-700">Please fix the following errors:</strong>
                    </div>
                    <ul class="list-disc list-inside text-sm text-red-600/90 ml-6 space-y-1">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form action="{{ route('admin.products.update', $product) }}" method="POST" enctype="multipart/form-data" class="bg-white/60 backdrop-blur-xl rounded-2xl border border-white shadow-sm p-6 lg:p-8">
                @csrf
                @method('PUT')

                <div class="grid grid-cols-1 lg:grid-cols-2 gap-10">
                    <!-- Left Column: Core Data -->
                    <div class="space-y-8">
                        
                        <div>
                            <h3 class="text-sm font-bold uppercase tracking-wider text-green-700 mb-6 pb-2 border-b border-gray-100">Primary Details</h3>
                            <div class="space-y-6">
                                <div class="relative w-full">
                                    <input type="text" name="product_name" id="product_name" value="{{ old('product_name', $product->product_name) }}" placeholder=" " required
                                           class="peer w-full px-4 py-3 border border-gray-200 rounded-xl bg-white/50 focus:bg-white focus:outline-none focus:ring-2 focus:ring-green-400 focus:border-transparent transition-all placeholder-transparent shadow-sm">
                                    <label for="product_name" class="absolute left-4 -top-2.5 text-xs font-medium bg-white px-1 text-gray-500 transition-all peer-placeholder-shown:top-3.5 peer-placeholder-shown:text-base peer-focus:-top-2.5 peer-focus:text-xs peer-focus:text-green-600 rounded cursor-text">
                                        Product Name
                                    </label>
                                </div>

                                <div class="relative w-full">
                                    <input type="text" name="variety_size" id="variety_size" value="{{ old('variety_size', $product->variety_size) }}" placeholder=" "
                                           class="peer w-full px-4 py-3 border border-gray-200 rounded-xl bg-white/50 focus:bg-white focus:outline-none focus:ring-2 focus:ring-green-400 focus:border-transparent transition-all placeholder-transparent shadow-sm">
                                    <label for="variety_size" class="absolute left-4 -top-2.5 text-xs font-medium bg-white px-1 text-gray-500 transition-all peer-placeholder-shown:top-3.5 peer-placeholder-shown:text-base peer-focus:-top-2.5 peer-focus:text-xs peer-focus:text-green-600 rounded cursor-text">
                                        Variety / Size
                                    </label>
                                    <p class="text-[10px] text-gray-400 mt-1 ml-2">e.g. Large, Medium, Grade A</p>
                                </div>

                                <div class="relative w-full">
                                    <textarea name="description" id="description" rows="4" placeholder=" "
                                              class="peer w-full px-4 py-3 border border-gray-200 rounded-xl bg-white/50 focus:bg-white focus:outline-none focus:ring-2 focus:ring-green-400 focus:border-transparent transition-all placeholder-transparent shadow-sm resize-none">{{ old('description', $product->description) }}</textarea>
                                    <label for="description" class="absolute left-4 -top-2.5 text-xs font-medium bg-white px-1 text-gray-500 transition-all peer-placeholder-shown:top-3.5 peer-placeholder-shown:text-base peer-focus:-top-2.5 peer-focus:text-xs peer-focus:text-green-600 rounded cursor-text">
                                        Product Description
                                    </label>
                                </div>
                            </div>
                        </div>

                        <div>
                            <h3 class="text-sm font-bold uppercase tracking-wider text-green-700 mb-6 pb-2 border-b border-gray-100">Media Gallery</h3>
                            <div class="rounded-2xl border border-gray-200 bg-gradient-to-br from-green-50 via-white to-emerald-50 p-4 shadow-sm">
                                <div id="image-upload-dropzone"
                                     class="relative overflow-hidden rounded-2xl border-2 border-dashed border-green-200 bg-white/90 p-6 transition duration-200 hover:border-green-400 hover:bg-green-50/60 cursor-pointer">
                                    <input type="file" name="images[]" id="images" multiple accept="image/*" class="sr-only" onchange="previewImages(this)">
                                    
                                    <label for="images" class="block cursor-pointer">
                                        <div class="mx-auto flex flex-col items-center text-center">
                                            <div class="flex h-14 w-14 items-center justify-center rounded-2xl bg-green-100 text-green-600 shadow-sm mb-3">
                                                <i class="fas fa-cloud-upload-alt text-2xl"></i>
                                            </div>
                                            <h3 class="text-sm font-semibold text-gray-900">Drop replacement images here</h3>
                                            <p class="mt-1 text-xs text-gray-500">or click to browse from device</p>
                                            <div class="mt-4 flex flex-wrap items-center justify-center gap-2 text-[10px] text-gray-500">
                                                <span class="rounded-full bg-white px-2.5 py-1 shadow-sm border border-gray-100">Replaces current</span>
                                                <span class="rounded-full bg-white px-2.5 py-1 shadow-sm border border-gray-100">Max 10 images</span>
                                            </div>
                                        </div>
                                    </label>
                                </div>

                                <div id="image-selection-meta" class="mt-4 hidden items-center justify-between rounded-xl border border-gray-200 bg-white px-4 py-3 shadow-sm">
                                    <div>
                                        <p id="selected-image-count" class="text-sm font-semibold text-gray-900">0 images selected</p>
                                        <p id="image-limit-note" class="text-[10px] text-gray-500">Uploading new images replaces current gallery.</p>
                                    </div>
                                    <button type="button" id="clear-selected-images" class="inline-flex flex-shrink-0 items-center rounded-lg border border-gray-200 bg-gray-50 px-3 py-1.5 text-xs font-medium text-gray-600 transition hover:bg-gray-100 hover:text-red-500">
                                        Clear All
                                    </button>
                                </div>
                            </div>

                            <div id="image-preview" class="mt-5 hidden">
                                <h4 class="text-xs font-bold text-gray-800 uppercase mb-3">New Staged Images</h4>
                                <div id="preview-container" class="grid grid-cols-2 lg:grid-cols-3 gap-3"></div>
                            </div>

                            @if($product->images->count() > 0)
                                <div class="mt-5">
                                    <div class="flex items-center justify-between mb-3">
                                        <h4 class="text-xs font-bold text-gray-800 uppercase">Current Gallery</h4>
                                        <span class="rounded-full bg-green-100 text-green-700 px-2 py-0.5 text-[10px] font-bold border border-green-200">
                                            {{ $product->images->count() }} Saved
                                        </span>
                                    </div>
                                    <div class="grid grid-cols-2 lg:grid-cols-3 gap-3">
                                        @foreach($product->images as $image)
                                            <div class="overflow-hidden rounded-xl border border-gray-200 bg-white shadow-sm group">
                                                <div class="relative h-24 overflow-hidden bg-gray-100">
                                                    <img src="{{ asset('storage/' . $image->image_path) }}" class="h-full w-full object-cover transition duration-300 group-hover:scale-110">
                                                    @if($loop->first)
                                                        <span class="absolute bottom-1.5 left-1.5 rounded-full bg-gray-900/80 backdrop-blur-md px-2 py-0.5 text-[9px] font-bold text-white shadow-sm border border-gray-600/50">Cover</span>
                                                    @endif
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            @endif
                        </div>
                    </div>

                    <!-- Right Column: Numbers & Setting -->
                    <div class="space-y-8">
                        
                        <div class="bg-gradient-to-br from-green-50/50 to-emerald-50/50 p-6 rounded-2xl border border-green-100 shadow-sm relative overflow-hidden">
                            <div class="absolute -right-4 -top-4 text-green-200/40 transform rotate-12 pointer-events-none">
                                <i class="fas fa-tags text-8xl"></i>
                            </div>
                            <h3 class="text-sm font-bold uppercase tracking-wider text-green-800 mb-6 pb-2 border-b border-green-200/50 relative z-10">Pricing & Inventory</h3>
                            
                            <div class="space-y-6 relative z-10">
                                <div class="grid grid-cols-2 gap-4">
                                    <div class="relative w-full">
                                        <input type="number" name="quantity" id="quantity" value="{{ old('quantity', $product->quantity) }}" min="1" placeholder=" " required
                                               class="peer w-full px-4 py-3 border border-white/60 rounded-xl bg-white/70 focus:bg-white focus:outline-none focus:ring-2 focus:ring-green-400 focus:border-transparent transition-all placeholder-transparent shadow-[0_2px_10px_rgb(0,0,0,0.02)]">
                                        <label for="quantity" class="absolute left-4 -top-2.5 text-xs font-medium bg-emerald-50 px-1 text-gray-600 transition-all peer-placeholder-shown:top-3.5 peer-placeholder-shown:text-base peer-focus:-top-2.5 peer-focus:text-xs peer-focus:text-green-700 peer-focus:bg-white rounded cursor-text">
                                            Quantity
                                        </label>
                                    </div>

                                    <div class="relative w-full group">
                                        <select name="unit" id="unit" required
                                                class="peer w-full px-4 py-3 border border-white/60 rounded-xl bg-white/70 focus:bg-white focus:outline-none focus:ring-2 focus:ring-green-400 focus:border-transparent shadow-[0_2px_10px_rgb(0,0,0,0.02)] appearance-none">
                                            @foreach(['pieces', 'trays', 'dozen', 'kilos', 'boxes', 'bunches', 'sacks'] as $unit)
                                                <option value="{{ $unit }}" {{ old('unit', $product->unit) === $unit ? 'selected' : '' }}>
                                                    {{ ucfirst($unit) }}
                                                </option>
                                            @endforeach
                                        </select>
                                        <label for="unit" class="absolute left-4 -top-2.5 text-xs font-medium bg-emerald-50 px-1 text-gray-600 rounded transition-all">
                                            Unit Type
                                        </label>
                                        <div class="absolute inset-y-0 right-4 flex items-center pointer-events-none text-gray-400">
                                            <i class="fas fa-chevron-down text-sm"></i>
                                        </div>
                                    </div>
                                </div>

                                <div class="grid grid-cols-2 gap-4">
                                    <div class="relative w-full">
                                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-gray-500">
                                            ₱
                                        </div>
                                        <input type="number" name="price" id="price" value="{{ old('price', $product->price) }}" step="0.01" min="0" placeholder=" " required
                                               class="peer w-full pl-8 pr-4 py-3 border border-white/60 rounded-xl bg-white/70 focus:bg-white focus:outline-none focus:ring-2 focus:ring-green-400 focus:border-transparent transition-all placeholder-transparent shadow-[0_2px_10px_rgb(0,0,0,0.02)]">
                                        <label for="price" class="absolute left-8 -top-2.5 text-xs font-medium bg-emerald-50 px-1 text-gray-600 transition-all peer-placeholder-shown:top-3.5 peer-placeholder-shown:text-base peer-focus:-top-2.5 peer-focus:text-xs peer-focus:text-green-700 peer-focus:bg-white peer-focus:left-4 rounded cursor-text">
                                            Price per Unit
                                        </label>
                                    </div>

                                    <div class="relative w-full">
                                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-green-600 font-bold">
                                            ₱
                                        </div>
                                        <input type="text" id="total_amount_display" readonly
                                               class="w-full pl-8 pr-4 py-3 border border-green-200/50 rounded-xl bg-green-100/30 text-green-800 font-bold shadow-inner">
                                        <label class="absolute left-4 -top-2.5 text-xs font-bold bg-emerald-50 px-1 text-green-700 rounded">
                                            Total Value
                                        </label>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div>
                            <h3 class="text-sm font-bold uppercase tracking-wider text-green-700 mb-6 pb-2 border-b border-gray-100">Status & Logistics</h3>
                            <div class="space-y-6">
                                <div class="grid grid-cols-2 gap-4">
                                    <div class="relative w-full group">
                                        <select name="status" id="status" 
                                                class="peer w-full px-4 py-3 border border-gray-200 rounded-xl bg-white/50 focus:bg-white focus:outline-none focus:ring-2 focus:ring-green-400 focus:border-transparent shadow-sm appearance-none font-semibold text-gray-700">
                                            @foreach(['Available', 'Pending', 'Sold Out'] as $status)
                                                <option value="{{ $status }}" {{ old('status', $product->status) === $status ? 'selected' : '' }}>
                                                    {{ $status }}
                                                </option>
                                            @endforeach
                                        </select>
                                        <label for="status" class="absolute left-4 -top-2.5 text-xs font-medium bg-white px-1 text-gray-500 transition-all rounded">
                                            Listing Status
                                        </label>
                                        <div class="absolute inset-y-0 right-4 flex items-center pointer-events-none text-gray-400">
                                            <i class="fas fa-chevron-down text-sm"></i>
                                        </div>
                                    </div>

                                    <div class="relative w-full">
                                        <input type="date" name="harvest_date" id="harvest_date" value="{{ old('harvest_date', optional($product->harvest_date)->format('Y-m-d')) }}" placeholder=" "
                                               class="peer w-full px-4 py-3 border border-gray-200 rounded-xl bg-white/50 focus:bg-white focus:outline-none focus:ring-2 focus:ring-green-400 focus:border-transparent transition-all placeholder-transparent shadow-sm appearance-none">
                                        <label for="harvest_date" class="absolute left-4 -top-2.5 text-xs font-medium bg-white px-1 text-gray-500 transition-all peer-placeholder-shown:top-3.5 peer-placeholder-shown:text-base peer-focus:-top-2.5 peer-focus:text-xs peer-focus:text-green-600 rounded cursor-text">
                                            Harvest Date
                                        </label>
                                    </div>
                                </div>

                                <div class="p-5 bg-gray-50/50 rounded-2xl border border-gray-100">
                                    <h4 class="text-xs font-bold text-gray-600 uppercase mb-4 flex items-center gap-2"><i class="fas fa-map-marker-alt"></i> Location Data</h4>
                                    <div class="grid grid-cols-2 gap-4">
                                        <div class="relative w-full">
                                            <input type="text" name="purok_street" id="purok_street" value="{{ old('purok_street', $product->purok_street) }}" placeholder=" "
                                                   class="peer w-full px-3 py-2 border border-gray-200 rounded-lg bg-white focus:outline-none focus:ring-2 focus:ring-green-400 focus:border-transparent transition-all placeholder-transparent text-sm">
                                            <label for="purok_street" class="absolute left-3 -top-2 text-[10px] font-medium bg-white px-1 text-gray-500 transition-all peer-placeholder-shown:top-2 peer-placeholder-shown:text-sm peer-focus:-top-2 peer-focus:text-[10px] peer-focus:text-green-600 rounded cursor-text">
                                                Street / Purok
                                            </label>
                                        </div>
                                        <div class="relative w-full">
                                            <input type="text" name="barangay" id="barangay" value="{{ old('barangay', $product->barangay) }}" placeholder=" "
                                                   class="peer w-full px-3 py-2 border border-gray-200 rounded-lg bg-white focus:outline-none focus:ring-2 focus:ring-green-400 focus:border-transparent transition-all placeholder-transparent text-sm">
                                            <label for="barangay" class="absolute left-3 -top-2 text-[10px] font-medium bg-white px-1 text-gray-500 transition-all peer-placeholder-shown:top-2 peer-placeholder-shown:text-sm peer-focus:-top-2 peer-focus:text-[10px] peer-focus:text-green-600 rounded cursor-text">
                                                Barangay
                                            </label>
                                        </div>
                                        <div class="relative w-full">
                                            <input type="text" name="municipality_city" id="municipality_city" value="{{ old('municipality_city', $product->municipality_city) }}" placeholder=" "
                                                   class="peer w-full px-3 py-2 border border-gray-200 rounded-lg bg-white focus:outline-none focus:ring-2 focus:ring-green-400 focus:border-transparent transition-all placeholder-transparent text-sm">
                                            <label for="municipality_city" class="absolute left-3 -top-2 text-[10px] font-medium bg-white px-1 text-gray-500 transition-all peer-placeholder-shown:top-2 peer-placeholder-shown:text-sm peer-focus:-top-2 peer-focus:text-[10px] peer-focus:text-green-600 rounded cursor-text">
                                                City / Municipality
                                            </label>
                                        </div>
                                        <div class="relative w-full">
                                            <input type="text" name="province" id="province" value="{{ old('province', $product->province) }}" placeholder=" "
                                                   class="peer w-full px-3 py-2 border border-gray-200 rounded-lg bg-white focus:outline-none focus:ring-2 focus:ring-green-400 focus:border-transparent transition-all placeholder-transparent text-sm">
                                            <label for="province" class="absolute left-3 -top-2 text-[10px] font-medium bg-white px-1 text-gray-500 transition-all peer-placeholder-shown:top-2 peer-placeholder-shown:text-sm peer-focus:-top-2 peer-focus:text-[10px] peer-focus:text-green-600 rounded cursor-text">
                                                Province
                                            </label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>

                <div class="mt-10 pt-6 flex justify-end gap-4 border-t border-gray-100">
                    <a href="{{ route('admin.products.index') }}"
                       class="rounded-xl border border-gray-200 bg-white px-6 py-3 text-sm font-semibold text-gray-700 shadow-sm hover:bg-gray-50 transition-colors">
                        Cancel
                    </a>
                    <button type="submit"
                            class="bg-gradient-to-r from-green-600 to-emerald-500 text-white font-bold px-8 py-3 rounded-xl shadow-[0_10px_20px_-10px_rgba(16,185,129,0.5)] hover:shadow-[0_15px_25px_-10px_rgba(16,185,129,0.6)] transform hover:-translate-y-0.5 transition-all duration-300">
                        <i class="fas fa-save mr-2"></i> Save Changes
                    </button>
                </div>
            </form>
        </div>
    </main>
</div>

<!-- Kept existing functional javascript unaltered except UI classes -->
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
        selectedImageFiles.forEach((file) => dataTransfer.items.add(file));
        productImageInput.files = dataTransfer.files;
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
            img.onload = function() { URL.revokeObjectURL(img.src); };

            const removeButton = document.createElement('button');
            removeButton.type = 'button';
            removeButton.className = 'absolute right-1.5 top-1.5 flex h-6 w-6 items-center justify-center rounded-full bg-white/90 backdrop-blur text-xs font-bold text-red-600 shadow-sm transition hover:bg-red-600 hover:text-white';
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

    function previewImages(input) { handleSelectedFiles(input.files); }

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
