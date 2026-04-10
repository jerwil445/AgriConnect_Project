window.openFarmerModal = function(firstName, lastName, email, phone, farmName, productType, farmAddress) {
    const fullName = firstName + ' ' + lastName;
    document.getElementById('modal-farmer-name').textContent = fullName;
    document.getElementById('modal-email').textContent = email;
    document.getElementById('modal-phone').textContent = phone;
    document.getElementById('modal-farm-name').textContent = farmName;
    document.getElementById('modal-product-type').textContent = productType;
    document.getElementById('modal-farm-address').textContent = farmAddress;
    document.getElementById('modal-avatar').src = 
        `https://ui-avatars.com/api/?name=${encodeURIComponent(fullName)}&background=dcfce7&color=14532d&size=200&bold=true`;

    const modal = document.getElementById('farmerProfileModal');
    const card = modal.querySelector('div');
    
    modal.classList.remove('hidden');
    modal.classList.add('flex');
    
    requestAnimationFrame(() => {
        modal.classList.remove('opacity-0');
        card.classList.remove('scale-95');
        card.classList.add('scale-100');
    });
    
    document.body.style.overflow = 'hidden';
};

window.closeFarmerModal = function() {
    const modal = document.getElementById('farmerProfileModal');
    const card = modal.querySelector('div');
    
    modal.classList.add('opacity-0');
    card.classList.remove('scale-100');
    card.classList.add('scale-95');
    
    setTimeout(() => {
        modal.classList.add('hidden');
        modal.classList.remove('flex');
        document.body.style.overflow = '';
    }, 300);
};

document.addEventListener('DOMContentLoaded', function() {
    const closeBtn = document.getElementById('closeFarmerModalBtn');
    if (closeBtn) closeBtn.addEventListener('click', window.closeFarmerModal);

    const modal = document.getElementById('farmerProfileModal');
    if (modal) {
        modal.addEventListener('click', function(e) {
            if (e.target === this) window.closeFarmerModal();
        });
    }

    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') window.closeFarmerModal();
    });
});
