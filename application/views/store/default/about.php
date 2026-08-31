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
<section class="about-page">
  <div class="container">
     <div class="row">
	   <div class="col-12 col-md-12 col-lg-4 col-xl-6">
	   	<?php 
	   		$aboutimage = $store_setting['aboutimage'] ? base_url('assets/images/site/'. $store_setting['aboutimage']) : base_url('assets/store/default/img/about-img.png');
	   		?>

	     <img src="<?=$aboutimage;?>" class="img-fluid img-about-main mt-4" alt="<?= __('store.image') ?>">
	   </div>
	   <div class="col-12 col-md-12 col-lg-8 col-xl-6">
	      <div class="about-top-text">
		    <h2><?= __('store.about_us') ?></h2>
			<img src="<?= base_url('assets/store/default/'); ?>img/popline.png" class="cn-titlebar mx-0"  alt="<?= __('store.image') ?>">
			<?= !empty($content['about_content']) ? $content['about_content'] : __('store.about_us_if_not_exist'); ?>
			<a href="<?= $base_url ?>contact"><?= __('store.contact_us') ?></a>
		  </div>
	   </div>
	 </div> 
  </div>
</section>