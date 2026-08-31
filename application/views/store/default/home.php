<?php
/**
 * Default theme — Home page (Amazon wireframe)
 *
 * @contract  Store API v1 — page: home
 * @see       Store_cart_payload::page_home()
 * @see       /store/api/v1/pages/home
 *
 * GLOBALS (always available via Storeapp::view)
 *   $store_setting  array   All store settings key-value map
 *   $client         array   Logged-in customer array; empty array if guest
 *   $home_link      string  Absolute URL to store homepage
 *   $settings       array   Alias of $store_setting
 *
 * PAGE VARIABLES
 *   $sliders          array   Homepage slider images [{id, image, title, sub, link}, ...]
 *   $featured         array   Featured products list [{id, name, price, image, link, ...}, ...]
 *   $categories       array   Root category list [{id, name, image, link, ...}, ...]
 *   $new_products     array   Latest/newest products
 *   $store_info       array   Store name, description, logo etc.
 *   $best_sellers     array   Best-selling products (optional, may be empty)
 *   $special_offers   array   Products on sale / with discount codes (optional)
 */
?>

<?php if (($store_setting['homepage_slider_enabled'] ?? '1') !== '0'): ?>
<section class="amz-hero-banner">
	<div id="amzHeroCarousel" class="carousel slide" data-bs-ride="carousel" data-bs-interval="6000">
		<div class="carousel-inner">
			<?php
			$homepage_slider = !empty($store_setting['homepage_slider']) ? json_decode($store_setting['homepage_slider']) : [];
			$homepage_slider = is_array($homepage_slider) ? $homepage_slider : [];
			for ($i = 0; $i < count($homepage_slider); $i++):
				$homepage_slider_available = true;
				$slider_bg = !empty($homepage_slider[$i]->slider_background_image)
					? base_url('assets/images/site/' . $homepage_slider[$i]->slider_background_image)
					: base_url('assets/store/default/img/banner.png');
			?>
			<div class="carousel-item <?= ($i == 0) ? 'active' : '' ?>">
				<div class="amz-hero-slide" style="background-image:url(<?= $slider_bg ?>)">
					<div class="amz-hero-overlay"></div>
					<div class="amz-hero-content">
						<?php if (!empty($homepage_slider[$i]->title)): ?>
							<h1 class="amz-hero-title"><?= htmlspecialchars($homepage_slider[$i]->title, ENT_QUOTES) ?></h1>
						<?php endif; ?>
						<?php if (!empty($homepage_slider[$i]->sub_title)): ?>
							<p class="amz-hero-subtitle"><?= htmlspecialchars($homepage_slider[$i]->sub_title, ENT_QUOTES) ?></p>
						<?php endif; ?>
						<?php if (!empty($homepage_slider[$i]->content)): ?>
							<p class="amz-hero-desc"><?= htmlspecialchars($homepage_slider[$i]->content, ENT_QUOTES) ?></p>
						<?php endif; ?>
						<?php if (!empty($homepage_slider[$i]->button_text)): ?>
							<a href="<?= $homepage_slider[$i]->button_link ?>" class="amz-hero-cta">
								<?= htmlspecialchars($homepage_slider[$i]->button_text) ?> <i class="fas fa-angle-right"></i>
							</a>
						<?php endif; ?>
					</div>
				</div>
			</div>
			<?php endfor; ?>

			<?php if (!isset($homepage_slider_available)): ?>
			<div class="carousel-item active">
				<div class="amz-hero-slide" style="background-image:url(<?= base_url('assets/store/default/img/demo-slide-1.jpg') ?>)">
					<div class="amz-hero-overlay"></div>
					<div class="amz-hero-content">
						<h1 class="amz-hero-title"><?= __('store.welcome_to_store') ?></h1>
						<p class="amz-hero-subtitle"><?= __('store.new_collection') ?></p>
						<p class="amz-hero-desc"><?= __('store.curated_collection_desc') ?></p>
						<a href="<?= base_url('store/category') ?>" class="amz-hero-cta"><?= __('store.shop_now') ?> <i class="fas fa-angle-right"></i></a>
					</div>
				</div>
			</div>
			<?php endif; ?>
		</div>
		<button class="amz-hero-arrow amz-hero-prev" type="button" data-bs-target="#amzHeroCarousel" data-bs-slide="prev">
			<i class="fas fa-chevron-left"></i>
		</button>
		<button class="amz-hero-arrow amz-hero-next" type="button" data-bs-target="#amzHeroCarousel" data-bs-slide="next">
			<i class="fas fa-chevron-right"></i>
		</button>
	</div>
