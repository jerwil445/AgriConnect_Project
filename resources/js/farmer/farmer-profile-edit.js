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
});
