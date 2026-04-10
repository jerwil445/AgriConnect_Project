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
