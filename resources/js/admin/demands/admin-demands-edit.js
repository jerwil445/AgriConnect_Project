window.addEventListener('DOMContentLoaded', function() {
    const sizes = ['small', 'medium', 'large', 'extra_large', 'jumbo'];
    const eggSizeHidden = document.getElementById('egg_size_hidden');
    
    sizes.forEach(size => {
        const checkbox = document.getElementById(`${size}_checkbox`);
        const trayContainer = document.getElementById(`${size}_tray_container`);
        const trayInput = document.getElementById(`${size}_trays`);
        
        if (checkbox) {
            checkbox.addEventListener('change', function() {
                if (this.checked) {
                    trayContainer.classList.remove('hidden');
                    trayContainer.classList.add('animate-in', 'fade-in', 'slide-in-from-top-2', 'duration-200');
                } else {
                    trayContainer.classList.add('hidden');
                    if (trayInput) trayInput.value = '';
                }
                updateEggSizeHidden();
            });
        }
        
        if (trayInput) {
            trayInput.addEventListener('input', updateEggSizeHidden);
        }
    });
    
    function updateEggSizeHidden() {
        const selectedValues = [];
        sizes.forEach(size => {
            const checkbox = document.getElementById(`${size}_checkbox`);
            if (checkbox && checkbox.checked) {
                const trayInput = document.getElementById(`${size}_trays`);
                const trayCount = trayInput ? trayInput.value : '';
                
                if (trayCount) {
                    selectedValues.push(`${size} (${trayCount} tray${trayCount > 1 ? 's' : ''})`);
                } else {
                    selectedValues.push(size);
                }
            }
        });
        
        if (eggSizeHidden) {
            eggSizeHidden.value = selectedValues.join(', ');
        }
    }
});
