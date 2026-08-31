<?php
/**
 * Starter 2026 — Checkout Cart Summary (partial)
 *
 * @contract  Store API v1 — fragment: checkout_cart (sidebar in checkout.php)
 *
 * VARIABLES (inherited from checkout.php parent scope)
 *   $products  array   Cart items [{product_name, quantity, price, variation_price, image}]
 *   $totals    array   Order totals keyed by slug (subtotal, shipping, coupon, grand_total)
 */
$currency = $store_setting['currency_sign'] ?? '$';
?>

<form id="checkout-cart-form">
    <input type="hidden" name="checkout_page" value="true">
    <?php if (!empty($products)): ?>
    <div class="s26-checkout-cart">
        <?php
        $subtotal = 0;
        foreach ($products as $key => $product):
            $item_total = ($product['product_price'] + $product['variation_price']) * $product['quantity'];
            $subtotal += $item_total;

            $combinationString = "";
            if(isset($product['variation']) && !empty($product['variation'])) {
                $variation = json_decode($product['variation']);
                foreach ($variation as $vkey => $value) {
                    if($vkey == 'colors') {
                        $combinationString .= ($combinationString == "") ? explode("-", $value)[1] : ", " . explode("-", $value)[1];
                    } else {
                        $combinationString .= ($combinationString == "") ? $value : ", " . $value;
                    }
                }
            }
        ?>
        <div class="s26-checkout-cart__item">
            <div class="s26-checkout-cart__img">
                <img src="<?= $product['product_featured_image'] ?>"
                     alt="<?= htmlspecialchars($product['product_name']) ?>"
                     onerror="this.src='<?= base_url('assets/store/default/img/pr-img.png') ?>'">
                <span class="s26-checkout-cart__qty-badge"><?= $product['quantity'] ?></span>
            </div>
            <div class="s26-checkout-cart__info">
                <a href="<?= $product['link'] ?>" class="s26-checkout-cart__name">
                    <?= htmlspecialchars($product['product_name']) ?>
                </a>
                <?php if ($combinationString != ""): ?>
                <small class="s26-checkout-cart__variant"><?= $combinationString ?></small>
                <?php endif; ?>
                <div class="s26-checkout-cart__meta">
                    <div class="s26-qty-control cart-counter">
                        <button type="button" class="s26-qty-btn"><i class="fas fa-minus"></i></button>
                        <input type="text" name="quantity[<?= $product['cart_id'] ?>]" value="<?= $product['quantity'] ?>" class="qty-input">
                        <button type="button" class="s26-qty-btn add plus"><i class="fas fa-plus"></i></button>
                    </div>
                    <span class="s26-checkout-cart__price"><?= c_format($product['product_price'] + $product['variation_price']) ?></span>
                </div>
            </div>
            <div class="s26-checkout-cart__actions">
                <span class="s26-checkout-cart__total"><?= c_format($item_total) ?></span>
                <button type="button" class="btn btn-xs btn-remove-cart s26-checkout-cart__remove"
                        data-href="<?= $base_url . "cart/?checkout_page=true&remove=" . $product['cart_id'] ?>">
                    <i class="fas fa-trash-alt"></i>
                </button>
            </div>
        </div>
        <?php endforeach; ?>

        <!-- Coupon -->
        <?php
        $applied_coupon = !empty($applied_coupon) ? $applied_coupon : [];
        $first_coupon   = !empty($applied_coupon) ? reset($applied_coupon) : null;
        $first_pid      = !empty($products) ? $products[0]['product_id'] : '';
        ?>
        <?php if ($first_coupon): ?>
        <div class="s26-coupon-applied mt-3">
            <div class="s26-coupon-badge">
                <i class="fas fa-tag" style="color:var(--s26-success,#22c55e);font-size:13px;flex-shrink:0"></i>
                <span style="font-size:13px;font-weight:600;flex:1">
                    <?= htmlspecialchars($first_coupon['name'] ?: ($first_coupon['code'] ?? '')) ?>
                </span>
                <button type="button" class="btn-remove-coupon"
                        style="background:none;border:none;cursor:pointer;color:var(--s26-text-muted);font-size:16px;line-height:1;padding:0 2px"
                        title="<?= __('store.remove') ?? 'Remove' ?>">×</button>
            </div>
        </div>
        <?php else: ?>
        <div class="s26-coupon-input-group mt-3" style="display:flex;align-items:center;gap:8px;background:var(--s26-bg-secondary,#f8f9fa);border:1px solid var(--s26-border,#e5e7eb);border-radius:var(--s26-radius-xs,6px);padding:4px 4px 4px 12px;">
            <i class="fas fa-ticket-alt" style="color:var(--s26-text-muted);font-size:13px;flex-shrink:0"></i>
            <input type="text" class="coupon-code"
                   placeholder="<?= __('store.coupon_code') ?? 'Coupon Code' ?>"
                   style="flex:1;border:none;outline:none;background:transparent;font-size:13px;padding:8px 0">
            <input type="hidden" class="first-product-id" value="<?= htmlspecialchars($first_pid) ?>">
            <button type="button" class="btn-apply-coupon"
                    style="background:var(--s26-dark,#1e293b);color:#fff;border:none;border-radius:var(--s26-radius-xs,6px);padding:8px 14px;font-size:12px;font-weight:700;cursor:pointer;white-space:nowrap">
                <?= __('store.apply') ?? 'Apply' ?>
            </button>
        </div>
        <div class="coupon-msg" style="font-size:12px;margin-top:4px"></div>
        <?php endif; ?>

        <!-- Totals -->
        <?php if (!empty($totals)): ?>
        <div class="s26-checkout-cart__totals">
            <?php foreach ($totals as $key => $value): 
                // The controller provides 'title' and 'amount' keys
                $label  = isset($value['title']) ? $value['title'] : (isset($value['text']) ? $value['text'] : '');
                $amount = isset($value['amount']) ? $value['amount'] : (isset($value['value']) ? $value['value'] : 0);
                
                // Fallback labels if title is empty
                if (empty($label)) {
                    $fallback_labels = [
                        'subtotal' => __('store.subtotal') ?? 'Subtotal',
                        'shipping' => __('store.shipping') ?? 'Shipping',
                        'tax'      => __('store.tax') ?? 'Tax',
                        'total'    => __('store.total') ?? 'Total',
                        'discount' => __('store.discount') ?? 'Discount',
                        'coupon'   => __('store.coupon') ?? 'Coupon',
                    ];
                    $label = isset($fallback_labels[$key]) ? $fallback_labels[$key] : ucfirst(str_replace('_', ' ', $key));
                }
                $is_grand = (strtolower(trim($label)) == 'total' || strtolower(trim($label)) == strtolower(__('store.total')));
            ?>
            <div class="s26-checkout-cart__total-row <?= $is_grand ? 's26-checkout-cart__total-row--grand' : '' ?>">
                <span><?= $label ?></span>
                <span class="fw-bold"><?= c_format($amount) ?></span>
            </div>
            <?php endforeach; ?>
        </div>
        <?php endif; ?>
    </div>
    <?php else: ?>
    <div class="s26-empty-state" style="padding:40px 20px">
        <div class="s26-empty-state__icon" style="width:64px;height:64px;margin-bottom:16px">
            <i class="fas fa-shopping-bag" style="font-size:24px"></i>
        </div>
        <p class="text-muted"><?= __('store.cart_is_blank') ?? 'Your cart is empty' ?></p>
        <a href="<?= $base_url ?>category" class="s26-btn-outline s26-btn--sm">
            <?= __('store.continue_shopping') ?? 'Continue Shopping' ?>
        </a>
    </div>
    <?php endif; ?>
</form>
