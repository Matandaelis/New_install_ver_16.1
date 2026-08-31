<?php
/**
 * Starter 2026 — Checkout Confirmation Step (partial)
 *
 * @contract  Store API v1 — fragment: checkout_confirm (rendered inside checkout.php)
 *
 * VARIABLES (inherited from checkout.php parent scope)
 *   $allow_comment     bool   Whether order notes/comments are enabled
 *   $allow_upload_file bool   Whether payment proof upload is enabled
 *
 * TWO MODES:
 *   1) Payment required  — $payment_mode is NOT set.
 *      Payment gateway form is appended AFTER this view by the controller.
 *      Show "Almost Done" + order comments. Do NOT show "Order Placed".
 *
 *   2) Zero / No payment — $payment_mode == 'no_payment'.
 *      The "checkount-without-pyament" view is appended by the controller.
 *      Keep this section minimal (order comments only) since that view handles messaging.
 *
 * CodeIgniter 3.1.9
 */

$needs_payment = !isset($payment_mode) || $payment_mode !== 'no_payment';
?>

<?php if ($needs_payment): ?>
<!-- ── Payment Still Required ── -->
<div class="s26-checkout-confirm s26-checkout-confirm--pending">
    <div class="s26-checkout-confirm__icon s26-checkout-confirm__icon--pending">
        <div class="s26-checkout-confirm__ring s26-checkout-confirm__ring--pending"></div>
        <i class="fas fa-credit-card"></i>
    </div>
    <h2 class="s26-checkout-confirm__title"><?= __('store.almost_done') ?? 'Almost Done!' ?></h2>
    <p class="s26-checkout-confirm__text">
        <?= __('store.complete_payment_to_finalize') ?? 'Please complete the payment below to finalize your order.' ?>
    </p>
    <p class="s26-checkout-confirm__subtext text-muted" style="font-size:13px;">
        <i class="fas fa-lock me-1" style="font-size:11px"></i>
        <?= __('store.your_order_is_reserved') ?? 'Your order has been reserved. Complete the payment step below to confirm it.' ?>
    </p>
</div>
<?php endif; ?>

<?php
/* ── Order Comment Fields (both modes, if enabled) ── */
if (isset($order_comment_setting) && !empty($order_comment_setting['status'])): ?>
<div class="s26-order-comments-confirm mt-3 mb-3">
    <?php foreach ($order_comment_setting['title'] as $key => $value): ?>
    <fieldset class="s26-checkout-card mb-3" style="border:1px solid var(--s26-border);border-radius:var(--s26-radius);padding:16px;">
        <legend style="font-size:14px;font-weight:600;padding:0 8px;width:auto;margin-bottom:8px;"><?= $value ?></legend>
        <div class="form-group required">
            <input type="hidden" name="comment[<?= $key ?>][title]" value="<?= $value ?>">
            <textarea name="comment[<?= $key ?>][comment]" id="comment_textarea<?= $key ?>" class="s26-form-control" rows="4" placeholder="<?= __('store.comment') ?? 'Your comment...' ?>"></textarea>
        </div>
    </fieldset>
    <?php endforeach; ?>
</div>
<?php endif; ?>

<?php if (!$needs_payment): ?>
<!-- ── No Payment Needed — minimal divider before the "without payment" view ── -->
<div class="s26-checkout-confirm">
    <div class="s26-checkout-confirm__icon">
        <div class="s26-checkout-confirm__ring"></div>
        <i class="fas fa-check"></i>
    </div>
</div>
<?php endif; ?>
