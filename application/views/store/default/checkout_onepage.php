<?php
/**
 * Default theme — One-page Checkout (all steps on a single page)
 *
 * @contract  Store API v1 — page: checkout (one-page variant)
 * @see       Store_cart_payload::page_checkout()
 * @see       /store/api/v1/pages/checkout
 *
 * GLOBALS (always available via Storeapp::view)
 *   $store_setting  array   All store settings key-value map
 *   $client         array   Logged-in customer array; empty array if guest
 *   $home_link      string  Absolute URL to store homepage
 *
 * PAGE VARIABLES  (same contract as checkout.php)
 *   $products              array   Cart items
 *   $totals                array   Cart totals {subtotal, shipping, coupon_discount, total}
 *   $is_logged             array   Logged-in customer; false if guest
 *   $settings              array   Store settings (alias of $store_setting)
 *   $paymentsetting        array   Raw payment settings
 *   $payment_gateways      array   Processed gateway list (via AJAX)
 *   $allow_shipping        bool    Whether shipping is required
 *   $allow_upload_file     bool    Whether proof-of-payment upload is enabled
 *   $shipping_error_message string Error if shipping not available (empty if OK)
 *   $show_blue_message     bool    Whether to show the shipping notice banner
 *   $is_guest_flow         bool    Whether this is a guest checkout session
 *   $confirm_html          string  Pre-rendered confirmation step HTML
 *   $order_comment_setting array   Order note/comment configuration {status, title[]}
 *   $checkout_url          string  Form action URL
 *   $cart_update_url       string  URL for cart quantity-update AJAX calls
 *   $sub_total             string  Formatted subtotal
 *   $total                 string  Formatted grand total
 */
