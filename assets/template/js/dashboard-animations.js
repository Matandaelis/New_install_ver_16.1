/**
 * V14 Dashboard Animations
 * - CountUp number animation
 * - Card entrance stagger (Intersection Observer)
 * - Sparkline chart helper
 * - Ring chart helper
 */
(function () {
    'use strict';

    /* ============================================================
       1. CountUp Animation
       ============================================================ */
    function easeOutExpo(t) {
        return t === 1 ? 1 : 1 - Math.pow(2, -10 * t);
    }

    function formatNumber(num, decimals, prefix, suffix) {
        prefix = prefix || '';
        suffix = suffix || '';
        var fixed = num.toFixed(decimals);
        // Add thousand separators
        var parts = fixed.split('.');
        parts[0] = parts[0].replace(/\B(?=(\d{3})+(?!\d))/g, ',');
        return prefix + parts.join('.') + suffix;
    }

    function countUp(element) {
        var target = parseFloat(element.getAttribute('data-countup')) || 0;
        var decimals = parseInt(element.getAttribute('data-countup-decimals')) || 0;
        var prefix = element.getAttribute('data-countup-prefix') || '';
        var suffix = element.getAttribute('data-countup-suffix') || '';
        var duration = parseInt(element.getAttribute('data-countup-duration')) || 1500;

        if (target === 0) {
            element.textContent = prefix + '0' + suffix;
            return;
        }

        var startTime = null;
        function step(timestamp) {
            if (!startTime) startTime = timestamp;
            var progress = Math.min((timestamp - startTime) / duration, 1);
            var easedProgress = easeOutExpo(progress);
            var current = target * easedProgress;
            element.textContent = formatNumber(current, decimals, prefix, suffix);
            if (progress < 1) {
                requestAnimationFrame(step);
            } else {
                element.textContent = formatNumber(target, decimals, prefix, suffix);
            }
        }
        requestAnimationFrame(step);
    }

    /* ============================================================
       2. Card Entrance Stagger (Intersection Observer)
       ============================================================ */
    function initCardAnimations() {
        var cards = document.querySelectorAll('.card-animate');
        if (!cards.length) return;

        if (!('IntersectionObserver' in window)) {
            // Fallback: show all immediately
            cards.forEach(function (c) { c.classList.add('visible'); });
            return;
        }

        var observer = new IntersectionObserver(function (entries) {
            entries.forEach(function (entry) {
                if (entry.isIntersecting) {
                    var idx = parseInt(entry.target.getAttribute('data-animate-index')) || 0;
                    setTimeout(function () {
                        entry.target.classList.add('visible');
                    }, idx * 80);
                    observer.unobserve(entry.target);
                }
            });
        }, { threshold: 0.1 });

        cards.forEach(function (card, i) {
            if (!card.hasAttribute('data-animate-index')) {
                card.setAttribute('data-animate-index', i);
            }
            observer.observe(card);
        });
    }

    /* ============================================================
       3. CountUp Initialization (with Intersection Observer)
       ============================================================ */
    function initCountUpAnimations() {
        var counters = document.querySelectorAll('[data-countup]');
        if (!counters.length) return;

        if (!('IntersectionObserver' in window)) {
            counters.forEach(function (el) { countUp(el); });
            return;
        }

        var observer = new IntersectionObserver(function (entries) {
            entries.forEach(function (entry) {
                if (entry.isIntersecting) {
                    countUp(entry.target);
                    observer.unobserve(entry.target);
                }
            });
        }, { threshold: 0.1 });

        counters.forEach(function (el) { observer.observe(el); });
    }

    /* ============================================================
       4. Sparkline Chart Helper
       Uses Chart.js (must be loaded)
       ============================================================ */
    window.createSparkline = function (canvasId, data, color, fillColor) {
        var canvas = document.getElementById(canvasId);
        if (!canvas || typeof Chart === 'undefined') return null;

        var ctx = canvas.getContext('2d');

        // Create gradient fill
        var gradient = ctx.createLinearGradient(0, 0, 0, 40);
        if (fillColor) {
            gradient.addColorStop(0, fillColor);
        } else {
            gradient.addColorStop(0, color.replace(')', ', 0.3)').replace('rgb', 'rgba'));
        }
        gradient.addColorStop(1, 'rgba(255, 255, 255, 0)');

        // Labels: just numbers 1..n
        var labels = data.map(function (_, i) { return i + 1; });

        return new Chart(ctx, {
            type: 'line',
            data: {
                labels: labels,
                datasets: [{
                    data: data,
                    borderColor: color,
                    borderWidth: 2.5,
                    backgroundColor: gradient,
                    fill: true,
                    tension: 0.35,
                    pointRadius: 2,
                    pointBackgroundColor: color,
                    pointBorderColor: '#fff',
                    pointBorderWidth: 1,
                    pointHoverRadius: 5,
                    pointHoverBackgroundColor: color,
                    pointHoverBorderColor: '#fff',
                    pointHoverBorderWidth: 2
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: { display: false },
                    tooltip: {
                        enabled: true,
                        mode: 'index',
                        intersect: false,
                        callbacks: {
                            title: function () { return ''; },
                            label: function (ctx) { return ctx.parsed.y.toLocaleString(); }
                        },
                        padding: 6,
                        displayColors: false,
                        bodyFont: { size: 11 }
                    }
                },
                scales: {
                    x: { display: false },
                    y: { display: false }
                },
                interaction: {
                    mode: 'index',
                    intersect: false
                },
                elements: {
                    line: {
                        borderCapStyle: 'round',
                        borderJoinStyle: 'round'
                    }
                }
            }
        });
    };

    /* ============================================================
       5. Ring / Donut Chart Helper
       Uses Chart.js (must be loaded)
       ============================================================ */
    window.createRingChart = function (canvasId, labels, data, colors, centerHtml) {
        var canvas = document.getElementById(canvasId);
        if (!canvas || typeof Chart === 'undefined') return null;

        var ctx = canvas.getContext('2d');
        var isDark = document.documentElement.getAttribute('data-bs-theme') === 'dark';

        // If all values are 0, show a placeholder gray ring
        var hasData = data.some(function(v) { return v > 0; });
        var chartData = hasData ? data : [1];
        var chartColors = hasData ? colors : [isDark ? '#374151' : '#e5e7eb'];
        var chartLabels = hasData ? labels : ['No Data'];

        var chart = new Chart(ctx, {
            type: 'doughnut',
            data: {
                labels: chartLabels,
                datasets: [{
                    data: chartData,
                    backgroundColor: chartColors,
                    borderWidth: 2,
                    borderColor: isDark ? '#1e2130' : '#ffffff',
                    hoverBorderColor: isDark ? '#252836' : '#ffffff',
                    hoverOffset: hasData ? 8 : 0
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: true,
                cutout: '74%',
                plugins: {
                    legend: {
                        position: 'bottom',
                        labels: {
                            padding: 12,
                            usePointStyle: true,
                            pointStyle: 'circle',
                            font: { size: 11 },
                            color: isDark ? '#9aa0a6' : '#6c757d'
                        }
                    },
                    tooltip: {
                        padding: 10,
                        bodyFont: { size: 12 }
                    }
                },
                animation: {
                    animateRotate: true,
                    duration: 1200,
                    easing: 'easeOutQuart'
                }
            }
        });

        // Set center text if provided
        if (centerHtml) {
            var container = canvas.closest('.ring-chart-container');
            if (container) {
                var center = container.querySelector('.ring-chart-center-text');
                if (center) center.innerHTML = centerHtml;
            }
        }

        return chart;
    };

    /* ============================================================
       6. Init All on DOMContentLoaded
       ============================================================ */
    function initAll() {
        initCardAnimations();
        initCountUpAnimations();
    }

    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', initAll);
    } else {
        initAll();
    }
})();
