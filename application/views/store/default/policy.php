<?php
/**
 * Default theme — Store Policy page (privacy, terms, etc.)
 *
 * @contract  Store API v1 — page: policy
 * @see       Store_cart_payload::page_policy()
 * @see       /store/api/v1/pages/policy
 *
 * GLOBALS (always available via Storeapp::view)
 *   $store_setting  array   All store settings key-value map
 *   $client         array   Logged-in customer array; empty array if guest
 *   $home_link      string  Absolute URL to store homepage
 *
 * PAGE VARIABLES
 *   $policy_content  string  Policy body HTML (rich text from admin)
 *   $policy_title    string  Page title/heading text
 *   $policy_image    string  Hero image URL (empty if not set)
 */
?>
<section class="about-page">
  <div class="container">
     <div class="row">
	   <div class="col-12 col-md-12 col-lg-4 col-xl-6">
	   	<?php 
	   		$policyimage = $store_setting['policyimage'] ? base_url('assets/images/site/'. $store_setting['policyimage']) : base_url('assets/store/default/img/about-img.png');
	   		?>
	     <img src="<?=$policyimage;?>" class="img-fluid img-about-main mt-4" alt="<?= __('store.image') ?>">
	   </div>
	   <div class="col-12 col-md-12 col-lg-8 col-xl-6">
	      <div class="about-top-text">
		    <h2><?= __('store.privacy_policy') ?></h2>
			<img src="<?= base_url('assets/store/default/'); ?>img/popline.png" class="cn-titlebar mx-0"  alt="<?= __('store.image') ?>">
			<?= !empty($content['policy_content']) ? $content['policy_content'] : __('store.privacy_if_not_exist'); ?>
			<a href="<?= $base_url ?>contact"><?= __('store.contact_us') ?></a>
		  </div>
	   </div>
	 </div> 
  </div>
</section>