<?php
/**
 * Starter 2026 — Mini Cart Dropdown (AJAX partial)
 *
 * @contract  Store API v1 — fragment: mini_cart
 * @note      Loaded via AJAX. No layout wrapping.
 *
 * GLOBALS  $store_setting, $base_url
 *
 * VARIABLES
 *   $products   array   Cart items [{product_name, quantity, price, image, ...}]
 *   $sub_total  string  Formatted cart subtotal
 */
?>

<?php if($products) { ?>

<div class="s26-mini-cart">
    <div class="s26-mini-cart__header">
        <h6><?= __('store.shopping_cart') ?? 'Shopping Cart' ?></h6>
        <span class="s26-mini-cart__count"><?= count($products) ?> <?= __('store.items') ?? 'items' ?></span>
    </div>

    <div class="s26-mini-cart__items">
        <?php foreach ($products as $key => $product) { ?>
        <div class="s26-mini-cart__item">
            <div class="s26-mini-cart__item-img">
                <a href="<?= $product['link'] ?>">
                    <img src="<?= $product['product_featured_image'] ?>" alt="<?= __('store.product_image') ?? 'Product' ?>"
                         onerror="this.src='<?= base_url('assets/store/default/img/pr-img.png') ?>'">
                </a>
            </div>
            <div class="s26-mini-cart__item-info">
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
                ?>
                <a href="<?= $product['link'] ?>" class="s26-mini-cart__item-name">
                    <?= htmlspecialchars($product['product_name']) ?>
                    <?= ($combinationString != "") ? "<small>($combinationString)</small>" : "" ?>
                </a>
                <div class="s26-mini-cart__item-meta">
                    <span><?= $product['quantity'] ?> × <?= c_format($product['product_price'] + $product['variation_price']) ?></span>
                </div>
            </div>
            <button type="button" class="btn btn-xs btn-remove-cart s26-mini-cart__remove"
                    data-href="<?= $base_url . "cart/?checkout_page=true&remove=" . $product['cart_id'] ?>">
                <i class="fas fa-times"></i>
            </button>
        </div>
        <?php } ?>
    </div>

    <div class="s26-mini-cart__footer">
        <div class="s26-mini-cart__total">
            <span><?= __('store.subtotal') ?? 'Subtotal' ?></span>
            <span class="s26-mini-cart__total-value"><?= c_format($sub_total) ?></span>
        </div>
        <a href="<?= base_url('store/cart') ?>" class="s26-btn-primary w-100 justify-content-center" style="font-size:13px;padding:12px 20px;">
            <i class="fas fa-shopping-bag"></i>
            <?= __('store.view_cart') ?? 'View Cart' ?>
        </a>
    </div>
</div>

<?php } else { ?>

<div class="s26-mini-cart-empty">
    <div class="s26-mini-cart-empty__icon">
        <i class="fas fa-shopping-bag"></i>
    </div>
    <p><?= __('store.cart_is_blank') ?? 'Your cart is empty' ?></p>
    <a href="<?= base_url('store/category') ?>" style="font-size:13px;font-weight:700;color:var(--s26-primary);text-decoration:none;">
        <?= __('store.continue_shopping') ?? 'Start Shopping' ?> <i class="fas fa-arrow-right" style="font-size:11px"></i>
    </a>
</div>

<?php } ?>
