document.addEventListener('DOMContentLoaded', function () {
    const profilePictureInput = document.getElementById('profile_picture');
    const preview = document.getElementById('profile-preview');

    if (profilePictureInput && preview) {
        profilePictureInput.addEventListener('change', function (e) {
            const file = e.target.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = function (e) {
                    preview.style.transition = 'all 0.3s cubic-bezier(0.4, 0, 0.2, 1)';
                    preview.style.transform = 'scale(0.8)';
                    preview.style.opacity = '0.5';

                    setTimeout(() => {
                        preview.src = e.target.result;
                        preview.style.transform = 'scale(1)';
                        preview.style.opacity = '1';
                    }, 300);
                };
                reader.readAsDataURL(file);
            }
        });
    }
    // Category Chips Logic
    const categoryChips = document.querySelectorAll('.category-chip');
    const categoriesInput = document.getElementById('categories-input');
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
                chip.classList.remove('border-green-500', 'text-green-600', 'bg-green-50');
                chip.classList.add('border-gray-100', 'bg-white', 'text-gray-500');
            } else {
                // Add
                selectedCategories.push(category);
                chip.classList.add('border-green-500', 'text-green-600', 'bg-green-50');
                chip.classList.remove('border-gray-100', 'bg-white', 'text-gray-500');
            }

            categoriesInput.value = selectedCategories.join(',');
        });
    });
});
