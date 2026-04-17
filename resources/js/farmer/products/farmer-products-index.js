document.addEventListener('DOMContentLoaded', function () {
    // Dropdown Toggling
    window.toggleDropdownById = function (id) {
        const dropdown = document.getElementById(id);
        const allDropdowns = document.querySelectorAll('[id^="dropdown-menu-"]');

        allDropdowns.forEach(d => {
            if (d.id !== id) d.classList.add('hidden');
        });

        if (dropdown) {
            dropdown.classList.toggle('hidden');
        }
    };

    window.addEventListener('click', function (e) {
        if (!e.target.closest('button') && !e.target.closest('form')) {
            document.querySelectorAll('[id^="dropdown-menu-"]').forEach(d => d.classList.add('hidden'));
        }
    });

    // Change product status
    window.changeProductStatus = function (productId, status) {
        // Close the dropdown
        const dropdown = document.getElementById('dropdown-menu-' + productId);
        if (dropdown) {
            dropdown.classList.add('hidden');
        }

        // Show confirmation
        if (confirm('Are you sure you want to change the status of this product to ' + status + '?')) {
            // Get CSRF token
            const csrfTokenElement = document.querySelector('meta[name="csrf-token"]');
            const csrfToken = csrfTokenElement ? csrfTokenElement.getAttribute('content') : '';

            // Make AJAX request
            fetch('/farmer/products/' + productId + '/status', {
                method: 'PUT',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': csrfToken
                },
                body: JSON.stringify({
                    status: status
                })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    // Reload the page to show updated status
                    location.reload();
                } else {
                    alert('Failed to update status: ' + data.message);
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('Failed to update status. Please try again.');
            });
        }
    };
});
