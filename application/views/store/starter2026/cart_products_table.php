<?php
/**
 * Starter 2026 — Cart Products Table (partial)
 *
 * @contract  Store API v1 — fragment: cart_products_table
 * @note      Included by cart.php and also returned by AJAX cart-update calls.
 *
 * VARIABLES (inherited from cart.php / AJAX handler)
 *   $products   array   Cart items [{product_id, product_name, quantity, price, variation_price, image, slug}]
 *   $sub_total  string  Formatted cart subtotal
 *   $total      string  Formatted cart total
 *   $cart_url   string  URL of the cart page (for update-form action)
 */
?>

<div class="s26-cart-table">
    <!-- Desktop Header -->
    <div class="s26-cart-table__head d-none d-md-flex">
        <div class="s26-cart-col s26-cart-col--product"><?= __('store.product') ?? 'Product' ?></div>
        <div class="s26-cart-col s26-cart-col--price"><?= __('store.price') ?? 'Price' ?></div>
        <div class="s26-cart-col s26-cart-col--qty"><?= __('store.quantity') ?? 'Qty' ?></div>
        <div class="s26-cart-col s26-cart-col--total"><?= __('store.total') ?? 'Total' ?></div>
        <div class="s26-cart-col s26-cart-col--action"></div>
    </div>

    <!-- Cart Items -->
    <?php foreach ($products as $key => $product) { ?>
    <div class="s26-cart-item">
        <!-- Product Info -->
        <div class="s26-cart-col s26-cart-col--product">
            <div class="s26-cart-item__inner">
                <div class="s26-cart-item__img">
                    <a href="<?= $product['link'] ?>">
                        <img src="<?= (!empty($product['product_featured_image'])) ? $product['product_featured_image'] : base_url('assets/store/default/img/pr-img.png'); ?>"
                             alt="<?= __('store.product_image') ?? 'Product' ?>"
                             onerror="this.src='<?= base_url('assets/store/default/img/pr-img.png') ?>'">
                    </a>
                </div>
                <div class="s26-cart-item__info">
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
                    <a href="<?= $product['link'] ?>" class="s26-cart-item__name">
                        <?= htmlspecialchars($product['product_name']) ?>
                    </a>
                    <?php if($combinationString != ""): ?>
                    <span class="s26-cart-item__variant"><?= $combinationString ?></span>
                    <?php endif; ?>
                </div>
            </div>
        </div>

        <!-- Price -->
        <div class="s26-cart-col s26-cart-col--price">
            <span class="s26-cart-mobile-label d-md-none"><?= __('store.price') ?? 'Price' ?>:</span>
            <div>
                <?php if(!empty($product['product_msrp']) && $product['product_msrp'] > 0): ?>
                <small class="s26-cart-item__original-price"><?= c_format($product['product_msrp'] + $product['variation_price']) ?></small>
                <?php endif; ?>
                <span class="s26-cart-item__price"><?= c_format($product['product_price'] + $product['variation_price']) ?></span>
            </div>
        </div>

        <!-- Quantity -->
        <div class="s26-cart-col s26-cart-col--qty">
            <span class="s26-cart-mobile-label d-md-none"><?= __('store.quantity') ?? 'Qty' ?>:</span>
            <?php if(!in_array($product['product_type'],['downloadable','video','videolink'])){ ?>
            <div class="s26-qty-control cart-counter" id="field-<?= $product['cart_id'] ?>">
                <button type="button" class="s26-qty-btn sub" aria-label="Decrease">
                    <i class="fas fa-minus"></i>
                </button>
                <input class="qty-input" name="quantity[<?= $product['cart_id'] ?>]" type="text" value="<?= $product['quantity'] ?>" min="1">
                <button type="button" class="s26-qty-btn add" aria-label="Increase">
                    <i class="fas fa-plus"></i>
                </button>
            </div>
            <?php } else { ?>
            <span class="s26-cart-item__qty-static"><?= $product['quantity'] ?></span>
            <?php } ?>
        </div>

        <!-- Total -->
        <div class="s26-cart-col s26-cart-col--total">
            <span class="s26-cart-mobile-label d-md-none"><?= __('store.total') ?? 'Total' ?>:</span>
            <span class="s26-cart-item__total"><?= c_format($product['total']) ?></span>
        </div>

        <!-- Remove -->
        <div class="s26-cart-col s26-cart-col--action">
            <a href="<?= $cart_url."?remove=".$product['cart_id'] ?>" class="s26-cart-remove" title="<?= __('store.remove') ?? 'Remove' ?>">
                <i class="fas fa-trash-alt"></i>
            </a>
        </div>
    </div>
    <?php } ?>

    <!-- Totals -->
    <div class="s26-cart-totals">
        <div class="s26-cart-totals__row">
            <span><?= __('store.subtotal') ?? 'Subtotal' ?></span>
            <span class="s26-cart-totals__value"><?= c_format($sub_total) ?></span>
        </div>
        <div class="s26-cart-totals__row s26-cart-totals__row--grand">
            <span><?= __('store.total') ?? 'Total' ?></span>
            <span class="s26-cart-totals__value"><?= c_format($total) ?></span>
        </div>
    </div>
</div>