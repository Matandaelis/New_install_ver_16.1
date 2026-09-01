<?php
/**
 * Default theme — Mini cart dropdown partial (server-rendered)
 *
 * @contract  Store API v1 — fragment: mini_cart (AJAX partial rendered inside layout header)
 * @see       Store_cart_payload::page_cart()
 * @see       Loaded via AJAX POST store/mini_cart
 *
 * VARIABLES (injected via AJAX by Store controller)
 *   $products  array   Cart items [{cart_id, product_id, product_name, quantity, product_price, variation_price, product_featured_image, variation, link}, ...]
 *   $sub_total string  Formatted cart sub total
 *   $total     string  Formatted cart grand total
 */
?>
<?php if($products) { ?>
<div class="amz-mini-cart">
    <div class="amz-mini-cart__header">
        <span class="amz-mini-cart__title"><?= __('store.cart') ?: 'Cart' ?></span>
        <span class="amz-mini-cart__count"><?= count($products) ?> <?= __('store.items') ?: 'items' ?></span>
    </div>
    <div class="amz-mini-cart__items">
        <?php foreach ($products as $product) { ?>
            <?php
                $combinationString = "";
                if(isset($product['variation']) && !empty($product['variation'])) {
                    $variation = json_decode($product['variation']);
                    foreach ($variation as $key => $value) {
                        if($key == 'colors') {
                            $combinationString .= ($combinationString == "") ? explode("-",$value)[1] : ", ".explode("-",$value)[1];
                        } else {
                            $combinationString .= ($combinationString == "") ? $value : ", ".$value;
                        }
                    }
                }
                $item_price = $product['product_price'] + $product['variation_price'];
            ?>
            <div class="amz-mini-cart__item">
                <a href="<?= $product['link'] ?>" class="amz-mini-cart__img-link">
                    <img src="<?= $product['product_featured_image'] ?>" class="amz-mini-cart__img" alt="<?= $product['product_name'] ?>" loading="lazy">
                </a>
                <div class="amz-mini-cart__info">
                    <a href="<?= $product['link'] ?>" class="amz-mini-cart__name"><?= $product['product_name'] ?></a>
                    <?php if($combinationString != ""): ?>
                        <span class="amz-mini-cart__variant"><?= $combinationString ?></span>
                    <?php endif; ?>
                    <div class="amz-mini-cart__qty"><?= __('store.quantity') ?: 'Qty' ?>: <?= $product['quantity'] ?></div>
                    <div class="amz-mini-cart__price"><?= c_format($item_price) ?></div>
                </div>
                <button type="button" class="amz-mini-cart__remove btn-remove-cart" data-href="<?= $base_url."cart/?checkout_page=true&remove=".$product['cart_id'] ?>" aria-label="<?= __('store.remove') ?: 'Remove' ?>">
                    <i class="fas fa-times" aria-hidden="true"></i>
                </button>
            </div>
        <?php } ?>
    </div>
    <div class="amz-mini-cart__footer">
        <div class="amz-mini-cart__totals">
            <div class="amz-mini-cart__subtotal">
                <span><?= __('store.subtotal') ?: 'Subtotal' ?></span>
                <span><?= c_format($sub_total) ?></span>
            </div>
        </div>
        <a href="<?= base_url('store/cart') ?>" class="amz-btn amz-btn-cart-dropdown"><?= __('store.view_cart') ?: 'View Cart' ?></a>
    </div>
</div>
<?php } else { ?>
    <div class="cart-empty">
         <?php $cartimage = ($store_setting['cartimage']) ? base_url('assets/images/site/'.$store_setting['cartimage']) : base_url('assets/store/default/').'img/cart-icon-empty.png'; ?>
        <img src="<?= $cartimage ?>" alt="<?= __('store.icon') ?>">
        <p><?= __('store.cart_is_blank') ?></p>
    </div>
<?php } ?>
