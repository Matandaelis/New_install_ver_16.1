;(function ($) {
    "use strict";

    var $window = $(window);

    /* Sticky Navbar — swap navbar-dark to navbar-light for proper contrast */
    $window.on('scroll', function() {
        var $navbar = $(".mp-navbar");
        var $innerNav = $navbar.find("nav.navbar");
        if ($window.scrollTop() > 100) {
            if (!$navbar.hasClass("stick")) {
                $navbar.addClass("stick");
                $innerNav.removeClass("navbar-dark").addClass("navbar-light");
            }
        } else {
            if ($navbar.hasClass("stick")) {
                $navbar.removeClass("stick");
                $innerNav.removeClass("navbar-light").addClass("navbar-dark");
            }
        }
    });

    /* Testimonial Slider */
    $('.testimonial-slider').owlCarousel({
        center: true,
        items: 1,
        loop: true,
        margin: 20,
        autoplay: true,
        autoplayTimeout: 5000,
        dots: false,
        nav: false,
        responsive: {
            0: { items: 1 },
            768: { items: 2 },
            992: { items: 3 }
        }
    });

    /* FAQ Accordion: Toggle chevron icon rotation
       BS5 dispatches native events, so use addEventListener */
    document.querySelectorAll('.mp-faq-accordion .collapse').forEach(function(el) {
        el.addEventListener('show.bs.collapse', function() {
            var btn = this.previousElementSibling;
            if (btn) {
                var icon = btn.querySelector('button i');
                if (icon) icon.style.transform = 'rotate(180deg)';
            }
        });
        el.addEventListener('hide.bs.collapse', function() {
            var btn = this.previousElementSibling;
            if (btn) {
                var icon = btn.querySelector('button i');
                if (icon) icon.style.transform = 'rotate(0deg)';
            }
        });
    });

    /* Scroll Animation: fade in elements as they enter the viewport */
    var animatedElements = document.querySelectorAll('.mp-animate');
    if (animatedElements.length && 'IntersectionObserver' in window) {
        var animObserver = new IntersectionObserver(function(entries) {
            entries.forEach(function(entry) {
                if (entry.isIntersecting) {
                    entry.target.style.animationPlayState = 'running';
                    animObserver.unobserve(entry.target);
                }
            });
        }, { threshold: 0.15 });

        animatedElements.forEach(function(el) {
            el.style.animationPlayState = 'paused';
            animObserver.observe(el);
        });
    }

    /* Password Toggle for Login/Forgot Password forms */
    $(document).on('click', '.mp-toggle-password', function() {
        var input = $(this).closest('.input-group').find('input');
        var icon = $(this).find('i');
        if (input.attr('type') === 'password') {
            input.attr('type', 'text');
            icon.removeClass('bi-eye-slash').addClass('bi-eye');
        } else {
            input.attr('type', 'password');
            icon.removeClass('bi-eye').addClass('bi-eye-slash');
        }
    });

})(jQuery);
