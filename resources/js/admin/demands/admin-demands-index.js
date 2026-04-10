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
            const demandName = this.getAttribute('data-demand-name');
            if (confirm(`⚠️ PERMANENT DELETE: Remove market demand for "${demandName}"?\n\nThis will also sever any active algorithm matches.`)) {
                this.submit();
            }
        });
    });
});
