document.addEventListener('DOMContentLoaded', function () {
    // Handle mark as read buttons
    var markAsReadButtons = document.querySelectorAll('.mark-as-read');
    markAsReadButtons.forEach(function (button) {
        button.addEventListener('click', function (e) {
            e.preventDefault();
            e.stopPropagation();

            var url = this.getAttribute('data-url');
            if (!url) {
                console.error('Mark as read URL is missing.');
                return;
            }

            this.disabled = true;
            this.classList.add('opacity-50');

            // Send AJAX request to mark as read
            fetch(url, {
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
                        this.disabled = false;
                        this.classList.remove('opacity-50');
                        alert('Error marking notification as read.');
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    this.disabled = false;
                    this.classList.remove('opacity-50');
                    alert('An error occurred while marking the notification as read.');
                });
        });
    });
});