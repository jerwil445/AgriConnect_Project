document.addEventListener('DOMContentLoaded', function () {
    // Notification dropdown toggle
    const notificationBtn = document.getElementById('notification-button');
    const notificationDropdown = document.getElementById('notification-dropdown');

    if (notificationBtn && notificationDropdown) {
        notificationBtn.addEventListener('click', function (e) {
            e.stopPropagation();
            notificationDropdown.classList.toggle('hidden');
        });

        document.addEventListener('click', function (e) {
            if (!notificationBtn.contains(e.target) && !notificationDropdown.contains(e.target)) {
                notificationDropdown.classList.add('hidden');
            }
        });
    }

    // Mark as read AJAX logic
    const markAsReadBtns = document.querySelectorAll('.mark-as-read');
    markAsReadBtns.forEach(btn => {
        btn.addEventListener('click', function (e) {
            e.stopPropagation();
            const url = this.dataset.url;
            const notificationId = this.dataset.notificationId;

            fetch(url, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                    'Accept': 'application/json',
                    'Content-Type': 'application/json'
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    this.remove();
                    const item = document.querySelector(`.notification-item[data-notification-id="${notificationId}"]`);
                    if (item) item.classList.add('opacity-50');

                    // Update count badge
                    const badge = document.querySelector('#notification-button .bg-red-500');
                    if (badge) {
                        let count = parseInt(badge.textContent);
                        if (count > 1) {
                            badge.textContent = count - 1;
                        } else {
                            badge.parentElement.remove();
                        }
                    }
                }
            })
            .catch(error => console.error('Error marking notification as read:', error));
        });
    });
});