</section>
<?php endif; ?>

<section class="amz-category-strip">
	<div class="container">
		<div class="amz-category-scroll">
			<?php if (!empty($category_tags)): ?>
				<?php foreach ($category_tags as $tag): ?>
					<a href="<?= base_url('store/category/' . $tag['slug']) ?>" class="amz-cat-chip"><?= htmlspecialchars($tag['name']) ?></a>
				<?php endforeach; ?>
			<?php else: ?>
				<a href="#" class="amz-cat-chip"><?= __('store.demo_tag_electronics') ?></a>
				<a href="#" class="amz-cat-chip"><?= __('store.demo_tag_fashion') ?></a>
				<a href="#" class="amz-cat-chip"><?= __('store.demo_tag_home') ?></a>
			<?php endif; ?>
			<a href="<?= $base_url ?>category" class="amz-cat-chip amz-cat-all"><i class="fas fa-th-large me-1"></i> <?= __('store.see_all_categories') ?></a>
		</div>
	</div>
</section>

<section class="amz-section">
	<div class="container">
		<div class="amz-section-header">
			<h2 class="amz-section-title"><?= __('store.trending_products') ?></h2>
			<div class="amz-inline-search">
				<input id="searchProduct" type="text" class="amz-inline-search-input" placeholder="<?= __('store.search') ?>...">
				<i class="fas fa-search"></i>
			</div>
		</div>
		<div class="amz-product-grid product-list-trending"></div>
		<a href="javascript:void(0);" class="amz-show-more see-more-trendings" data-next_page="1" data-request_page_section="trending">
			<?= __('store.show_more') ?> <i class="fas fa-chevron-down"></i>
		</a>
	</div>
</section>

<?php if (($store_setting['homepage_features_enabled'] ?? '1') !== '0'): ?>
<?php
$homepage_features = (isset($store_setting['homepage_features']) && !empty($store_setting['homepage_features'])) ? json_decode($store_setting['homepage_features']) : [];
?>
<section class="amz-features-strip">
	<div class="container">
		<div class="amz-features-grid">
			<?php foreach ($homepage_features as $hf):
				$feat_img = !empty($hf->feature_image) ? base_url('assets/images/site/' . $hf->feature_image) : base_url('assets/store/default/img/stats1.png');
			?>
			<div class="amz-feature-card">
				<div class="amz-feature-icon">
					<img src="<?= $feat_img ?>" alt="">
				</div>
				<div class="amz-feature-text">
					<h4><?= $hf->title ?></h4>
					<p><?= $hf->sub_title ?></p>
				</div>
			</div>
			<?php endforeach; ?>

			<?php if (empty($homepage_features)): ?>
			<div class="amz-feature-card">
				<div class="amz-feature-icon"><i class="fas fa-truck"></i></div>
				<div class="amz-feature-text">
					<h4><?= __('store.free_shipping') ?></h4>
					<p><?= __('store.free_shipping_all_order') ?></p>
				</div>
			</div>
			<div class="amz-feature-card">
				<div class="amz-feature-icon"><i class="fas fa-undo"></i></div>
				<div class="amz-feature-text">
					<h4><?= __('store.100_money_guarantee') ?></h4>
					<p><?= __('store.30_days_money_back') ?></p>
				</div>
			</div>
			<div class="amz-feature-card">
				<div class="amz-feature-icon"><i class="fas fa-headset"></i></div>
				<div class="amz-feature-text">
					<h4><?= __('store.help_center') ?></h4>
					<p><?= __('store.24_7_support_system') ?></p>
				</div>
			</div>
			<div class="amz-feature-card">
				<div class="amz-feature-icon"><i class="fas fa-lock"></i></div>
				<div class="amz-feature-text">
					<h4><?= __('store.payment_method') ?></h4>
					<p><?= __('store.secure_payment') ?></p>
				</div>
			</div>
			<?php endif; ?>
		</div>
	</div>
