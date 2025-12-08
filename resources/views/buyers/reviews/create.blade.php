@extends('layouts.buyers_page')

@section('title', 'Leave a Review')

@section('content')
<div class="px-4 py-8">
    <div class="max-w-3xl mx-auto">
        <div class="bg-white rounded-lg shadow-md p-6">
            <div class="flex justify-between items-center mb-6">
                <h1 class="text-2xl font-bold text-gray-800">Leave a Review</h1>
                <a href="{{ route('buyer.orders') }}" class="text-indigo-600 hover:text-indigo-800">
                    &larr; Back to Orders
                </a>
            </div>

            <!-- Order Information -->
            <div class="bg-gray-50 rounded-lg p-4 mb-6">
                <h2 class="font-semibold text-gray-800 mb-3">Order Information</h2>
                <div class="grid grid-cols-2 gap-4 text-sm">
                    <div>
                        <span class="text-gray-600">Order ID:</span>
                        <span class="font-medium ml-2">#{{ $transaction->id }}</span>
                    </div>
                    <div>
                        <span class="text-gray-600">Product:</span>
                        <span class="font-medium ml-2">{{ $transaction->product->egg_type ?? 'N/A' }}</span>
                    </div>
                    <div>
                        <span class="text-gray-600">Farmer:</span>
                        <span class="font-medium ml-2">{{ $transaction->farmer->first_name }} {{ $transaction->farmer->last_name }}</span>
                    </div>
                    <div>
                        <span class="text-gray-600">Total Amount:</span>
                        <span class="font-medium ml-2">₱{{ number_format($transaction->total_amount, 2) }}</span>
                    </div>
                </div>
            </div>

            <!-- Review Form -->
            <form action="{{ route('buyer.orders.review.submit', $transaction->id) }}" method="POST">
                @csrf

                <div class="space-y-6">
                    <!-- Overall Rating -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            Overall Rating <span class="text-red-500">*</span>
                        </label>
                        <div class="flex items-center space-x-2">
                            @for($i = 1; $i <= 5; $i++)
                                <label class="cursor-pointer">
                                    <input type="radio" name="overall_rating" value="{{ $i }}" class="hidden star-radio" required>
                                    <svg class="w-8 h-8 star-icon text-gray-300 hover:text-yellow-400 transition-colors" fill="currentColor" viewBox="0 0 20 20">
                                        <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                                    </svg>
                                </label>
                            @endfor
                        </div>
                        @error('overall_rating')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Product Quality Rating -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            Product Quality <span class="text-red-500">*</span>
                        </label>
                        <div class="flex items-center space-x-2">
                            @for($i = 1; $i <= 5; $i++)
                                <label class="cursor-pointer">
                                    <input type="radio" name="product_quality_rating" value="{{ $i }}" class="hidden star-radio" required>
                                    <svg class="w-8 h-8 star-icon text-gray-300 hover:text-yellow-400 transition-colors" fill="currentColor" viewBox="0 0 20 20">
                                        <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                                    </svg>
                                </label>
                            @endfor
                        </div>
                        @error('product_quality_rating')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Delivery Rating -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            Delivery <span class="text-red-500">*</span>
                        </label>
                        <div class="flex items-center space-x-2">
                            @for($i = 1; $i <= 5; $i++)
                                <label class="cursor-pointer">
                                    <input type="radio" name="delivery_rating" value="{{ $i }}" class="hidden star-radio" required>
                                    <svg class="w-8 h-8 star-icon text-gray-300 hover:text-yellow-400 transition-colors" fill="currentColor" viewBox="0 0 20 20">
                                        <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                                    </svg>
                                </label>
                            @endfor
                        </div>
                        @error('delivery_rating')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Communication Rating -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            Communication <span class="text-red-500">*</span>
                        </label>
                        <div class="flex items-center space-x-2">
                            @for($i = 1; $i <= 5; $i++)
                                <label class="cursor-pointer">
                                    <input type="radio" name="communication_rating" value="{{ $i }}" class="hidden star-radio" required>
                                    <svg class="w-8 h-8 star-icon text-gray-300 hover:text-yellow-400 transition-colors" fill="currentColor" viewBox="0 0 20 20">
                                        <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                                    </svg>
                                </label>
                            @endfor
                        </div>
                        @error('communication_rating')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Packaging Rating -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            Packaging <span class="text-red-500">*</span>
                        </label>
                        <div class="flex items-center space-x-2">
                            @for($i = 1; $i <= 5; $i++)
                                <label class="cursor-pointer">
                                    <input type="radio" name="packaging_rating" value="{{ $i }}" class="hidden star-radio" required>
                                    <svg class="w-8 h-8 star-icon text-gray-300 hover:text-yellow-400 transition-colors" fill="currentColor" viewBox="0 0 20 20">
                                        <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                                    </svg>
                                </label>
                            @endfor
                        </div>
                        @error('packaging_rating')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Comment -->
                    <div>
                        <label for="comment" class="block text-sm font-medium text-gray-700 mb-2">
                            Your Review (Optional)
                        </label>
                        <textarea 
                            id="comment" 
                            name="comment" 
                            rows="4" 
                            class="shadow-sm focus:ring-indigo-500 focus:border-indigo-500 block w-full sm:text-sm border-gray-300 rounded-md"
                            placeholder="Share your experience with this order..."
                        >{{ old('comment') }}</textarea>
                        @error('comment')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                        <p class="text-gray-500 text-xs mt-1">Maximum 1000 characters</p>
                    </div>

                    <!-- Submit Button -->
                    <div class="flex justify-end space-x-3">
                        <a href="{{ route('buyer.orders') }}" 
                           class="inline-flex items-center px-4 py-2 border border-gray-300 shadow-sm text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none">
                            Cancel
                        </a>
                        <button type="submit" 
                                class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none">
                            Submit Review
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Handle star rating interactions
    const starContainers = document.querySelectorAll('[name$="_rating"]');
    
    starContainers.forEach(function(radioGroup) {
        const name = radioGroup.getAttribute('name');
        const container = radioGroup.closest('div').querySelector('.flex');
        const stars = container.querySelectorAll('.star-icon');
        const radios = container.querySelectorAll('.star-radio');
        
        // Handle click on star
        radios.forEach(function(radio, index) {
            radio.addEventListener('change', function() {
                updateStars(stars, index + 1);
            });
        });
        
        // Handle hover
        stars.forEach(function(star, index) {
            star.addEventListener('mouseenter', function() {
                updateStars(stars, index + 1);
            });
        });
        
        // Reset on mouse leave
        container.addEventListener('mouseleave', function() {
            const checkedRadio = container.querySelector('.star-radio:checked');
            if (checkedRadio) {
                const checkedIndex = Array.from(radios).indexOf(checkedRadio);
                updateStars(stars, checkedIndex + 1);
            } else {
                updateStars(stars, 0);
            }
        });
    });
    
    function updateStars(stars, rating) {
        stars.forEach(function(star, index) {
            if (index < rating) {
                star.classList.remove('text-gray-300');
                star.classList.add('text-yellow-400');
            } else {
                star.classList.remove('text-yellow-400');
                star.classList.add('text-gray-300');
            }
        });
    }
});
</script>
@endsection
