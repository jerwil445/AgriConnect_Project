document.addEventListener('DOMContentLoaded', function () {
    const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

    function bindAction(selector, urlBuilder, successHandler, confirmMessage) {
        document.querySelectorAll(selector).forEach(button => {
            button.addEventListener('click', function () {
                const transactionId = this.getAttribute('data-transaction-id');
                if (!confirm(confirmMessage)) return;

                fetch(urlBuilder(transactionId), {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': csrfToken
                    }
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        successHandler(data);
                    } else {
                        alert('Error: ' + data.message);
                    }
                })
                .catch(() => alert('An error occurred while processing your request.'));
            });
        });
    }

    bindAction('.accept-order-btn',
        id => `/transactions/${id}/accept`,
        () => location.reload(),
        'Are you sure you want to accept this order?');

    bindAction('.reject-order-btn',
        id => `/transactions/${id}/reject`,
        () => {
            // Get redirect URL from data attribute or determine based on role
            const redirectUrl = document.body.dataset.ordersRedirectUrl || '/orders';
            window.location.href = redirectUrl;
        },
        'Are you sure you want to reject this order? This action cannot be undone.');

    bindAction('.mark-prepared-btn',
        id => `/transactions/${id}/mark-prepared`,
        () => location.reload(),
        'Are you sure you want to mark this order as prepared?');

    bindAction('.assign-logistics-btn',
        id => `/transactions/${id}/assign-logistics`,
        () => location.reload(),
        'Are you sure you want to assign logistics for this order?');

    bindAction('.mark-paid-btn',
        id => `/transactions/${id}/mark-paid`,
        () => location.reload(),
        'Are you sure you want to mark this order as paid?');

    bindAction('.mark-delivered-by-buyer-btn',
        id => `/transactions/${id}/mark-delivered-by-buyer`,
        () => location.reload(),
        'Are you sure you want to mark this order as delivered?');
});
