@php
    use App\Models\Message;
    $unreadMessageCount = Auth::check() ? Message::where('receiver_id', Auth::id())->where('is_read', false)->count() : 0;
    
    $navItems = [
        ['label' => 'Dashboard', 'icon' => 'M4 12l8-8 8 8M6 10.5V19a1 1 0 0 0 1 1h3v-3a1 1 0 0 1 1-1h2a1 1 0 0 1 1 1v3h3a1 1 0 0 0 1-1v-8.5', 'route' => route('farmer.dashboard'), 'active' => request()->routeIs('farmer.dashboard')],
        ['label' => 'Listings Products', 'icon' => 'M15 4h3a1 1 0 0 1 1 1v15a1 1 0 0 1-1 1H6a1 1 0 0 1-1-1V5a1 1 0 0 1 1-1h3m0 3h6m-3 5h3m-6 0h.01M12 16h3m-6 0h.01M10 3v4h4V3h-4Z', 'route' => route('products.index'), 'active' => request()->routeIs('products.*')],
        ['label' => 'Matches', 'icon' => 'M16 19h4a1 1 0 0 0 1-1v-1a3 3 0 0 0-3-3h-2m-2.236-4a3 3 0 1 0 0-4M3 18v-1a3 3 0 0 1 3-3h4a3 3 0 0 1 3 3v1a1 1 0 0 1-1 1H4a1 1 0 0 1-1-1Zm8-10a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z', 'route' => route('farmer.matches'), 'active' => request()->routeIs('farmer.matches')],
        ['label' => 'Orders', 'icon' => 'M12 6h8m-8 6h8m-8 6h8M4 16a2 2 0 1 1 3.321 1.5L4 20h5M4 5l2-1v6m-2 0h4', 'route' => route('farmer.orders'), 'active' => request()->routeIs('farmer.orders')],
        ['label' => 'Messages', 'icon' => 'M9 17h6l3 3v-3h2V9h-2M4 4h11v8H9l-3 3v-3H4V4Z', 'route' => route('farmer.messages'), 'active' => request()->routeIs('farmer.messages')],
        ['label' => 'Analytics', 'icon' => 'M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6', 'route' => '#'],
    ];
@endphp

<aside id="sidebar" class="fixed top-0 left-0 h-screen w-64 bg-white shadow-lg z-40 flex flex-col transform -translate-x-full md:translate-x-0 transition-transform duration-300 ease-in-out farmer-sidebar">
    <div class="p-6 border-b border-gray-200 flex items-center justify-between">
        <h1 class="flex items-center gap-1 text-2xl font-bold text-green-700">
            <svg class="w-9 h-9 text-green-500" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" aria-hidden="true">
                <path stroke-linecap="round" stroke-linejoin="round" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
            </svg>
            <span>ArgiConnect</span>
        </h1>
        <button id="close-btn" class="md:hidden text-gray-500">
            <i class="fa-solid fa-xmark text-xl"></i>
        </button>
    </div>
    <nav class="flex-1 p-4 space-y-2">
        @foreach($navItems as $item)
            <a href="{{ $item['route'] }}" 
               class="flex items-center gap-3 p-2 rounded-lg text-gray-700 hover:bg-green-100 hover:text-green-600 font-medium transition-all duration-300 {{ $item['active'] ?? false ? 'bg-green-100 text-green-600' : '' }}">
                <svg class="w-6 h-6 text-gray-800 dark:text-white" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24">
                    <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $item['icon'] }}" />
                </svg>
                {{ $item['label'] }}
                @if($item['label'] === 'Messages')
                    @if($unreadMessageCount > 0)
                        <span class="inline-flex items-center justify-center w-5 h-5 text-xs font-bold text-white bg-red-500 rounded-full messages-count">
                            {{ $unreadMessageCount }}
                        </span>
                    @else
                        <span class="inline-flex items-center justify-center w-5 h-5 text-xs font-bold text-white bg-red-500 rounded-full messages-count hidden">
                            0
                        </span>
                    @endif
                @endif
            </a>
        @endforeach
    </nav>
</aside>
<svg class="w-6 h-6 text-gray-800 dark:text-white" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24">
  <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17h6l3 3v-3h2V9h-2M4 4h11v8H9l-3 3v-3H4V4Z"/>
</svg>

