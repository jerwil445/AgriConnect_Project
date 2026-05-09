document.addEventListener('DOMContentLoaded', function () {
    const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

    // Handle conversation selection
    const conversationItems = document.querySelectorAll('.conversation-item');
    conversationItems.forEach(item => {
        item.addEventListener('click', function () {
            const transactionId = this.getAttribute('data-transaction-id');

            // Update active state
            conversationItems.forEach(i => i.classList.remove('bg-indigo-100', 'border-l-4', 'border-l-indigo-500'));
            this.classList.add('bg-indigo-100', 'border-l-4', 'border-l-indigo-500');

            // Load conversation via AJAX
            Promise.all([
                fetch(`/messages/conversation/${transactionId}`).then(response => response.text()),
                fetch(`/messages/transaction-details/${transactionId}`).then(response => response.text())
            ])
            .then(([conversationHtml, detailsHtml]) => {
                document.getElementById('conversation-container').innerHTML = conversationHtml;
                document.getElementById('transaction-details').innerHTML = detailsHtml;

                // Re-initialize message functionality
                initializeMessaging();

                // Initialize order modal functionality
                if (typeof window.initializeOrderModal === 'function') {
                    window.initializeOrderModal();
                }

                // Refresh unread message counts after opening a conversation
                refreshUnreadCounts();
            })
            .catch(error => {
                console.error('Error loading conversation:', error);
            });
        });
    });

    // Function to refresh unread message counts
    function refreshUnreadCounts() {
        // Refresh sidebar/header message count
        fetch('/messages/unread-count')
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    // Update counts in sidebar/header
                    document.querySelectorAll('.messages-count').forEach(el => {
                        if (data.count > 0) {
                            el.textContent = data.count;
                            el.classList.remove('hidden');
                        } else {
                            el.classList.add('hidden');
                        }
                    });
                }
            })
            .catch(error => {
                console.error('Error refreshing message counts:', error);
            });

        // Refresh conversation list counts
        fetch('/messages/unread-count-by-conversation')
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    // Update each conversation item with unread count
                    document.querySelectorAll('.conversation-item').forEach(item => {
                        const conversationThreadId = item.getAttribute('data-conversation-thread-id');
                        const countElement = item.querySelector('.unread-count');

                        if (countElement) {
                            if (data.counts[conversationThreadId] && data.counts[conversationThreadId] > 0) {
                                countElement.textContent = data.counts[conversationThreadId];
                                countElement.classList.remove('hidden');
                            } else {
                                countElement.classList.add('hidden');
                            }
                        }
                    });
                }
            })
            .catch(error => {
                console.error('Error refreshing conversation counts:', error);
            });
    }

    // Initialize messaging functionality
    function initializeMessaging() {
        const messageForm = document.getElementById('message-form');
        if (!messageForm) return;

        const messageInput = document.getElementById('message-input');
        const messagesContainer = document.getElementById('messages-container');

        // Scroll to bottom of messages
        if (messagesContainer) {
            messagesContainer.scrollTop = messagesContainer.scrollHeight;
        }

        // Handle message submission
        messageForm.addEventListener('submit', function (e) {
            e.preventDefault();

            const message = messageInput.value.trim();
            if (message === '') return;

            const transactionId = this.getAttribute('data-transaction-id');

            // Disable form while sending
            messageInput.disabled = true;
            messageForm.querySelector('button').disabled = true;

            // Send message via AJAX
            fetch(`/transactions/${transactionId}/messages`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': csrfToken
                },
                body: JSON.stringify({ message: message })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    // Add message to container
                    const isSender = data.data.is_sender;
                    const messageText = data.data.message;
                    
                    // Check if the viewer is the farmer of this transaction
                    const farmerId = parseInt(messageForm.getAttribute('data-farmer-id'));
                    const currentUserId = parseInt(messageForm.getAttribute('data-current-user-id'));
                    const isFarmerSide = (farmerId === currentUserId);
                    const messageElement = document.createElement('div');
                    messageElement.className = 'mb-6 ' + (isSender ? 'flex justify-end' : 'flex justify-start');
                    messageElement.innerHTML = `
                        <div class="max-w-[80%] md:max-w-[70%]">
                            <div class="px-5 py-3.5 shadow-sm ${isSender ? 'bg-green-600 text-white rounded-2xl rounded-tr-none' : 'bg-white border border-gray-100 text-gray-800 rounded-2xl rounded-tl-none'}">
                                <p class="text-sm leading-relaxed whitespace-pre-line">${messageText}</p>
                            </div>
                            <div class="flex items-center mt-2 px-1 ${isSender ? 'justify-end' : 'justify-start'}">
                                <p class="text-[10px] font-bold text-gray-400 uppercase tracking-tighter">
                                    ${data.data.created_at}
                                </p>
                                ${isSender ? `
                                    <div class="ml-2 flex items-center">
                                        <i class="fas fa-check text-[8px] text-gray-300"></i>
                                    </div>
                                ` : ''}
                            </div>
                        </div>
                    `;
                    messagesContainer.appendChild(messageElement);

                    // Clear input and scroll to bottom
                    messageInput.value = '';
                    messagesContainer.scrollTop = messagesContainer.scrollHeight;

                    // Refresh message counts after sending a message
                    refreshUnreadCounts();
                } else {
                    alert('Error sending message: ' + data.message);
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('An error occurred while sending the message.');
            })
            .finally(() => {
                // Re-enable form
                messageInput.disabled = false;
                messageForm.querySelector('button').disabled = false;
                messageInput.focus();
            });
        });
    }

    // Expose to window for manual re-init
    window.initializeMessaging = initializeMessaging;

    // Auto-resize textarea
    document.addEventListener('input', function (e) {
        if (e.target.id === 'message-input') {
            e.target.style.height = 'auto';
            e.target.style.height = (e.target.scrollHeight > 100 ? 100 : e.target.scrollHeight) + 'px';
        }
    });

    // Periodically refresh message counts (every 30 seconds)
    setInterval(refreshUnreadCounts, 30000);

    // Initial call to initialize messaging
    initializeMessaging();
});

