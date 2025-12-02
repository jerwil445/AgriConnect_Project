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
                // Initialize size selection when modal opens
                if (typeof initializeSizeSelection === 'function') {
                    initializeSizeSelection();
                }
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

// Initialize size selection functionality
function initializeSizeSelection() {
    // Remove existing event listeners to prevent duplicates
    document.querySelectorAll('.increase-tray').forEach(button => {
        button.removeEventListener('click', handleIncreaseClick);
        button.addEventListener('click', handleIncreaseClick);
    });
    
    document.querySelectorAll('.decrease-tray').forEach(button => {
        button.removeEventListener('click', handleDecreaseClick);
        button.addEventListener('click', handleDecreaseClick);
    });
    
    // Add event listeners for manual input changes
    document.querySelectorAll('.tray-input').forEach(input => {
        input.removeEventListener('change', handleInputChange);
        input.addEventListener('change', handleInputChange);
        
        // Also listen for input events for real-time updates
        input.removeEventListener('input', handleInputChange);
        input.addEventListener('input', handleInputChange);
    });
    
    // Initial total calculation
    updateTotals();
}

// Handler functions for increase/decrease buttons
function handleIncreaseClick() {
    const sizeId = this.getAttribute('data-size-id');
    const maxTrays = parseInt(this.getAttribute('data-max'));
    const pricePerTray = parseFloat(this.getAttribute('data-price-per-tray'));
    
    const input = document.getElementById(`tray_count_${sizeId}`);
    let currentValue = parseInt(input.value) || 0;
    
    if (currentValue < maxTrays) {
        currentValue++;
        input.value = currentValue;
        updateTotals();
    }
}

function handleDecreaseClick() {
    const sizeId = this.getAttribute('data-size-id');
    const pricePerTray = parseFloat(this.getAttribute('data-price-per-tray'));
    
    const input = document.getElementById(`tray_count_${sizeId}`);
    let currentValue = parseInt(input.value) || 0;
    
    if (currentValue > 0) {
        currentValue--;
        input.value = currentValue;
        updateTotals();
    }
}

// Handler function for manual input changes
function handleInputChange() {
    const sizeId = this.getAttribute('data-size-id');
    const maxTrays = parseInt(this.getAttribute('max'));
    let currentValue = parseInt(this.value) || 0;
    
    // Ensure value is within valid range
    if (currentValue < 0) {
        currentValue = 0;
        this.value = 0;
    } else if (currentValue > maxTrays) {
        currentValue = maxTrays;
        this.value = maxTrays;
    }
    
    updateTotals();
}

// Update total quantity and price
function updateTotals() {
    let totalQuantity = 0;
    let totalPrice = 0;
    
    // Calculate totals from all size inputs
    document.querySelectorAll('.tray-input').forEach(input => {
        const trayCount = parseInt(input.value) || 0;
        const pricePerTray = parseFloat(input.getAttribute('data-price-per-tray')) || 0;
        
        totalQuantity += trayCount;
        totalPrice += trayCount * pricePerTray;
    });
    
    // Update hidden inputs
    document.getElementById('total_quantity').value = totalQuantity;
    document.getElementById('total_price').value = totalPrice.toFixed(2);
    
    // Update display elements
    document.getElementById('display_total_quantity').textContent = totalQuantity;
    document.getElementById('display_total_price').textContent = '₱' + totalPrice.toFixed(2);
    
    // Update the Egg Sizes information in Transaction Details
    updateEggSizesInfo();
}

// Update Egg Sizes information in Transaction Details
function updateEggSizesInfo() {
    // Store original values when the page first loads
    if (!window.originalEggSizes) {
        window.originalEggSizes = {};
        document.querySelectorAll('.tray-input').forEach(input => {
            const sizeId = input.getAttribute('data-size-id');
            const trayCountElement = document.querySelector(`#size-tray-count-${sizeId}`);
            const unitPriceElement = document.querySelector(`#size-unit-price-${sizeId}`);
            const totalPriceElement = document.querySelector(`#size-total-price-${sizeId}`);
            
            if (trayCountElement && unitPriceElement && totalPriceElement) {
                window.originalEggSizes[sizeId] = {
                    trayCount: trayCountElement.textContent,
                    unitPrice: unitPriceElement.textContent,
                    totalPrice: totalPriceElement.textContent
                };
            }
        });
        
        // Store original product quantity
        const quantityElement = document.querySelector('.product-quantity');
        if (quantityElement) {
            window.originalProductQuantity = quantityElement.textContent;
        }
    }
    
    // Update each size's information
    document.querySelectorAll('.tray-input').forEach(input => {
        const sizeId = input.getAttribute('data-size-id');
        const trayCount = parseInt(input.value) || 0;
        const pricePerTray = parseFloat(input.getAttribute('data-price-per-tray')) || 0;
        const totalPrice = trayCount * pricePerTray;
        
        // Get elements
        const trayCountElement = document.querySelector(`#size-tray-count-${sizeId}`);
        const unitPriceElement = document.querySelector(`#size-unit-price-${sizeId}`);
        const totalPriceElement = document.querySelector(`#size-total-price-${sizeId}`);
        
        if (trayCount > 0) {
            // Update with current values when trays are selected
            if (trayCountElement) {
                trayCountElement.textContent = `(${trayCount} trays)`;
            }
            if (unitPriceElement) {
                unitPriceElement.textContent = '₱' + pricePerTray.toFixed(2) + '/tray';
            }
            if (totalPriceElement) {
                totalPriceElement.textContent = '₱' + totalPrice.toFixed(2);
            }
        } else {
            // Restore original values when no trays are selected
            if (window.originalEggSizes && window.originalEggSizes[sizeId]) {
                if (trayCountElement) {
                    trayCountElement.textContent = window.originalEggSizes[sizeId].trayCount;
                }
                if (unitPriceElement) {
                    unitPriceElement.textContent = window.originalEggSizes[sizeId].unitPrice;
                }
                if (totalPriceElement) {
                    totalPriceElement.textContent = window.originalEggSizes[sizeId].totalPrice;
                }
            }
        }
    });
    
    // Update overall product information
    const totalQuantity = parseInt(document.getElementById('total_quantity').value) || 0;
    const totalPrice = parseFloat(document.getElementById('total_price').value) || 0;
    
    // Update quantity in product information
    const quantityElement = document.querySelector('.product-quantity');
    if (quantityElement) {
        if (totalQuantity > 0) {
            // Get the unit from the existing text
            const existingText = quantityElement.textContent;
            const unitMatch = existingText.match(/\d+\s*(\w+)/);
            const unit = unitMatch ? unitMatch[1] : 'trays';
            quantityElement.textContent = `${totalQuantity} ${unit}`;
        } else if (window.originalProductQuantity) {
            // Restore original quantity when no trays are selected
            quantityElement.textContent = window.originalProductQuantity;
        }
    }
    
    // Update total amount
    const totalAmountElement = document.querySelector('.total-amount');
    if (totalAmountElement) {
        totalAmountElement.textContent = '₱' + totalPrice.toFixed(2);
    }
}

// Export the functions so they can be called when content is dynamically loaded
window.initializeOrderModal = initializeOrderModal;
window.initializeSizeSelection = initializeSizeSelection;
window.updateTotals = updateTotals;