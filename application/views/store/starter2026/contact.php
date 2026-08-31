<?php
/**
 * Starter 2026 — Contact Us Page
 *
 * @contract  Store API v1 — page: contact
 *
 * GLOBALS  $store_setting, $home_link, $base_url, $googlerecaptcha
 *
 * PAGE VARIABLES
 *   $settings  array   Store settings (email, phone, address pulled from $store_setting)
 *   $content   array   Page content keyed by field — use $content['contact_content'] for extra HTML
 *   $category  array   Root categories (for header nav)
 *
 * NOTE  $tnc_link is built inline from $store_setting['storeurl'] + '/policy'
 */
?>

<div class="container">
    <nav class="s26-breadcrumb" aria-label="Breadcrumb">
        <a href="<?= $home_link ?? $base_url ?>"><?= __('store.home') ?? 'Home' ?></a>
        <span class="separator"><i class="fas fa-chevron-right"></i></span>
        <span class="current"><?= __('store.contact_us') ?? 'Contact Us' ?></span>
    </nav>
</div>

<section class="s26-contact-page">
    <div class="container">

        <!-- Page Header -->
        <div class="text-center mb-5">
            <div class="s26-contact-icon-wrap">
                <i class="fas fa-envelope"></i>
            </div>
            <h1 class="s26-page-title"><?= __('store.contact_us') ?? 'Get in Touch' ?></h1>
            <p class="s26-page-subtitle"><?= __('store.contact_subtitle') ?? 'We\'d love to hear from you. Send us a message and we\'ll respond as soon as possible.' ?></p>
        </div>

        <!-- Contact Info Cards -->
        <div class="row g-3 mb-5">
            <?php if (!empty($store_setting['contact_number'])): ?>
            <div class="col-md-4">
                <div class="s26-contact-info-card">
                    <div class="s26-contact-info-card__icon">
                        <i class="fas fa-phone-alt"></i>
                    </div>
                    <h5><?= __('store.phone') ?? 'Phone' ?></h5>
                    <p><?= $store_setting['contact_number'] ?></p>
                </div>
            </div>
            <?php endif; ?>
            <?php if (!empty($store_setting['email'])): ?>
            <div class="col-md-4">
                <div class="s26-contact-info-card">
                    <div class="s26-contact-info-card__icon">
                        <i class="fas fa-envelope"></i>
                    </div>
                    <h5><?= __('store.email') ?? 'Email' ?></h5>
                    <p><?= $store_setting['email'] ?></p>
                </div>
            </div>
            <?php endif; ?>
            <?php if (!empty($store_setting['address'])): ?>
            <div class="col-md-4">
                <div class="s26-contact-info-card">
                    <div class="s26-contact-info-card__icon">
                        <i class="fas fa-map-marker-alt"></i>
                    </div>
                    <h5><?= __('store.address') ?? 'Address' ?></h5>
                    <p><?= $store_setting['address'] ?></p>
                </div>
            </div>
            <?php endif; ?>
        </div>

        <div class="row g-4">
            <!-- Contact Form -->
            <div class="col-lg-6">
                <div class="s26-contact-form-card">
                    <h3 class="s26-contact-form-card__title">
                        <i class="fas fa-paper-plane"></i>
                        <?= __('store.send_us_a_message') ?? 'Send Us a Message' ?>
                    </h3>
                    <form class="form-horizontal" id="contact-form" action="" method="post">
                        <div class="row g-3">
                            <div class="col-md-6">
                                <div class="s26-form-group">
                                    <label class="s26-form-label"><?= __('store.your_name') ?? 'Your Name' ?></label>
                                    <input id="name" name="name" type="text" class="s26-form-input" placeholder="<?= __('store.your_name') ?? 'John Doe' ?>" value="<?= set_value('name') ?>" required>
                                    <?php if(!empty(form_error('name'))): ?>
                                        <span class="text-danger" style="font-size:12px"><?= form_error('name') ?></span>
                                    <?php endif; ?>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="s26-form-group">
                                    <label class="s26-form-label"><?= __('store.your_email') ?? 'Your Email' ?></label>
                                    <input id="email" name="email" type="email" class="s26-form-input" placeholder="<?= __('store.your_email') ?? 'john@example.com' ?>" value="<?= set_value('email') ?>" required>
                                    <?php if(!empty(form_error('email'))): ?>
                                        <span class="text-danger" style="font-size:12px"><?= form_error('email') ?></span>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>

                        <div class="s26-form-group">
                            <label class="s26-form-label"><?= __('store.your_phone') ?? 'Phone' ?></label>
                            <input id="phone" name="phone" type="text" class="s26-form-input" placeholder="<?= __('store.your_phone') ?? '+1 (555) 000-0000' ?>" value="<?= set_value('phone') ?>">
                            <?php if(!empty(form_error('phone'))): ?>
                                <span class="text-danger" style="font-size:12px"><?= form_error('phone') ?></span>
                            <?php endif; ?>
                        </div>

                        <div class="s26-form-group">
                            <label class="s26-form-label"><?= __('store.message') ?? 'Message' ?></label>
                            <textarea name="message" id="message" rows="5" class="s26-form-input" placeholder="<?= __('store.please_enter_your_message_here') ?? 'How can we help you?' ?>" required><?= set_value('message') ?></textarea>
                            <?php if(!empty(form_error('message'))): ?>
                                <span class="text-danger" style="font-size:12px"><?= form_error('message') ?></span>
                            <?php endif; ?>
                        </div>

                        <div class="s26-form-group">
                            <label class="s26-form-check">
                                <input type="checkbox" name="terms" value="1" checked required>
                                <span><?= __('store.i_agree_to') ?? 'I agree to the' ?>
                                    <a href="<?= isset($tnc_link) && $tnc_link ? $tnc_link : base_url('term-condition') ?>" target="_blank">
                                        <?= __('store.terms_n_conditions') ?? 'Terms & Conditions' ?>
                                    </a>
                                </span>
                            </label>
                            <?php if(!empty(form_error('terms'))): ?>
                                <span class="text-danger" style="font-size:12px"><?= __('store.please_check_terms') ?></span>
                            <?php endif; ?>
                        </div>

                        <?php if (!empty($googlerecaptcha['store_contact']) && !empty($googlerecaptcha['sitekey'])): ?>
                            <?php
                                $recaptcha_version = $googlerecaptcha['version'] ?? 'v2';
                                $sitekey = $googlerecaptcha['sitekey'];
                            ?>
                            <?php if ($recaptcha_version === 'v2'): ?>
                                <div class="s26-form-group">
                                    <script src="https://www.google.com/recaptcha/api.js" async defer></script>
                                    <div class="g-recaptcha" data-sitekey="<?= $sitekey ?>"></div>
                                </div>
                            <?php elseif ($recaptcha_version === 'v3'): ?>
                                <script src="https://www.google.com/recaptcha/api.js?render=<?= $sitekey ?>"></script>
                                <script>
                                    grecaptcha.ready(function() {
                                        grecaptcha.execute('<?= $sitekey ?>', {action: 'store_contact'}).then(function(token) {
                                            var input = document.getElementById('recaptcha_token_store_contact');
                                            if (input) input.value = token;
                                        });
                                    });
                                </script>
                                <input type="hidden" name="g-recaptcha-response" id="recaptcha_token_store_contact">
                            <?php endif; ?>
                        <?php endif; ?>

                        <button type="submit" class="s26-btn-primary w-100 justify-content-center">
                            <i class="fas fa-paper-plane"></i>
                            <?= __('store.submit') ?? 'Send Message' ?>
                        </button>
                    </form>
                </div>
            </div>

            <!-- Map -->
            <div class="col-lg-6">
                <div class="s26-contact-map">
                    <?php if(!empty($store_setting['contact_us_map'])): ?>
                        <?= $store_setting['contact_us_map'] ?>
                    <?php else: ?>
                        <div class="s26-contact-map__placeholder">
                            <i class="fas fa-map-marked-alt"></i>
                            <p><?= __('store.map_not_configured') ?? 'Map coming soon' ?></p>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>

    </div>
</section>