<?php
/**
 * Starter 2026 — One-Page Checkout (partial / alternate checkout mode)
 *
 * @contract  Store API v1 — fragment: checkout_onepage
 * @note      Used when the store setting "one_page_checkout" is enabled.
 *            Receives the same variables as checkout.php.
 *
 * GLOBALS  $store_setting, $client, $home_link, $base_url, $googlerecaptcha
 *
 * VARIABLES (same contract as checkout.php)
 *   $products               array        Cart items
 *   $totals                 array        Subtotal, shipping, coupon, grand_total
 *   $is_logged              array|false  Logged-in customer (false = guest)
 *   $settings               array        Store settings
 *   $paymentsetting         array        Enabled payment methods and public keys
 *   $allow_shipping         bool         Whether shipping is required
 *   $allow_upload_file      bool         Whether proof-of-payment upload is enabled
 *   $shipping_error_message string       Error if shipping unavailable to user country
 *   $countries              array        All countries for shipping address selector
 *   $is_guest_flow          bool         Whether this is a guest checkout session
 *   $checkout_url           string       Form POST action URL
 *   $cart_update_url        string       URL for cart quantity-update AJAX calls
 *   $sub_total              string       Formatted subtotal
 *   $total                  string       Formatted total (alias of sub_total at checkout stage)
 *   $show_blue_message      bool         Show shipping restriction notice banner
 *   $confirm_html           string       Pre-rendered payment confirmation step HTML (if resuming)
 */
$store_setting = $store_setting ?? ($settings ?? []);
$currency = $store_setting['currency_sign'] ?? '$';
$is_rtl = (isset($store_setting['store_direction']) && $store_setting['store_direction'] == 'rtl');
$confirm_order_url = (isset($base_url) ? $base_url : base_url('store/')) . 'confirm_order';
?>

<link rel="stylesheet" href="<?= base_url('assets/plugins/tel/css/intlTelInput.css') ?>?v=<?= av() ?>">
<script src="<?= base_url('assets/plugins/tel/js/intlTelInput.js') ?>"></script>

<div class="container">
    <nav class="s26-breadcrumb" aria-label="Breadcrumb">
        <a href="<?= $home_link ?? $base_url ?>"><?= __('store.home') ?? 'Home' ?></a>
        <span class="separator"><i class="fas fa-chevron-right"></i></span>
        <span class="current"><?= __('store.checkout') ?? 'Checkout' ?></span>
    </nav>
</div>

<!-- Progress Bar -->
<div class="container">
    <div class="s26-checkout-steps">
        <div class="s26-checkout-step active"><div class="s26-checkout-step__icon"><i class="fas fa-shopping-cart"></i></div><span class="s26-checkout-step__label"><?= __('store.cart') ?? 'Cart' ?></span></div>
        <div class="s26-checkout-step__line completed"></div>
        <div class="s26-checkout-step active"><div class="s26-checkout-step__icon"><i class="fas fa-truck"></i></div><span class="s26-checkout-step__label"><?= __('store.shipping') ?? 'Shipping' ?></span></div>
        <div class="s26-checkout-step__line completed"></div>
        <div class="s26-checkout-step active"><div class="s26-checkout-step__icon"><i class="fas fa-credit-card"></i></div><span class="s26-checkout-step__label"><?= __('store.payment') ?? 'Payment' ?></span></div>
    </div>
</div>

