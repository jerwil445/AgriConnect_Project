document.addEventListener('DOMContentLoaded', () => {
    document.querySelectorAll('.delete-form').forEach(form => {
        form.addEventListener('submit', function(e) {
            e.preventDefault();
            const productName = this.getAttribute('data-product-name');
            if (confirm(`⚠️ CRITICAL WARNING: You are about to permanently delete "${productName}".\n\nThis will remove all inventory records, imagery, and un-matched demands associated with it. This cannot be undone.\n\nProceed with deletion?`)) {
                this.submit();
            }
        });
    });
});
