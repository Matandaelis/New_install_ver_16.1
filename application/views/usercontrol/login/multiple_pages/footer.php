<?php
    if(is_array($theme_settings) && isset($theme_settings[0])) {
        $footer = $theme_settings[0];
    }
?>

<?php if( current_url() != site_url('/login') && current_url() != site_url('/register') && current_url() != site_url('/register/vendor') && current_url() != site_url('/forget-password') && current_url() != site_url('/terms-of-use')){ ?>

    <!-- CTA Banner Before Footer -->
    <section class="mp-cta" style="background-image: url('<?= base_url('assets/login/multiple_pages') ?>/img/cta-bg.jpg');">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-lg-7">
                    <div class="mp-section-title">
                        <div class="mp-section-icon"><i class="bi bi-rocket-takeoff"></i></div>
                        <h2><?= (isset($footer->banner_bottom_title) && !empty($footer->banner_bottom_title)) ? __($footer->banner_bottom_title) : __('front.what_is_lorem_ipsum') ?></h2>
                        <p><?= (isset($footer->banner_bottom_slug) && !empty($footer->banner_bottom_slug)) ? __($footer->banner_bottom_slug) : __('front.lorem_ipsum_description') ?></p>
                    </div>
                    <a class="mp-btn-cta front_button_color front_button_hover_color front_button_text_color" href="<?= $footer->banner_button_link ?>">
                        <?= (isset($footer->banner_button_text) && !empty($footer->banner_button_text)) ? __($footer->banner_button_text) : __('front.lorem_ipsum') ?>
                        <i class="bi bi-arrow-right"></i>
                    </a>
                </div>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer class="mp-footer">
        <div class="container">
            <div class="row">
                <?php if(isset($footer_menu['menu_1']) && !empty($footer_menu['menu_1'])): ?>
                <div class="col-lg-3 col-md-6">
                    <h4><?= (isset($footer->footer_menu_title_a) && !empty($footer->footer_menu_title_a)) ? $footer->footer_menu_title_a : "Menu A Link"; ?></h4>
                    <ul>
                        <?php foreach($footer_menu['menu_1'] as $menu): ?>
                        <li><a href="<?= $menu['url'] ?>" <?= ($menu['target_blank'] == 1) ? 'target="_blank"' : '';?>><?= $menu['title'];?></a></li>
                        <?php endforeach; ?>
                    </ul>
                </div>
                <?php endif; ?>

                <?php if(isset($footer_menu['menu_2']) && !empty($footer_menu['menu_2'])): ?>
                <div class="col-lg-3 col-md-6">
                    <h4><?= (isset($footer->footer_menu_title_b) && !empty($footer->footer_menu_title_b)) ? $footer->footer_menu_title_b : "Menu B Link"; ?></h4>
                    <ul>
                        <?php foreach($footer_menu['menu_2'] as $menu): ?>
                        <li><a href="<?= $menu['url'] ?>" <?= ($menu['target_blank'] == 1) ? 'target="_blank"' : '';?>><?= $menu['title'];?></a></li>
                        <?php endforeach; ?>
                    </ul>
                </div>
                <?php endif; ?>

                <?php if(isset($footer_menu['menu_3']) && !empty($footer_menu['menu_3'])): ?>
                <div class="col-lg-3 col-md-6">
                    <h4><?= (isset($footer->footer_menu_title_c) && !empty($footer->footer_menu_title_c)) ? $footer->footer_menu_title_c : "Menu C Link"; ?></h4>
                    <ul>
                        <?php foreach($footer_menu['menu_3'] as $menu): ?>
                        <li><a href="<?= $menu['url'] ?>" <?= ($menu['target_blank'] == 1) ? 'target="_blank"' : '';?>><?= $menu['title'];?></a></li>
                        <?php endforeach; ?>
                    </ul>
                </div>
                <?php endif; ?>

                <?php if(isset($footer_menu['menu_4']) && !empty($footer_menu['menu_4'])): ?>
                <div class="col-lg-3 col-md-6">
                    <h4><?= (isset($footer->footer_menu_title_d) && !empty($footer->footer_menu_title_d)) ? $footer->footer_menu_title_d : "Menu D Link"; ?></h4>
                    <ul>
                        <?php foreach($footer_menu['menu_4'] as $menu): ?>
                        <li><a href="<?= $menu['url'] ?>" <?= ($menu['target_blank'] == 1) ? 'target="_blank"' : '';?>><?= $menu['title'];?></a></li>
                        <?php endforeach; ?>
                    </ul>
                </div>
                <?php endif; ?>

                <?php if((!isset($footer_menu['menu_1']) || empty($footer_menu['menu_1'])) && (!isset($footer_menu['menu_2']) || empty($footer_menu['menu_2'])) && (!isset($footer_menu['menu_3']) || empty($footer_menu['menu_3'])) && (!isset($footer_menu['menu_4']) || empty($footer_menu['menu_4']))): ?>
                <div class="col-lg-3 col-md-6">
                    <h4>LOREM IPSUM TEXT</h4>
                    <ul>
                        <li><a href="">Lorem Ipsum</a></li>
                        <li><a href="">Lorem Ipsum</a></li>
                        <li><a href="">Lorem Ipsum</a></li>
                    </ul>
                </div>
                <div class="col-lg-3 col-md-6">
                    <h4>LOREM IPSUM TEXT</h4>
                    <ul>
                        <li><a href="">Lorem Ipsum</a></li>
                        <li><a href="">Lorem Ipsum</a></li>
                        <li><a href="">Lorem Ipsum</a></li>
                    </ul>
                </div>
                <div class="col-lg-3 col-md-6">
                    <h4>LOREM IPSUM TEXT</h4>
                    <ul>
                        <li><a href="">Lorem Ipsum</a></li>
                        <li><a href="">Lorem Ipsum</a></li>
                        <li><a href="">Lorem Ipsum</a></li>
                    </ul>
                </div>
                <div class="col-lg-3 col-md-6">
                    <h4>LOREM IPSUM TEXT</h4>
                    <ul>
                        <li><a href="">Lorem Ipsum</a></li>
                        <li><a href="">Lorem Ipsum</a></li>
                        <li><a href="">Lorem Ipsum</a></li>
                    </ul>
                </div>
                <?php endif; ?>
            </div>

            <div class="mp-footer-copyright">
                <?= (isset($footer->copyright) && !empty($footer->copyright)) ? $footer->copyright : __('front.copyright_all_rights_reserved')." ".date('Y'); ?>
            </div>
        </div>
    </footer>

<?php } ?>

    <!-- Bootstrap 5 JS Bundle (includes Popper) -->
    <script src="<?= base_url('assets/template/js/bootstrap.bundle.min.js') ?>?v=<?= av() ?>"></script>
    <script src="<?= base_url('assets/login/multiple_pages/js/owl.carousel.min.js') ?>?v=<?= av() ?>"></script>
    <script src="<?= base_url('assets/login/multiple_pages/js/jquery.mousewheel.min.js') ?>?v=<?= av() ?>"></script>
    <script src="<?= base_url('assets/login/multiple_pages/js/active.js') ?>?v=<?= av() ?>"></script>
    <script src="<?= base_url('assets/template/js/front-dark-mode.js') ?>?v=<?= av() ?>"></script>
    <script src="<?= base_url('assets/template/js/main.js') ?>?v=<?= av() ?>"></script>

    <?php include __DIR__ . "/../cookies_consent.php"; ?>

    <?= render_recaptcha_scripts(['login', 'forgot']) ?>

    <script>
    $(function() {
        /* ---- Sticky Navbar (inline to bypass active.js cache) ---- */
        var $mpNavbar = $(".mp-navbar");
        if ($mpNavbar.length) {
            var $innerNav = $mpNavbar.find("nav.navbar");
            $(window).on('scroll.mpSticky', function() {
                if ($(window).scrollTop() > 100) {
                    if (!$mpNavbar.hasClass("stick")) {
                        $mpNavbar.addClass("stick");
                        $innerNav.removeClass("navbar-dark").addClass("navbar-light");
                    }
                } else {
                    if ($mpNavbar.hasClass("stick")) {
                        $mpNavbar.removeClass("stick");
                        $innerNav.removeClass("navbar-light").addClass("navbar-dark");
                    }
                }
            });
        }

        var topSliderAutoplay     = <?= (isset($theme_multiple_page_settings['top_slider_auto_play']) && $theme_multiple_page_settings['top_slider_auto_play'] == 1) ? "true" : "false" ?>;
        var topSliderAutoplayTime = <?= $theme_multiple_page_settings['top_slider_auto_timing'] ?? 10 ?>;
        var runnerAutoplay        = <?= (isset($theme_multiple_page_settings['home_runner_auto_play']) && $theme_multiple_page_settings['home_runner_auto_play'] == 1) ? "true" : "false" ?>;
        var runnerAutoplayTime    = <?= $theme_multiple_page_settings['home_runner_auto_timing'] ?? 10 ?>;
        var contentAutoplay       = <?= (isset($theme_multiple_page_settings['home_content_auto_play']) && $theme_multiple_page_settings['home_content_auto_play'] == 1) ? "true" : "false" ?>;
        var contentAutoplayTime   = <?= $theme_multiple_page_settings['home_content_auto_timing'] ?? 10 ?>;

        var homeSliderOwl = $(".home-top-slider").owlCarousel({
            items: 1,
            loop: true,
            nav: true,
            dots: false,
            autoplay: topSliderAutoplay,
            autoplayTimeout: (topSliderAutoplayTime * 1000),
            navText: ['<i class="bi bi-chevron-left"></i>','<i class="bi bi-chevron-right"></i>']
        });

        homeSliderOwl.on('changed.owl.carousel', function(property){
            var current = property.item.index;
            var src = $(property.target).find(".mp-hero-content").eq(current);
            if(src.data('background')) {
                $('.mp-hero').css('background-image', 'url(' + src.data('background') + ')');
            }
        });

        $(".news-ticker-slider").owlCarousel({
            items: 1,
            loop: true,
            nav: true,
            dots: false,
            navText: ['<i class="bi bi-chevron-left"></i>','<i class="bi bi-chevron-right"></i>'],
            autoplay: runnerAutoplay,
            autoplayTimeout: (runnerAutoplayTime * 1000)
        });

        $(".featured-slider").owlCarousel({
            items: 1,
            loop: true,
            autoHeight: true,
            autoplay: contentAutoplay,
            autoplayTimeout: (contentAutoplayTime * 1000)
        });
    });
    </script>

</body>
</html>
