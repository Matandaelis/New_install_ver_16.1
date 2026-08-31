/**
 * V14 Theme Toggle — Dark / Light Mode
 * Reads from localStorage for instant load, syncs to DB via AJAX.
 */
(function () {
    'use strict';

    var STORAGE_KEY = 'v14_theme';

    /* ---- Helpers ---- */
    function getCurrentTheme() {
        return document.documentElement.getAttribute('data-bs-theme') || 'light';
    }

    function applyTheme(theme) {
        document.documentElement.setAttribute('data-bs-theme', theme);
        localStorage.setItem(STORAGE_KEY, theme);
        // Update all toggle button icons
        document.querySelectorAll('.theme-toggle-btn').forEach(function (btn) {
            var sun = btn.querySelector('.fa-sun');
            var moon = btn.querySelector('.fa-moon');
            if (sun && moon) {
                sun.style.display = theme === 'dark' ? 'inline-block' : 'none';
                moon.style.display = theme === 'dark' ? 'none' : 'inline-block';
            }
        });
    }

    function saveThemeToDB(theme) {
        if (typeof window.affiliatePro === 'undefined') return;
        var base = window.affiliatePro.base_url || '';
        // Determine which controller to call
        var isAdmin = (window.location.href.indexOf('/admincontrol') !== -1);
        var endpoint = isAdmin
            ? base + 'admincontrol/save_theme'
            : base + 'usercontrol/save_theme';

        // Use fetch with fallback to XMLHttpRequest
        if (window.fetch) {
            fetch(endpoint, {
                method: 'POST',
                headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
                body: 'theme=' + encodeURIComponent(theme)
            }).catch(function () { /* silent fail */ });
        } else {
            var xhr = new XMLHttpRequest();
            xhr.open('POST', endpoint, true);
            xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
            xhr.send('theme=' + encodeURIComponent(theme));
        }
    }

    /* ---- Init on DOM ready ---- */
    function init() {
        // Bind toggle buttons
        document.querySelectorAll('.theme-toggle-btn').forEach(function (btn) {
            btn.addEventListener('click', function (e) {
                e.preventDefault();
                var next = getCurrentTheme() === 'dark' ? 'light' : 'dark';
                applyTheme(next);
                saveThemeToDB(next);
            });
        });
    }

    // Apply stored theme IMMEDIATELY (before DOMContentLoaded) to prevent flash
    var stored = localStorage.getItem(STORAGE_KEY);
    if (stored === 'dark' || stored === 'light') {
        applyTheme(stored);
    }

    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', init);
    } else {
        init();
    }
})();
