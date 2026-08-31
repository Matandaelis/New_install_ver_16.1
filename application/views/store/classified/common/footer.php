</div>
<?php
  $db =& get_instance();
  $products = $db->Product_model;
  $cookies_consent = $products->getSettings('site','cookies_consent');
  $cookies_consent_mesag = $products->getSettings('site', 'cookies_consent_mesag');
?>
<?php 
$storelogowidth='';
$storelogoheight=40;

  if($store_setting['store_custom_logo_size']==1)
  {
    $storelogowidth=$store_setting['store_logo_custom_width'];
    $storelogoheight=$store_setting['store_logo_custom_height'];
  }
?>
<?php
include __DIR__ . "/cookies_consent.php";
?>
<?php if(!empty($cookies_consent) && $cookies_consent['cookies_consent'] == 1){ ?>
<?php }?>

<footer class="footer bg-dark text-light mt-5" aff-section="classified_footer"></footer>
<script aff-template="classified_footer" type="text/html">
	<div class="container py-5">
		<div class="row g-4">
			<!-- Company Info Section -->
			<div class="col-lg-4 col-md-6">
				<div class="footer-brand mb-4">
					<a href="{{home_page_url}}" class="text-decoration-none d-inline-block mb-3">
						<?php  $logo = ($store_setting['logo']) ? base_url('assets/images/site/'.$store_setting['logo']) : base_url('assets/store/default/').'img/logo.png'; ?>
							<img 
							    src="<?= $logo ?>" 
							    onerror="this.src='<?= base_url('assets/store/default/img/logo.png') ?>';" 
							    height="<?= $storelogoheight ?>" 
							    <?= $storelogowidthstr ?> 
							    loading="lazy" 
							    class="img-fluid"
							    style="max-height: <?= $storelogoheight ?>px; height: auto;">
					</a>
				</div>

				<div class="footer-about">
					<p class="text-light-emphasis mb-3 lh-lg">{{about_content}}</p>
					<a href="{{aboutus_page_url}}" class="btn btn-outline-light btn-sm rounded-pill px-4">
						<i class="fas fa-arrow-right me-1"></i><?= __('store.read_more')?>
					</a>
				</div>
			</div>

			<!-- Recent Products Section -->
			{{#recent_products_available}}
			<div class="col-lg-4 col-md-6">
				<div class="footer-section">
					<h5 class="text-white fw-bold mb-4 border-bottom border-light border-opacity-25 pb-2">
						<i class="fas fa-clock me-2 text-primary"></i><?= __('store.recent_ads')?>
					</h5>

					<div class="recent-products-list">
						{{#recent_products}}
						<div class="d-flex align-items-start mb-3 p-2 rounded bg-light bg-opacity-10 hover-lift">
							<a href="{{product_details_url}}" class="text-decoration-none flex-shrink-0 me-3">
								<img alt="{{product_name}}" 
									 src="{{product_featured_image}}" 
									 class="rounded shadow-sm object-fit-cover" 
									 style="width: 60px; height: 60px;" 
									 loading="lazy" />
							</a>

							<div class="flex-grow-1 min-w-0">
								<a href="{{product_details_url}}" class="text-decoration-none">
									<h6 class="text-white fw-semibold mb-1 text-truncate">{{product_name}}</h6>
								</a>
								
								<div class="d-flex justify-content-between align-items-center">
									<small class="text-light-emphasis">
										<i class="fas fa-calendar-alt me-1"></i>{{product_created_date}}
									</small>
									<span class="badge bg-primary rounded-pill fw-semibold">{{product_price}}</span>
								</div>
							</div>
						</div>
						{{/recent_products}}
					</div>
				</div>
			</div>
			{{/recent_products_available}}

			<!-- Promotions Gallery Section -->
			<div class="col-lg-4 col-md-12">
				<div class="footer-section">
					<h5 class="text-white fw-bold mb-4 border-bottom border-light border-opacity-25 pb-2">
						<i class="fas fa-images me-2 text-success"></i><?= __('store.promotions')?>
					</h5>

					<div class="promotion-gallery">
						<div class="row g-2">
							<div class="col-4">
								<a href="#" class="d-block position-relative overflow-hidden rounded gallery-item">
									<img alt="<?= __('store.promotions')?>" 
										 src="<?= base_url('assets/store/classified/'); ?>media/listing/j3.jpg" 
										 class="img-fluid w-100 h-100 object-fit-cover hover-zoom" 
										 style="height: 80px;" 
										 loading="lazy" />
									<div class="gallery-overlay position-absolute top-0 start-0 w-100 h-100 d-flex align-items-center justify-content-center bg-dark bg-opacity-50 opacity-0">
										<i class="fas fa-search-plus text-white"></i>
									</div>
								</a>
							</div>
							<div class="col-4">
								<a href="#" class="d-block position-relative overflow-hidden rounded gallery-item">
									<img alt="<?= __('store.promotions')?>" 
										 src="<?= base_url('assets/store/classified/'); ?>media/listing/j4.jpg" 
										 class="img-fluid w-100 h-100 object-fit-cover hover-zoom" 
										 style="height: 80px;" 
										 loading="lazy" />
									<div class="gallery-overlay position-absolute top-0 start-0 w-100 h-100 d-flex align-items-center justify-content-center bg-dark bg-opacity-50 opacity-0">
										<i class="fas fa-search-plus text-white"></i>
									</div>
								</a>
							</div>
							<div class="col-4">
								<a href="#" class="d-block position-relative overflow-hidden rounded gallery-item">
									<img alt="<?= __('store.promotions')?>" 
										 src="<?= base_url('assets/store/classified/'); ?>media/listing/j5.jpg" 
										 class="img-fluid w-100 h-100 object-fit-cover hover-zoom" 
										 style="height: 80px;" 
										 loading="lazy" />
									<div class="gallery-overlay position-absolute top-0 start-0 w-100 h-100 d-flex align-items-center justify-content-center bg-dark bg-opacity-50 opacity-0">
										<i class="fas fa-search-plus text-white"></i>
									</div>
								</a>
							</div>
							<div class="col-4">
								<a href="#" class="d-block position-relative overflow-hidden rounded gallery-item">
									<img alt="<?= __('store.promotions')?>" 
										 src="<?= base_url('assets/store/classified/'); ?>media/listing/j6.jpg" 
										 class="img-fluid w-100 h-100 object-fit-cover hover-zoom" 
										 style="height: 80px;" 
										 loading="lazy" />
									<div class="gallery-overlay position-absolute top-0 start-0 w-100 h-100 d-flex align-items-center justify-content-center bg-dark bg-opacity-50 opacity-0">
										<i class="fas fa-search-plus text-white"></i>
									</div>
								</a>
							</div>
							<div class="col-4">
								<a href="#" class="d-block position-relative overflow-hidden rounded gallery-item">
									<img alt="<?= __('store.promotions')?>" 
										 src="<?= base_url('assets/store/classified/'); ?>media/listing/j7.jpg" 
										 class="img-fluid w-100 h-100 object-fit-cover hover-zoom" 
										 style="height: 80px;" 
										 loading="lazy" />
									<div class="gallery-overlay position-absolute top-0 start-0 w-100 h-100 d-flex align-items-center justify-content-center bg-dark bg-opacity-50 opacity-0">
										<i class="fas fa-search-plus text-white"></i>
									</div>
								</a>
							</div>
							<div class="col-4">
								<a href="#" class="d-block position-relative overflow-hidden rounded gallery-item">
									<img alt="<?= __('store.promotions')?>" 
										 src="<?= base_url('assets/store/classified/'); ?>media/listing/j8.jpg" 
										 class="img-fluid w-100 h-100 object-fit-cover hover-zoom" 
										 style="height: 80px;" 
										 loading="lazy" />
									<div class="gallery-overlay position-absolute top-0 start-0 w-100 h-100 d-flex align-items-center justify-content-center bg-dark bg-opacity-50 opacity-0">
										<i class="fas fa-search-plus text-white"></i>
									</div>
								</a>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>

	<!-- Footer Bottom -->
	<div class="footer-bottom bg-black py-4 border-top border-light border-opacity-10">
		<div class="container">
			<div class="row align-items-center g-3">
				<div class="col-lg-6 col-md-12 text-center text-lg-start">
					<div class="copyright-text text-light-emphasis">
						{{{privacy_and_copyrights}}}
					</div>
				</div>

				<div class="col-lg-6 col-md-12">
					<div class="payment-methods d-flex justify-content-center justify-content-lg-end align-items-center flex-wrap gap-3">
						{{#payment_gateways}}
						<div class="payment-item">
							<a href="javascript:void(0);" class="text-decoration-none" data-bs-toggle="tooltip" title="{{title}}">
								<img alt="{{title}}" 
									 src="{{icon}}" 
									 width="68" 
									 height="32" 
									 class="img-fluid rounded shadow-sm hover-lift-sm bg-white p-1" 
									 loading="lazy"
									 onerror="this.onerror=null;this.style.display='none'" />
							</a>
						</div>
						{{/payment_gateways}}
					</div>
				</div>
			</div>
		</div>
	</div>

	<style>
		/* Enhanced Footer Styles using minimal custom CSS */
		.filter-brightness {
			filter: brightness(1.2);
		}
		
		.hover-lift {
			transition: transform 0.3s ease, box-shadow 0.3s ease;
		}
		
		.hover-lift:hover {
			transform: translateY(-2px);
			box-shadow: 0 4px 15px rgba(0,0,0,0.2);
		}
		
		.hover-lift-sm:hover {
			transform: translateY(-1px);
		}
		
		.gallery-item:hover .gallery-overlay {
			opacity: 1 !important;
			transition: opacity 0.3s ease;
		}
		
		.hover-zoom {
			transition: transform 0.3s ease;
		}
		
		.gallery-item:hover .hover-zoom {
			transform: scale(1.1);
		}
		
		.object-fit-cover {
			object-fit: cover;
		}
		
		.min-w-0 {
			min-width: 0;
		}
		
		@media (max-width: 768px) {
			.footer-bottom .row > div {
				text-align: center !important;
			}
			
			.payment-methods {
				justify-content: center !important;
			}
		}
	</style>

</script>

<script src="<?= base_url('assets/store/classified/'); ?>assets/js/app.js"></script>

<?php if(isset($aff_item_id)) { ?>
	<input type="hidden" name="aff_item_id" value="<?php echo $aff_item_id; ?>">
<?php } ?>

<?php if(isset($aff_query)){ ?>
	<textarea name="aff_query_payload" style="display:none"><?php echo json_encode($aff_query); ?></textarea>
<?php } ?>

<script> 
	const BASE_URL = "<?= base_url(); ?>";
	const mobile_number_errors = ['<?= __('store.invalid_number') ?>','<?= __('store.invalid_country_code') ?>','<?= __('store.too_short') ?>','<?= __('store.too_long') ?>','<?= __('store.invalid_number') ?>', '<?= __('store.mobile_number_is_required') ?>'];
	
	// Initialize Bootstrap tooltips for payment gateways
	document.addEventListener('DOMContentLoaded', function() {
		var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
		var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
			return new bootstrap.Tooltip(tooltipTriggerEl);
		});
	});
</script>

<script src="<?= base_url('assets/plugins/') ?>mustache.js"></script>
<script src="<?= base_url('assets/store/') ?>affclassifiedstore.js"></script>

</body>
</html>