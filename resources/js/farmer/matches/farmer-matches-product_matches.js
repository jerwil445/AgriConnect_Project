document.addEventListener('DOMContentLoaded', function () {
    // Buyer Profile Modal Logic
    window.openBuyerModal = function (id, firstName, lastName, email, phone, company, businessType, address) {
        const fullName = firstName + ' ' + lastName;
        const modal = document.getElementById('buyerProfileModal');
        if (!modal) return;

        const modalName = document.getElementById('modal-buyer-name');
        const modalEmail = document.getElementById('modal-email');
        const modalPhone = document.getElementById('modal-phone');
        const modalCompany = document.getElementById('modal-company');
        const modalBusinessType = document.getElementById('modal-business-type');
        const modalAddress = document.getElementById('modal-address');
        const modalAvatar = document.getElementById('modal-avatar');

        if (modalName) modalName.textContent = fullName;
        if (modalEmail) modalEmail.textContent = email;
        if (modalPhone) modalPhone.textContent = phone;
        if (modalCompany) modalCompany.textContent = company;
        if (modalBusinessType) modalBusinessType.textContent = businessType;
        if (modalAddress) modalAddress.textContent = address;
        if (modalAvatar) {
            modalAvatar.src = `https://ui-avatars.com/api/?name=${encodeURIComponent(fullName)}&background=e0f2fe&color=0369a1&size=200&bold=true`;
        }

        const card = modal.querySelector('div');
        
        modal.classList.remove('hidden');
        modal.classList.add('flex');
        
        // Entry animation using requestAnimationFrame
        requestAnimationFrame(() => {
            modal.classList.remove('opacity-0');
            if (card) {
                card.classList.remove('scale-95');
                card.classList.add('scale-100');
            }
        });
        
        document.body.style.overflow = 'hidden';
    };

    window.closeBuyerModal = function () {
        const modal = document.getElementById('buyerProfileModal');
        if (!modal) return;
        
        const card = modal.querySelector('div');
        
        modal.classList.add('opacity-0');
        if (card) {
            card.classList.remove('scale-100');
            card.classList.add('scale-95');
        }
        
        setTimeout(() => {
            modal.classList.add('hidden');
            modal.classList.remove('flex');
            document.body.style.overflow = '';
        }, 300);
    };

    // Close on backdrop click
    const buyerProfileModal = document.getElementById('buyerProfileModal');
    if (buyerProfileModal) {
        buyerProfileModal.addEventListener('click', function (e) {
            if (e.target === this) closeBuyerModal();
        });
    }

    // Close on escape key
    document.addEventListener('keydown', function (e) {
        if (e.key === 'Escape') closeBuyerModal();
    });
});