/* ── Product Details Modal Functions (Global) ── */
window.openProductDetailsModal = function(btn) {
    const modal = document.getElementById('productDetailsModal');
    if (!modal) return;

    const d = btn.dataset;

    const titleEl = document.getElementById('pdm-title');
    if (titleEl) titleEl.textContent = d.productName;

    const priceEl = document.getElementById('pdm-price');
    const priceUnit = document.getElementById('pdm-price-unit');
    if (priceEl) priceEl.textContent = '₱' + d.price;
    if (priceUnit) priceUnit.textContent = ' / ' + d.unit;

    const varietyEl = document.getElementById('pdm-variety');
    const harvestEl  = document.getElementById('pdm-harvest');
    const qtyEl     = document.getElementById('pdm-quantity');
    if (varietyEl) varietyEl.textContent = d.variety;
    if (harvestEl)  harvestEl.textContent  = d.harvest || 'N/A';
    if (qtyEl)     qtyEl.textContent     = d.quantity + ' ' + d.unit;

    const locEl = document.getElementById('pdm-location');
    if (locEl) locEl.textContent = d.location || 'Location not specified';

    const farmerNameEl  = document.getElementById('pdm-farmer-name');
    const farmNameEl    = document.getElementById('pdm-farm-name');
    const farmerPhoneEl = document.getElementById('pdm-farmer-phone');
    const farmAddrEl    = document.getElementById('pdm-farm-address');
    if (farmerNameEl)  farmerNameEl.textContent  = d.farmer;
    if (farmNameEl)    farmNameEl.textContent    = d.farmName || '';
    if (farmerPhoneEl) farmerPhoneEl.textContent = d.farmerPhone || 'N/A';
    if (farmAddrEl)    farmAddrEl.textContent    = d.farmAddress || 'N/A';

    const statusText  = document.getElementById('pdm-status-text');
    const statusBadge = document.getElementById('pdm-status-badge');
    const status = d.status || 'Available';
    if (statusText) statusText.textContent = status;
    if (statusBadge) {
        const dot = statusBadge.querySelector('span');
        if (status === 'Available') {
            statusBadge.className = 'inline-flex items-center gap-1.5 text-xs font-semibold px-2.5 py-1 rounded-full bg-white text-green-700 shadow-sm';
            if (dot) dot.className = 'w-1.5 h-1.5 rounded-full bg-green-500 inline-block';
        } else {
            statusBadge.className = 'inline-flex items-center gap-1.5 text-xs font-semibold px-2.5 py-1 rounded-full bg-white text-red-600 shadow-sm';
            if (dot) dot.className = 'w-1.5 h-1.5 rounded-full bg-red-500 inline-block';
        }
    }

    const img = document.getElementById('pdm-product-img');
    const noImage = document.getElementById('pdm-no-image');
    if (img && noImage) {
        if (d.image) {
            img.src = d.image;
            img.classList.remove('hidden');
            noImage.classList.add('hidden');
        } else {
            img.classList.add('hidden');
            noImage.classList.remove('hidden');
        }
    }

    const switchBtn = document.getElementById('pdm-switch-btn');
    if (switchBtn) {
        switchBtn.onclick = function () {
            window.closeProductDetailsModal();
            window.switchToTransaction(d.transactionId);
        };
    }

    modal.classList.remove('hidden');
    document.body.style.overflow = 'hidden';
};

window.closeProductDetailsModal = function() {
    const modal = document.getElementById('productDetailsModal');
    if (modal) modal.classList.add('hidden');
    document.body.style.overflow = '';
};

window.switchToTransaction = function(transactionId) {
    Promise.all([
        fetch(`/messages/conversation/${transactionId}`).then(r => r.text()),
        fetch(`/messages/transaction-details/${transactionId}`).then(r => r.text())
    ])
    .then(([conversationHtml, detailsHtml]) => {
        document.getElementById('conversation-container').innerHTML = conversationHtml;
        document.getElementById('transaction-details').innerHTML = detailsHtml;

        document.querySelectorAll('.conversation-item').forEach(item => {
            item.classList.remove('bg-indigo-100', 'border-l-4', 'border-l-indigo-500');
            if (item.getAttribute('data-transaction-id') == transactionId) {
                item.classList.add('bg-indigo-100', 'border-l-4', 'border-l-indigo-500');
            }
        });

        if (typeof window.initializeMessaging === 'function') window.initializeMessaging();
        if (typeof window.initializeOrderModal  === 'function') window.initializeOrderModal();
    })
    .catch(err => console.error('Error loading transaction:', err));
};

document.addEventListener('click', function (e) {
    if (e.target && e.target.id === 'pdm-backdrop') window.closeProductDetailsModal();
});

document.addEventListener('keydown', function (e) {
    if (e.key === 'Escape') window.closeProductDetailsModal();
});