</section>
<?php endif; ?>

<section class="amz-section">
	<div class="container">
		<div class="amz-section-header">
			<h2 class="amz-section-title"><?= __('store.new_products') ?></h2>
		</div>
		<div class="amz-product-grid product-list-new"></div>
		<a href="javascript:void(0);" class="amz-show-more see-more-new" data-next_page="1" data-request_page_section="new">
			<?= __('store.show_more') ?> <i class="fas fa-chevron-down"></i>
		</a>
	</div>
</section>

<?php if (($store_setting['homepage_banner_enabled'] ?? '1') !== '0'): ?>
<section class="amz-promo-banner">
	<div class="container">
		<?php
		$banner_img = !empty($store_setting['hbanimage'])
			? base_url('assets/images/site/' . $store_setting['hbanimage'])
			: base_url('assets/store/default/img/demo-banner-ad.jpg');
		$homepage_banner = isset($store_setting['homepage_banner']) ? json_decode($store_setting['homepage_banner']) : (object)[];
		?>
		<div class="amz-promo-card" style="background-image:url(<?= $banner_img ?>)">
			<div class="amz-promo-overlay"></div>
			<div class="amz-promo-content">
				<h3><?= !empty($homepage_banner->title) ? $homepage_banner->title : __('store.demo_banner_title') ?></h3>
				<p><?= !empty($homepage_banner->content) ? $homepage_banner->content : __('store.demo_banner_content') ?></p>
				<a href="<?= !empty($homepage_banner->button_link) ? $homepage_banner->button_link : base_url('store/category') ?>" class="amz-hero-cta">
					<?= !empty($homepage_banner->button_text) ? $homepage_banner->button_text : __('store.demo_banner_btn') ?> <i class="fas fa-angle-right"></i>
				</a>
			</div>
		</div>
	</div>
</section>
<?php endif; ?>

<?php if (!empty($category)): ?>
<section class="amz-section">
	<div class="container">
		<h2 class="amz-section-title"><?= __('store.categories') ?></h2>
		<div class="amz-category-grid">
			<?php foreach ($category as $cat_value):
				$cat_img = !empty($cat_value['image'])
					? base_url('assets/images/product/upload/thumb/' . $cat_value['image'])
					: base_url('assets/images/no_image_available.png');
			?>
			<a href="<?= base_url('store/category/' . $cat_value['slug']) ?>" class="amz-category-tile">
				<div class="amz-category-tile-img">
					<img src="<?= $cat_img ?>" alt="<?= htmlspecialchars($cat_value['name']) ?>">
				</div>
				<span class="amz-category-tile-name"><?= htmlspecialchars($cat_value['name']) ?></span>
			</a>
			<?php endforeach; ?>
		</div>
	</div>
</section>
<?php endif; ?>

