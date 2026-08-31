<?php
/**
 * Starter 2026 — Checkout Page
 *
 * @contract  Store API v1 — page: checkout
 * @endpoint  GET store/api/v1/pages/checkout
 *
 * GLOBALS  $store_setting, $client, $home_link, $base_url, $googlerecaptcha
 *
 * PAGE VARIABLES
 *   $products               array        Cart items
 *   $totals                 array        Subtotal, shipping, coupon, grand_total
 *   $is_logged              array|false  Logged-in customer (false = guest)
 *   $settings               array        Store settings
 *   $paymentsetting         array        Enabled payment methods and public keys
 *   $allow_shipping         bool         Whether shipping is required for this order
 *   $allow_upload_file      bool         Whether proof-of-payment upload is enabled
 *   $shipping_error_message string       Error shown when shipping is unavailable to user country
 *   $show_blue_message      bool         Show shipping restriction notice
 *   $is_guest_flow          bool         Whether this is a guest checkout session
 *   $confirm_html           string       Pre-rendered payment confirmation step HTML
 *   $order_comment_setting  array        Order comment/note configuration
 *   $checkout_url           string       Form POST action URL
 *   $cart_update_url        string       URL for cart quantity-update AJAX calls
 *   $sub_total              string       Formatted subtotal (before shipping/tax)
 *   $total                  string       Formatted total (alias of sub_total at checkout stage)
 *
 * PARTIALS INCLUDED
 *   checkout_cart.php      — cart summary sidebar
 *   checkout_shipping.php  — shipping address step
 *   checkout_confirm.php   — order confirmation step
 *   payment_methods.php    — payment method selector
 */
$currency = $store_setting['currency_sign'] ?? '$';
$is_rtl = (isset($store_setting['store_direction']) && $store_setting['store_direction'] == 'rtl');
?>

<link rel="stylesheet" href="<?= base_url('assets/plugins/tel/css/intlTelInput.css') ?>?v=<?= av() ?>">
<script src="<?= base_url('assets/plugins/tel/js/intlTelInput.js') ?>"></script>

<div class="container">
    <nav class="s26-breadcrumb" aria-label="Breadcrumb">
        <a href="<?= $home_link ?? $base_url ?>"><?= __('store.home') ?? 'Home' ?></a>
        <span class="separator"><i class="fas fa-chevron-right"></i></span>
        <a href="<?= $base_url ?>cart"><?= __('store.cart') ?? 'Cart' ?></a>
        <span class="separator"><i class="fas fa-chevron-right"></i></span>
        <span class="current"><?= __('store.checkout') ?? 'Checkout' ?></span>
    </nav>
</div>

<!-- ═══════════════ CHECKOUT PROGRESS STEPS ═══════════════ -->
<div class="container">
    <div class="s26-checkout-steps">
        <div class="s26-checkout-step completed" data-step="1">
            <div class="s26-checkout-step__icon">
                <i class="fas fa-shopping-bag"></i>
            </div>
            <span class="s26-checkout-step__label"><?= __('store.cart') ?? 'Cart' ?></span>
        </div>
        <div class="s26-checkout-step__line completed"></div>
        <div class="s26-checkout-step active" data-step="2">
            <div class="s26-checkout-step__icon">
                <i class="fas fa-truck"></i>
            </div>
            <span class="s26-checkout-step__label"><?= __('store.shipping') ?? 'Shipping' ?></span>
        </div>
        <div class="s26-checkout-step__line"></div>
        <div class="s26-checkout-step" data-step="3">
            <div class="s26-checkout-step__icon">
                <i class="fas fa-credit-card"></i>
            </div>
            <span class="s26-checkout-step__label"><?= __('store.payment') ?? 'Payment' ?></span>
        </div>
        <div class="s26-checkout-step__line"></div>
        <div class="s26-checkout-step" data-step="4">
            <div class="s26-checkout-step__icon">
                <i class="fas fa-check-circle"></i>
            </div>
            <span class="s26-checkout-step__label"><?= __('store.confirm') ?? 'Confirm' ?></span>
        </div>
    </div>
</div>

