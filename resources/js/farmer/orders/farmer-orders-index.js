document.addEventListener('DOMContentLoaded', function () {
    const csrfTokenElement = document.querySelector('meta[name="csrf-token"]');
    const csrfToken = csrfTokenElement ? csrfTokenElement.getAttribute('content') : '';

    // Status Change function (for general PATCH updates)
    window.updateOrderStatus = function (orderId, status) {
        if (confirm('Are you sure you want to change the status of this order to ' + status + '?')) {
            fetch(`/farmer/orders/${orderId}/status`, {
                method: 'PATCH',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': csrfToken,
                    'Accept': 'application/json'
                },
                body: JSON.stringify({ status: status })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    window.showToast(data.message || 'Status updated successfully', 'success');
                    setTimeout(() => location.reload(), 1500);
                } else {
                    window.showToast('Failed to update status: ' + data.message, 'error');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                window.showToast('An error occurred while updating the order status.', 'error');
            });
        }
    };

    // Generic Action function for POST requests with confirmation
    function handleTransactionAction(buttonSelector, urlTemplate, confirmationMessage) {
        document.querySelectorAll(buttonSelector).forEach(button => {
            button.addEventListener('click', function () {
                const transactionId = this.getAttribute('data-transaction-id');
                const url = urlTemplate.replace('{id}', transactionId);
                if (confirm(confirmationMessage)) {
                    fetch(url, {
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
                            window.showToast(data.message, 'success');
                            setTimeout(() => location.reload(), 1500);
                        } else {
                            window.showToast('Error: ' + data.message, 'error');
                        }
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        window.showToast('An error occurred while processing your request.', 'error');
                    });
                }
            });
        });
    }

    // Accept Order
    handleTransactionAction('.accept-order-btn', '/transactions/{id}/accept', 'Are you sure you want to accept this order?');
    
    // Reject Order
    handleTransactionAction('.reject-order-btn', '/transactions/{id}/reject', 'Are you sure you want to reject this order? This action cannot be undone.');
    
    // Mark Prepared
    handleTransactionAction('.mark-prepared-btn', '/transactions/{id}/mark-prepared', 'Are you sure you want to mark this order as prepared?');
    
    // Assign Logistics
    handleTransactionAction('.assign-logistics-btn', '/transactions/{id}/assign-logistics', 'Are you sure you want to assign logistics for this order?');

    // Toggle dropdown visibility
    window.toggleOrderDropdown = function (orderId, userType) {
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

    // Close dropdowns on outside click
    window.addEventListener('click', function (e) {
        if (!e.target.closest('button')) {
            document.querySelectorAll('[id$="-dropdown-menu"]').forEach(d => d.classList.add('hidden'));
        }
    });
});