<section class="s26-checkout-page">
    <div class="container">
        <div id="s26-onepage-main">
        <div class="row g-4">
            <!-- Left Column: Forms -->
            <div class="col-lg-7">
                <form id="checkout-one-page-form" method="post" action="#" enctype="multipart/form-data">

                    <!-- Guest fields when no shipping (firstname, lastname, email required by confirm_order) -->
                    <?php if (!$allow_shipping && !$is_logged): ?>
                    <div class="s26-checkout-card mb-4">
                        <div class="s26-checkout-card__header">
                            <i class="fas fa-user"></i>
                            <h3><?= __('store.customer_info') ?? 'Customer Information' ?></h3>
                        </div>
                        <div class="s26-checkout-card__body">
                            <input type="hidden" name="classified_checkout" value="1">
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <label class="s26-form-label"><?= __('store.first_name') ?> <span class="text-danger">*</span></label>
                                    <input type="text" name="firstname" class="s26-form-control" required placeholder="<?= __('store.enter_your_first_name') ?>">
                                </div>
                                <div class="col-md-6">
                                    <label class="s26-form-label"><?= __('store.last_name') ?> <span class="text-danger">*</span></label>
                                    <input type="text" name="lastname" class="s26-form-control" required placeholder="<?= __('store.enter_your_last_name') ?>">
                                </div>
                                <div class="col-md-6">
                                    <label class="s26-form-label"><?= __('store.email') ?> <span class="text-danger">*</span></label>
                                    <input type="email" name="email" class="s26-form-control" required placeholder="<?= __('store.enter_your_email_address') ?>">
                                </div>
                                <div class="col-md-6">
                                    <label class="s26-form-label"><?= __('store.phone') ?></label>
                                    <input type="hidden" id="phonenumber-input" name="PhoneNumberInput" value="">
                                    <input type="text" name="phone" id="s26-onepage-phone" class="s26-form-control" onkeypress="return isNumberKey(event);" placeholder="<?= __('store.phone') ?>">
                                </div>
                            </div>
                            <p class="mt-2 mb-0" style="font-size:13px">
                                <span class="text-muted"><?= __('store.already_have_account') ?? 'Already have an account?' ?></span>
                                <a href="<?= isset($base_url) ? $base_url : base_url('store/') ?>login" style="color:var(--s26-primary);font-weight:700"><?= __('store.login') ?></a>
                            </p>
                        </div>
                    </div>
                    <script>
                    var tel_input_onepage = intlTelInput(document.querySelector("#s26-onepage-phone"), {
                        initialCountry: "auto",
                        utilsScript: "<?= base_url('/assets/plugins/tel/js/utils.js?1562189064761') ?>",
                        separateDialCode: true,
                        dropdownContainer: document.body,
                        placeholderNumberType: "MOBILE",
                        autoPlaceholder: "aggressive",
                        geoIpLookup: function(success) {
                            $.get("https://ipinfo.io", function() {}, "jsonp").always(function(resp) {
                                success((resp && resp.country) ? resp.country : "");
                            });
                        },
                    });
                    </script>
                    <?php elseif (!$is_logged): ?>
                    <!-- Minimal hint when shipping loads guest fields -->
                    <div class="s26-checkout-card mb-4">
                        <div class="s26-checkout-card__body py-2">
                            <span class="text-muted"><?= __('store.already_have_account') ?? 'Already have an account?' ?></span>
                            <a href="<?= isset($base_url) ? $base_url : base_url('store/') ?>login" style="color:var(--s26-primary);font-weight:700"><?= __('store.login') ?></a>
                        </div>
                    </div>
                    <?php endif; ?>

                    <!-- Shipping restriction messages -->
                    <?php if (isset($shipping_error_message) && $shipping_error_message !== ''): ?>
                    <div class="alert alert-danger mb-3"><i class="fas fa-exclamation-circle me-2"></i><?= htmlspecialchars($shipping_error_message) ?></div>
                    <?php endif; ?>
                    <?php if (!$allow_shipping && !empty($show_blue_message)): ?>
                    <div class="alert alert-info mb-3"><i class="fas fa-info-circle me-2"></i><?= __('store.shipping_not_allows') ?></div>
                    <?php endif; ?>

                    <!-- Shipping Address -->
                    <?php if ($allow_shipping): ?>
                    <div class="s26-checkout-card mb-4">
                        <div class="s26-checkout-card__header">
                            <i class="fas fa-map-marker-alt"></i>
                            <h3><?= __('store.shipping_address') ?? 'Shipping Address' ?></h3>
                        </div>
                        <div class="s26-checkout-card__body">
                            <div id="s26-onepage-shipping">
                                <div class="text-center py-4">
                                    <div class="spinner-border spinner-border-sm text-primary"></div>
                                    <span class="text-muted ms-2"><?= __('store.loading') ?? 'Loading...' ?></span>
                                </div>
                            </div>
                        </div>
                    </div>
                    <?php endif; ?>

                    <!-- Payment -->
                    <div class="s26-checkout-card mb-4">
                        <div class="s26-checkout-card__header">
                            <i class="fas fa-credit-card"></i>
                            <h3><?= __('store.payment_method') ?? 'Payment Method' ?></h3>
                        </div>
                        <div class="s26-checkout-card__body">
                            <div id="s26-onepage-payment">
                                <div class="dynamic-payment"></div>
                            </div>
                        </div>
                    </div>

                    <!-- Upload File (per-product setting) -->
                    <?php if ($allow_upload_file): ?>
                    <div class="s26-checkout-card mb-4">
                        <div class="s26-checkout-card__header">
                            <i class="fas fa-paperclip"></i>
                            <h3><?= __('store.add_files_to_your_order') ?></h3>
                        </div>
                        <div class="s26-checkout-card__body">
                            <p class="text-muted small mb-2">(gif, jpeg, jpg, png, pdf, doc, docx, zip, tar)</p>
                            <link rel="stylesheet" type="text/css" href="<?= base_url('assets/template/css/jquery.uploadPreviewer.css') ?>?v=<?= av() ?>">
                            <div class="s26-upload-zone">
                                <div class="s26-upload-btn">
                                    <i class="fas fa-cloud-upload-alt"></i>
                                    <?= __('store.order_upload_file') ?>
                                    <input type="file" class="downloadable_file_input" multiple="">
                                </div>
                                <div id="priview-table" class="table-responsive mt-2" style="display:none;">
                                    <table class="table table-sm"><tbody></tbody></table>
                                </div>
                            </div>
                        </div>
                    </div>
                    <?php endif; ?>

                    <!-- Agree checkbox (required by confirm_order) -->
                    <div class="s26-checkout-card mb-4">
                        <div class="s26-checkout-card__body">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="agree" value="1" id="s26-onepage-agree" required>
                                <label class="form-check-label" for="s26-onepage-agree"><?= __('store.agree_text') ?></label>
                            </div>
                        </div>
                    </div>
                </form>
            </div>

            <!-- Right Column: Order Summary -->
            <div class="col-lg-5">
                <div class="s26-checkout-card s26-checkout-sticky">
                    <div class="s26-checkout-card__header">
                        <i class="fas fa-receipt"></i>
                        <h3><?= __('store.order_summary') ?? 'Order Summary' ?></h3>
                    </div>
                    <div class="s26-checkout-card__body">
                        <?php if (!empty($products)): ?>
                        <?php foreach ($products as $p): ?>
                        <div class="s26-onepage-item">
                            <div class="s26-onepage-item__img">
                                <img src="<?= !empty($p['product_featured_image']) ? $p['product_featured_image'] : base_url('assets/images/no-image.png') ?>"
                                     alt="" onerror="this.onerror=null;this.src='<?= base_url('assets/images/no-image.png') ?>'" loading="lazy">
                                <span class="s26-onepage-item__badge"><?= $p['quantity'] ?></span>
                            </div>
                            <div class="s26-onepage-item__info">
                                <p class="s26-onepage-item__name"><?= htmlspecialchars($p['product_name']) ?></p>
                                <?php if (!empty($p['variation_name'])): ?>
                                <small class="text-muted"><?= $p['variation_name'] ?></small>
                                <?php endif; ?>
                            </div>
                            <span class="s26-onepage-item__price"><?= c_format(($p['product_price'] + ($p['variation_price'] ?? 0)) * $p['quantity']) ?></span>
                        </div>
                        <?php endforeach; ?>
                        <?php endif; ?>

                        <!-- Coupon -->
                        <div class="s26-coupon-input-group mt-3" style="padding:4px 4px 4px 14px;">
                            <i class="fas fa-ticket-alt" style="color:var(--s26-text-muted);font-size:13px"></i>
                            <input type="text" name="coupon_code" class="coupon-code" placeholder="<?= __('store.coupon_code') ?? 'Coupon Code' ?>" style="flex:1;border:none;outline:none;background:transparent;font-size:13px;font-family:var(--s26-font);padding:8px 0">
                            <button type="button" class="btn-apply-coupon" id="apply-coupon" style="background:var(--s26-dark);color:#fff;border:none;border-radius:var(--s26-radius-xs);padding:8px 16px;font-size:12px;font-weight:700;cursor:pointer"><?= __('store.apply') ?? 'Apply' ?></button>
                        </div>

                        <!-- Totals -->
                        <div class="s26-checkout-cart__totals mt-3">
                            <?php if (!empty($totals)): ?>
                            <?php foreach ($totals as $t): ?>
                            <div class="s26-checkout-cart__total-row">
                                <span class="text-muted"><?= $t['text'] ?? $t['title'] ?? '' ?></span>
                                <span class="fw-bold"><?= c_format($t['value'] ?? $t['amount'] ?? 0) ?></span>
                            </div>
                            <?php endforeach; ?>
                            <?php endif; ?>
                            <div class="s26-checkout-cart__total-row s26-checkout-cart__total-row--grand">
                                <span><?= __('store.total') ?? 'Total' ?></span>
                                <span style="color:var(--s26-primary)"><?= isset($total) ? c_format($total) : '' ?></span>
                            </div>
                        </div>

                        <div id="s26-onepage-warning" class="mt-3"></div>
                        <button type="submit" form="checkout-one-page-form" id="s26-place-order-btn" class="s26-btn-primary s26-btn--lg w-100 justify-content-center mt-4">
                            <i class="fas fa-lock"></i>
                            <?= __('store.place_order') ?? 'Place Order' ?>
                        </button>

                        <p class="text-center mt-3" style="font-size:12px;color:var(--s26-text-muted)">
                            <i class="fas fa-shield-alt me-1"></i>
                            <?= __('store.secure_checkout') ?? 'Secure & encrypted checkout' ?>
                        </p>
                    </div>
                </div>
            </div>
        </div>
        </div>

        <!-- Confirmation area (shown after order placed) -->
        <div id="s26-onepage-confirm" class="d-none mt-4">
            <div class="row g-4">
                <!-- Left: confirm content + payment form -->
                <div class="col-lg-7">
                    <div id="checkout-confirm"></div>
                </div>
                <!-- Right: order summary -->
                <div class="col-lg-5">
                    <div class="s26-checkout-card s26-checkout-sticky">
                        <div class="s26-checkout-card__header">
                            <i class="fas fa-receipt"></i>
                            <h3><?= __('store.order_summary') ?? 'Order Summary' ?></h3>
                        </div>
                        <div class="s26-checkout-card__body">
                            <?php foreach ($products as $p): ?>
                            <div class="s26-onepage-item">
                                <div class="s26-onepage-item__img">
                                    <img src="<?= !empty($p['product_featured_image']) ? $p['product_featured_image'] : base_url('assets/images/no-image.png') ?>"
                                         alt="" onerror="this.onerror=null;this.src='<?= base_url('assets/images/no-image.png') ?>'" loading="lazy">
                                    <span class="s26-onepage-item__badge"><?= $p['quantity'] ?></span>
                                </div>
                                <div class="s26-onepage-item__info">
                                    <p class="s26-onepage-item__name"><?= htmlspecialchars($p['product_name']) ?></p>
                                </div>
                                <span class="s26-onepage-item__price"><?= c_format(($p['product_price'] + ($p['variation_price'] ?? 0)) * $p['quantity']) ?></span>
                            </div>
                            <?php endforeach; ?>
                            <div class="s26-checkout-cart__totals mt-3">
                                <div class="s26-checkout-cart__total-row s26-checkout-cart__total-row--grand">
                                    <span><?= __('store.total') ?? 'Total' ?></span>
                                    <span style="color:var(--s26-primary)"><?= isset($total) ? c_format($total) : '' ?></span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<script>