<section class="s26-checkout-page">
    <div class="container">

        <?php if (!$is_logged) { ?>
        <!-- ═══════════════ AUTH SECTION ═══════════════ -->
        <div class="s26-checkout-auth" id="s26-auth-section" style="<?= !empty($is_guest_flow) ? 'display:none' : '' ?>">
            <div class="s26-checkout-card">
                <div class="s26-checkout-card__header">
                    <i class="fas fa-user-circle"></i>
                    <h3><?= __('store.personal_details') ?? 'Personal Details' ?></h3>
                </div>
                <?php
                    // $googlerecaptcha is provided by Storeapp::view()
                ?>
                <div class="s26-checkout-card__body">
                    <div class="row g-4">
                        <!-- Login -->
                        <div class="col-md-5">
                            <div class="s26-auth-box">
                                <h5 class="s26-auth-box__title">
                                    <i class="fas fa-sign-in-alt"></i>
                                    <?= __('store.login_with_existing_account') ?? 'Login' ?>
                                </h5>
                                <form id="login-form">
                                    <input type="hidden" name="store_checkout" value="1">
                                    <div class="mb-3">
                                        <label class="s26-form-label"><?= __('store.username') ?? 'Username' ?></label>
                                        <input type="text" name="username" class="s26-form-control" placeholder="<?= __('store.username') ?>">
                                    </div>
                                    <div class="mb-3">
                                        <label class="s26-form-label"><?= __('store.password') ?? 'Password' ?></label>
                                        <input type="password" name="password" class="s26-form-control" placeholder="<?= __('store.password') ?>">
                                    </div>
                                    <?php if (isset($googlerecaptcha['client_login']) && $googlerecaptcha['client_login']) { ?>
                                    <div class="mb-3">
                                        <div class="g-recaptcha" id='client_login'></div>
                                        <input type="hidden" name="captch_response">
                                    </div>
                                    <?php } ?>
                                    <button type="submit" class="s26-btn-primary w-100 justify-content-center">
                                        <?= __('store.login') ?? 'Login' ?>
                                    </button>
                                </form>
                                <div class="s26-auth-divider">
                                    <span><?= __('store.or') ?? 'or' ?></span>
                                </div>
                                <button id="btnGuestcontinues" class="s26-btn-outline w-100 justify-content-center">
                                    <i class="fas fa-user-secret"></i>
                                    <?= __('store.guest_checkout') ?? 'Guest Checkout' ?>
                                </button>
                            </div>
                        </div>

                        <div class="col-md-2 d-flex align-items-center justify-content-center">
                            <div class="s26-auth-separator d-none d-md-block"></div>
                        </div>

                        <!-- Register -->
                        <div class="col-md-5">
                            <div class="s26-auth-box">
                                <h5 class="s26-auth-box__title">
                                    <i class="fas fa-user-plus"></i>
                                    <?= __('store.create_a_new_account') ?? 'Create Account' ?>
                                </h5>
                                <form id="register-form">
                                    <input type="hidden" name="store_checkout" value="1">
                                    <div class="row g-3">
                                        <div class="col-6">
                                            <label class="s26-form-label"><?= __('store.first_name') ?></label>
                                            <input type="text" name="f_name" class="s26-form-control" placeholder="<?= __('store.first_name') ?>">
                                        </div>
                                        <div class="col-6">
                                            <label class="s26-form-label"><?= __('store.last_name') ?></label>
                                            <input type="text" name="l_name" class="s26-form-control" placeholder="<?= __('store.last_name') ?>">
                                        </div>
                                    </div>
                                    <div class="mb-3 mt-3">
                                        <label class="s26-form-label"><?= __('store.username') ?></label>
                                        <input type="text" name="username" class="s26-form-control" placeholder="<?= __('store.username') ?>">
                                    </div>
                                    <div class="mb-3">
                                        <label class="s26-form-label"><?= __('store.email') ?></label>
                                        <input type="email" name="email" class="s26-form-control" placeholder="<?= __('store.email') ?>">
                                    </div>
                                    <div class="mb-3">
                                        <label class="s26-form-label"><?= __('store.phone_number') ?></label>
                                        <input type="text" name="phone" id="phoneregister" onkeypress="return isNumberKey(event);" class="s26-form-control">
                                    </div>
                                    <script type="text/javascript">
                                        var tel_inputre = intlTelInput(document.querySelector("#phoneregister"), {
                                            initialCountry: "auto",
                                            utilsScript: "<?= base_url('/assets/plugins/tel/js/utils.js?1562189064761') ?>",
                                            separateDialCode: true,
                                            dropdownContainer: document.body,
                                            placeholderNumberType: "MOBILE",
                                            autoPlaceholder: "aggressive",
                                            geoIpLookup: function(success, failure) {
                                                $.get("https://ipinfo.io", function() {}, "jsonp").always(function(resp) {
                                                    var countryCode = (resp && resp.country) ? resp.country : "";
                                                    success(countryCode);
                                                });
                                            },
                                        });
                                    </script>
                                    <div class="row g-3 mb-3">
                                        <div class="col-6">
                                            <label class="s26-form-label"><?= __('store.password') ?></label>
                                            <input type="password" name="password" class="s26-form-control" placeholder="<?= __('store.password') ?>">
                                        </div>
                                        <div class="col-6">
                                            <label class="s26-form-label"><?= __('store.confirm_password') ?></label>
                                            <input type="password" name="c_password" class="s26-form-control" placeholder="<?= __('store.confirm_password') ?>">
                                        </div>
                                    </div>
                                    <?php if (isset($googlerecaptcha['client_register']) && $googlerecaptcha['client_register']) { ?>
                                    <div class="mb-3">
                                        <div class="g-recaptcha" id='client_register'></div>
                                        <input type="hidden" name="captch_response">
                                    </div>
                                    <?php } ?>
                                    <?php if (
                                        (isset($googlerecaptcha['client_register']) && $googlerecaptcha['client_register']) ||
                                        (isset($googlerecaptcha['client_login']) && $googlerecaptcha['client_login'])
                                    ) { ?>
                                    <script async defer src='https://www.google.com/recaptcha/api.js?onload=onloadCallback&render=explicit'></script>
                                    <script type="text/javascript">
                                        var gcaptch = {};
                                        var onloadCallback = function() {
                                            var recaptchas = document.querySelectorAll('div[class=g-recaptcha]');
                                            for (var i = 0; i < recaptchas.length; i++) {
                                                gcaptch[recaptchas[i].id] = grecaptcha.render(recaptchas[i].id, {
                                                    'sitekey' : '<?= $googlerecaptcha['sitekey'] ?>',
                                                });
                                            }
                                        }
                                    </script>
                                    <?php } ?>
                                    <button type="submit" class="s26-btn-primary w-100 justify-content-center">
                                        <?= __('store.register') ?? 'Register' ?>
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <?php } ?>

        <!-- ═══════════════ CART SUMMARY ═══════════════ -->
        <div class="cart-wrapper">
            <div class="s26-checkout-card checkout-setp cart-step">
                <div class="s26-checkout-card__header">
                    <i class="fas fa-receipt"></i>
                    <h3><?= __('store.purchase_of_details') ?? 'Order Summary' ?></h3>
                </div>
                <div class="s26-checkout-card__body">
                    <div class="cart-loader"></div>
                    <div class="cart-body"></div>
                </div>
            </div>
        </div>

        <!-- ═══════════════ SHIPPING FORM ═══════════════ -->
        <div class="non-confirm mt-4">
            <?php if(!empty($is_guest_flow) || $allow_shipping){ ?>
            <div class="checkout-form" <?= (!$is_logged) ? (empty($is_guest_flow) ? 'style="display:none;"' : "") : ""; ?>>
                <div class="s26-checkout-card checkout-setp shipping-step">
                    <div class="s26-checkout-card__header">
                        <i class="fas fa-map-marker-alt"></i>
                        <h3><?php echo $allow_shipping == 1 ? __('store.billing_shipping_address') : __('store.billing_address'); ?></h3>
                    </div>
                    <div class="s26-checkout-card__body">
                        <?php if(isset($shipping_error_message) && $shipping_error_message !== ''){ ?>
                            <div class="alert alert-danger s26-alert">
                                <i class="fas fa-exclamation-triangle"></i>
                                <?= htmlspecialchars($shipping_error_message) ?>
                            </div>
                        <?php } ?>
                        <div class="cart-loader"></div>
                        <div class="cart-body">
                            <?php if (!$allow_shipping && !$is_logged): ?>
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="s26-form-label"><?= __('store.enter_your_first_name') ?></label>
                                        <input type="text" placeholder="<?= __('store.enter_your_first_name') ?>" name="firstname" class="s26-form-control" required>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="s26-form-label"><?= __('store.enter_your_last_name') ?></label>
                                        <input type="text" placeholder="<?= __('store.enter_your_last_name') ?>" name="lastname" class="s26-form-control" required>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="s26-form-label"><?= __('store.enter_your_email_address') ?></label>
                                        <input type="email" placeholder="<?= __('store.enter_your_email_address') ?>" name="email" class="s26-form-control" required>
                                        <input type="hidden" name="classified_checkout" value="1">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="s26-form-label"><?= __('store.phone') ?></label>
                                        <input type="hidden" id="phonenumber-input" name='PhoneNumberInput' value="" class="s26-form-control">
                                        <div>
                                            <input onkeypress="return isNumberKey(event);" id="phoneguest" placeholder="<?= __('store.phone') ?>" class="s26-form-control" type="text" name="phone" value="">
                                        </div>
                                    </div>
                                    <script type="text/javascript">
                                        var tel_input = intlTelInput(document.querySelector("#phoneguest"), {
                                            initialCountry: "auto",
                                            utilsScript: "<?= base_url() ?>assets/plugins/tel/js/utils.js?1562189064761",
                                            separateDialCode: true,
                                            dropdownContainer: document.body,
                                            placeholderNumberType: "MOBILE",
                                            autoPlaceholder: "aggressive",
                                            geoIpLookup: function(success, failure) {
                                                $.get("https://ipinfo.io", function() {}, "jsonp").always(function(resp) {
                                                    var countryCode = (resp && resp.country) ? resp.country : "";
                                                    success(countryCode);
                                                });
                                            },
                                        });
                                    </script>
                                </div>
                            </div>
                            <?php endif ?>
                        </div>
                    </div>
                </div>
            </div>
            <?php } ?>
        </div>

        <!-- ═══════════════ PAYMENT SECTION ═══════════════ -->
        <?php
        $check_total_for_skip_payment = 1;
        foreach ($totals as $key => $value) {
            $check_total_for_skip_payment = $value['amount'];
        }

        if($check_total_for_skip_payment > 0) { ?>
        <div class="non-confirm">
            <div class="checkout-payments mt-4" <?= (!$is_logged) ? (empty($is_guest_flow) ? 'style="display:none;"' : "") : ""; ?>>
                <div class="s26-checkout-card checkout-setp">
                    <div class="s26-checkout-card__header">
                        <i class="fas fa-credit-card"></i>
                        <h3><?= __('store.payment_methods') ?? 'Payment Method' ?></h3>
                    </div>
                    <div class="s26-checkout-card__body">
                        <div class="dynamic-payment"></div>

                        <?php if($allow_upload_file){ ?>
                        <div class="s26-file-upload mt-4">
                            <p class="s26-form-label">
                                <i class="fas fa-paperclip"></i>
                                <?= __('store.add_files_to_your_order') ?>
                                <small class="text-muted d-block">(gif, jpeg, jpg, png, pdf, doc, docx, zip, tar)</small>
                            </p>
                            <link rel="stylesheet" type="text/css" href="<?= base_url('assets/template/css/jquery.uploadPreviewer.css') ?>?v=<?= av() ?>">
                            <div class="s26-upload-zone">
                                <div class="s26-upload-btn">
                                    <i class="fas fa-cloud-upload-alt"></i>
                                    <?= __('store.order_upload_file') ?? 'Upload Files' ?>
                                    <input type="file" class="downloadable_file_input" multiple="">
                                </div>
                                <div id="priview-table" class="table-responsive mt-3" style="display:none;">
                                    <table class="table table-sm"><tbody></tbody></table>
                                </div>
                            </div>
                        </div>
                        <?php } ?>

                        <!-- Order Comment -->
                        <div class="s26-order-comment mt-4">
                            <label class="s26-form-label" for="orderComment">
                                <i class="fas fa-comment-dots me-1"></i>
                                <?= __('store.order_comments') ?? 'Order Notes' ?>
                            </label>
                            <textarea name="order_comment" id="orderComment" class="s26-form-control" rows="3" placeholder="<?= __('store.order_comments') ?? 'Notes about your order...' ?>"></textarea>
                        </div>

                        <!-- Agree + Confirm Section -->
                        <div class="s26-checkout-confirm-wrap mt-4">
                            <div class="s26-agree-row">
                                <div class="form-check d-flex align-items-start gap-2">
                                    <input type="checkbox" class="form-check-input s26-agree-check" id="customCheck" name="agree" value="1">
                                    <label class="form-check-label s26-agree-label" for="customCheck"><?= __('store.agree_text') ?></label>
                                </div>
                            </div>

                            <div class="warning-div mt-3"></div>

                            <div class="mt-4">
                                <button type="button" class="s26-btn-primary s26-btn--lg w-100 justify-content-center confirm-order">
                                    <i class="fas fa-lock me-2"></i>
                                    <?= __('store.confirm_and_pay') ?? 'Confirm & Pay' ?>
                                </button>
                                <p class="s26-secure-note text-center mt-3">
                                    <i class="fas fa-shield-alt me-1"></i>
                                    <?= __('store.secure_checkout') ?? 'Your payment information is encrypted and secure.' ?>
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <?php } else { ?>
        <div class="non-confirm">
            <div class="checkout-payments mt-4" <?= (!$is_logged) ? (empty($is_guest_flow) ? 'style="display:none;"' : "") : ""; ?>>
                <div class="s26-checkout-card checkout-setp">
                    <div class="s26-checkout-card__body">
                        <div class="s26-checkout-confirm-wrap">
                            <div class="s26-agree-row">
                                <div class="form-check d-flex align-items-start gap-2">
                                    <input type="checkbox" class="form-check-input s26-agree-check" id="customCheck" name="agree" value="1">
                                    <label class="form-check-label s26-agree-label" for="customCheck"><?= __('store.agree_text') ?></label>
                                </div>
                            </div>
                            <div class="warning-div mt-3"></div>
                            <div class="mt-4">
                                <button type="button" class="s26-btn-primary s26-btn--lg w-100 justify-content-center confirm-order">
                                    <i class="fas fa-check-circle me-2"></i>
                                    <?= __('store.confirm_order') ?? 'Confirm Order' ?>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <?php } ?>

        <!-- ═══════════════ CONFIRM STEP (hidden by default) ═══════════════ -->
        <div class="confirm-checkout" style="display:none;">
            <div class="s26-checkout-card checkout-setp confirm-step mt-4">
                <div class="s26-checkout-card__header">
                    <i class="fas fa-check-double" style="color:var(--s26-success)"></i>
                    <h3><?= __('store.complete_your_order') ?? 'Complete Your Order' ?></h3>
                </div>
                <div class="s26-checkout-card__body" id="checkout-confirm">
                </div>
            </div>
        </div>

    </div>
