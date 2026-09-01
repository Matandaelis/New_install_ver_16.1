<?php
/**
 * Default theme — Product list / grid partial (server-rendered)
 *
 * @contract  Store API v1 — fragment: product_list (AJAX partial, also used in category.php)
 * @see       Store_cart_payload::page_category()
 * @see       Loaded via AJAX GET store/get_products
 *
 * VARIABLES
 *   $products   array   Product list [{product_slug, product_featured_image, product_name, product_short_description, product_price, product_old_price, product_avg_rating, country_code, country_name, state_name, ...}, ...]
 *   $user_id    int     Current user/vendor ID
 *   $currency   string  Currency symbol
 *   $cart_url   string  URL for add-to-cart AJAX calls
 */
if(empty($products)) { ?>
    <div class="amz-product-wrapper amz-product-wrapper--empty">
        <div class="amz-product-info amz-product-info--center">
            <p class="amz-text-muted"><?= __('store.no_products_available') ?></p>
        </div>
    </div>
<?php } ?>

<?php foreach ($products as $product) { ?>
    <?php
        $href = base_url("store/". base64_encode($user_id) . "/product/". $product['product_slug']);
        $image = (!empty($product['product_featured_image'])) ? base_url('assets/images/product/upload/thumb/'. $product['product_featured_image']) : base_url('assets/store/default/').'img/no-image.png';
        $rating = intval($product['product_avg_rating'] ?? 0);
        $has_old = !empty($product['product_old_price']) && $product['product_old_price'] > $product['product_price'];
    ?>
    <div class="amz-product-wrapper">
        <div class="amz-product-img">
            <a href="<?= $href ?>">
                <img alt="<?= htmlspecialchars($product['product_name']) ?>" src="<?= $image ?>" class="amz-product-img__el" loading="lazy" onerror="this.src='<?= base_url('assets/store/default/img/no-image.png') ?>';" />
            </a>
            <?php if($has_old): ?>
                <span class="amz-product-badge amz-product-badge--sale"><?= __('store.sale') ?: 'Sale' ?></span>
            <?php endif; ?>
        </div>
        <div class="amz-product-info">
            <a href="<?= $href ?>" class="amz-product-name"><?= htmlspecialchars($product['product_name'] ?? 'Untitled') ?></a>
            <div class="amz-product-price">
                <?php if($has_old): ?>
                    <span class="amz-product-price__old"><?= c_format($product['product_old_price']) ?></span>
                <?php endif; ?>
                <span class="amz-product-price__current"><?= c_format($product['product_price']) ?></span>
            </div>
            <div class="amz-product-rating">
                <?php for($i = 0; $i < 5; $i++): ?>
                    <i class="fa<?= $i < $rating ? '-solid' : '-regular' ?> fa-star" aria-hidden="true"></i>
                <?php endfor; ?>
            </div>
            <?php if(!empty($product['product_short_description'])): ?>
                <p class="amz-product-desc"><?= htmlspecialchars(mb_strimwidth($product['product_short_description'], 0, 80, '...')) ?></p>
            <?php endif; ?>
            <a href="<?= $href ?>" class="amz-btn amz-btn-details"><?= __('store.details') ?></a>
        </div>
    </div>
<?php } ?>
