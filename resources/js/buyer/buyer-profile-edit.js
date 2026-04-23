document.addEventListener('DOMContentLoaded', () => {
    // Modern Profile Preview with Scale Effect
    const profilePictureInput = document.getElementById('profile_picture');
    if (profilePictureInput) {
        profilePictureInput.addEventListener('change', function (e) {
            const file = e.target.files[0];
            if (file) {
                const reader = new FileReader();
                const preview = document.getElementById('profile-preview');
                reader.onload = function (e) {
                    preview.classList.add('scale-75', 'opacity-0');
                    setTimeout(() => {
                        preview.src = e.target.result;
                        preview.classList.remove('scale-75', 'opacity-0');
                        preview.classList.add('scale-100', 'opacity-100');
                    }, 150);
                }
                reader.readAsDataURL(file);
            }
        });
    }
    // Buyer Interest Categories Logic
    const categoryChips = document.querySelectorAll('.category-chip');
    const categoriesInput = document.getElementById('buyer-categories-input');
    let selectedCategories = [];

    // Initialize from hidden input
    if (categoriesInput && categoriesInput.value) {
        selectedCategories = categoriesInput.value.split(',').filter(c => c.trim() !== "");
    }

    categoryChips.forEach(chip => {
        chip.addEventListener('click', () => {
            const category = chip.dataset.category;
            
            if (selectedCategories.includes(category)) {
                // Remove
                selectedCategories = selectedCategories.filter(c => c !== category);
                chip.classList.remove('border-indigo-500', 'text-indigo-600', 'bg-indigo-50');
                chip.classList.add('border-gray-100', 'bg-white', 'text-gray-500');
            } else {
                // Add
                selectedCategories.push(category);
                chip.classList.add('border-indigo-500', 'text-indigo-600', 'bg-indigo-50');
                chip.classList.remove('border-gray-100', 'bg-white', 'text-gray-500');
            }

            categoriesInput.value = selectedCategories.join(',');
        });
    });
});
