document.addEventListener('DOMContentLoaded', function() {
    const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');

    // Mark Paid button functionality
    document.querySelectorAll('.mark-paid-btn').forEach(button => {
        button.addEventListener('click', function() {
            const transactionId = this.getAttribute('data-transaction-id');
            if (confirm('Are you sure you want to mark this order as paid?')) {
                fetch(`/transactions/${transactionId}/mark-paid`, {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': csrfToken
                        }
                    })
                    .then(response => {
                        if (!response.ok) throw new Error('Network response was not ok');
                        return response.json();
                    })
                    .then(data => {
                        if (data.success) {
                            alert(data.message);
                            location.reload();
                        } else {
                            alert('Error: ' + data.message);
                        }
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        alert('An error occurred while processing your request.');
                    });
            }
        });
    });

    // Mark Delivered button functionality
    document.querySelectorAll('.mark-delivered-btn').forEach(button => {
        button.addEventListener('click', function() {
            const transactionId = this.getAttribute('data-transaction-id');
            if (confirm('Are you sure you want to mark this order as delivered?')) {
                fetch(`/transactions/${transactionId}/mark-delivered-by-buyer`, {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': csrfToken
                        }
                    })
                    .then(response => {
                        if (!response.ok) throw new Error('Network response was not ok');
                        return response.json();
                    })
                    .then(data => {
                        if (data.success) {
                            alert(data.message);
                            location.reload();
                        } else {
                            alert('Error: ' + data.message);
                        }
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        alert('An error occurred while processing your request.');
                    });
            }
        });
    });

    // Toggle dropdown visibility
    window.toggleOrderDropdown = function(orderId, userType) {
        const dropdown = document.getElementById(userType + '-order-dropdown-menu-' + orderId);
        if (!dropdown) return;
        
        const isVisible = !dropdown.classList.contains('hidden');

        // Hide all dropdowns first
        document.querySelectorAll('[id$="-dropdown-menu-' + orderId + '"]').forEach(el => {
            el.classList.add('hidden');
        });

        // Toggle the clicked dropdown
        if (!isVisible) {
            dropdown.classList.remove('hidden');
        }
    };
    
    // Global click listener to close dropdowns
    window.addEventListener('click', function(e) {
        if (!e.target.closest('button')) {
            document.querySelectorAll('[id^="buyer-order-dropdown-menu-"]').forEach(d => d.classList.add('hidden'));
        }
    });
});
