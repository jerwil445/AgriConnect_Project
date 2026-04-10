document.addEventListener('DOMContentLoaded', () => {
    const deleteForm = document.querySelector('.delete-form');
    if (deleteForm) {
        deleteForm.addEventListener('submit', function(e) {
            e.preventDefault();
            const userName = this.getAttribute('data-user-name');
            
            if (confirm(`⚠️ WARNING: Are you absolutely sure you want to permanently delete the user account for ${userName}?\n\nThis will erase all related data.`)) {
                this.submit();
            }
        });
    }
});
