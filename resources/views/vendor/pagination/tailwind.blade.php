@if ($paginator->hasPages())
    <nav role="navigation" aria-label="{{ __('Pagination Navigation') }}" class="flex items-center justify-center py-1">
        {{-- Previous Page Link --}}
        @if ($paginator->onFirstPage())
            <span class="w-10 h-10 flex items-center justify-center text-gray-300 cursor-not-allowed">
                <i class="fas fa-chevron-left text-xs"></i>
            </span>
        @else
            <a href="{{ $paginator->previousPageUrl() }}" rel="prev" 
                class="w-10 h-10 flex items-center justify-center text-gray-400 hover:text-blue-600 hover:bg-blue-50 rounded-lg transition-all duration-200" 
                aria-label="{{ __('pagination.previous') }}">
                <i class="fas fa-chevron-left text-xs"></i>
            </a>
        @endif

        {{-- Pagination Elements --}}
        <div class="flex items-center gap-1 mx-2">
            @foreach ($elements as $element)
                {{-- "Three Dots" Separator --}}
                @if (is_string($element))
                    <span class="w-10 h-10 flex items-center justify-center text-gray-400">
                        {{ $element }}
                    </span>
                @endif

                {{-- Array Of Links --}}
                @if (is_array($element))
                    @foreach ($element as $page => $url)
                        @if ($page == $paginator->currentPage())
                            <span aria-current="page" 
                                class="w-10 h-10 flex items-center justify-center bg-blue-500 text-white font-semibold rounded-lg shadow-sm shadow-blue-200">
                                {{ $page }}
                            </span>
                        @else
                            <a href="{{ $url }}" 
                                class="w-10 h-10 flex items-center justify-center text-gray-600 font-medium hover:text-blue-600 hover:bg-blue-50 rounded-lg transition-all duration-200" 
                                aria-label="{{ __('Go to page :page', ['page' => $page]) }}">
                                {{ $page }}
                            </a>
                        @endif
                    @endforeach
                @endif
            @endforeach
        </div>

        {{-- Next Page Link --}}
        @if ($paginator->hasMorePages())
            <a href="{{ $paginator->nextPageUrl() }}" rel="next" 
                class="w-10 h-10 flex items-center justify-center text-gray-400 hover:text-blue-600 hover:bg-blue-50 rounded-lg transition-all duration-200" 
                aria-label="{{ __('pagination.next') }}">
                <i class="fas fa-chevron-right text-xs"></i>
            </a>
        @else
            <span class="w-10 h-10 flex items-center justify-center text-gray-300 cursor-not-allowed">
                <i class="fas fa-chevron-right text-xs"></i>
            </span>
        @endif
    </nav>
@endif
