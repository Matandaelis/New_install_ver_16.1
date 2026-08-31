<?php include(APPPATH.'/views/usercontrol/login/multiple_pages/header.php'); ?>

<?php
    if(!empty($theme_sliders)):
    for ($i=0; $i < sizeof($theme_sliders); $i++) {
        if($theme_sliders[$i]->status == 1){
            $heroData = $theme_sliders[$i];
            break;
        }
    }
    endif;

    $heroImage = (isset($heroData) && !empty($heroData->image)) ? base_url('assets/images/theme_images/'.$heroData->image) : base_url('assets/login/multiple_pages/img/hero-bg.jpg');
?>

<!-- Hero Section -->
<section class="mp-hero" style="background-image: url('<?= $heroImage ?>');">
    <div class="mp-floating-shapes">
        <div class="mp-shape mp-shape-1"></div>
        <div class="mp-shape mp-shape-2"></div>
        <div class="mp-shape mp-shape-3"></div>
        <div class="mp-shape mp-shape-4"></div>
        <div class="mp-shape mp-shape-5"></div>
    </div>
    <div class="container mp-hero-slider home-top-slider owl-carousel">
        <?php
        for ($i=0; $i < sizeof($theme_sliders); $i++) {
            if($theme_sliders[$i]->status == 1){
                $slideHeroImage = (isset($theme_sliders[$i]) && !empty($theme_sliders[$i]->image)) ? base_url('assets/images/theme_images/'.$theme_sliders[$i]->image) : base_url('assets/login/multiple_pages/img/hero-bg.jpg');
        ?>
        <div class="row">
            <div class="col-lg-8">
                <div class="mp-hero-content" data-background="<?= $slideHeroImage; ?>">
                    <h1 class="display-3"><?= (isset($theme_sliders[$i]) && !empty($theme_sliders[$i]->title)) ? $theme_sliders[$i]->title : "Lorem Ipsum<br>is Simply Dummy" ?></h1>
                    <p><?= (isset($theme_sliders[$i]) && !empty($theme_sliders[$i]->description)) ? $theme_sliders[$i]->description : "Lorem Ipsum has been the industry's standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book."; ?></p>
                    <a href="<?= (isset($theme_sliders[$i]) && !empty($theme_sliders[$i]->link)) ? $theme_sliders[$i]->link : base_url('register'); ?>" target="_blank" class="mp-btn-hero front_button_color front_button_hover_color front_button_text_color">
                        <?= (isset($theme_sliders[$i]) && !empty($theme_sliders[$i]->button_text)) ? $theme_sliders[$i]->button_text : __('front.join_as_affiliate'); ?>
                        <i class="bi bi-arrow-right"></i>
                    </a>
                </div>
            </div>
        </div>
        <?php
                }
            }

            if(! isset($heroData)) {
        ?>
        <div class="row">
            <div class="col-lg-8">
                <div class="mp-hero-content" data-background="<?= $heroImage; ?>">
                    <h1 class="display-3">Lorem Ipsum<br>is Simply Dummy</h1>
                    <p>Lorem Ipsum has been the industry's standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book.</p>
                    <a href="<?= base_url('register') ?>" target="_blank" class="mp-btn-hero front_button_color front_button_hover_color front_button_text_color">
                        <?= __('front.join_as_affiliate'); ?>
                        <i class="bi bi-arrow-right"></i>
                    </a>
                </div>
            </div>
        </div>
        <?php
            }
        ?>
    </div>
</section>

<?php
    foreach ($theme_settings as $settings) {
        $top_banner_slider = json_decode($settings->top_banner_slider);
    }
?>

<!-- Wave Divider -->
<div class="mp-wave-divider">
    <svg viewBox="0 0 1200 60" preserveAspectRatio="none" xmlns="http://www.w3.org/2000/svg">
        <path d="M0,0 C300,60 900,0 1200,40 L1200,60 L0,60 Z" fill="currentColor" style="color: <?= $front_runner_bar_color['front_runner_bar_color'] ?>;" />
    </svg>
