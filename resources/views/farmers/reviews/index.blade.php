@extends('layouts.farmers_page')

@section('title', 'Reviews & Ratings')

@section('content')
<div class="md:ml-64 p-4 sm:p-6 lg:p-8">
    <!-- Header -->
    <div class="mb-6">
        <h1 class="text-2xl sm:text-3xl font-bold text-gray-900">Reviews & Ratings</h1>
        <p class="text-sm sm:text-base text-gray-600 mt-1">Customer feedback and ratings</p>
    </div>

    <!-- Rating Summary -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 sm:gap-6 mb-8">
        <!-- Overall Rating -->
        <div class="bg-white rounded-xl shadow-sm p-4 sm:p-6">
            <div class="text-center">
                <div class="text-5xl font-bold text-gray-900 mb-2">
                    {{ number_format($avgRatings->overall ?? 0, 1) }}
                </div>
                <div class="flex justify-center mb-2">
                    @for($i = 1; $i <= 5; $i++)
                        @if($i <= round($avgRatings->overall ?? 0))
                            <svg class="w-6 h-6 text-yellow-400" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                            </svg>
                        @else
                            <svg class="w-6 h-6 text-gray-300" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                            </svg>
                        @endif
                    @endfor
                </div>
                <div class="text-sm text-gray-500">Based on {{ $farmer->total_reviews }} reviews</div>
            </div>
        </div>

        <!-- Rating Breakdown -->
        <div class="bg-white rounded-xl shadow-sm p-4 sm:p-6 md:col-span-2">
            <h3 class="text-lg font-semibold text-gray-900 mb-4">Rating Distribution</h3>
            <div class="space-y-3">
                @for($star = 5; $star >= 1; $star--)
                    @php
                        $count = $ratingBreakdown->get($star)->count ?? 0;
                        $percentage = $farmer->total_reviews > 0 ? ($count / $farmer->total_reviews) * 100 : 0;
                    @endphp
                    <div class="flex items-center gap-3">
                        <span class="text-sm font-medium text-gray-700 w-12">{{ $star }} star</span>
                        <div class="flex-1 bg-gray-200 rounded-full h-2">
                            <div class="bg-yellow-400 h-2 rounded-full" style="width: {{ $percentage }}%"></div>
                        </div>
                        <span class="text-sm text-gray-500 w-12 text-right">{{ $count }}</span>
                    </div>
                @endfor
            </div>
        </div>
    </div>

    <!-- Rating Categories -->
    <div class="grid grid-cols-2 lg:grid-cols-4 gap-3 sm:gap-4 mb-8">
        <div class="bg-white rounded-lg shadow-sm p-4 text-center">
            <div class="text-2xl font-bold text-gray-900">{{ number_format($avgRatings->quality ?? 0, 1) }}</div>
            <div class="text-sm text-gray-600">Product Quality</div>
        </div>
        <div class="bg-white rounded-lg shadow-sm p-4 text-center">
            <div class="text-2xl font-bold text-gray-900">{{ number_format($avgRatings->delivery ?? 0, 1) }}</div>
            <div class="text-sm text-gray-600">Delivery</div>
        </div>
        <div class="bg-white rounded-lg shadow-sm p-4 text-center">
            <div class="text-2xl font-bold text-gray-900">{{ number_format($avgRatings->communication ?? 0, 1) }}</div>
            <div class="text-sm text-gray-600">Communication</div>
        </div>
        <div class="bg-white rounded-lg shadow-sm p-4 text-center">
            <div class="text-2xl font-bold text-gray-900">{{ number_format($avgRatings->packaging ?? 0, 1) }}</div>
            <div class="text-sm text-gray-600">Packaging</div>
        </div>
    </div>

    <!-- Reviews List -->
    <div class="bg-white rounded-xl shadow-sm overflow-hidden">
        <div class="p-4 sm:p-6 border-b border-gray-200">
            <h2 class="text-lg sm:text-xl font-bold text-gray-900">Customer Reviews</h2>
        </div>

        @if($reviews->count() > 0)
        <div class="divide-y divide-gray-200">
            @foreach($reviews as $review)
            <div class="p-4 sm:p-6">
                <div class="flex items-start gap-4">
                    <!-- Buyer Avatar -->
                    <div class="w-12 h-12 bg-green-100 rounded-full flex items-center justify-center flex-shrink-0">
                        <span class="text-green-700 font-semibold text-lg">
                            {{ substr($review->buyer->first_name, 0, 1) }}
                        </span>
                    </div>

                    <div class="flex-1">
                        <!-- Header -->
                        <div class="flex items-center justify-between mb-2">
                            <div>
                                <h4 class="font-semibold text-gray-900">
                                    {{ $review->buyer->first_name }} {{ $review->buyer->last_name }}
                                </h4>
                                <div class="flex items-center gap-2 mt-1">
                                    <div class="flex">
                                        @for($i = 1; $i <= 5; $i++)
                                            @if($i <= round($review->overall_rating))
                                                <svg class="w-4 h-4 text-yellow-400" fill="currentColor" viewBox="0 0 20 20">
                                                    <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                                                </svg>
                                            @else
                                                <svg class="w-4 h-4 text-gray-300" fill="currentColor" viewBox="0 0 20 20">
                                                    <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                                                </svg>
                                            @endif
                                        @endfor
                                    </div>
                                    @if($review->is_verified_purchase)
                                        <span class="text-xs bg-green-100 text-green-700 px-2 py-0.5 rounded">Verified Purchase</span>
                                    @endif
                                </div>
                            </div>
                            <span class="text-sm text-gray-500">{{ $review->created_at->diffForHumans() }}</span>
                        </div>

                        <!-- Comment -->
                        @if($review->comment)
                        <p class="text-gray-700 mb-3">{{ $review->comment }}</p>
                        @endif

                        <!-- Rating Details -->
                        <div class="flex flex-wrap gap-3 text-sm mb-3">
                            @if($review->product_quality_rating)
                                <span class="text-gray-600">Quality: <span class="font-medium">{{ $review->product_quality_rating }}/5</span></span>
                            @endif
                            @if($review->delivery_rating)
                                <span class="text-gray-600">Delivery: <span class="font-medium">{{ $review->delivery_rating }}/5</span></span>
                            @endif
                            @if($review->communication_rating)
                                <span class="text-gray-600">Communication: <span class="font-medium">{{ $review->communication_rating }}/5</span></span>
                            @endif
                        </div>

                        <!-- Farmer Reply -->
                        @if($review->reply)
                        <div class="bg-gray-50 rounded-lg p-4 mt-3">
                            <div class="flex items-start gap-2">
                                <svg class="w-5 h-5 text-green-600 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M18 10c0 3.866-3.582 7-8 7a8.841 8.841 0 01-4.083-.98L2 17l1.338-3.123C2.493 12.767 2 11.434 2 10c0-3.866 3.582-7 8-7s8 3.134 8 7zM7 9H5v2h2V9zm8 0h-2v2h2V9zM9 9h2v2H9V9z" clip-rule="evenodd"/>
                                </svg>
                                <div class="flex-1">
                                    <div class="font-semibold text-gray-900 text-sm mb-1">Your Reply</div>
                                    <p class="text-gray-700 text-sm">{{ $review->reply }}</p>
                                    <span class="text-xs text-gray-500 mt-1 inline-block">{{ $review->replied_at->diffForHumans() }}</span>
                                </div>
                            </div>
                        </div>
                        @else
                        <!-- Reply Form -->
                        <form action="{{ route('farmer.reviews.reply', $review->id) }}" method="POST" class="mt-3">
                            @csrf
                            <div class="flex flex-col sm:flex-row gap-2">
                                <input type="text" name="reply" placeholder="Reply to this review..." 
                                       class="flex-1 px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent text-sm sm:text-base"
                                       required>
                                <button type="submit" class="px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 transition-colors whitespace-nowrap">
                                    Reply
                                </button>
                            </div>
                        </form>
                        @endif
                    </div>
                </div>
            </div>
            @endforeach
        </div>

        <!-- Pagination -->
        <div class="p-4 sm:p-6 border-t border-gray-200">
            {{ $reviews->links() }}
        </div>
        @else
        <div class="p-12 text-center">
            <svg class="w-16 h-16 text-gray-400 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z"/>
            </svg>
            <h3 class="text-lg font-semibold text-gray-900 mb-2">No Reviews Yet</h3>
            <p class="text-gray-500">Reviews from buyers will appear here after they complete their orders.</p>
        </div>
        @endif
    </div>
</div>
@endsection
