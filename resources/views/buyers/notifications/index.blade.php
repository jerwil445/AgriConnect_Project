@extends('layouts.buyers_page')

@section('title', 'Buyer Notifications • AgriConnect')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="bg-white rounded-xl shadow-sm border border-gray-100">
        <div class="px-6 py-5 border-b border-gray-100 flex justify-between items-center">
            <div>
                <h2 class="text-lg font-semibold text-gray-900">Notifications</h2>
                <p class="text-sm text-gray-500 mt-1">Manage your notifications</p>
            </div>
            <div class="flex items-center gap-3">
                @if($notifications->where('read_at', null)->count() > 0)
                    <button type="button" id="mark-all-read" 
                            class="px-4 py-2 text-sm font-medium text-white bg-green-600 rounded-lg hover:bg-green-700 transition">
                        Mark all as read
                    </button>
                @endif
                <a href="{{ route('buyer.dashboard') }}" class="text-indigo-600 hover:text-indigo-800">
                    &larr; Back to Dashboard
                </a>
            </div>
        </div>

        @if($notifications->isEmpty())
            <div class="p-8 text-center">
                <svg class="w-16 h-16 mx-auto text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"></path>
                </svg>
                <p class="mt-4 text-gray-600 font-medium">No notifications yet.</p>
                <p class="text-gray-500 text-sm mt-2">You'll see notifications here when farmers respond to your demands.</p>
            </div>
        @else
            <div class="divide-y divide-gray-200">
                @foreach($notifications as $notification)
                    <div class="px-6 py-4 hover:bg-gray-50 {{ $notification->read_at ? 'bg-gray-50' : 'bg-white' }}">
                        <div class="flex items-start">
                            <div class="flex-shrink-0 pt-1">
                                @if($notification->read_at)
                                    <div class="h-3 w-3 rounded-full bg-gray-300"></div>
                                @else
                                    <div class="h-3 w-3 rounded-full bg-green-500"></div>
                                @endif
                            </div>
                            <div class="ml-4 flex-1">
                                <div class="flex items-center justify-between">
                                    <p class="text-sm font-medium text-gray-900">{{ $notification->data['message'] ?? 'Notification' }}</p>
                                    <div class="text-xs text-gray-500">
                                        {{ $notification->created_at->diffForHumans() }}
                                    </div>
                                </div>
                                <div class="mt-1 text-sm text-gray-600">
                                    <p>{{ $notification->data['message'] ?? 'No message content' }}</p>
                                </div>
                                @if(isset($notification->data['data']) && is_array($notification->data['data']))
                                    @if(isset($notification->data['data']['product_name']))
                                        <p class="text-xs text-gray-600 mt-1">Product: {{ $notification->data['data']['product_name'] }}</p>
                                    @endif
                                @endif
                                @if(!$notification->read_at)
                                    <div class="mt-2">
                                        <button type="button" 
                                                class="text-sm font-medium text-indigo-600 hover:text-indigo-500 mark-as-read"
                                                data-notification-id="{{ $notification->id }}">
                                            Mark as read
                                        </button>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
            
            @if($notifications->hasPages())
                <div class="px-6 py-4 bg-gray-50 border-t border-gray-200">
                    {{ $notifications->links() }}
                </div>
            @endif
        @endif
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Handle mark as read buttons
        var markAsReadButtons = document.querySelectorAll('.mark-as-read');
        markAsReadButtons.forEach(function(button) {
            button.addEventListener('click', function() {
                var notificationId = this.getAttribute('data-notification-id');
                
                // Send AJAX request to mark as read
                fetch('/buyer/notifications/' + notificationId + '/read', {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                        'Content-Type': 'application/json',
                        'Accept': 'application/json'
                    }
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        // Reload the page to reflect the updated status
                        location.reload();
                    } else {
                        alert('Error marking notification as read.');
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('An error occurred while marking the notification as read.');
                });
            });
        });
        
        // Handle mark all as read button
        var markAllReadButton = document.getElementById('mark-all-read');
        if (markAllReadButton) {
            markAllReadButton.addEventListener('click', function() {
                if (confirm('Are you sure you want to mark all notifications as read?')) {
                    // Send AJAX request to mark all as read
                    fetch('/buyer/notifications/read-all', {
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                            'Content-Type': 'application/json',
                            'Accept': 'application/json'
                        }
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            // Reload the page to reflect the updated status
                            location.reload();
                        } else {
                            alert('Error marking all notifications as read.');
                        }
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        alert('An error occurred while marking all notifications as read.');
                    });
                }
            });
        }
    });
</script>
@endsection