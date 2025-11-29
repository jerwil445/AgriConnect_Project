// Order Confirmation Modal Functionality
document.addEventListener('DOMContentLoaded', function() {
    initializeOrderModal();
});

// Function to initialize the order modal
function initializeOrderModal() {
    const confirmOrderBtn = document.getElementById('confirmOrderBtn');
    const orderModal = document.getElementById('orderModal');
    const closeModal = document.getElementById('closeModal');
    const cancelOrder = document.getElementById('cancelOrder');
    
    if (confirmOrderBtn) {
        confirmOrderBtn.addEventListener('click', function() {
            if (orderModal) {
                orderModal.classList.remove('hidden');
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
    
    // Close modal when clicking outside
    window.addEventListener('click', function(event) {
        if (orderModal && event.target === orderModal) {
            orderModal.classList.add('hidden');
        }
    });
}

// Export the function so it can be called when content is dynamically loaded
window.initializeOrderModal = initializeOrderModal;