</section>

<!-- ═══════════════ CHECKOUT JAVASCRIPT ═══════════════ -->
<script type="text/javascript">
var isGuest = '<?= !empty($is_guest_flow) ? 1 : '' ?>';
var allow_shipping = '<?= $allow_shipping ?>';

$('[name="payment_gateway"]').on('change', function(){
    if($(this).val() == 'bank_transfer'){
        $('.bank-transfer-instruction').slideDown();
    } else {
        $('.bank-transfer-instruction').slideUp();
    }
});

$(".cart-step").delegate(".btn-remove-cart", "click", function(){
    $this = $(this);
    $.ajax({
        url: $this.attr("data-href"),
        type: 'POST',
        dataType: 'json',
        success: function(json){ getCart(); }
    });
    return false;
});

$(".cart-step").on('click', '.btn-apply-coupon', function() {
    var $btn = $(this);
    var $wrap = $btn.closest('.s26-coupon-input-group');
    var coupon_code = $wrap.find('.coupon-code').val().trim();
    var product_id  = $wrap.find('.first-product-id').val();
    if (!coupon_code) return;
    $btn.prop('disabled', true).css('opacity', '0.7');
    $.ajax({
        url: '<?= base_url('store/add_coupon') ?>',
        type: 'POST',
        dataType: 'json',
        data: { coupon_code: coupon_code, product_id: product_id },
        success: function(json) {
            if (json['success']) {
                getCart();
            } else if (json['error']) {
                $(".cart-step .coupon-msg").html('<span style="color:#ef4444">' + json['error'] + '</span>');
                $btn.prop('disabled', false).css('opacity', '1');
            }
        },
        error: function() {
            $btn.prop('disabled', false).css('opacity', '1');
        }
    });
});

