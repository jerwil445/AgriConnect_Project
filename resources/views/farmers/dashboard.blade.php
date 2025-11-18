@extends('layouts.farmers_page')

@section('title', 'Farmer Dashboard • AgriConnect')

@php
    $metrics = [
        ['label' => 'Active Listings', 'value' => 12],
        ['label' => 'Pending Orders', 'value' => 5],
        ['label' => 'Total Sales', 'value' => '$15,000'],
    ];

    $activities = [
        ['title' => 'Order #12345 – 500 lbs of Wheat', 'time' => 'Today, 8:00 AM'],
        ['title' => 'Listing for Organic Apples created', 'time' => 'Yesterday, 2:30 PM'],
        ['title' => 'Message from Buyer: Inquiry about Corn', 'time' => '2 days ago, 10:00 AM'],
    ];
@endphp

@section('content')
    <div class="grid gap-4 sm:grid-cols-2 xl:grid-cols-3">
        @foreach($metrics as $metric)
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 px-5 py-4">
                <p class="text-sm text-gray-500">{{ $metric['label'] }}</p>
                <p class="text-3xl font-semibold text-gray-900 mt-2">{{ $metric['value'] }}</p>
                <span class="text-xs text-green-500 font-semibold mt-1 inline-flex items-center gap-1">
                    <i class="fas fa-chevron-up text-[10px]"></i> This month +10%
                </span>
            </div>
        @endforeach
    </div>

    <div class="mt-8 grid gap-6 xl:grid-cols-[1.2fr_1fr]">
        <section class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
            <div class="flex items-center justify-between mb-6">
                <div>
                    <h2 class="text-lg font-semibold text-gray-900">Sales Trends</h2>
                    <p class="text-sm text-gray-500">Overview for the last 6 months.</p>
                </div>
                <span class="text-xs font-semibold text-green-600 bg-green-50 px-3 py-1 rounded-full">+10% this month</span>
            </div>

            <div class="relative h-48 bg-gradient-to-b from-green-50 to-white rounded-xl p-4">
                <div class="absolute inset-4">
                    <svg viewBox="0 0 300 120" preserveAspectRatio="none" class="w-full h-full">
                        <defs>
                            <linearGradient id="trendFill" x1="0" x2="0" y1="0" y2="1">
                                <stop offset="0%" stop-color="#22c55e" stop-opacity="0.25" />
                                <stop offset="100%" stop-color="#22c55e" stop-opacity="0" />
                            </linearGradient>
                        </defs>
                        <path d="M0,80 C40,40 60,60 100,30 C140,0 150,70 190,40 C230,10 240,80 280,50 L300,120 L0,120 Z"
                            fill="url(#trendFill)"></path>
                        <path d="M0,80 C40,40 60,60 100,30 C140,0 150,70 190,40 C230,10 240,80 280,50"
                            fill="none" stroke="#16a34a" stroke-width="4" stroke-linecap="round"></path>
                    </svg>
                </div>
            </div>

            <div class="mt-6 flex items-center justify-between text-sm text-gray-500">
                @foreach(['Jan','Feb','Mar','Apr','May','Jun'] as $month)
                    <span>{{ $month }}</span>
                @endforeach
            </div>
        </section>

        <section class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
            <h2 class="text-lg font-semibold text-gray-900 mb-4">Recent Activity</h2>
            <div class="space-y-4">
                @foreach($activities as $activity)
                    <div class="flex items-start gap-3">
                        <span class="w-10 h-10 rounded-lg bg-green-50 flex items-center justify-center text-green-600">
                            <i class="fas fa-seedling"></i>
                        </span>
                        <div>
                            <p class="text-sm font-medium text-gray-800">{{ $activity['title'] }}</p>
                            <p class="text-xs text-gray-500">{{ $activity['time'] }}</p>
                        </div>
                    </div>
                @endforeach
            </div>
        </section>
    </div>

    <div class="mt-8 grid md:grid-cols-3 gap-6">
        <section class="bg-white rounded-2xl border border-gray-100 shadow-sm p-5">
            <h3 class="text-sm font-semibold text-gray-700">Supply Status</h3>
            <p class="text-3xl font-bold text-gray-900 mt-3">82%</p>
            <p class="text-xs text-gray-500 mt-1">Fields ready for harvesting.</p>
        </section>

        <section class="bg-white rounded-2xl border border-gray-100 shadow-sm p-5">
            <h3 class="text-sm font-semibold text-gray-700">Next Delivery</h3>
            <p class="text-3xl font-bold text-gray-900 mt-3">Thursday</p>
            <p class="text-xs text-gray-500 mt-1">Scheduled to Davao City Market.</p>
        </section>

        <section class="bg-white rounded-2xl border border-gray-100 shadow-sm p-5">
            <h3 class="text-sm font-semibold text-gray-700">Quality Score</h3>
            <p class="text-3xl font-bold text-gray-900 mt-3">4.8/5</p>
            <p class="text-xs text-gray-500 mt-1">Based on buyer reviews.</p>
        </section>
    </div>
@endsection
