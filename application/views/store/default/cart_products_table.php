<?php
/**
 * Default theme — Cart products table partial (Amazon wireframe)
 *
 * @contract  Store API v1 — fragment: cart_products_table (rendered inside cart.php and checkout flow)
 * @see       Store_cart_payload::page_cart()
 *
 * VARIABLES (inherited from parent cart/checkout scope)
 *   $products       array   Cart items [{id, product_id, name, qty, price, total, image, link}, ...]
 *   $cart_url       string  URL for cart update/remove AJAX calls
 *   $currency       string  Currency symbol
 */
?>
<div class="amz-cart-wrapper">

    <!-- Cart Header Row -->
    <div class="amz-cart-header">
        <span class="amz-cart-col-product"><?= __('store.product') ?></span>
        <span class="amz-cart-col-price"><?= __('store.price') ?></span>
        <span class="amz-cart-col-qty"><?= __('store.quantity') ?></span>
        <span class="amz-cart-col-total"><?= __('store.total') ?></span>
    </div>

    <?php foreach ($products as $key => $product): ?>
    <?php
        $combinationString = "";
        if (isset($product['variation']) && !empty($product['variation'])) {
            $variation = json_decode($product['variation']);
            foreach ($variation as $k => $v) {
                if ($k == 'colors') {
                    $combinationString .= ($combinationString == "") ? explode("-", $v)[1] : "," . explode("-", $v)[1];
                } else {
                    $combinationString .= ($combinationString == "") ? $v : "," . $v;
                }
            }
        }
        $img_src = !empty($product['product_featured_image']) ? $product['product_featured_image'] : base_url('assets/store/default/img/1.png');
        $is_downloadable = in_array($product['product_type'], ['downloadable', 'video', 'videolink']);
    ?>
    <div class="amz-cart-row">
        <!-- Product -->
        <div class="amz-cart-col-product">
            <div class="amz-cart-thumb">
                <a href="<?= $product['link'] ?>">
                    <img src="<?= $img_src ?>" alt="<?= htmlspecialchars($product['product_name']) ?>">
                </a>
            </div>
            <div class="amz-cart-info">
                <a href="<?= $product['link'] ?>" class="amz-cart-name"><?= $product['product_name'] ?> <?= ($combinationString != "") ? "(" . $combinationString . ")" : "" ?></a>
                <?php if (!empty($product['product_short_description'])): ?>
                <p class="amz-cart-desc"><?= $product['product_short_description'] ?></p>
                <?php endif; ?>
                <?php if ($is_downloadable): ?>
                <span class="amz-cart-badge">Digital</span>
                <?php endif; ?>
            </div>
        </div>

        <!-- Price -->
        <div class="amz-cart-col-price">
            <?php if (!empty($product['product_msrp'])): ?>
            <span class="amz-cart-was"><?= c_format($product['product_msrp'] + $product['variation_price']) ?></span>
            <?php endif; ?>
            <span class="amz-cart-price"><?= c_format($product['product_price'] + $product['variation_price']) ?></span>
        </div>

        <!-- Quantity -->
        <div class="amz-cart-col-qty">
            <?php if (!$is_downloadable): ?>
            <div class="amz-qty-selector">
                <button type="button" class="amz-qty-btn amz-qty-sub">-</button>
                <input class="qty-input amz-qty-input" name="quantity[<?= $product['cart_id'] ?>]" type="text" value="<?= $product['quantity'] ?>" min="1">
                <button type="button" class="amz-qty-btn amz-qty-add">+</button>
            </div>
            <?php else: ?>
            <span class="amz-qty-digital"><?= $product['quantity'] ?></span>
            <?php endif; ?>
        </div>

        <!-- Total + Delete -->
        <div class="amz-cart-col-total">
            <span class="amz-cart-total-price"><?= c_format($product['total']) ?></span>
            <a href="<?= $cart_url . "?remove=" . $product['cart_id'] ?>" class="amz-cart-remove" title="<?= __('store.remove') ?>">
                <i class="fas fa-trash-alt"></i>
            </a>
        </div>
    </div>
    <?php endforeach; ?>

    <!-- Cart Summary Footer -->
    <div class="amz-cart-summary">
        <div class="amz-cart-summary-inner">
            <div class="amz-cart-summary-row">
                <span><?= __('store.subtotal') ?></span>
                <span><?= c_format($sub_total) ?></span>
            </div>
            <div class="amz-cart-summary-row amz-cart-summary-total">
                <span><?= __('store.total') ?></span>
                <span><?= c_format($total) ?></span>
            </div>
        </div>
    </div>
</div>
