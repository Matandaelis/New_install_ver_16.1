<?php
/**
 * Default theme — About page
 *
 * @contract  Store API v1 — page: about
 * @see       Store_cart_payload::page_about()
 * @see       /store/api/v1/pages/about
 *
 * GLOBALS (always available via Storeapp::view)
 *   $store_setting  array   All store settings key-value map
 *   $client         array   Logged-in customer array; empty array if guest
 *   $home_link      string  Absolute URL to store homepage
 *
 * PAGE VARIABLES
 *   $about_content  string  Rich-text about page body (HTML)
 *   $about_image    string  URL to about page hero image (empty if not set)
 *   $about_title    string  Page heading text
 */
?>
<section class="amz-about">
    <div class="container">
        <h1 class="amz-about__title"><?= $about_title ?: __('store.about_us') ?></h1>
        <div class="amz-about__grid">
            <div class="amz-about__image">
                <?php
                    $aboutimage = $store_setting['aboutimage']
                        ? base_url('assets/images/site/'.$store_setting['aboutimage'])
                        : base_url('assets/store/default/img/about-img.png');
                ?>
                <img src="<?= $aboutimage ?>" alt="<?= __('store.about_us') ?>" loading="lazy">
            </div>
            <div class="amz-about__content">
                <?= !empty($content['about_content']) ? $content['about_content'] : __('store.about_us_if_not_exist') ?>
                <a href="<?= $base_url ?>contact" class="amz-btn amz-btn-details"><?= __('store.contact_us') ?></a>
            </div>
        </div>
    </div>
</section>
