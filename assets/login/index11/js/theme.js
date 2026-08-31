/**
 * Index11 Theme JS — "Photo Split"
 */
document.addEventListener('DOMContentLoaded', function () {

    // Password Toggle
    document.querySelectorAll('.toggle-password').forEach(function (btn) {
        btn.addEventListener('click', function () {
            var target = document.querySelector(this.getAttribute('data-target'));
            if (!target) return;
            var icon = this.querySelector('i');
            if (target.type === 'password') {
                target.type = 'text';
                icon.classList.remove('bi-eye');
                icon.classList.add('bi-eye-slash');
            } else {
                target.type = 'password';
                icon.classList.remove('bi-eye-slash');
                icon.classList.add('bi-eye');
            }
        });
    });

    // Input Focus Effect
    document.querySelectorAll('.idx11-input').forEach(function (input) {
        input.addEventListener('focus', function () {
            this.closest('.idx11-input-group').classList.add('focused');
        });
        input.addEventListener('blur', function () {
            this.closest('.idx11-input-group').classList.remove('focused');
        });
    });

});
