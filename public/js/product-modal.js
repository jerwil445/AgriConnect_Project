// Product Modal Functions
function openProductModal(productId) {
    // Fetch product details and display in modal
    fetch(`/buyer/products/${productId}/modal`)
        .then(response => response.text())
        .then(html => {
            document.getElementById('productModalContent').innerHTML = html;
            document.getElementById('productModal').classList.remove('hidden');
        })
        .catch(error => {
            console.error('Error loading product details:', error);
        });
}

function closeProductModal() {
    document.getElementById('productModal').classList.add('hidden');
}

// Close modal when clicking outside
document.addEventListener('DOMContentLoaded', function() {
    const modal = document.getElementById('productModal');
    if (modal) {
        modal.addEventListener('click', function(e) {
            if (e.target === this) {
                closeProductModal();
            }
        });
    }
});