<?php
$bs_cards = (isset($store_setting['bs_cards']) && !empty($store_setting['bs_cards'])) ? json_decode($store_setting['bs_cards']) : [];
if (!empty($bs_cards)):
?>
<section class="amz-section">
	<div class="container">
		<div class="amz-promo-grid">
			<?php foreach ($bs_cards as $hf):
				$card_link = !empty($hf->button_link) ? $hf->button_link : '#';
				$card_img = !empty($hf->feature_image) ? base_url('assets/images/site/' . $hf->feature_image) : base_url('assets/store/default/img/blog1.png');
			?>
			<a href="<?= $card_link ?>" class="amz-promo-tile <?= $hf->link_target === 'true' ? 'target="_blank"' : '' ?>" <?= !empty($hf->bg_color) ? 'style="background-color:' . $hf->bg_color . '"' : '' ?>>
				<img src="<?= $card_img ?>" alt="<?= htmlspecialchars($hf->title) ?>" class="amz-promo-tile-img">
				<div class="amz-promo-tile-content">
					<h4><?= $hf->title ?></h4>
					<p><?= $hf->sub_title ?></p>
				</div>
			</a>
			<?php endforeach; ?>
		</div>

		<?php
		$para = isset($store_setting['homepage_bottom_section']) ? json_decode($store_setting['homepage_bottom_section']) : '';
		$para = isset($para->content) ? $para->content : '';
		?>
		<?php if (!empty(strip_tags($para))): ?>
		<div class="amz-bottom-text">
			<div class="amz-bottom-text-inner">
				<?= $para ?>
			</div>
			<a href="javascript:void(0);" class="amz-show-more blog-more"><?= __('store.show_more') ?> <i class="fas fa-chevron-down"></i></a>
		</div>
		<?php endif; ?>
	</div>
</section>
<?php endif; ?>

<?php include 'product-list-template.php'; ?>

<script type="text/javascript">
$(document).on('click', '.blog-more', function(){
	var el = $(".amz-bottom-text-inner"),
	curHeight = el.height(),
	autoHeight = el.css('height', 'auto').height();
	el.height(curHeight).animate({height: autoHeight}, 500);
	$(this).after('<a href="javascript:void(0);" class="amz-show-more blog-less"><?= __('store.hide') ?> <i class="fas fa-chevron-up"></i></a>');
	$(this).remove();
});

$(document).on('click', '.blog-less', function(){
	var el = $(".amz-bottom-text-inner");
	el.animate({height: '60px'}, 500);
	$(this).after('<a href="javascript:void(0);" class="amz-show-more blog-more"><?= __('store.show_more') ?> <i class="fas fa-chevron-down"></i></a>');
	$(this).remove();
});

$(document).ready(function() {
	load_Product($('#searchProduct').val());
	$('#searchProduct').keyup(function(e) {
		e.preventDefault();
		load_Product($(this).val());
	});
});

$(document).on('click', '.see-more', function() {
	load_Product(null, {
		next_page: $(this).data('next_page'),
		request_page_section: $(this).data('request_page_section')
	});
});

function load_Product(search, postData) {
	var data = postData || {};
	data.search = search;
	data.request_page = 'home';
	var ajaxReq = $.ajax({
		url: "<?= base_url() ?>" + 'Store/load_Product',
		type: 'POST',
		dataType: 'JSON',
		data: data,
		beforeSend: function() {
			if (ajaxReq !== 'ToCancelPrevReq' && ajaxReq.readyState < 4) ajaxReq.abort();
		},
		success: function(res) {
			if (res.trendings) {
				if (postData && postData.next_page && postData.next_page > 1) {
					$('.product-list-trending').append(Mustache.render($('#product-list-template').html(), res.trendings));
				} else {
					$('.product-list-trending').html(Mustache.render($('#product-list-template').html(), res.trendings));
				}
				$('.see-more-trendings').data('next_page', res.trendings.next_page);
				if (res.trendings.is_last_page) $('.see-more-trendings').hide();
			}
			if (res.new) {
				if (postData && postData.next_page && postData.next_page > 1) {
					$('.product-list-new').append(Mustache.render($('#product-list-template').html(), res.new));
				} else {
					$('.product-list-new').html(Mustache.render($('#product-list-template').html(), res.new));
				}
				$('.see-more-new').data('next_page', res.new.next_page);
				if (res.new.is_last_page) $('.see-more-new').hide();
			}
			if (res.category && res.category.new && res.category.new.length) {
				$('.amz-category-scroll').html(res.category.new);
			}
			if (res.category && res.category.all && res.category.all.length) {
				$('.amz-cat-chip').show();
			}
		}
	});
}
</script>
