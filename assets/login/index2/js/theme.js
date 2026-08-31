/* ============================================================
   Theme Index2: "Split Elegance" — JavaScript
   ============================================================ */

document.addEventListener('DOMContentLoaded', function() {

    /* --- Password Toggle (show/hide) --- */
    var togglePassword = document.querySelector('.toggle-password');
    var passwordInput = document.querySelector('input[name="password"]');

    if (togglePassword && passwordInput) {
        togglePassword.addEventListener('click', function(e) {
            e.preventDefault();
            var type = passwordInput.getAttribute('type') === 'password' ? 'text' : 'password';
            passwordInput.setAttribute('type', type);

            var icon = this.querySelector('i');
            if (type === 'password') {
                icon.className = 'bi bi-eye';
            } else {
                icon.className = 'bi bi-eye-slash';
            }
        });
    }

    /* --- Input focus enhancement --- */
    var inputs = document.querySelectorAll('.idx2-input');
    inputs.forEach(function(input) {
        input.addEventListener('focus', function() {
            this.closest('.idx2-input-group').classList.add('focused');
        });
        input.addEventListener('blur', function() {
            this.closest('.idx2-input-group').classList.remove('focused');
        });
    });

});
