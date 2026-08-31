<?php
/**
 * Starter 2026 — Shopping Cart Page
 *
 * @contract  Store API v1 — page: cart
 * @endpoint  GET store/api/v1/pages/cart
 *
 * GLOBALS  $store_setting, $client, $home_link, $base_url
 *
 * PAGE VARIABLES
 *   $products   array   Cart items [{product_id, product_name, quantity, price, variation_price, image, ...}]
 *   $sub_total  string  Formatted cart subtotal
 *   $total      string  Formatted cart total (before shipping)
 *   $settings   array   Store settings
 *   $cart_url   string  URL of the cart page
 *
 * PARTIALS INCLUDED
 *   cart_products_table.php  — renders the items table (receives same scope)
 */
?>

<div class="container">
    <nav class="s26-breadcrumb" aria-label="Breadcrumb">
        <a href="<?= $home_link ?? $base_url ?>"><?= __('store.home') ?? 'Home' ?></a>
        <span class="separator"><i class="fas fa-chevron-right"></i></span>
        <span class="current"><?= __('store.shopping_cart') ?? 'Shopping Cart' ?></span>
    </nav>
</div>

<section class="s26-cart-page">
    <div class="container">

        <?php if($products) { ?>

        <div class="s26-cart-header-row">
            <h1 class="s26-page-title"><?= __('store.shopping_cart') ?? 'Shopping Cart' ?></h1>
        </div>

        <form method="POST" id="cart-form">
            <div class="s26-cart-content">
                <div class="s26-cart-table-wrap cart-table">
                    <?php include_once "cart_products_table.php"; ?>
                </div>

                <div class="s26-cart-actions">
                    <a href="<?= $base_url ?>" class="s26-btn-outline">
                        <i class="fas fa-arrow-left"></i>
                        <?= __('store.continue_shopping') ?? 'Continue Shopping' ?>
                    </a>
                    <a href="<?= $base_url ?>checkout" class="s26-btn-primary">
                        <?= __('store.checkout') ?? 'Checkout' ?>
                        <i class="fas fa-arrow-right"></i>
                    </a>
                </div>
            </div>
        </form>

        <?php } else { ?>

        <!-- Empty Cart State -->
        <div class="s26-empty-state">
            <div class="s26-empty-state__icon">
                <i class="fas fa-shopping-bag"></i>
            </div>
            <h2 class="s26-empty-state__title"><?= __('store.shopping_cart_is_empty') ?? 'Your cart is empty' ?></h2>
            <p class="s26-empty-state__text"><?= __('store.empty_cart_message') ?? 'Looks like you haven\'t added any products to your cart yet.' ?></p>
            <a href="<?= $base_url ?>" class="s26-btn-primary">
                <i class="fas fa-shopping-bag"></i>
                <?= __('store.continue_shopping') ?? 'Start Shopping' ?>
            </a>
        </div>

        <?php } ?>
    </div>
</section>

<script type="text/javascript">
var xhr;
$("#cart-form").delegate(".qty-input","change",function(){
    if(xhr && xhr.readyState != 4) xhr.abort();
    $this = $(this);
    xhr = $.ajax({
        url:'',
        type:'POST',
        dataType:'json',
        data:$("#cart-form").serialize(),
        beforeSend:function(){},
        complete:function(){},
        success:function(json){
            $('.cart-table').html(json.html);
            updateCart();
            if(json.success == false){
                $(".print-message").html(
                    '<div class="alert alert-danger fade show rounded shadow d-flex align-items-center" role="alert">' +
                    '<i class="bi bi-exclamation-triangle-fill mr-2 flex-shrink-0"></i>' +
                    '<strong class="flex-grow-1">' + json['message'] + '</strong>' +
                    '<button type="button" class="close ml-2 flex-shrink-0" onclick="$(this).closest(\'.alert\').fadeOut(300,function(){$(this).remove()})" aria-label="Close"><span aria-hidden="true">&times;</span></button>' +
                    '</div>'
                );
                removeAlertsAfterTimeout();
            }
        }
    })
    return false;
});

$(document).delegate(".s26-qty-btn","click",function(){
    var val = parseInt($(this).closest(".s26-qty-control").find("input").val()) || 1;
    if($(this).hasClass("add")) { val++ }
    else { val-- }
    if(val <= 0) val = 1;
    $(this).closest(".s26-qty-control").find("input").val(val).trigger("change");
});
</script>
