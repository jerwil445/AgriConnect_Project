document.addEventListener('DOMContentLoaded', function () {
    // Mobile navigation dropdown toggle
    const mobileSidebarToggle = document.getElementById('mobileSidebarToggle');
    const mobileNavDropdown = document.getElementById('mobile-nav-dropdown');

    if (mobileSidebarToggle && mobileNavDropdown) {
        mobileSidebarToggle.addEventListener('click', function (e) {
            e.stopPropagation();
            mobileNavDropdown.classList.toggle('hidden');
        });

        // Close dropdown when clicking outside
        document.addEventListener('click', function (e) {
            if (!mobileSidebarToggle.contains(e.target) && !mobileNavDropdown.contains(e.target)) {
                mobileNavDropdown.classList.add('hidden');
            }
        });
    }

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

        // Close dropdown when pressing Escape key
        document.addEventListener('keydown', function (e) {
            if (e.key === 'Escape') {
                notificationDropdown.classList.add('hidden');
            }
        });
    }

    // Ajax Mark as Read for Notifications
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
                    // Remove the "Mark as read" button or update UI
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
