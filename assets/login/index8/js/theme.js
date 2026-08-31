/**
 * Theme Index8: "Midnight Luxe" — Interactions
 */
(function () {
    'use strict';

    /* Password toggle */
    document.querySelectorAll('.toggle-password').forEach(function (btn) {
        btn.addEventListener('click', function () {
            var target = document.querySelector(this.getAttribute('data-target'));
            if (!target) return;
            var isPassword = target.type === 'password';
            target.type = isPassword ? 'text' : 'password';
            var icon = this.querySelector('i');
            if (icon) {
                icon.classList.toggle('bi-eye', !isPassword);
                icon.classList.toggle('bi-eye-slash', isPassword);
            }
        });
    });

    /* Focus ring for input groups */
    document.querySelectorAll('.idx8-input').forEach(function (input) {
        input.addEventListener('focus', function () {
            var group = this.closest('.idx8-input-group');
            if (group) group.classList.add('focused');
        });
        input.addEventListener('blur', function () {
            var group = this.closest('.idx8-input-group');
            if (group) group.classList.remove('focused');
        });
    });
})();