// V14: One-page Checkout - All steps on a single page
$store_setting = isset($store_setting) ? $store_setting : (isset($settings) ? $settings : []);
$currency = isset($store_setting['currency_sign']) ? $store_setting['currency_sign'] : '$';
$is_rtl = (isset($store_setting['store_direction']) && $store_setting['store_direction'] == 'rtl');
$confirm_order_url = (isset($base_url) ? $base_url : base_url('store/')) . 'confirm_order';
$checkout_shipping_url = base_url('store/checkout_shipping');
$get_payment_url = base_url('store/get_payment_mothods');
?>
<link rel="stylesheet" href="<?= base_url('assets/plugins/tel/css/intlTelInput.css') ?>?v=<?= av() ?>">
<script src="<?= base_url('assets/plugins/tel/js/intlTelInput.js') ?>"></script>
<div class="container py-4">
    <div class="row justify-content-center">
        <div class="col-lg-10">
            <!-- Progress Indicator -->
            <div class="d-flex align-items-center justify-content-center mb-4 gap-3">
                <span class="badge bg-primary rounded-pill px-3 py-2"><i class="fas fa-shopping-cart me-1"></i> <?= __('store.cart') ?></span>
                <i class="fas fa-chevron-right text-muted"></i>
                <span class="badge bg-primary rounded-pill px-3 py-2"><i class="fas fa-truck me-1"></i> <?= __('store.shipping') ?></span>
                <i class="fas fa-chevron-right text-muted"></i>
                <span class="badge bg-primary rounded-pill px-3 py-2"><i class="fas fa-credit-card me-1"></i> <?= __('store.payment') ?></span>
            </div>

            <div id="onepage-checkout-main">
            <form id="onepage-checkout-form" method="post" action="#" enctype="multipart/form-data">
                <div class="row g-4">
                    <!-- Left Column: Shipping + Payment -->
                    <div class="col-lg-7">
                        <!-- Guest fields when no shipping (firstname, lastname, email required for confirm_order) -->
                        <?php if (!$allow_shipping && !$is_logged): ?>
                        <div class="card border-0 shadow-sm mb-4">
                            <div class="card-header bg-light border-0">
                                <h6 class="mb-0 fw-bold"><i class="fas fa-user me-2"></i><?= __('store.customer_info') ?></h6>
                            </div>
                            <div class="card-body">
                                <input type="hidden" name="classified_checkout" value="1">
                                <div class="row g-3">
                                    <div class="col-md-6">
                                        <label class="form-label"><?= __('store.first_name') ?> <span class="text-danger">*</span></label>
                                        <input type="text" name="firstname" class="form-control" required placeholder="<?= __('store.enter_your_first_name') ?>">
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label"><?= __('store.last_name') ?> <span class="text-danger">*</span></label>
                                        <input type="text" name="lastname" class="form-control" required placeholder="<?= __('store.enter_your_last_name') ?>">
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label"><?= __('store.email') ?> <span class="text-danger">*</span></label>
                                        <input type="email" name="email" class="form-control" required placeholder="<?= __('store.enter_your_email_address') ?>">
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label"><?= __('store.phone') ?></label>
                                        <input type="hidden" id="phonenumber-input" name="PhoneNumberInput" value="" class="form-control">
                                        <input type="text" id="phone" name="phone" class="form-control" onkeypress="return isNumberKey(event);" placeholder="<?= __('store.phone') ?>">
                                        <script>
                                        var tel_input = intlTelInput(document.querySelector('#phone'), {
                                            initialCountry: 'auto',
                                            utilsScript: '<?= base_url('/assets/plugins/tel/js/utils.js?1562189064761') ?>',
                                            separateDialCode: true,
                                            dropdownContainer: document.body,
                                            placeholderNumberType: 'MOBILE',
                                            autoPlaceholder: 'aggressive',
                                            geoIpLookup: function(success, failure) {
                                                $.get('https://ipinfo.io', function(){}, 'jsonp').always(function(resp){
                                                    var countryCode = (resp && resp.country) ? resp.country : '';
                                                    success(countryCode);
                                                });
                                            },
                                        });
                                        </script>
                                    </div>
                                </div>
                                <div class="mt-2">
                                    <small class="text-muted"><?= __('store.already_have_account') ?> <a href="<?= isset($base_url) ? $base_url : base_url('store/') ?>login"><?= __('store.login') ?></a></small>
                                </div>
                            </div>
                        </div>
                        <?php elseif (!$is_logged): ?>
                        <!-- Minimal customer hint when shipping loads guest fields -->
                        <div class="card border-0 shadow-sm mb-4">
                            <div class="card-body py-2">
                                <small class="text-muted"><?= __('store.already_have_account') ?> <a href="<?= isset($base_url) ? $base_url : base_url('store/') ?>login"><?= __('store.login') ?></a></small>
                            </div>
                        </div>
                        <?php endif; ?>

                        <!-- Shipping restriction messages -->
                        <?php if (isset($shipping_not_allow_error_message)): ?>
                        <div class="alert alert-danger mb-3"><i class="fas fa-exclamation-circle me-2"></i><?= $shipping_not_allow_error_message ?></div>
                        <?php endif; ?>
                        <?php if (!$allow_shipping && !empty($show_blue_message)): ?>
                        <div class="alert alert-info mb-3"><i class="fas fa-info-circle me-2"></i><?= __('store.shipping_not_allows') ?></div>
                        <?php endif; ?>

                        <!-- Shipping Address -->
                        <?php if ($allow_shipping): ?>
                        <div class="card border-0 shadow-sm mb-4">
                            <div class="card-header bg-light border-0">
                                <h6 class="mb-0 fw-bold"><i class="fas fa-map-marker-alt me-2"></i><?= __('store.shipping_address') ?></h6>
                            </div>
                            <div class="card-body">
                                <div id="onepage-shipping-form">
                                    <div class="text-center py-3">
                                        <div class="spinner-border spinner-border-sm text-primary" role="status"></div>
                                        <span class="ms-2 text-muted"><?= __('store.loading') ?>...</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <?php endif; ?>

                        <!-- Payment Method (loaded via get_payment_mothods for correct payment_gateway names) -->
                        <div class="card border-0 shadow-sm mb-4">
                            <div class="card-header bg-light border-0">
                                <h6 class="mb-0 fw-bold"><i class="fas fa-credit-card me-2"></i><?= __('store.payment_method') ?></h6>
                            </div>
                            <div class="card-body">
                                <div id="onepage-payment-methods">
                                    <div class="text-center py-3">
                                        <div class="spinner-border spinner-border-sm text-primary" role="status"></div>
                                        <span class="ms-2 text-muted"><?= __('store.loading') ?>...</span>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Upload File (per-product setting) -->
                        <?php if ($allow_upload_file): ?>
                        <div class="card border-0 shadow-sm mb-4">
                            <div class="card-header bg-light border-0">
                                <h6 class="mb-0 fw-bold"><i class="fas fa-paperclip me-2"></i><?= __('store.add_files_to_your_order') ?></h6>
                            </div>
                            <div class="card-body">
                                <p class="text-muted small mb-2">(gif, jpeg, jpg, png, pdf, doc, docx, zip, tar)</p>
                                <link rel="stylesheet" type="text/css" href="<?= base_url('assets/template/css/jquery.uploadPreviewer.css') ?>?v=<?= av() ?>">
                                <div class="mb-3 downloadable_file_div p-3 bg-light rounded border" style="white-space:inherit;">
                                    <div class="file-preview-button btn btn-outline-secondary">
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
                        <div class="card border-0 shadow-sm mb-4">
                            <div class="card-body">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" name="agree" value="1" id="onepage-agree" required>
                                    <label class="form-check-label" for="onepage-agree"><?= __('store.agree_text') ?></label>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Right Column: Order Summary -->
                    <div class="col-lg-5">
                        <div class="card border-0 shadow-sm sticky-top" style="top:20px">
                            <div class="card-header bg-light border-0">
                                <h6 class="mb-0 fw-bold"><i class="fas fa-receipt me-2"></i><?= __('store.order_summary') ?></h6>
                            </div>
                            <div class="card-body">
                                <?php if (!empty($products)): ?>
                                <?php foreach ($products as $p): ?>
                                <div class="d-flex align-items-center gap-3 mb-3 pb-3 border-bottom">
                                    <div class="position-relative flex-shrink-0">
                                        <img src="<?= !empty($p['product_featured_image']) ? $p['product_featured_image'] : base_url('assets/images/no-image.png') ?>" class="rounded" width="50" height="50" style="object-fit:cover" onerror="this.onerror=null;this.src='<?= base_url('assets/images/no-image.png') ?>'" alt="">
                                        <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-primary" style="font-size:10px"><?= $p['quantity'] ?></span>
                                    </div>
                                    <div class="flex-grow-1">
                                        <p class="mb-0 small fw-medium text-truncate" style="max-width:180px"><?= htmlspecialchars($p['product_name']) ?></p>
                                        <?php if (!empty($p['variation_name'])): ?>
                                        <small class="text-muted"><?= $p['variation_name'] ?></small>
                                        <?php endif; ?>
                                    </div>
                                    <span class="fw-medium"><?= c_format($p['product_price'] * $p['quantity']) ?></span>
                                </div>
                                <?php endforeach; ?>
                                <?php endif; ?>

                                <!-- Coupon -->
                                <div class="mb-3">
                                    <div class="input-group">
                                        <input type="text" name="coupon_code" class="form-control form-control-sm" placeholder="<?= __('store.coupon_code') ?>">
                                        <button type="button" class="btn btn-outline-primary btn-sm" id="apply-coupon"><?= __('store.apply') ?></button>
                                    </div>
                                </div>

                                <!-- Totals -->
                                <div class="border-top pt-3">
                                    <?php if (!empty($totals)): ?>
                                    <?php foreach ($totals as $t): ?>
                                    <div class="d-flex justify-content-between mb-2">
                                        <span class="text-muted"><?= $t['title'] ?? ($t['text'] ?? '') ?></span>
                                        <span><?= c_format($t['amount'] ?? ($t['value'] ?? 0)) ?></span>
                                    </div>
                                    <?php endforeach; ?>
                                    <?php endif; ?>
                                    <hr>
                                    <div class="d-flex justify-content-between">
                                        <span class="fw-bold fs-5"><?= __('store.total') ?></span>
                                        <span class="fw-bold fs-5 text-primary"><?= c_format($total) ?></span>
                                    </div>
                                </div>

                                <div id="onepage-warning-div" class="mt-2"></div>
                                <button type="submit" id="onepage-place-order" class="btn btn-primary w-100 mt-4 py-3 fw-bold">
                                    <i class="fas fa-lock me-2"></i><?= __('store.place_order') ?>
                                </button>

                                <div class="text-center mt-2">
                                    <small class="text-muted"><i class="fas fa-shield-alt me-1"></i> <?= __('store.secure_checkout') ?></small>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
            </div>

            <!-- Confirmation area (shown after order placed, like multi-step) -->
            <div id="onepage-confirm-area" class="d-none">
                <div class="row g-4">
                    <!-- Left: payment gateway + confirm content -->
                    <div class="col-lg-7">
                        <div id="checkout-confirm"></div>
                    </div>
                    <!-- Right: order summary -->
                    <div class="col-lg-5">
                        <div class="card border-0 shadow-sm sticky-top" style="top:20px">
                            <div class="card-header bg-light border-0">
                                <h6 class="mb-0 fw-bold"><i class="fas fa-receipt me-2"></i><?= __('store.order_summary') ?></h6>
                            </div>
                            <div class="card-body">
                                <?php foreach ($products as $p): ?>
                                <div class="d-flex justify-content-between align-items-center mb-2 pb-2 border-bottom">
                                    <div>
                                        <p class="mb-0 small fw-medium"><?= htmlspecialchars($p['product_name']) ?></p>
                                        <small class="text-muted"><?= __('store.qty') ?>: <?= $p['quantity'] ?></small>
                                    </div>
                                    <span class="fw-medium"><?= c_format($p['product_price'] * $p['quantity']) ?></span>
                                </div>
                                <?php endforeach; ?>
                                <div class="d-flex justify-content-between mt-3 pt-1">
                                    <span class="fw-bold"><?= __('store.total') ?></span>
                                    <span class="fw-bold text-primary fs-5"><?= c_format($total) ?></span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
