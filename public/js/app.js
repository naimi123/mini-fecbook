document.addEventListener('DOMContentLoaded', function () {
    const postImage = document.getElementById('postImage');
    const fileName = document.getElementById('fileName');

    if (postImage) {
        postImage.addEventListener('change', function () {
            if (this.files.length > 0) {
                fileName.textContent = this.files[0].name;
            }
        });
    }

    const profileImage = document.getElementById('profileImage');
    const profilePreview = document.getElementById('profilePreview');

    if (profileImage && profilePreview) {
        profileImage.addEventListener('change', function () {
            if (this.files.length > 0) {
                const reader = new FileReader();
                reader.onload = function (e) {
                    profilePreview.src = e.target.result;
                };
                reader.readAsDataURL(this.files[0]);
            }
        });
    }

    const alerts = document.querySelectorAll('.alert');
    alerts.forEach(function (alert) {
        setTimeout(function () {
            const bsAlert = new bootstrap.Alert(alert);
            bsAlert.close();
        }, 5000);
    });
});