</div>

<!-- News Ticker -->
<div class="mp-ticker">
    <div class="container">
        <div class="news-ticker-slider owl-carousel">
            <?php
            $noRunners = true;
            foreach($top_banner_slider as $runner){
                if(!empty($runner)) {
                    $noRunners = false;
            ?>
            <div class="mp-ticker-inner text-center">
                <p><?= $runner; ?></p>
            </div>
            <?php
                }
            }
            if ($noRunners == true){
            ?>
            <div class="mp-ticker-inner text-center">
                <p>Lorem Ipsum has been the industry's standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled</p>
            </div>
            <?php
            }
            ?>
        </div>
    </div>
</div>

<?php
    foreach($home_sections_settings as $hsSetting) {
        if($hsSetting->sec_is_enable){
            switch ($hsSetting->sec_title) {

                case 'Membership Section':
                    $db =& get_instance();
                    $membership = $db->Product_model->getSettings('membership');
                    if($membership['status']) {
?>

<!-- Membership / Pricing Section -->
<section class="mp-pricing-section">
    <div class="mp-section-shapes">
        <div class="mp-dot mp-dot-1"></div>
        <div class="mp-dot mp-dot-2"></div>
    </div>
    <div class="container">
        <div class="mp-section-title">
            <div class="mp-section-icon"><i class="bi bi-award"></i></div>
            <?php foreach($theme_settings as $settings) { $membershipTitles = $settings; } ?>
            <h2 class="front_theme_text_color"><?= (isset($membershipTitles) && !empty($membershipTitles->membership_top_title)) ? $membershipTitles->membership_top_title : __('front.best_affiliate_plan'); ?></h2>
            <p><?= (isset($membershipTitles) && !empty($membershipTitles->membership_sub_title)) ? $membershipTitles->membership_sub_title : "Lorem Ipsum is simply dummy text of the printing and typesetting industry."; ?></p>
        </div>

        <div class="row g-4 justify-content-center">
        <?php
        $plans = App\MembershipPlan::select('membership_plans.*','award_level.sale_comission_rate')->join('award_level','award_level.id','=','membership_plans.level_id','left')->where('status',1)->get()->toArray();
        foreach ($plans as $plan) {
            $plansAvailable = true;
        ?>
        <div class="col-lg-4 col-md-6">
            <div class="mp-price-card">
                <?php if($plan['special']) {
                    $percentage = round((($plan['price'] - $plan['special']) * 100) / $plan['price']);
                ?>
                <div class="mp-price-badge" style="background: <?= $plan['label_background'] ?>; color: <?= $plan['label_color'] ?>;">
                    <?= __('front.save') ?> <?= $percentage ?>%
                </div>
                <?php } ?>

                <div class="mp-plan-header">
                    <h4 style="color: <?= $plan['label_background'] ?>;"><?= $plan['name'] ?></h4>
                    <img class="img-fluid" src="<?= base_url('assets/login/multiple_pages') ?>/img/<?= $plan['plan_icon'] ? $plan['plan_icon'] : 'saturn.png' ?>" alt="<?= $plan['name'] ?>">
                    <div class="mp-plan-price">
                        <h2 style="color: <?= $plan['label_background'] ?>;">
                            <?php if($plan['price'] == 0) { ?>
                                FREE
                            <?php } else { ?>
                                <?php if($plan['special']) { ?>
                                    <?= c_format($plan['special']) ?>
                                <?php } else { ?>
                                    <?= c_format($plan['price'],true) ?>
                                <?php } ?>
                            <?php } ?>
                        </h2>
                        <span>
                            <?php if($plan['special']) { ?>
                                <del><?= c_format($plan['price'],true) ?></del>
                            <?php } ?>
                            <span style="color: <?= $plan['label_background'] ?>;">
                                <?php
                                if($plan['billing_period'] == "lifetime_free") {
                                    echo __('front.lifetime');
                                } else if($plan['billing_period'] == "custom") {
                                    echo $plan['custom_period'] . " " . __('front.days');
                                } else {
                                    echo ucwords(strtolower($plan['billing_period']));
                                }
                                ?>
                            </span>
                        </span>
                    </div>
                </div>

                <?php if($plan['bonus']) { ?>
                <div class="mp-plan-commission" style="color: <?= $plan['label_background'] ?>;">
                    <?= __('front.bonus_rate') . " " . c_format($plan['bonus'], true) ?>
                </div>
                <?php } ?>

                <?php if($plan['user_type'] == 2): ?>
                <div class="mp-plan-commission" style="color: <?= $plan['label_background'] ?>;">
                    <?= __('user.campaign').' : '.(isset($plan['campaign']) ? $plan['campaign'] : __('user.unlimited')) ?>
                </div>
                <div class="mp-plan-commission" style="color: <?= $plan['label_background'] ?>;">
                    <?= __('user.product').' : '.(isset($plan['product']) ? $plan['product'] : __('user.unlimited')) ?>
                </div>
                <?php endif ?>

                <?php if($plan['commission_sale_status']): ?>
                <?php $sale_comission_rate = ($plan['sale_comission_rate']) ? $plan['sale_comission_rate'].'%' : __('front.default') ?>
                <div class="mp-plan-commission" style="color: <?= $plan['label_background'] ?>;">
                    <?= __('front.affiliate_commission').' '.$sale_comission_rate ?>
                </div>
                <?php endif ?>

                <div class="mp-plan-features">
                    <?php
                    if(!empty($plan['description'])) {
                        echo $plan['description'];
                    } else {
                        echo '<ul class="list-unstyled text-center">
                                <li>Lorem Ipsum is simply dummy text</li>
                                <li>Lorem Ipsum is simply dummy text</li>
                                <li>Lorem Ipsum is simply dummy text</li>
                            </ul>';
                    }
                    ?>
                </div>

                <div class="mp-plan-footer">
                    <a href="<?= base_url('/register') ?>" style="background-color: <?= $plan['label_background'] ?>; color: <?= $plan['label_color'] ?>;">
                        <?= __('front.register_plan') ?>
                    </a>
                </div>
            </div>
        </div>
        <?php
        }

        if(!isset($plansAvailable)) {
        ?>
        <div class="col-lg-4 col-md-6">
            <div class="mp-price-card">
                <div class="mp-price-badge" style="background: #4361ee; color: #fff;"><?= __('front.save') ?> 90%</div>
                <div class="mp-plan-header">
                    <h4>30 <?= __('front.days') ?></h4>
                    <img src="<?= base_url('assets/login/multiple_pages') ?>/img/saturn.png" alt="saturn">
                    <div class="mp-plan-price">
                        <h2>$1</h2>
                        <span><del>$10</del> <span>/<?= __('front.per_monthly') ?></span></span>
                    </div>
                </div>
                <ul class="mp-plan-features">
                    <li>Lorem Ipsum is simply dummy text</li>
                    <li>Lorem Ipsum is simply dummy text</li>
                    <li>Lorem Ipsum is simply dummy text</li>
                </ul>
                <div class="mp-plan-footer">
                    <a href="" style="background: #4361ee; color: #fff;">Lorem Ipsum</a>
                </div>
            </div>
        </div>

        <div class="col-lg-4 col-md-6">
            <div class="mp-price-card">
                <div class="mp-price-badge" style="background: #f72585; color: #fff;"><i class="bi bi-star-fill"></i> <?= __('front.save') ?> 90%</div>
                <div class="mp-plan-header">
                    <h4>30 <?= __('front.days') ?></h4>
                    <img src="<?= base_url('assets/login/multiple_pages') ?>/img/uranus.png" alt="uranus">
                    <div class="mp-plan-price">
                        <h2>$1</h2>
                        <span><del>$10</del> <span>/<?= __('front.per_monthly') ?></span></span>
                    </div>
                </div>
                <ul class="mp-plan-features">
                    <li>Lorem Ipsum is simply dummy text</li>
                    <li>Lorem Ipsum is simply dummy text</li>
                    <li>Lorem Ipsum is simply dummy text</li>
                </ul>
                <div class="mp-plan-footer">
                    <a href="" style="background: #f72585; color: #fff;">Lorem Ipsum</a>
                </div>
            </div>
        </div>

        <div class="col-lg-4 col-md-6">
            <div class="mp-price-card">
                <div class="mp-price-badge" style="background: #7c3aed; color: #fff;"><?= __('front.save') ?> 90%</div>
                <div class="mp-plan-header">
                    <h4>30 <?= __('front.days') ?></h4>
                    <img src="<?= base_url('assets/login/multiple_pages') ?>/img/asteroid.png" alt="asteroid">
                    <div class="mp-plan-price">
                        <h2>$1</h2>
                        <span><del>$10</del> <span>/<?= __('front.per_monthly') ?></span></span>
                    </div>
                </div>
                <ul class="mp-plan-features">
                    <li>Lorem Ipsum is simply dummy text</li>
                    <li>Lorem Ipsum is simply dummy text</li>
                    <li>Lorem Ipsum is simply dummy text</li>
                </ul>
                <div class="mp-plan-footer">
                    <a href="" style="background: #7c3aed; color: #fff;">Lorem Ipsum</a>
                </div>
            </div>
        </div>
        <?php
        }
        ?>
        </div>
    </div>
</section>

<?php
                    }
                    break;

                case 'Home Content':
?>

<!-- Featured / Home Content Section -->
<section class="mp-featured-section">
    <div class="container">
        <div class="mp-featured-slider featured-slider owl-carousel">
            <?php
            foreach($theme_homecontent as $homecontent){
                if ($homecontent->status == 1) {
                    $homeContentAvailable = true;
            ?>
            <div class="single-featured">
                <div class="row align-items-center g-5">
                    <div class="col-lg-6">
                        <div class="mp-featured-content mp-animate">
                            <h2 class="front_theme_text_color"><?= $homecontent->title; ?></h2>
                            <p><?= $homecontent->description ?></p>
                        </div>
                    </div>
                    <div class="col-lg-5 offset-lg-1">
                        <div class="mp-featured-image mp-animate mp-animate-delay-2">
                            <?php
                            $image_url = (!empty($homecontent->image)) ? base_url('assets/images/theme_images/'.$homecontent->image) : base_url('assets/login/multiple_pages/img/featured-img.png');
                            ?>
                            <img src="<?= $image_url; ?>" alt="<?= __('front.login_image') ?>" class="img-fluid">
                        </div>
                    </div>
                </div>
            </div>
            <?php
                }
            }

            if(!isset($homeContentAvailable)) {
            ?>
            <div class="single-featured">
                <div class="row align-items-center g-5">
                    <div class="col-lg-6">
                        <div class="mp-featured-content">
                            <h2 class="front_theme_text_color">What is Lorem Ipsum?</h2>
                            <p>Contrary to popular belief, Lorem Ipsum is not simply random text. It has roots in a piece of classical Latin literature from 45 BC, making it over 2000 years old.</p>
                        </div>
                    </div>
                    <div class="col-lg-5 offset-lg-1">
                        <div class="mp-featured-image">
                            <img src="<?= base_url('assets/login/multiple_pages') ?>/img/featured-img.png" alt="<?= __('front.featured_image') ?>" class="img-fluid">
                        </div>
                    </div>
                </div>
            </div>
            <?php
            }
            ?>
        </div>
    </div>
</section>

<?php
                    break;

                case 'Home Section':
?>

<!-- Blog / Sections Area -->
<section class="mp-blog-section">
    <div class="mp-section-shapes">
        <div class="mp-dot mp-dot-1"></div>
        <div class="mp-dot mp-dot-2"></div>
    </div>
    <div class="container">
        <div class="mp-section-title">
            <div class="mp-section-icon"><i class="bi bi-grid-3x3-gap"></i></div>
            <?php foreach($theme_settings as $settings) { $homeSecTitles = $settings; } ?>
            <h2 class="front_theme_text_color">
                <?= (isset($homeSecTitles) && !empty($homeSecTitles->home_section_title)) ? __($homeSecTitles->home_section_title) : __('front.what_is_lorem_ipsum'); ?>
            </h2>
            <p>
                <?= (isset($homeSecTitles) && !empty($homeSecTitles->home_section_subtitle)) ? __($homeSecTitles->home_section_subtitle) : __('front.lorem_ipsum_description'); ?>
            </p>
        </div>

        <div class="row g-4 justify-content-center">
        <?php
        $i = 0;
        foreach($theme_sections as $section) {
            if ($section->status == 1) {
                $isSectionsAvailanle = true;
                $image_url = (!empty($section->image)) ? base_url('assets/images/theme_images/'.$section->image) : base_url('assets/login/multiple_pages/img/blog-image'.($i+1).'.jpg');
        ?>
        <div class="col-lg-4 col-md-6">
            <div class="mp-blog-card mp-animate mp-animate-delay-<?= ($i % 4) + 1 ?>">
                <div class="mp-blog-img">
                    <img src="<?= $image_url ?>" alt="<?= $section->title ?>">
                    <span class="mp-blog-badge front_button_color front_button_text_color"><?= $section->title ?></span>
                </div>
                <div class="mp-blog-body">
                    <p><?= $section->description ?></p>
                    <a class="mp-blog-link front_button_color front_button_text_color" href="<?= $section->link ?>" target="_blank">
                        <?= $section->button_text ?>
                        <i class="bi bi-arrow-right"></i>
                    </a>
                </div>
            </div>
        </div>
        <?php
                $i++;
            }
        }

        if(!isset($isSectionsAvailanle)) {
        ?>
        <div class="col-lg-4 col-md-6">
            <div class="mp-blog-card">
                <div class="mp-blog-img">
                    <img src="<?= base_url('assets/login/multiple_pages') ?>/img/blog-image.jpg" alt="<?= __('front.blog') ?>">
                    <span class="mp-blog-badge front_button_color front_button_text_color">Section 1</span>
                </div>
                <div class="mp-blog-body">
                    <p>Lorem Ipsum is simply dummy text of the printing and typesetting industry.</p>
                    <a class="mp-blog-link front_button_color front_button_text_color" href="">Lorem Ipsum <i class="bi bi-arrow-right"></i></a>
                </div>
            </div>
        </div>
        <div class="col-lg-4 col-md-6">
            <div class="mp-blog-card">
                <div class="mp-blog-img">
                    <img src="<?= base_url('assets/login/multiple_pages') ?>/img/blog-image-2.jpg" alt="<?= __('front.blog') ?>">
                    <span class="mp-blog-badge front_button_color front_button_text_color">Section 2</span>
                </div>
                <div class="mp-blog-body">
                    <p>Lorem Ipsum is simply dummy text of the printing and typesetting industry.</p>
                    <a class="mp-blog-link front_button_color front_button_text_color" href="">Lorem Ipsum <i class="bi bi-arrow-right"></i></a>
                </div>
            </div>
        </div>
        <div class="col-lg-4 col-md-6">
            <div class="mp-blog-card">
                <div class="mp-blog-img">
                    <img src="<?= base_url('assets/login/multiple_pages') ?>/img/blog-image-3.jpg" alt="<?= __('front.blog') ?>">
                    <span class="mp-blog-badge front_button_color front_button_text_color">Section 3</span>
                </div>
                <div class="mp-blog-body">
                    <p>Lorem Ipsum is simply dummy text of the printing and typesetting industry.</p>
                    <a class="mp-blog-link front_button_color front_button_text_color" href="">Lorem Ipsum <i class="bi bi-arrow-right"></i></a>
                </div>
            </div>
        </div>
        <?php
        }
        ?>
        </div>
    </div>
</section>

<?php
                    break;

                case 'Video Section':
                    foreach($theme_settings as $settings) { $video_section = $settings; }
                    $video_bg = (isset($video_section) && !empty($video_section->homepage_video_section_bg)) ? base_url('assets/images/theme_images/'.$video_section->homepage_video_section_bg) : base_url('assets/login/multiple_pages/img/video-section-bg.png');
?>

<!-- Video Section -->
<section class="mp-video-section">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-lg-6">
                <div class="mp-video-header text-start">
                    <h2 class="front_theme_text_color"><?= __('front.watch_our_videos') ?></h2>
                </div>
            </div>
            <div class="col-lg-6">
                <div class="mp-video-carousel" id="carouselExampleControls">
                    <div id="videoCarousel" class="carousel slide" data-bs-ride="carousel">
                        <div class="carousel-inner">
                            <?php
                            $i = 0;
                            foreach ($theme_videos as $video) {
                                if($video->status == 1){
                            ?>
                            <div class="carousel-item <?= ($i==0)?"active":""; ?>">
                                <div class="mp-video-card">
                                    <div class="our-video-header text-center mb-3">
                                        <h5 class="front_theme_text_color"><?php echo ($video->video_title != "") ? $video->video_title : __('front.watch_our_videos'); ?></h5>
                                        <?php echo ($video->video_sub_title != "") ? "<p class='text-muted small'>".$video->video_sub_title."</p>" : ""; ?>
                                    </div>
                                    <div class="video-inner ratio ratio-16x9" style="border-radius: var(--mp-radius); overflow: hidden;">
                                        <?php
                                        if (!function_exists('convertVideoToEmbedUrl')) {
                                            function convertVideoToEmbedUrl($url) {
                                                if (strpos($url, 'embed') !== false) { return $url; }
                                                $videoId = '';
                                                if (preg_match('/[?&]v=([^&]+)/', $url, $matches)) { $videoId = $matches[1]; }
                                                elseif (preg_match('/youtu\.be\/([^?&]+)/', $url, $matches)) { $videoId = $matches[1]; }
                                                elseif (preg_match('/m\.youtube\.com\/watch\?v=([^&]+)/', $url, $matches)) { $videoId = $matches[1]; }
                                                if ($videoId) { return "https://www.youtube.com/embed/{$videoId}"; }
                                                if (strpos($url, 'vimeo.com') !== false) {
                                                    if (preg_match('/vimeo\.com\/(\d+)/', $url, $matches)) {
                                                        return "https://player.vimeo.com/video/{$matches[1]}";
                                                    }
                                                }
                                                return $url;
                                            }
                                        }
                                        $embedUrl = convertVideoToEmbedUrl($video->video_link);
                                        ?>
                                        <iframe src="<?= $embedUrl ?>" frameborder="0" allow="accelerometer; autoplay; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>
                                    </div>
                                </div>
                            </div>
                            <?php
                                    $i++;
                                }
                            }
                            if($i == 0){
                            ?>
                            <div class="carousel-item active">
                                <div class="mp-video-card">
                                    <div class="text-center mb-3">
                                        <h5><?= __('front.watch_our_videos') ?></h5>
                                    </div>
                                    <img class="img-fluid" src="<?= base_url('assets/login/multiple_pages') ?>/img/video-bg.jpg" alt="<?= __('front.video') ?>" style="border-radius: var(--mp-radius);">
                                </div>
                            </div>
                            <?php
                            }
                            ?>
                        </div>
                        <?php if($i > 1) { ?>
                        <button class="carousel-control-prev" type="button" data-bs-target="#videoCarousel" data-bs-slide="prev">
                            <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                        </button>
                        <button class="carousel-control-next" type="button" data-bs-target="#videoCarousel" data-bs-slide="next">
                            <span class="carousel-control-next-icon" aria-hidden="true"></span>
                        </button>
                        <?php } ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<?php
                    break;

                case 'Recommendation Section':
?>

<!-- Testimonial Section -->
<section class="mp-testimonial-section">
    <div class="mp-section-shapes">
        <div class="mp-dot mp-dot-1"></div>
        <div class="mp-dot mp-dot-2"></div>
    </div>
    <div class="container">
        <div class="mp-section-title">
            <div class="mp-section-icon"><i class="bi bi-chat-quote"></i></div>
            <?php foreach($theme_settings as $settings) { $recommendation_section = $settings; } ?>
            <h2 class="front_theme_text_color">
                <?= (isset($recommendation_section) && !empty($recommendation_section->recommendation_section_title)) ? __($recommendation_section->recommendation_section_title) : __('front.what_is_lorem_ipsum'); ?>
            </h2>
            <p>
                <?= (isset($recommendation_section) && !empty($recommendation_section->recommendation_section_subtitle)) ? __($recommendation_section->recommendation_section_subtitle) : __('front.lorem_ipsum_description'); ?>
            </p>
        </div>
    </div>

    <div class="testimonial-slider owl-carousel">
        <?php
        foreach($theme_recommendation as $recommendation):
            if ($recommendation->status==1) {
                $isRecommendationAvailable = true;
                $image_url = (!empty($recommendation->image)) ? base_url('assets/images/theme_images/'.$recommendation->image) : base_url('assets/login/multiple_pages/img/client-1.png');
        ?>
        <div class="mp-testimonial-card">
            <div class="mp-testimonial-author">
                <img src="<?= $image_url ?>" alt="<?= $recommendation->title ?>">
                <div>
                    <h5 class="front_theme_text_color"><?= $recommendation->title ?></h5>
                    <span><?= $recommendation->occupation ?></span>
                </div>
            </div>
            <div class="mp-testimonial-body">
                <p><?= $recommendation->description ?></p>
            </div>
        </div>
        <?php
            }
        endforeach;

        if(!isset($isRecommendationAvailable)) {
        ?>
        <div class="mp-testimonial-card">
            <div class="mp-testimonial-author">
                <img src="<?= base_url('assets/login/multiple_pages') ?>/img/client-1.png" alt="<?= __('front.client') ?>">
                <div>
                    <h5 class="front_theme_text_color">Metehan</h5>
                    <span>Designer</span>
                </div>
            </div>
            <div class="mp-testimonial-body">
                <p>Lorem Ipsum has been the industry's standard dummy text ever since the 1500s.</p>
            </div>
        </div>
        <div class="mp-testimonial-card">
            <div class="mp-testimonial-author">
                <img src="<?= base_url('assets/login/multiple_pages') ?>/img/client-2.png" alt="<?= __('front.client') ?>">
                <div>
                    <h5 class="front_theme_text_color">Metehan</h5>
                    <span>Designer</span>
                </div>
            </div>
            <div class="mp-testimonial-body">
                <p>Lorem Ipsum has been the industry's standard dummy text ever since the 1500s.</p>
            </div>
        </div>
        <div class="mp-testimonial-card">
            <div class="mp-testimonial-author">
                <img src="<?= base_url('assets/login/multiple_pages') ?>/img/client-3.png" alt="<?= __('front.client') ?>">
                <div>
                    <h5 class="front_theme_text_color">Metehan</h5>
                    <span>Designer</span>
                </div>
            </div>
            <div class="mp-testimonial-body">
                <p>Lorem Ipsum has been the industry's standard dummy text ever since the 1500s.</p>
            </div>
        </div>
        <?php
        }
        ?>
    </div>
</section>

<?php
                    break;
            }
        }
    }
?>

<?php include(APPPATH.'/views/usercontrol/login/multiple_pages/footer.php'); ?>