$(".cart-step").on('click', '.btn-remove-coupon', function() {
    $.ajax({
        url: '<?= base_url('store/clear_coupon') ?>',
        type: 'POST',
        dataType: 'json',
        success: function() { getCart(); }
    });
});

var xhr;
$(".cart-step").delegate(".qty-input", "change", function(){
    if(xhr && xhr.readyState != 4) xhr.abort();
    $this = $(this);
    xhr = $.ajax({
        url: '<?= $cart_update_url ?>',
        type: 'POST',
        dataType: 'json',
        data: $("#checkout-cart-form").serialize(),
        success: function(json){
            getCart();
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
    });
    return false;
});

var cart_xhr;
function getCart() {
    if(cart_xhr && cart_xhr.readyState != 4) cart_xhr.abort();
    cart_xhr = $.ajax({
        url: '<?= base_url('store/checkout-cart') ?>',
        type: 'POST',
        dataType: 'html',
        beforeSend: function(){
            $(".cart-step .cart-body").html('<div class="text-center p-4"><div class="spinner-border spinner-border-sm text-primary"></div> <span class="text-muted ms-2"><?= __('store.loading') ?? 'Loading...' ?></span></div>');
        },
        success: function(html){ $(".cart-step .cart-body").html(html); },
        error: function(){ $(".cart-step .cart-body").html('<div class="text-danger p-3"><?= __('store.error_updating_cart') ?? 'Error updating cart.' ?></div>'); }
    });
}

function getShipping(countryCode) {
    countryCode = countryCode || null;
    if(countryCode != null) {
        $(".shipping-step .cart-body").load('<?= base_url('store/checkout_shipping') ?>/' + countryCode);
    } else {
        $(".shipping-step .cart-body").load('<?= base_url('store/checkout_shipping') ?>');
    }
}

function getPaymentMethods(){
    $.ajax({
        url: '<?= base_url('store/get_payment_mothods') ?>',
        type: 'POST',
        dataType: 'json',
        data: { data: $("#checkout-cart-form").serialize() },
        success: function(json){ $(".dynamic-payment").html(json['html']); }
    });
}

<?php if(!$allow_shipping){ ?>
    getCart();
<?php } ?>
if(allow_shipping) getShipping();
getPaymentMethods();

$('.shipping-step').delegate('[name="country"]', "change", function(){
    $this = $(this);
    let countryCode = $(this).val();
    if(isGuest) renderStateAndCart(countryCode);
    else getShipping(countryCode);
});

function renderStateAndCart(countryCode) {
    $.ajax({
        url: '<?= base_url('store/getState') ?>',
        type: 'POST',
        dataType: 'json',
        data: { id: countryCode, checkShipping: true },
        beforeSend: function(){ $('[name="state"]').prop("disabled", true); },
        complete: function(){ $('[name="state"]').prop("disabled", false); },
        success: function(json){
            $(".shipping-warning").html('');
            var html = '<option value=""><?= __('store.select_state') ?></option>';
            $.each(json['states'], function(i,j){
                var s = '';
                if(selected_state && selected_state == j['id']){ s = 'selected'; selected_state = 0; }
                html += "<option " + s + " value='" + j['id'] + "'>" + j['name'] + "</option>";
            });
            $('[name="state"]').html(html);
            getCart();
        }
    });
}

$(".confirm-order").on('click', function(){
    let phoneNumberInp = null;
    if($("#phone").length) phoneNumberInp = $("#phone");
    else if($("#phoneguest").length) phoneNumberInp = $("#phoneguest");

    if(phoneNumberInp !== null) {
        var errorMap = ['<?= __('store.invalid_number') ?>','<?= __('store.invalid_country_code') ?>','<?= __('store.too_short') ?>','<?= __('store.too_long') ?>','<?= __('store.invalid_number') ?>'];
        var is_valid = false;
        var errorInnerHTML = '';
        if (phoneNumberInp.val().trim()) {
            if (tel_input.isValidNumber()) {
                is_valid = true;
                tel_input.setNumber(phoneNumberInp.val().trim());
                $("#phonenumber-input").val("+" + tel_input.getSelectedCountryData().dialCode + ' ' + phoneNumberInp.val().trim());
            } else {
                var errorCode = tel_input.getValidationError();
                errorInnerHTML = errorMap[errorCode];
            }
        } else {
            errorInnerHTML = '<?= __('store.mobile_number_is_required') ?>';
        }
        $(".checkout-form .text-danger").remove();
        if(!is_valid){
            phoneNumberInp.parents(".form-group").addClass("has-error");
            phoneNumberInp.parents(".form-group").find('> div').after("<span class='text-danger'>" + errorInnerHTML + "</span>");
            return false;
        }
    }

    $this = $(this);
    $container = $(".checkout-setp");
    var formData = new FormData();
    $container.find("input[type=text],input[type=email],input[type=tel],input[type=hidden],input[type=file],select,input[type=checkbox]:checked,input[type=radio]:checked,textarea").each(function(i,j){
        formData.append($(j).attr("name"), $(j).val());
    });
    if(typeof fileArray != 'undefined'){
        $.each(fileArray, function(i,j){ formData.append("downloadable_file[]", j.rawData); });
    }
    formData = formDataFilter(formData);

    $.ajax({
        url: '<?= $base_url ?>confirm_order',
        type: 'POST',
        cache: false,
        contentType: false,
        processData: false,
        data: formData,
        beforeSend: function(){ $this.btn("loading"); },
        complete: function(){ $this.btn("reset"); },
        success: function(result){
            $container.find(".has-error").removeClass("has-error");
            $container.find("span.text-danger,.alert-danger").remove();
            if(IsJsonString(result)){
                var result = $.parseJSON(result);
                if(result['confirm']){
                    $("#checkout-confirm").html(result['confirm']);
                    $(".confirm-checkout").show();
                    $(".non-confirm").hide();
                    // Update step indicators to show confirm as active
                    $(".s26-checkout-step").each(function(){
                        var step = $(this).data('step');
                        if(step < 4) $(this).removeClass('active').addClass('completed');
                        if(step == 4) $(this).addClass('active');
                    });
                    $(".s26-checkout-step__line").addClass('completed');
                    // Scroll to confirm section
                    $('html, body').animate({ scrollTop: $(".confirm-checkout").offset().top - 100 }, 400);
                }
                if(result['error']){
                    $(".warning-div").html('<div class="alert alert-danger alert-dismissible fade show"><i class="fas fa-exclamation-triangle me-2"></i>' + result['error'] + '<button type="button" onclick="$(this).parent().remove()" style="background:transparent;border:0;font-size:1.4rem;line-height:1;font-weight:700;opacity:.8;cursor:pointer;padding:0 2px;" aria-label="Close">&times;</button></div>');
                    $('html,body').animate({ scrollTop: $(".warning-div").offset().top - 80 }, 300);
                }
                if(result['errors']){
                    var unmatchedErrs = [];
                    $.each(result['errors'], function(i,j){
                        $ele = $container.find('[name="' + i + '"]');
                        if($ele.length){
                            $ele.parents(".form-group").addClass("has-error");
                            $ele.after("<span class='text-danger'>" + j + "</span>");
                        } else {
                            unmatchedErrs.push(j);
                        }
                    });
                    if (unmatchedErrs.length) {
                        var warnHtml = '<div class="alert alert-danger alert-dismissible fade show"><i class="fas fa-exclamation-triangle me-2"></i>' + unmatchedErrs.join('<br>') + '<button type="button" onclick="$(this).parent().remove()" style="background:transparent;border:0;font-size:1.4rem;line-height:1;font-weight:700;opacity:.8;cursor:pointer;padding:0 2px;" aria-label="Close">&times;</button></div>';
                        $(".warning-div").html(($(".warning-div").html() || '') + warnHtml);
                    }
                    $('html,body').animate({ scrollTop: $(".warning-div").offset().top - 80 }, 300);
                }
            } else {
                $("#checkout-confirm").html(result);
                $(".confirm-checkout").show();
                $(".non-confirm").hide();
                // Update step indicators
                $(".s26-checkout-step").each(function(){
                    var step = $(this).data('step');
                    if(step < 4) $(this).removeClass('active').addClass('completed');
                    if(step == 4) $(this).addClass('active');
                });
                $(".s26-checkout-step__line").addClass('completed');
                $('html, body').animate({ scrollTop: $(".confirm-checkout").offset().top - 100 }, 400);
            }
        }
    });
});

function IsJsonString(str) { try { JSON.parse(str); } catch (e) { return false; } return true; }
function backCheckout(){
    $("#checkout-confirm").html('');
    $(".confirm-checkout").hide();
    $(".non-confirm").show();
    // Reset step indicators
    $(".s26-checkout-step").each(function(){
        var step = $(this).data('step');
        if(step == 1) $(this).addClass('completed').removeClass('active');
        if(step == 2) $(this).addClass('active').removeClass('completed');
        if(step == 3 || step == 4) $(this).removeClass('active completed');
    });
    $(".s26-checkout-step__line").eq(0).addClass('completed');
    $(".s26-checkout-step__line").eq(1).removeClass('completed');
    $(".s26-checkout-step__line").eq(2).removeClass('completed');
    $('html, body').animate({ scrollTop: 0 }, 300);
}

$("#login-form").on('submit', function(){
    $this = $(this);
    $.ajax({
        url: '<?= $base_url ?>ajax_login',
        type: 'POST',
        dataType: 'json',
        data: $this.serialize(),
        beforeSend: function(){ $this.find("button[type=submit]").btn("loading"); },
        complete: function(){ $this.find("button[type=submit]").btn("reset"); },
        success: function(result){
            $this.find(".has-error").removeClass("has-error");
            $this.find("span.text-danger").remove();
            if(result['success']) location = '<?= $checkout_url ?>';
            if(result['errors']){
                $.each(result['errors'], function(i,j){
                    if(i=='captch_response' && typeof gcaptch['client_login'] != 'undefined') grecaptcha.reset(gcaptch['client_login']);
                    $ele = $this.find('[name="' + i + '"]');
                    if($ele.length){ $ele.parents(".form-group").addClass("has-error"); $ele.after("<span class='text-danger'>" + j + "</span>"); }
                });
            }
        }
    });
    return false;
});

$("#register-form").on('submit', function(e){
    e.preventDefault();
    $this = $(this);
    var errorMap = ['<?= __('store.invalid_number') ?>','<?= __('store.invalid_country_code') ?>','<?= __('store.too_short') ?>','<?= __('store.too_long') ?>','<?= __('store.invalid_number') ?>'];
    var is_valid = false, errorInnerHTML = '';
    if ($("#phoneregister").val().trim()) {
        if (tel_inputre.isValidNumber()) {
            is_valid = true;
            tel_inputre.setNumber($("#phoneregister").val().trim());
            $("#register-form").find('input[name="PhoneNumberInput"]').val("+" + tel_inputre.getSelectedCountryData().dialCode + ' ' + $("#phoneregister").val().trim());
        } else { errorInnerHTML = errorMap[tel_inputre.getValidationError()]; }
    } else { errorInnerHTML = '<?= __('store.mobile_number_is_required') ?>'; }
    $("#phoneregister").parents(".form-group").removeClass("has-error");
    $("#register-form .text-danger").remove();
    if(!is_valid){
        $("#phoneregister").parents(".form-group").addClass("has-error");
        $("#phoneregister").parents(".form-group").find('> div').after("<span class='text-danger'>" + errorInnerHTML + "</span>");
        return false;
    }
    $.ajax({
        url: '<?= $base_url ?>ajax_register',
        type: 'POST',
        dataType: 'json',
        data: $this.serialize(),
        beforeSend: function(){ $this.find("button[type=submit]").btn("loading"); },
        complete: function(){ $this.find("button[type=submit]").btn("reset"); },
        success: function(result){
            $this.find(".has-error").removeClass("has-error");
            $this.find("span.text-danger").remove();
            if(result['success']) location = '<?= $checkout_url ?>';
            if(result['errors']){
                $.each(result['errors'], function(i,j){
                    if(i=='captch_response' && typeof gcaptch['client_register'] != 'undefined') grecaptcha.reset(gcaptch['client_register']);
                    $ele = $this.find('[name="' + i + '"]');
                    if($ele.length){ $ele.parents(".form-group").addClass("has-error"); $ele.after("<span class='text-danger'>" + j + "</span>"); }
                });
            }
        }
    });
    return false;
});

$(document).delegate(".cart-counter button", "click", function(){
    var val = $(this).parent().find("input").val();
    if($(this).hasClass("plus")) val++;
    else val--;
    if(val <= 0) val = 1;
    $(this).parent().find("input").val(val).trigger("change");
});

var fileArray = [];
$('.downloadable_file_input').on('change', function(e){
    $.each(e.target.files, function(index, value){
        var fileReader = new FileReader();
        fileReader.readAsDataURL(value);
        fileReader.name = value.name;
        fileReader.rawData = value;
        fileArray.push(fileReader);
    });
    render_priview();
});

function render_priview() {
    var html = '';
    $.each(fileArray, function(i,j){
        html += '<tr><td>' + j.name + '</td><td><button type="button" class="btn btn-sm btn-danger" onClick="removeTr(this)" data-id="' + i + '"><?= __('store.remove') ?? 'Remove' ?></button></td></tr>';
    });
    $("#priview-table tbody").html(html);
    html ? $("#priview-table").show() : $("#priview-table").hide();
}
function removeTr(t){ if(!confirm('<?= __('store.are_you_sure') ?>')) return false; fileArray.splice($(t).attr("data-id"), 1); render_priview(); }

$("#btnGuestcontinues").click(function(){
    $.ajax({
        url: '<?= base_url() ?>store/guestCheckout',
        type: 'POST',
        dataType: 'json',
        success: function(result){
            if(result.status) {
                $(".checkout-form").show();
                $(".checkout-payments").show();
                $("#s26-auth-section").hide();
                window.location.reload();
            }
        }
    });
});

function isNumberKey(evt){
    var charCode = (evt.which) ? evt.which : event.keyCode;
    if (charCode != 46 && charCode != 45 && charCode > 31 && (charCode < 48 || charCode > 57)) return false;
    return true;
}
</script>
<?php if(!empty($confirm_html)): ?>
<script>
$(function(){
    $("#checkout-confirm").html(<?= json_encode($confirm_html) ?>);
    $(".confirm-checkout").show();
    $(".non-confirm").hide();
    $('html,body').animate({ scrollTop: $(".confirm-checkout").offset().top - 80 }, 400);
});
</script>
<?php endif; ?>
