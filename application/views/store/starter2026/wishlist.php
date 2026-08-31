<?php
/**
 * Starter 2026 — Customer Wishlist Page
 *
 * @contract  Store API v1 — page: wishlist
 * @auth      required
 *
 * GLOBALS  $store_setting, $client, $home_link, $base_url, $add_tocart_url
 *
 * PAGE VARIABLES
 *   $products     array   Wishlisted products [{product_id, product_name, product_price, product_featured_image, slug, ...}]
 *   $user_id      int     Affiliate user ID
 */
?>

<div class="container">
    <nav class="s26-breadcrumb" aria-label="Breadcrumb">
        <a href="<?= $home_link ?? $base_url ?>"><?= __('store.home') ?? 'Home' ?></a>
        <span class="separator"><i class="fas fa-chevron-right"></i></span>
        <span class="current"><?= __('store.wishlist') ?? 'Wishlist' ?></span>
    </nav>
</div>

<!-- Account Sub-Navigation -->
<div class="container">
    <div class="s26-account-nav">
        <a href="<?= $base_url ?>profile" class="s26-account-nav__link">
            <i class="fas fa-user"></i> <?= __('store.profile') ?? 'Profile' ?>
        </a>
        <a href="<?= $base_url ?>order" class="s26-account-nav__link">
            <i class="fas fa-gift"></i> <?= __('store.orders') ?? 'Orders' ?>
        </a>
        <a href="<?= $base_url ?>my_courses" class="s26-account-nav__link">
            <i class="fas fa-graduation-cap"></i> <?= __('store.my_courses') ?? 'My Courses' ?>
        </a>
        <a href="<?= $base_url ?>shipping" class="s26-account-nav__link">
            <i class="fas fa-truck"></i> <?= __('store.shipping') ?? 'Shipping' ?>
        </a>
        <a href="<?= $base_url ?>wishlist" class="s26-account-nav__link active">
            <i class="fas fa-heart"></i> <?= __('store.wishlist') ?? 'Wishlist' ?>
        </a>
        <a href="<?= $base_url ?>logout" class="s26-account-nav__link s26-account-nav__link--danger">
            <i class="fas fa-power-off"></i> <?= __('store.logout') ?? 'Logout' ?>
        </a>
    </div>
</div>

<?php
$_s26_wish_count = (isset($products) && is_array($products)) ? count($products) : 0;
$s26hdr_icon    = 'fas fa-heart';
$s26hdr_eyebrow = __('store.home') . ' &rsaquo; ' . __('store.wishlist');
$s26hdr_title   = __('store.wishlist');
$s26hdr_sub     = __('store.wishlist');
$s26hdr_stats   = [
    ['val' => $_s26_wish_count, 'lbl' => __('store.wishlist'), 'color' => '#f87171'],
];
include(APPPATH.'views/store/starter2026/_account_header.php');
?>

<section class="s26-wishlist-page">
    <div class="container">

        <?php if(isset($products) && sizeof($products) > 0): ?>

        <div class="row g-3">
            <?php foreach($products as $product):
                $href = base_url("store/". base64_encode($user_id) . "/product/". $product['product_slug']);
                $image = (!empty($product['product_featured_image']))
                    ? base_url('assets/images/product/upload/thumb/'. $product['product_featured_image'])
                    : base_url('assets/store/default/img/pr-img.png');
            ?>
            <div class="col-6 col-md-4 col-lg-3">
                <div class="s26-wishlist-card">
                    <div class="s26-wishlist-card__img">
                        <a href="<?= $href ?>">
                            <img src="<?= $image ?>" alt="<?= htmlspecialchars($product['product_name']) ?>"
                                 onerror="this.src='<?= base_url('assets/store/default/img/pr-img.png') ?>'">
                        </a>
                        <button type="button" class="s26-wishlist-remove-btn btn-remove-wishlist"
                                data-product_id="<?= $product['product_id'] ?>"
                                title="<?= __('store.remove') ?? 'Remove' ?>">
                            <i class="fas fa-times"></i>
                        </button>
                    </div>
                    <div class="s26-wishlist-card__body">
                        <a href="<?= $href ?>" class="s26-wishlist-card__name">
                            <?= htmlspecialchars($product['product_name']) ?>
                        </a>
                        <?php if(!empty($product['product_price'])): ?>
                        <span class="s26-wishlist-card__price">
                            <?= c_format($product['product_price']) ?>
                        </span>
                        <?php endif; ?>
                        <div class="s26-wishlist-card__actions mt-2">
                            <a href="<?= $href ?>" class="s26-btn-primary s26-btn--sm w-100 justify-content-center">
                                <i class="fas fa-shopping-bag"></i>
                                <?= __('store.view_product') ?? 'View Product' ?>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
            <?php endforeach; ?>
        </div>

        <?php else: ?>

        <!-- Empty Wishlist -->
        <div class="s26-empty-state">
            <div class="s26-empty-state__icon">
                <i class="fas fa-heart"></i>
            </div>
            <h2 class="s26-empty-state__title"><?= __('store.no_wishlisted_products_available') ?? 'Your wishlist is empty' ?></h2>
            <p class="s26-empty-state__text"><?= __('store.wishlist_empty_desc') ?? 'Start adding products you love to your wishlist.' ?></p>
            <a href="<?= $base_url ?>category" class="s26-btn-primary">
                <i class="fas fa-shopping-bag"></i>
                <?= __('store.continue_shopping') ?? 'Browse Products' ?>
            </a>
        </div>

        <?php endif; ?>

    </div>
</section>

<script>
$(document).on('click', '.btn-remove-wishlist', function(){
    var $card = $(this).closest('.col-6, .col-md-4, .col-lg-3');
    $.ajax({
        url: '<?= base_url('Store/toggle_wishlist') ?>',
        type: 'POST',
        dataType: 'json',
        data: { product_id: $(this).data('product_id') },
        success: function(json){
            $card.fadeOut(300, function(){ $(this).remove(); });
        },
    });
});
</script>
