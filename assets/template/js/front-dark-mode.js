/**
 * Front-end Theme Toggle — Dark / Light Mode
 * Reads from localStorage for instant load; stores in cookie as fallback.
 * Separate from admin panel's v14_theme key.
 */
(function () {
    'use strict';

    var STORAGE_KEY = 'front_theme_mode';

    function getCurrentTheme() {
        return document.documentElement.getAttribute('data-bs-theme') || 'light';
    }

    function refreshIntlTelDropdownTheme() {
        var dark = document.documentElement.getAttribute('data-bs-theme') === 'dark';
        document.querySelectorAll('.iti__country-list').forEach(function (list) {
            if (dark) {
                list.classList.remove('bg-white', 'text-dark');
                if (!list.classList.contains('shadow')) {
                    list.classList.add('shadow');
                }
            } else {
                list.classList.add('bg-white', 'text-dark', 'shadow');
            }
        });
        document.querySelectorAll('.iti__country').forEach(function (row) {
            if (dark) {
                row.classList.remove('text-dark');
            } else {
                row.classList.add('text-dark');
            }
        });
    }

    function applyTheme(theme) {
        document.documentElement.setAttribute('data-bs-theme', theme);
        try { localStorage.setItem(STORAGE_KEY, theme); } catch (e) {}
        document.cookie = STORAGE_KEY + '=' + theme + ';path=/;max-age=31536000;SameSite=Lax';

        refreshIntlTelDropdownTheme();
        try {
            document.dispatchEvent(new CustomEvent('front-theme-changed', { detail: { theme: theme } }));
        } catch (e) {}

        document.querySelectorAll('.front-theme-toggle').forEach(function (btn) {
            var sun  = btn.querySelector('.bi-sun-fill');
            var moon = btn.querySelector('.bi-moon-fill');
            if (sun && moon) {
                sun.style.display  = theme === 'dark' ? 'inline-block' : 'none';
                moon.style.display = theme === 'dark' ? 'none' : 'inline-block';
            }
        });
    }

    function init() {
        document.querySelectorAll('.front-theme-toggle').forEach(function (btn) {
            btn.addEventListener('click', function (e) {
                e.preventDefault();
                e.stopPropagation();
                var next = getCurrentTheme() === 'dark' ? 'light' : 'dark';
                applyTheme(next);
            });
        });
    }

    var stored = null;
    try { stored = localStorage.getItem(STORAGE_KEY); } catch (e) {}
    if (stored === 'dark' || stored === 'light') {
        applyTheme(stored);
    }

    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', init);
    } else {
        init();
    }
})();