function backCheckout() {
    $('#checkout-confirm').html('');
    $('#s26-onepage-confirm').addClass('d-none');
    $('#s26-onepage-main').removeClass('d-none');
    $('html,body').animate({ scrollTop: $('#s26-onepage-main').offset().top - 80 }, 400);
}

$(document).ready(function(){
    var $form = $('#checkout-one-page-form');
    var confirmOrderUrl = '<?= $confirm_order_url ?>';

    var shippingDiv = $('#s26-onepage-shipping');
    if (shippingDiv.length) {
        $.get('<?= base_url('store/checkout_shipping') ?>', function(html){ shippingDiv.html(html); })
            .fail(function(){ shippingDiv.html('<div class="alert alert-danger"><?= __('store.error_loading_shipping') ?></div>'); });
    }

    // Load payment methods
    $.ajax({
        url: '<?= base_url('store/get_payment_mothods') ?>',
        type: 'POST',
        dataType: 'json',
        data: { data: $form.serialize() },
        success: function(json){ $('#s26-onepage-payment .dynamic-payment').html(json && json.html ? json.html : ''); },
        error: function(){ $('#s26-onepage-payment .dynamic-payment').html('<p class="text-danger"><?= __('store.error_loading_payment') ?></p>'); }
    });

    // bank_transfer toggle
    $(document).on('change', '[name="payment_gateway"]', function(){
        if ($(this).val() == 'bank_transfer') $('.bank-transfer-instruction').slideDown();
        else $('.bank-transfer-instruction').slideUp();
    });

    // AJAX form submit - prevent normal POST (which used wrong URL and caused redirect)
    $form.on('submit', function(e){
        e.preventDefault();
        var $btn = $('#s26-place-order-btn');
        var $warning = $('#s26-onepage-warning');
        $warning.html('');
        $form.find('.has-error').removeClass('has-error');
        $form.find('.text-danger').remove();

        // Update PhoneNumberInput from intlTelInput if present
        var $phone = $('#phone');
        if (!$phone.length) $phone = $('#s26-onepage-phone');
        if ($phone.length && typeof tel_input !== 'undefined' && tel_input && $phone.val().trim()) {
            if (tel_input.isValidNumber()) {
                tel_input.setNumber($phone.val().trim());
                $('#phonenumber-input').val('+' + tel_input.getSelectedCountryData().dialCode + ' ' + $phone.val().trim());
            }
        } else if ($phone.length && typeof tel_input_onepage !== 'undefined' && tel_input_onepage && $phone.val().trim()) {
            if (tel_input_onepage.isValidNumber()) {
                tel_input_onepage.setNumber($phone.val().trim());
                $('#phonenumber-input').val('+' + tel_input_onepage.getSelectedCountryData().dialCode + ' ' + $phone.val().trim());
            }
        }

        var formData = new FormData(this);
        var couponVal = $('.s26-coupon-input-group .coupon-code').val();
        if (couponVal) formData.set('coupon_code', couponVal);
        if (typeof fileArray !== 'undefined') {
            $.each(fileArray, function(i, j){ formData.append('downloadable_file[]', j.rawData); });
        }

        $btn.prop('disabled', true).html('<i class="fas fa-spinner fa-spin me-2"></i><?= __('store.loading') ?>');

        $.ajax({
            url: confirmOrderUrl,
            type: 'POST',
            cache: false,
            contentType: false,
            processData: false,
            data: formData,
            dataType: 'text',
            success: function(result){
                $btn.prop('disabled', false).html('<i class="fas fa-lock"></i> <?= __('store.place_order') ?>');
                var json = null;
                try { json = typeof result === 'string' ? JSON.parse(result) : result; } catch(err) {}
                if (!json) {
                    $warning.html('<div class="alert alert-danger"><?= __('store.invalid_response') ?></div>');
                    return;
                }
                if (json.error) {
                    $warning.html('<div class="alert alert-danger">' + json.error + '</div>');
                    return;
                }
                if (json.errors) {
                    var unmatched = [];
                    $.each(json.errors, function(field, msg){
                        var $el = $form.find('[name="' + field + '"]');
                        if ($el.length) {
                            $el.closest('.s26-checkout-card__body, .form-check, .form-group').addClass('has-error');
                            $el.after('<span class="text-danger d-block small">' + msg + '</span>');
                        } else {
                            unmatched.push(msg);
                        }
                    });
                    if (unmatched.length) {
                        var alertHtml = '<div class="alert alert-danger alert-dismissible fade show"><i class="fas fa-exclamation-triangle me-2"></i>' + unmatched.join('<br>') + '<button type="button" onclick="$(this).parent().remove()" style="background:transparent;border:0;font-size:1.4rem;line-height:1;font-weight:700;opacity:.8;cursor:pointer;padding:0 2px;" aria-label="Close">&times;</button></div>';
                        $warning.html(($warning.html() || '') + alertHtml);
                    }
                    $('html,body').animate({ scrollTop: $warning.offset().top - 80 }, 300);
                    return;
                }
                if (json.confirm) {
                    $('#checkout-confirm').html(json.confirm);
                    $('#s26-onepage-main').addClass('d-none');
                    $('#s26-onepage-confirm').removeClass('d-none');
                    $('html,body').animate({ scrollTop: $('#s26-onepage-confirm').offset().top - 80 }, 400);
                    if (json.redirect) window.location.href = json.redirect;
                    return;
                }
                if (json.redirect && !json.confirm) { window.location.href = json.redirect; return; }
            },
            error: function(){
                $btn.prop('disabled', false).html('<i class="fas fa-lock"></i> <?= __('store.place_order') ?>');
                $warning.html('<div class="alert alert-danger"><?= __('store.error_submitting_order') ?></div>');
            }
        });
    });
});
</script>

<?php if ($allow_upload_file): ?>
<script>
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

function render_priview(){
    var html = '';
    $.each(fileArray, function(i,j){
        html += '<tr><td width="70px"><div class="upload-priview up-image"></div></td>';
        html += '<td>'+ j.name +'</td>';
        html += '<td width="70px"><button type="button" class="btn btn-danger btn-sm" onClick="removeTr(this)" data-id="'+ i +'"><?= __('store.remove') ?></button></td></tr>';
    });
    $('#priview-table tbody').html(html);
    $('#priview-table').toggle(html.length > 0);
}

function removeTr(t){
    if(!confirm('<?= __('store.are_you_sure') ?>')) return false;
    fileArray.splice($(t).attr('data-id'), 1);
    render_priview();
}
</script>
<?php endif; ?>

<?php if(!empty($confirm_html)): ?>
<script>
$(function(){
    $('#checkout-confirm').html(<?= json_encode($confirm_html) ?>);
    $('#s26-onepage-main').addClass('d-none');
    $('#s26-onepage-confirm').removeClass('d-none');
    $('html,body').animate({ scrollTop: $('#s26-onepage-confirm').offset().top - 80 }, 400);
});
</script>
<?php endif; ?>
