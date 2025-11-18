@php
    $navItems = [
        ['label' => 'Dashboard', 'icon' => 'fa-border-all', 'route' => '#', 'active' => true],
        ['label' => 'Listings', 'icon' => 'fa-clipboard-list', 'route' => '#'],
        ['label' => 'Orders', 'icon' => 'fa-shopping-bag', 'route' => '#'],
        ['label' => 'Messages', 'icon' => 'fa-comments', 'route' => '#'],
        ['label' => 'Analytics', 'icon' => 'fa-chart-line', 'route' => '#'],
    ];
@endphp

<aside class="hidden lg:flex w-64 bg-white border-r border-gray-100 flex-col justify-between py-8 px-6">
    <div>
        <div class="flex items-center gap-2 mb-10">
            <div class="w-10 h-10 flex items-center justify-center rounded-full bg-green-100 text-green-600 font-semibold">
                AC
            </div>
            <div>
                <p class="text-xs uppercase tracking-wide text-gray-400">AgriConnect</p>
                <p class="font-semibold text-green-700">Farmer Console</p>
            </div>
        </div>

        <nav class="space-y-1">
            @foreach($navItems as $item)
                <a href="{{ $item['route'] }}"
                    class="flex items-center gap-3 px-3 py-2 rounded-lg text-sm font-medium transition
                        {{ $item['active'] ?? false ? 'bg-green-50 text-green-700' : 'text-gray-600 hover:bg-gray-50 hover:text-green-600' }}">
                    <i class="fas {{ $item['icon'] }} text-base"></i>
                    {{ $item['label'] }}
                </a>
            @endforeach
        </nav>
    </div>

    <div class="space-y-4">
        <button
            class="w-full flex items-center justify-center gap-2 rounded-lg bg-green-600 text-white py-3 font-semibold text-sm shadow hover:bg-green-500 transition">
            <i class="fas fa-plus"></i>
            Add New Produce
        </button>

        <div class="bg-green-50 border border-green-100 rounded-lg p-4 text-sm text-green-800">
            <p class="font-semibold">Tip</p>
            <p class="mt-1 text-green-700">Keep your listings updated to reach more buyers.</p>
        </div>
    </div>
</aside>

