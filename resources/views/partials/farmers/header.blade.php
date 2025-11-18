@php
    $farmerName = auth()->user()->first_name ?? 'Farmer';
@endphp

<header class="bg-white shadow-sm border-b border-gray-100">
    <div class="px-4 lg:px-8 py-5 flex flex-col gap-4 lg:flex-row lg:items-center lg:justify-between">
        <div>
            <p class="text-sm text-gray-500">Dashboard</p>
            <h1 class="text-2xl font-semibold text-gray-900">Welcome back, {{ $farmerName }}</h1>
            <p class="text-sm text-gray-400">Monitor your farm performance at a glance.</p>
        </div>

        <div class="flex items-center gap-3">
            <button
                class="inline-flex items-center gap-2 rounded-lg border border-gray-200 px-4 py-2 text-sm font-semibold text-gray-600 hover:bg-gray-50 transition">
                <i class="fas fa-bell"></i>
                Alerts
            </button>
            <form action="{{ route('logout') }}" method="POST">
                @csrf
                <button type="submit"
                    class="inline-flex items-center gap-2 rounded-lg bg-green-600 px-4 py-2 text-sm font-semibold text-white shadow hover:bg-green-500 transition">
                    <i class="fas fa-sign-out-alt"></i>
                    Logout
                </button>
            </form>
        </div>
    </div>
</header>

