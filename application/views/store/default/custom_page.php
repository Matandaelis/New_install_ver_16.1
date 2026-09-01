<?php
/**
 * Default theme — Custom static page
 *
 * @contract  Store API v1 — page: custom_page
 * @see       Store_cart_payload::page_custom_page()
 * @see       /store/api/v1/pages/custom_page  (requires ?slug=PAGE_SLUG)
 *
 * GLOBALS (always available via Storeapp::view)
 *   $store_setting  array   All store settings key-value map
 *   $client         array   Logged-in customer array; empty array if guest
 *   $home_link      string  Absolute URL to store homepage
 *
 * PAGE VARIABLES
 *   $data  object  Custom page record {id, title, content, image, slug, created_at}
 */
?>
<section class="amz-about about-page">
  <div class="container">
     <h1 class="amz-about__title"><?= $data->title ?></h1>
     <div class="amz-about__grid row">
	   <div class="amz-about__image col-12 col-md-12 col-lg-4 col-xl-6">
        <?php $img = (!empty($data->image)) ? base_url('assets/images/site/'. $data->image) : base_url('assets/store/default/img/about-img.png'); ?>
        <img src="<?= $img ?>" class="img-fluid img-about-main mt-4" alt="<?= htmlspecialchars($data->title) ?>" loading="lazy">
	    </div>
	   <div class="amz-about__content col-12 col-md-12 col-lg-8 col-xl-6">
			<?= $data->content ?>
	   </div>
	 </div>
  </div>
</section>