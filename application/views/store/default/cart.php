<?php
/**
 * Default theme — Shopping cart page (Amazon wireframe)
 *
 * @contract  Store API v1 — page: cart
 * @see       Store_cart_payload::page_cart()
 * @see       /store/api/v1/pages/cart
 *
 * GLOBALS (always available via Storeapp::view)
 *   $store_setting  array   All store settings key-value map
 *   $client         array   Logged-in customer array; empty array if guest
 *   $home_link      string  Absolute URL to store homepage
 *
 * PAGE VARIABLES
 *   $products      array   Cart items [{id, product_id, name, qty, price, total, image, link}, ...]
 *   $totals        array   Cart totals {subtotal, shipping, coupon_discount, total}
 *   $checkout_url  string  URL to proceed to checkout
 *   $cart_url      string  URL for cart AJAX operations (update/remove)
 *   $coupon_url    string  URL for coupon application
 *   $currency      string  Currency symbol (e.g. '$')
 */
?>
<section class="amz-cart-page">
    <div class="container">
        <div class="amz-breadcrumb">
            <a href="<?= $home_link ?>"><?= __('store.home') ?></a>
            <span>/</span>
            <span class="amz-breadcrumb-active"><?= __('store.shopping_cart') ?></span>
        </div>

        <h1 class="amz-page-title"><?= __('store.shopping_cart') ?></h1>

        <?php if ($products): ?>
        <form method="POST" id="cart-form">
            <div class="amz-cart-layout">
                <!-- Cart Items -->
                <div class="amz-cart-main">
                    <div class="print-message"></div>
                    <div class="cart-table">
                        <?php include_once "cart_products_table.php"; ?>
                    </div>
                </div>

                <!-- Cart Sidebar -->
                <div class="amz-cart-aside">
                    <div class="amz-cart-aside-card">
                        <a href="<?= $checkout_url ?>" class="amz-btn-checkout-full">
                            <?= __('store.checkout') ?> <i class="fas fa-angle-right"></i>
                        </a>
                        <div class="amz-cart-coupon">
                            <label for="coupon-code"><?= __('store.coupon_code') ?></label>
                            <div class="amz-coupon-input-group">
                                <input type="text" id="coupon-code" class="amz-coupon-input" placeholder="<?= __('store.coupon_code') ?>">
                                <button type="button" class="amz-coupon-apply" id="apply-coupon"><?= __('store.apply') ?></button>
                            </div>
                        </div>
                        <a href="<?= $base_url ?>" class="amz-continue-shopping">
                            <i class="fas fa-arrow-left"></i> <?= __('store.continue_shopping') ?>
                        </a>
                    </div>
                </div>
            </div>
        </form>

        <?php else: ?>
        <!-- Empty Cart -->
        <div class="amz-cart-empty">
            <div class="amz-cart-empty-inner">
                <i class="fas fa-shopping-cart"></i>
                <h2><?= __('store.shopping_cart_is_empty') ?></h2>
                <p><?= __('store.your_cart_is_empty_add_items') ?></p>
                <a href="<?= $base_url ?>" class="amz-btn-primary">
                    <i class="fas fa-shopping-bag"></i> <?= __('store.continue_shopping') ?>
                </a>
            </div>
        </div>
        <?php endif; ?>
    </div>
</section>

<script type="text/javascript">
var xhr;

$("#cart-form").delegate(".qty-input", "change", function() {
    if (xhr && xhr.readyState != 4) xhr.abort();
    var $this = $(this);
    xhr = $.ajax({
        url: '',
        type: 'POST',
        dataType: 'json',
        data: $("#cart-form").serialize(),
        success: function(json) {
            $('.cart-table').html(json.html);
            updateCart();
            if (json.success == false) {
                $(".print-message").html('<div class="amz-alert amz-alert-danger">' +
                    '<i class="fas fa-exclamation-triangle"></i> ' + json.message +
                    ' <button type="button" class="amz-alert-close">&times;</button></div>');
            }
        }
    });
    return false;
});

$(document).delegate(".amz-qty-btn", "click", function() {
    var input = $(this).parent().find("input");
    var val = parseInt(input.val());
    if ($(this).hasClass("amz-qty-add")) val++;
    else val--;
    if (val <= 0) val = 1;
    input.val(val).trigger("change");
});

$(document).on('click', '.amz-alert-close', function() {
    $(this).parent().fadeOut(300, function() { $(this).remove(); });
});
</script>
