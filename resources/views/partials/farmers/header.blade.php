@php
    $farmerName = auth()->user()->first_name ?? 'Farmer';
@endphp

<header class="bg-white shadow-sm border-b border-gray-200">
    <div class="px-4 lg:px-8 py-3 lg:py-5 flex flex-col gap-3 lg:gap-4 lg:flex-row lg:items-center lg:justify-end">
        <!-- Mobile Menu Toggle -->
        <button id="menu-toggle" class="lg:hidden inline-flex items-center justify-center w-10 h-10 rounded-lg hover:bg-gray-100 text-gray-600">
            <i class="fas fa-bars text-lg"></i>
        </button>

        <div class="flex items-center gap-2 lg:gap-3">

            <!-- Notification Icon -->
            <div class="relative">
                <button id="notification-button"  type="button"
                    class="inline-flex items-center gap-2 rounded-lg border border-gray-200 px-3 lg:px-4 py-2 text-sm font-semibold text-gray-600 hover:bg-gray-50 transition">
                    <i class="fas fa-bell"></i>

                    @auth
                        @if (auth()->user()->unreadNotifications->count() > 0)
                            <span class="absolute -top-1 -right-1 flex h-4 w-4">
                                <span
                                    class="animate-ping absolute inline-flex h-full w-full rounded-full bg-red-400 opacity-75"></span>
                                <span
                                    class="relative inline-flex rounded-full h-4 w-4 bg-red-500 text-white text-xs items-center justify-center">
                                    {{ auth()->user()->unreadNotifications->count() }}
                                </span>
                            </span>
                        @endif
                    @endauth
                </button>

                <!-- Notification dropdown menu -->
                <div id="notification-dropdown"
                    class="hidden absolute right-0 z-50 mt-2 w-80 origin-top-right rounded-md bg-white py-1 shadow-lg ring-1 ring-black ring-opacity-5 focus:outline-none"
                    role="menu" aria-orientation="vertical" aria-labelledby="notification-button" tabindex="-1">
                    <div class="px-4 py-3 border-b border-gray-200">
                        <p class="text-sm font-medium text-gray-900">Notifications</p>
                    </div>
                    <div class="max-h-96 overflow-y-auto">
                        @auth
                            @forelse(auth()->user()->notifications as $notification)
                                <div class="px-4 py-3 hover:bg-gray-50 border-b border-gray-100">
                                    <div class="flex justify-between items-start">
                                        <div>
                                            <p class="text-sm text-gray-800 notification-message">
                                                {{ $notification->data['message'] ?? 'No message' }}</p>
                                            @if (isset($notification->data['data']) && is_array($notification->data['data']))
                                                @if (isset($notification->data['data']['product_name']))
                                                    <p class="text-xs text-gray-600 mt-1">Product:
                                                        {{ $notification->data['data']['product_name'] }}</p>
                                                @endif
                                                @if (isset($notification->data['data']['farmer_name']) && isset($notification->data['data']['buyer_name']))
                                                    @if (auth()->id() == $notification->notifiable_id)
                                                        @if (isset($notification->data['data']['actor']) && $notification->data['data']['actor'] === 'farmer')
                                                            <p class="text-xs text-gray-600">Action by: You (Farmer)</p>
                                                        @elseif(strpos($notification->data['message'] ?? '', 'Farmer accepted') !== false)
                                                            <p class="text-xs text-gray-600">From:
                                                                {{ $notification->data['data']['farmer_name'] }}</p>
                                                        @else
                                                            <p class="text-xs text-gray-600">From:
                                                                {{ $notification->data['data']['buyer_name'] }}</p>
                                                        @endif
                                                    @endif
                                                @elseif(isset($notification->data['data']['farmer_name']))
                                                    <p class="text-xs text-gray-600">From:
                                                        {{ $notification->data['data']['farmer_name'] }}</p>
                                                @elseif(isset($notification->data['data']['buyer_name']))
                                                    <p class="text-xs text-gray-600">From:
                                                        {{ $notification->data['data']['buyer_name'] }}</p>
                                                @endif
                                            @endif
                                        </div>
                                        @if (!$notification->read_at)
                                            <button type="button" 
                                                class="text-xs font-medium text-indigo-600 hover:text-indigo-500 mark-as-read flex-shrink-0 ml-2"
                                                data-url="{{ route('farmer.notifications.read', $notification->id) }}"
                                                data-notification-id="{{ $notification->id }}">
                                                Mark as read
                                            </button>
                                        @endif
                                    </div>
                                    <p class="text-xs text-gray-500 mt-1">{{ $notification->created_at->diffForHumans() }}
                                    </p>

                                    <!-- Add a link to view the transaction if it exists -->
                                    @if (isset($notification->data['transaction_id']))
                                        <div class="mt-2">
                                            <a href="{{ route('orders.show', ['transaction' => $notification->data['transaction_id']]) }}"
                                                class="text-xs text-indigo-600 hover:text-indigo-800">
                                                View Order
                                            </a>
                                        </div>
                                    @endif
                                </div>
                            @empty
                                <div class="px-4 py-6 text-center">
                                    <p class="text-sm text-gray-500">No notifications</p>
                                </div>
                            @endforelse
                        @else
                            <div class="px-4 py-6 text-center">
                                <p class="text-sm text-gray-500">Please log in to see notifications</p>
                            </div>
                        @endauth
                    </div>
                    @auth
                        @if (auth()->user()->notifications->count() > 0)
                            <div class="px-4 py-2 text-center border-t border-gray-200">
                                <a href="{{ route('farmer.notifications') }}"
                                    class="text-sm text-indigo-600 hover:text-indigo-900">View all notifications</a>
                            </div>
                        @endif
                    @endauth
                </div>
            </div>

            <div class="relative">
                <button id="user-menu-button" type="button"
                    class="flex items-center gap-2 rounded-lg border border-gray-200 px-3 lg:px-4 py-2 text-sm font-semibold text-gray-600 hover:bg-gray-50 transition focus:outline-none">
                    <img src="{{ Auth::user()->profile_picture ? asset('storage/' . Auth::user()->profile_picture) : 'https://images.unsplash.com/photo-1472099645785-5658abf4ff4e?ixlib=rb-1.2.1&ixid=eyJhcHBfaWQiOjEyMDd9&auto=format&fit=facearea&facepad=2&w=256&h=256&q=80' }}"
                        alt="User" class="h-6 w-6 rounded-full">
                    <span class="hidden sm:inline">Profile</span>
                    <i class="fas fa-chevron-down text-xs"></i>
                </button>

                <div id="user-dropdown"
                    class="absolute right-0 mt-2 w-48 rounded-md bg-white shadow-lg ring-1 ring-black ring-opacity-5 hidden z-50">
                    <div class="py-1" role="none">
                        <a href="{{ route('farmer.profile') }}"
                            class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100" role="menuitem">Your
                            Profile</a>
                        <a href="#" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100"
                            role="menuitem">Settings</a>
                        <form action="{{ route('logout') }}" method="POST" class="block">
                            @csrf
                            <button type="submit"
                                class="w-full text-left px-4 py-2 text-sm text-gray-700 hover:bg-gray-100"
                                role="menuitem">
                                Sign out
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</header>

<div class="container mx-auto px-4">
    @if (session('error'))
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                window.showToast("{{ session('error') }}", 'error');
            });
        </script>
    @endif

    @if (session('success'))
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                window.showToast("{{ session('success') }}", 'success');
            });
        </script>
    @endif
</div>

@vite('resources/js/partials/farmers/partial-farmers-header.js')

