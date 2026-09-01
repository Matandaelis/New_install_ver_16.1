<?php
/**
 * Default theme — Contact page
 *
 * @contract  Store API v1 — page: contact
 * @see       Store_cart_payload::page_contact()
 * @see       /store/api/v1/pages/contact
 *
 * GLOBALS (always available via Storeapp::view)
 *   $store_setting   array   All store settings key-value map
 *   $client          array   Logged-in customer array; empty array if guest
 *   $home_link       string  Absolute URL to store homepage
 *
 * PAGE VARIABLES
 *   $contact_content   string  Contact page body / instructions (HTML)
 *   $contact_email     string  Store contact email address
 *   $contact_phone     string  Store contact phone number
 *   $contact_address   string  Store physical address
 *   $contact_url       string  Form action URL
 *   $googlerecaptcha   string  Google reCAPTCHA site key (empty if disabled)
 */
?>
<section class="amz-contact">
    <div class="container">
        <h1 class="amz-contact__title"><?= __('store.contact_us') ?></h1>

        <?php if(!empty($content['contact_content'])): ?>
            <div class="amz-contact__intro"><?= $content['contact_content'] ?></div>
        <?php endif; ?>

        <div class="amz-contact__grid">
            <!-- Contact Form -->
            <div class="amz-contact__form">
                <h2 class="amz-section-title"><?= __('store.get_in_touch') ?: 'Send us a message' ?></h2>
                <form class="amz-form" action="" method="post">
                    <div class="amz-form__row">
                        <div class="amz-form__group">
                            <label class="amz-form__label" for="name"><?= __('store.your_name') ?></label>
                            <input id="name" value="<?= set_value('name') ?>" name="name" type="text" class="amz-form__input" required>
                            <?php if(!empty(form_error('name'))): ?>
                                <span class="amz-form__error-text"><?= form_error('name') ?></span>
                            <?php endif; ?>
                        </div>
                        <div class="amz-form__group">
                            <label class="amz-form__label" for="email"><?= __('store.your_email') ?></label>
                            <input id="email" value="<?= set_value('email') ?>" name="email" type="email" class="amz-form__input" required>
                            <?php if(!empty(form_error('email'))): ?>
                                <span class="amz-form__error-text"><?= form_error('email') ?></span>
                            <?php endif; ?>
                        </div>
                    </div>
                    <div class="amz-form__group">
                        <label class="amz-form__label" for="phone"><?= __('store.your_phone') ?></label>
                        <input id="phone" value="<?= set_value('phone') ?>" name="phone" type="text" class="amz-form__input">
                        <?php if(!empty(form_error('phone'))): ?>
                            <span class="amz-form__error-text"><?= form_error('phone') ?></span>
                        <?php endif; ?>
                    </div>
                    <div class="amz-form__group">
                        <label class="amz-form__label" for="message"><?= __('store.please_enter_your_message_here') ?></label>
                        <textarea class="amz-form__input amz-form__textarea" id="message" name="message" rows="5" required><?= set_value('message') ?></textarea>
                        <?php if(!empty(form_error('message'))): ?>
                            <span class="amz-form__error-text"><?= form_error('message') ?></span>
                        <?php endif; ?>
                    </div>
                    <div class="amz-form__group amz-form__checkbox">
                        <input type="checkbox" name="terms" value="1" id="terms" checked>
                        <label for="terms">
                            <a href="<?= $tnc_link ? $tnc_link : base_url('term-condition') ?>" target="_blank" class="amz-link">
                                <?= __('store.terms_n_conditions') ?>
                            </a>
                        </label>
                        <?php if(!empty(form_error('terms'))): ?>
                            <span class="amz-form__error-text"><?= __('store.please_check_terms') ?></span>
                        <?php endif; ?>
                    </div>

                    <?php if (!empty($googlerecaptcha['store_contact']) && !empty($googlerecaptcha['sitekey'])): ?>
                        <?php
                            $recaptcha_version = $googlerecaptcha['version'] ?? 'v2';
                            $sitekey = $googlerecaptcha['sitekey'];
                        ?>
                        <?php if ($recaptcha_version === 'v2'): ?>
                            <div class="amz-form__group">
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

                    <div class="amz-form__group">
                        <button type="submit" class="amz-btn amz-btn-primary"><?= __('store.submit') ?></button>
                    </div>
                </form>
            </div>

            <!-- Contact Info -->
            <div class="amz-contact__info">
                <h2 class="amz-section-title"><?= __('store.contact_info') ?></h2>
                <div class="amz-contact__details">
                    <?php
                        $store_contactimage = ($storesettings['contactimage'])
                            ? base_url('assets/images/site/'.$storesettings['contactimage'])
                            : '';
                    ?>
                    <?php if($store_contactimage): ?>
                        <img src="<?= $store_contactimage ?>" class="amz-contact__image" alt="<?= __('store.contact_us') ?>">
                    <?php endif; ?>

                    <div class="amz-contact__detail">
                        <i class="fas fa-phone" aria-hidden="true"></i>
                        <div>
                            <strong><?= __('store.phone') ?></strong>
                            <span><?= !empty($storesettings['contact_number']) ? $storesettings['contact_number'] : '+90 555 555 5555' ?></span>
                        </div>
                    </div>
                    <div class="amz-contact__detail">
                        <i class="fas fa-envelope" aria-hidden="true"></i>
                        <div>
                            <strong><?= __('store.email') ?></strong>
                            <span><?= !empty($storesettings['email']) ? $storesettings['email'] : 'lorem@lorem.com' ?></span>
                        </div>
                    </div>
                    <div class="amz-contact__detail">
                        <i class="fas fa-map-marker-alt" aria-hidden="true"></i>
                        <div>
                            <strong><?= __('store.address') ?></strong>
                            <span><?= !empty($storesettings['address']) ? $storesettings['address'] : 'Keas 69 Str. 15234, Chalandri Athens, Greece' ?></span>
                        </div>
                    </div>
                </div>

                <?php if(!empty($storesettings['contact_us_map'])): ?>
                <div class="amz-contact__map">
                    <?= $storesettings['contact_us_map'] ?>
                </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</section>
