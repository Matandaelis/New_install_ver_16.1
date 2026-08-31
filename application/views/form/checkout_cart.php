<?php if (!empty($products)) { ?>
<form id="checkout-cart-form" class="table-responsive">
    <input type="hidden" name="checkout_page" value="true">

    <div class="shopping-cart">
        <div class="column-labels">
            <label class="product-image"><?= __('store.image') ?></label>
            <label class="product-details"><?= __('store.product') ?></label>
            <label class="product-price"><?= __('store.price') ?></label>
            <label class="product-quantity"><?= __('store.quantity') ?></label>
            <label class="product-line-price"><?= __('store.total') ?></label>
        </div>

        <?php foreach ($products as $product) { ?>
        <div class="product">
            <div class="product-image">
                <img class="media-object" src="<?= $product['product_featured_image'] ?>">
            </div>
            <div class="product-details">
                <div class="product-title"><?= $product['product_name'] ?></div>
                <p class="product-description"><?= $product['product_short_description'] ?></p>
            </div>
            <div class="product-price"><?= c_format($product['product_price']) ?></div>
            <div class="product-quantity">
                <?php if ($product['product_type'] != 'downloadable'): ?>
                <div class="number-input mini-number-input">
                    <input type="text" name="quantity[<?= $product['cart_id'] ?>]"
                           value="<?= $product['quantity'] ?>" size="1"
                           class="form-control qty-input">
                    <div>
                        <span class="plus"> + </span>
                        <span class="minus"> - </span>
                    </div>
                </div>
                <?php else: ?>
                    <?= $product['quantity'] ?>
                <?php endif; ?>
            </div>
            <div class="product-line-price">
                <?= c_format($product['total']) ?>
                <?php if (count($products) > 1): ?>
                <button type="button" class="btn btn-sm btn-link btn-remove-cart text-danger p-0 ml-2"
                        data-href="<?= base_url('form/cart?checkout_page=true&remove=' . (int)$product['cart_id']) ?>"
                        title="<?= __('store.remove') ?? 'Remove' ?>">
                    <i class="fa fa-times"></i>
                </button>
                <?php endif; ?>
            </div>
        </div>
        <?php } ?>

        <div class="totals">
            <?php foreach ($totals as $value) { ?>
            <div class="totals-item">
                <label><?= $value['title'] ?></label>
                <div class="totals-value" id="cart-subtotal"><?= c_format($value['amount']) ?></div>
            </div>
            <?php } ?>
        </div>
    </div>

    <!-- ── Coupon section ── -->
    <div class="fc-coupon-section">

        <?php if (!empty($form_coupon_discount) && !empty($form_coupon)): ?>
        <!-- Coupon applied badge -->
        <div class="fc-coupon-applied">
            <div class="fc-coupon-badge">
                <span class="fc-coupon-icon"><i class="fa fa-tag"></i></span>
                <div class="fc-coupon-info">
                    <span class="fc-coupon-name">
                        <?php if (!empty($form_coupon['name'])): ?>
                            <?= htmlspecialchars($form_coupon['name']) ?>
                        <?php elseif (!empty($form_coupon['code'])): ?>
                            <?= htmlspecialchars($form_coupon['code']) ?>
                        <?php else: ?>
                            <?= __('store.coupon_discount') ?>
                        <?php endif; ?>
                    </span>
                    <span class="fc-coupon-saving">
                        <?= __('store.you_save') ?> <?= c_format($form_coupon_discount) ?>
                    </span>
                </div>
                <span class="fc-coupon-check"><i class="fa fa-check-circle"></i></span>
            </div>
            <div class="text-danger error-coupon-msg"></div>
        </div>
        <?php else: ?>
        <!-- Coupon input -->
        <div class="text-danger error-coupon-msg"></div>
        <div class="input-group fc-coupon-input">
            <input type="text" class="form-control coupon_code"
                   placeholder="<?= __('store.enter_coupon_code') ?>" name="coupon">
            <div class="input-group-append">
                <button type="button" class="submit-coupon btn btn-primary">
                    <?= __('store.apply') ?>
                </button>
            </div>
        </div>
        <?php endif; ?>

    </div>

</form>
<?php } else { ?>
<div class="alert alert-info"><?= __('store.cart_empty') ?? 'Your cart is empty.' ?></div>
<?php } ?>
