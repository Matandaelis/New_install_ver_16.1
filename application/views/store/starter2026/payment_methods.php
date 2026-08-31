<?php
/**
 * Starter 2026 — Payment Methods Display (partial)
 *
 * @contract  Store API v1 — fragment: payment_methods (rendered inside checkout.php)
 *
 * VARIABLES (injected by AJAX call to store/get_payment_methods)
 *   $payment_gateways  array  Active gateway list [{name, title, icon}, ...]
 *                             e.g. [["name"=>"stripe","title"=>"Credit Card","icon"=>"assets/...stripe.png"]]
 *
 * NOTE  $paymentsetting (raw settings) is NOT used here — $payment_gateways is the processed list.
 */
?>

<div class="s26-payment-methods">
    <?php if(empty($payment_gateways)): ?>
        <div class="s26-payment-empty">
            <i class="fas fa-credit-card"></i>
            <p><?= __('store.no_payment_options') ?? 'No payment options available' ?></p>
        </div>
    <?php else: ?>
        <div class="s26-payment-grid">
            <?php $i = 0; foreach($payment_gateways as $key => $value): ?>
            <a class="s26-payment-card <?= ($i == 0) ? 's26-payment-card--active active' : '' ?> payment-gateway-step"
               data-value="<?= $value['name'] ?>" href="javascript:void(0);">
                <div class="s26-payment-card__icon">
                    <img src="<?= base_url($value['icon']) ?>" alt="<?= htmlspecialchars($value['title'] ?? '') ?>"
                         onerror="this.parentElement.innerHTML='<i class=\'fas fa-credit-card\'></i>'">
                </div>
                <span class="s26-payment-card__name"><?= htmlspecialchars($value['title'] ?? '') ?></span>
                <div class="s26-payment-card__check">
                    <i class="fas fa-check-circle"></i>
                </div>
            </a>
            <input type="radio" name="payment_gateway" value="<?= $value['name'] ?>" <?= ($i == 0) ? "checked" : "" ?> style="display:none;">
            <?php $i++; endforeach; ?>
        </div>
    <?php endif; ?>
</div>


<script type="text/javascript">
$(document).on('click', ".s26-payment-card", function(){
    $('.s26-payment-card').removeClass('active s26-payment-card--active');
    $(this).addClass('active s26-payment-card--active');
    $('input[name="payment_gateway"][value="'+$(this).data('value')+'"]').trigger('click');
});
</script>
