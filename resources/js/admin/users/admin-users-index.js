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
            const userName = this.getAttribute('data-user-name');
            if (confirm(`⚠️ FINAL WARNING: Permanently delete account for "${userName}"?\n\nThis will purge all associated data, farm records, and transaction history. This action IRREVERSIBLE.`)) {
                this.submit();
            }
        });
    });
});
