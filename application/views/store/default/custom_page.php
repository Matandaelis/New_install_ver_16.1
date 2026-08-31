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
<section class="about-page">
  <div class="container">
     <div class="row">
	   <div class="col-12 col-md-12 col-lg-4 col-xl-6">
        <?php $img = (!empty($data->image)) ? base_url('assets/images/site/'. $data->image) : base_url('assets/store/default/img/about-img.png'); ?>
        <img src="<?= $img ?>" class="img-fluid img-about-main mt-4" alt="<?= __('store.image') ?>">
	    </div>
	   <div class="col-12 col-md-12 col-lg-8 col-xl-6">
	      <div class="about-top-text">
		    <h2><?= $data->title ?></h2>
			<img src="<?= base_url('assets/store/default/'); ?>img/popline.png" class="cn-titlebar mx-0"  alt="<?= __('store.image') ?>">
			<?= $data->content ?>
		  </div>
	   </div>
	 </div> 
  </div>
</section>