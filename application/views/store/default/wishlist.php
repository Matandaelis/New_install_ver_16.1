<?php
/**
 * Default theme — Wishlist page
 *
 * @contract  Store API v1 — page: wishlist
 * @see       Store_cart_payload::page_wishlist()
 * @see       /store/api/v1/pages/wishlist  (auth required)
 *
 * GLOBALS (always available via Storeapp::view)
 *   $store_setting  array   All store settings key-value map
 *   $client         array   Logged-in customer data
 *   $home_link      string  Absolute URL to store homepage
 *
 * PAGE VARIABLES
 *   $products      array   Wishlisted products [{id, name, price, old_price, image, link, ...}, ...]
 *   $wishlist_url  string  URL for wishlist add/remove AJAX calls
 *   $cart_url      string  URL to add product to cart
 */
?>
<?php $acc_active = 'wishlist'; include(APPPATH.'views/store/default/_account_nav.php'); ?>

<?php
$_wish_count = (isset($products) && is_array($products)) ? count($products) : 0;
$hdr_icon  = 'fa fa-heart';
$hdr_title = __('store.wishlist');
$hdr_sub   = __('store.profile') . ' &rsaquo; ' . __('store.wishlist');
$hdr_pills = [
    ['num' => $_wish_count, 'lbl' => __('store.wishlist'), 'color' => '#ff6b8a'],
];
include(APPPATH.'views/store/default/_account_header.php');
?>

<section class="profile-page">
    <div class="container main-container">
        <div class="acc-single-col">
            <div class="acc-form-card">
                <div class="acc-form-card__header">
                    <i class="fa fa-heart me-2" style="color:#ff6b8a"></i><?= __('store.wishlist') ?>
                    <span class="acc-form-card__count"><?= $_wish_count ?></span>
                </div>

                <?php if (isset($products) && count($products)): ?>
                    <?php foreach ($products as $product):
                        $href  = base_url("store/" . base64_encode($user_id) . "/product/" . $product['product_slug']);
                        $image = (!empty($product['product_featured_image']))
                            ? base_url('assets/images/product/upload/thumb/' . $product['product_featured_image'])
                            : base_url('assets/store/default/img/product1.png');
                    ?>
                    <div class="acc-wish-item">
                        <div class="acc-wish-item__img">
                            <img src="<?= $image ?>" alt="<?= htmlspecialchars($product['product_name']) ?>"
                                 onerror="this.onerror=null;this.src='<?= base_url('assets/images/no-image.png') ?>'">
                        </div>
                        <div class="acc-wish-item__info">
                            <span class="acc-wish-item__name"><?= htmlspecialchars($product['product_name']) ?></span>
                            <?php if (!empty($product['product_price'])): ?>
                            <span class="acc-wish-item__price"><?= c_format($product['product_price']) ?></span>
                            <?php endif; ?>
                        </div>
                        <div class="acc-wish-item__actions">
                            <a href="<?= $href ?>" class="btn acc-wish-btn acc-wish-btn--view">
                                <i class="fa fa-eye"></i><?= __('store.details') ?>
                            </a>
                            <button class="btn acc-wish-btn acc-wish-btn--remove btn-add-to-wishlist"
                                    data-product_id="<?= $product['product_id'] ?>">
                                <i class="fa fa-trash"></i><?= __('store.remove') ?>
                            </button>
                        </div>
                    </div>
                    <?php endforeach; ?>
                <?php else: ?>
                    <div class="acc-wish-empty">
                        <i class="fa fa-heart-o"></i>
                        <p><?= __('store.no_wishlisted_products_available') ?></p>
                        <a href="<?= $base_url ?>category" class="btn acc-btn-save" style="width:auto;padding:0 24px;margin:0 auto">
                            <i class="fa fa-shopping-bag"></i><?= __('store.browse_products') ?? __('store.categories') ?>
                        </a>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</section>

<script>
$(document).on('click', '.btn-add-to-wishlist', function() {
    var $btn = $(this);
    $.ajax({
        url: '<?= base_url('Store/toggle_wishlist') ?>',
        type: 'POST',
        dataType: 'json',
        data: { product_id: $btn.data('product_id') },
        success: function() { location.reload(); },
    });
});
</script>
