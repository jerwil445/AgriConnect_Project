window.toggleDropdownById = function(id) {
    const dropdown = document.getElementById(id);
    const allDropdowns = document.querySelectorAll('[id$="-dropdown"], [id^="actions-menu-"]');
    
    allDropdowns.forEach(d => {
        if (d.id !== id) d.classList.add('hidden');
    });
    
    if (dropdown) {
        dropdown.classList.toggle('hidden');
    }
};

window.addEventListener('click', function(e) {
    if (!e.target.closest('button') && !e.target.closest('form')) {
        document.querySelectorAll('[id$="-dropdown"], [id^="actions-menu-"]').forEach(d => d.classList.add('hidden'));
    }
});

document.addEventListener('DOMContentLoaded', () => {
    document.querySelectorAll('.delete-form').forEach(form => {
        form.addEventListener('submit', function(e) {
            e.preventDefault();
            const productName = this.getAttribute('data-product-name');
            if (confirm(`PURGE RECORD: Are you sure you want to permanently delete "${productName}" from the marketplace inventory?`)) {
                this.submit();
            }
        });
    });
});
