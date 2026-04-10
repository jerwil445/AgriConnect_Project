window.toggleDropdownById = function(id) {
    const dropdown = document.getElementById(id);
    const allDropdowns = document.querySelectorAll('[id$="-dropdown"]');
    
    allDropdowns.forEach(d => {
        if (d.id !== id) d.classList.add('hidden');
    });
    
    if (dropdown) {
        dropdown.classList.toggle('hidden');
    }
};

window.addEventListener('click', function(e) {
    if (!e.target.closest('button')) {
        document.querySelectorAll('[id$="-dropdown"]').forEach(d => d.classList.add('hidden'));
    }
});

document.addEventListener('DOMContentLoaded', () => {
    document.querySelectorAll('.delete-form').forEach(form => {
        form.addEventListener('submit', function(e) {
            e.preventDefault();
            const matchId = this.getAttribute('data-match-id');
            if (confirm(`SYSTEM ALERT: Sever match connection #${matchId}?\n\nThis will terminate the algorithmic link between these stakeholders.`)) {
                this.submit();
            }
        });
    });
});
