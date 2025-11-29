@extends('layouts.farmers_page')

@section('content')
<div class="ml-64">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-3xl font-bold text-gray-800">Notifications</h1>
        <a href="{{ route('farmer.matches') }}" class="text-indigo-600 hover:text-indigo-800">
            &larr; Back to Matches
        </a>
    </div>

    @if($notifications->isEmpty())
        <div class="bg-white shadow-md rounded-lg p-6 text-center">
            <svg class="w-16 h-16 mx-auto text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"></path>
            </svg>
            <p class="mt-4 text-gray-600 font-medium">No notifications yet.</p>
            <p class="text-gray-500 text-sm mt-2">You'll see notifications here when buyers show interest in your products.</p>
        </div>
    @else
        <div class="bg-white shadow-md rounded-lg overflow-hidden">
            <ul class="divide-y divide-gray-200">
                @foreach($notifications as $notification)
                    <li class="px-6 py-4 hover:bg-gray-50 {{ $notification->read_at ? 'bg-gray-50' : 'bg-white' }}">
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
                                    <p class="text-sm font-medium text-gray-900">New Match Notification</p>
                                    <div class="text-xs text-gray-500">
                                        {{ $notification->created_at->diffForHumans() }}
                                    </div>
                                </div>
                                <div class="mt-1 text-sm text-gray-600">
                                    <p>{{ $notification->data['message'] }}</p>
                                </div>
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
                    </li>
                @endforeach
            </ul>
            
            @if($notifications->hasPages())
                <div class="px-6 py-4 bg-gray-50 border-t border-gray-200">
                    {{ $notifications->links() }}
                </div>
            @endif
        </div>
    @endif
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Handle mark as read buttons
        var markAsReadButtons = document.querySelectorAll('.mark-as-read');
        markAsReadButtons.forEach(function(button) {
            button.addEventListener('click', function(e) {
                var notificationId = this.getAttribute('data-notification-id');
                
                // Send AJAX request to mark as read
                fetch('/farmer/notifications/' + notificationId + '/read', {
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
    });
</script>
@endsection