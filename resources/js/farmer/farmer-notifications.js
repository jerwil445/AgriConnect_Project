document.addEventListener('DOMContentLoaded', function () {
    const markAsReadButtons = document.querySelectorAll('.mark-as-read');

    markAsReadButtons.forEach(button => {
        button.addEventListener('click', function () {
            const url = this.getAttribute('data-url');
            const notificationId = this.getAttribute('data-notification-id');
            const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

            fetch(url, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': csrfToken,
                    'Accept': 'application/json'
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    // Update UI: hide button, change background, update dot
                    this.closest('.flex-shrink-0')?.querySelector('.bg-green-500')?.classList.replace('bg-green-500', 'bg-gray-300');
                    this.closest('li')?.classList.add('bg-gray-50');
                    this.remove(); // Remove the "Mark as read" button
                } else {
                    alert('Failed to mark as read');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('An error occurred');
            });
        });
    });
});
