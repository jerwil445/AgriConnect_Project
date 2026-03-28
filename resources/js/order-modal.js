document.addEventListener('DOMContentLoaded', function() {
    initializeOrderModal();
});

function initializeOrderModal() {
    const confirmOrderBtn = document.getElementById('confirmOrderBtn');
    const orderModal = document.getElementById('orderModal');
    const closeModal = document.getElementById('closeModal');
    const cancelOrder = document.getElementById('cancelOrder');
    const orderQuantityInput = document.getElementById('order_quantity_simple');
    const totalPreview = document.getElementById('order-total-preview');

    if (confirmOrderBtn) {
        confirmOrderBtn.addEventListener('click', function() {
            if (orderModal) {
                orderModal.classList.remove('hidden');
                updateOrderPreview();
            }
        });
    }

    if (closeModal) {
        closeModal.addEventListener('click', function() {
            if (orderModal) {
                orderModal.classList.add('hidden');
            }
        });
    }

    if (cancelOrder) {
        cancelOrder.addEventListener('click', function() {
            if (orderModal) {
                orderModal.classList.add('hidden');
            }
        });
    }

    if (orderQuantityInput) {
        orderQuantityInput.addEventListener('input', updateOrderPreview);
        orderQuantityInput.addEventListener('change', updateOrderPreview);
    }

    window.addEventListener('click', function(event) {
        if (orderModal && event.target === orderModal) {
            orderModal.classList.add('hidden');
        }
    });

    function updateOrderPreview() {
        if (!orderQuantityInput || !totalPreview) {
            return;
        }

        const quantity = Math.max(0, parseInt(orderQuantityInput.value || '0', 10));
        const unitPrice = parseFloat(orderQuantityInput.getAttribute('data-price-per-unit') || '0');
        totalPreview.textContent = '₱' + (quantity * unitPrice).toFixed(2);
    }
}

window.initializeOrderModal = initializeOrderModal;
