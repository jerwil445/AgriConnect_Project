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
});
