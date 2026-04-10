document.addEventListener('DOMContentLoaded', () => {
    document.querySelectorAll('.delete-form').forEach(form => {
        form.addEventListener('submit', function(e) {
            e.preventDefault();
            const demandName = this.getAttribute('data-demand-name');
            if (confirm(`⚠️ WARNING: Permanently delete demand for "${demandName}"?\n\nThis will break active match calculations and archival records.`)) {
                this.submit();
            }
        });
    });
});
