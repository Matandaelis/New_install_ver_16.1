/* Index4 Theme JS — Emerald Professional */
(function () {
    'use strict';

    /* Password toggle */
    document.querySelectorAll('.toggle-password').forEach(function (btn) {
        btn.addEventListener('click', function () {
            var input = this.closest('.idx4-input-group').querySelector('input[type="password"], input[type="text"]');
            if (!input) return;
            var isPassword = input.type === 'password';
            input.type = isPassword ? 'text' : 'password';
            var icon = this.querySelector('i');
            if (icon) {
                icon.classList.toggle('bi-eye', !isPassword);
                icon.classList.toggle('bi-eye-slash', isPassword);
            }
        });
    });

    /* Subtle input focus effect */
    document.querySelectorAll('.idx4-input').forEach(function (input) {
        input.addEventListener('focus', function () {
            var g = this.closest('.idx4-input-group');
            if (g) g.classList.add('focused');
        });
        input.addEventListener('blur', function () {
            var g = this.closest('.idx4-input-group');
            if (g) g.classList.remove('focused');
        });
    });
})();