function backCheckout() {
    $('#checkout-confirm').html('');
    $('#onepage-confirm-area').addClass('d-none');
    $('#onepage-checkout-main').removeClass('d-none');
    $('html,body').animate({ scrollTop: $('#onepage-checkout-main').offset().top - 80 }, 400);
}

$(document).ready(function(){
    var $form = $('#onepage-checkout-form');
    var confirmOrderUrl = '<?= $confirm_order_url ?>';
    var shippingUrl = '<?= $checkout_shipping_url ?>';
    var paymentUrl = '<?= $get_payment_url ?>';

    // Load shipping form
    var $shippingDiv = $('#onepage-shipping-form');
    if ($shippingDiv.length) {
        $.get(shippingUrl, function(html){
            $shippingDiv.html(html);
        }).fail(function(){
            $shippingDiv.html('<div class="alert alert-danger"><?= __('store.error_loading_shipping') ?></div>');
        });
    }

    // Load payment methods via get_payment_mothods (uses payment_gateway name, sets session)
    $.ajax({
        url: paymentUrl,
        type: 'POST',
        dataType: 'json',
        data: { data: $form.serialize() },
        success: function(json){
            if (json && json.html) {
                $('#onepage-payment-methods').html(json.html);
            }
        },
        error: function(){
            $('#onepage-payment-methods').html('<p class="text-danger"><?= __('store.error_loading_payment') ?></p>');
        }
    });

    // bank_transfer toggle
    $(document).on('change', '[name="payment_gateway"]', function(){
        if ($(this).val() == 'bank_transfer') {
            $('.bank-transfer-instruction').slideDown();
        } else {
            $('.bank-transfer-instruction').slideUp();
        }
    });

    // AJAX form submit
    $form.on('submit', function(e){
        e.preventDefault();
        var $btn = $('#onepage-place-order');
        var $warning = $('#onepage-warning-div');
        $warning.html('');
        $form.find('.has-error').removeClass('has-error');
        $form.find('.text-danger').remove();

        // Update PhoneNumberInput from intlTelInput if present (from checkout_shipping)
        var $phone = $('#phone');
        if ($phone.length && typeof tel_input !== 'undefined' && tel_input && $phone.val().trim()) {
            if (tel_input.isValidNumber()) {
                tel_input.setNumber($phone.val().trim());
                $('#phonenumber-input').val('+' + tel_input.getSelectedCountryData().dialCode + ' ' + $phone.val().trim());
            }
        }

        var formData = new FormData(this);
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
                $btn.prop('disabled', false).html('<i class="fas fa-lock me-2"></i><?= __('store.place_order') ?>');
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
                            $el.closest('.mb-3, .form-check, .card-body').addClass('has-error');
                            $el.after('<span class="text-danger d-block">' + msg + '</span>');
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
                    $('#onepage-checkout-main').addClass('d-none');
                    $('#onepage-confirm-area').removeClass('d-none');
                    $('html,body').animate({ scrollTop: $('#onepage-confirm-area').offset().top - 80 }, 400);
                    if (json.redirect) { window.location.href = json.redirect; }
                    return;
                }
                if (json.redirect && !json.confirm) {
                    window.location.href = json.redirect;
                }
            },
            error: function(){
                $btn.prop('disabled', false).html('<i class="fas fa-lock me-2"></i><?= __('store.place_order') ?>');
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
        html += '<td width="70px"><button type="button" class="btn btn-danger btn-sm remove-priview" onClick="removeTr(this)" data-id="'+ i +'"><?= __('store.remove') ?></button></td></tr>';
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
    $('#onepage-checkout-main').addClass('d-none');
    $('#onepage-confirm-area').removeClass('d-none');
    $('html,body').animate({ scrollTop: $('#onepage-confirm-area').offset().top - 80 }, 400);
});
</script>
<?php endif; ?>
