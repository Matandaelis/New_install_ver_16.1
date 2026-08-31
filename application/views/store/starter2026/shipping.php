<?php
/**
 * Starter 2026 — Saved Shipping Addresses Page
 *
 * @contract  Store API v1 — page: shipping
 * @auth      required
 *
 * GLOBALS  $store_setting, $client, $home_link, $base_url
 *
 * PAGE VARIABLES
 *   $addresses  array   Customer's saved shipping addresses
 *   $countries  array   All countries for address form selector
 *   $settings   array   Store settings
 */
?>

<link rel="stylesheet" href="<?= base_url('assets/plugins/tel/css/intlTelInput.css') ?>?v=<?= av() ?>">
<script src="<?= base_url('assets/plugins/tel/js/intlTelInput.js') ?>"></script>

<div class="container">
    <nav class="s26-breadcrumb" aria-label="Breadcrumb">
        <a href="<?= $home_link ?? $base_url ?>"><?= __('store.home') ?? 'Home' ?></a>
        <span class="separator"><i class="fas fa-chevron-right"></i></span>
        <span class="current"><?= __('store.shipping') ?? 'Shipping' ?></span>
    </nav>
</div>

<!-- Account Navigation -->
<div class="container">
    <div class="s26-account-nav">
        <a href="<?= $base_url ?>profile" class="s26-account-nav__link">
            <i class="fas fa-user"></i> <?= __('store.profile') ?? 'Profile' ?>
        </a>
        <a href="<?= $base_url ?>order" class="s26-account-nav__link">
            <i class="fas fa-gift"></i> <?= __('store.orders') ?? 'Orders' ?>
        </a>
        <a href="<?= $base_url ?>my_courses" class="s26-account-nav__link">
            <i class="fas fa-graduation-cap"></i> <?= __('store.my_courses') ?? 'My Courses' ?>
        </a>
        <a href="<?= $base_url ?>shipping" class="s26-account-nav__link active">
            <i class="fas fa-truck"></i> <?= __('store.shipping') ?? 'Shipping' ?>
        </a>
        <a href="<?= $base_url ?>wishlist" class="s26-account-nav__link">
            <i class="fas fa-heart"></i> <?= __('store.wishlist') ?? 'Wishlist' ?>
        </a>
        <a href="<?= $base_url ?>logout" class="s26-account-nav__link s26-account-nav__link--danger">
            <i class="fas fa-power-off"></i> <?= __('store.logout') ?? 'Logout' ?>
        </a>
    </div>
</div>

<?php
$s26hdr_icon    = 'fas fa-truck';
$s26hdr_eyebrow = __('store.home') . ' &rsaquo; ' . __('store.shipping_details');
$s26hdr_title   = __('store.shipping_details');
$s26hdr_sub     = __('store.shipping_details');
$s26hdr_stats   = [];
include(APPPATH.'views/store/starter2026/_account_header.php');
?>

<section class="s26-shipping-page">
    <div class="container">

        <div class="row g-4">
            <!-- Shipping Form -->
            <div class="col-lg-7">
                <div class="s26-checkout-card">
                    <div class="s26-checkout-card__header">
                        <i class="fas fa-map-marker-alt"></i>
                        <h3><?= __('store.shipping_details') ?? 'Shipping Details' ?></h3>
                    </div>
                    <div class="s26-checkout-card__body">
                        <form action="<?= base_url('store/shipping') ?>" method="post" id="shipping-form" enctype="multipart/form-data">
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <div class="s26-form-group">
                                        <label class="s26-form-label"><?= __('store.country') ?> <span class="text-danger">*</span></label>
                                        <?php $selected = isset($shipping) ? $shipping['country_id'] : '' ?>
                                        <select class="s26-form-input" name="country">
                                            <option value=""><?= __('store.select_country') ?? 'Select Country' ?></option>
                                            <?php if (isset($country)): foreach ($country as $key => $value) { ?>
                                                <option <?= $selected == $value->id ? 'selected' : '' ?> value="<?= $value->id ?>"><?= $value->name ?></option>
                                            <?php } endif; ?>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="s26-form-group">
                                        <label class="s26-form-label"><?= __('store.state') ?> <span class="text-danger">*</span></label>
                                        <select class="s26-form-input" name="state">
                                            <option value=""><?= __('store.select_state') ?? 'Select State' ?></option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="s26-form-group">
                                        <label class="s26-form-label"><?= __('store.city') ?> <span class="text-danger">*</span></label>
                                        <input class="s26-form-input" name="city" type="text" value="<?= isset($shipping) ? $shipping['city'] : '' ?>" placeholder="<?= __('store.city') ?>">
                                        <?php if(isset($errors) && $errors && isset($errors['city'])) { ?>
                                        <div class="text-danger mt-1" style="font-size:12px"><?= $errors['city'] ?></div>
                                        <?php } ?>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="s26-form-group">
                                        <label class="s26-form-label"><?= __('store.postal_code') ?></label>
                                        <input class="s26-form-input" name="zip_code" type="text" value="<?= isset($shipping) ? $shipping['zip_code'] : '' ?>" placeholder="<?= __('store.postal_code') ?>">
                                        <?php if(isset($errors) && $errors && isset($errors['zip_code'])) { ?>
                                        <div class="text-danger mt-1" style="font-size:12px"><?= $errors['zip_code'] ?></div>
                                        <?php } ?>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="s26-form-group">
                                        <label class="s26-form-label"><?= __('store.phone_number') ?> <span class="text-danger">*</span></label>
                                        <input type="hidden" id="phonenumber-input" name='PhoneNumberInput' value="">
                                        <input onkeypress="return isNumberKey(event);" id="phone" class="s26-form-input" type="text" name="phone" value="<?= isset($shipping) ? $shipping['phone'] : '' ?>" placeholder="<?= __('store.phone_number') ?>">
                                        <?php if(isset($errors) && $errors && isset($errors['phone'])) { ?>
                                        <div class="text-danger mt-1" style="font-size:12px"><?= $errors['phone'] ?></div>
                                        <?php } ?>
                                    </div>
                                </div>
                                <div class="col-12">
                                    <div class="s26-form-group">
                                        <label class="s26-form-label"><?= __('store.full_address') ?> <span class="text-danger">*</span></label>
                                        <textarea class="s26-form-input" name="address" rows="3" placeholder="<?= __('store.full_address') ?>"><?= isset($shipping) ? $shipping['address'] : '' ?></textarea>
                                        <?php if(isset($errors) && $errors && isset($errors['address'])) { ?>
                                        <div class="text-danger mt-1" style="font-size:12px"><?= $errors['address'] ?></div>
                                        <?php } ?>
                                    </div>
                                </div>
                                <div class="col-12">
                                    <button class="s26-btn-primary" id="update-profile" type="submit">
                                        <i class="fas fa-save"></i>
                                        <?= __('client.update_shipping') ?? 'Save Address' ?>
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            <!-- Current Saved Address Preview -->
            <div class="col-lg-5">
                <?php if (isset($shipping) && !empty($shipping)): ?>
                <div class="s26-checkout-card">
                    <div class="s26-checkout-card__header">
                        <i class="fas fa-bookmark"></i>
                        <h3><?= __('store.current_address') ?? 'Current Address' ?></h3>
                    </div>
                    <div class="s26-checkout-card__body">
                        <div class="s26-saved-address-preview">
                            <?php if (!empty($shipping['address'])): ?>
                            <p class="mb-2"><i class="fas fa-map-marker-alt text-primary me-2"></i><?= htmlspecialchars($shipping['address']) ?></p>
                            <?php endif; ?>
                            <?php if (!empty($shipping['city'])): ?>
                            <p class="mb-2 text-muted"><i class="fas fa-city me-2" style="opacity:.5"></i><?= htmlspecialchars($shipping['city']) ?></p>
                            <?php endif; ?>
                            <?php if (!empty($shipping['phone'])): ?>
                            <p class="mb-0 text-muted"><i class="fas fa-phone me-2" style="opacity:.5"></i><?= htmlspecialchars($shipping['phone']) ?></p>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
                <?php else: ?>
                <div class="s26-checkout-card">
                    <div class="s26-checkout-card__body text-center py-5">
                        <i class="fas fa-map-marker-alt" style="font-size:36px;color:var(--s26-border);margin-bottom:16px;display:block"></i>
                        <p class="text-muted mb-0"><?= __('store.no_shipping_address') ?? 'No shipping address saved yet.' ?></p>
                    </div>
                </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</section>

<script type="text/javascript">
var tel_input = intlTelInput(document.querySelector("#phone"), {
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

$('#shipping-form').submit(function() {
    var errorMap = ['<?= __('store.invalid_number') ?>','<?= __('store.invalid_country_code') ?>','<?= __('store.too_short') ?>','<?= __('store.too_long') ?>','<?= __('store.invalid_number') ?>'];
    var is_valid = false, errorInnerHTML = '';
    if ($("#phone").val().trim()) {
        if (tel_input.isValidNumber()) {
            is_valid = true;
            tel_input.setNumber($("#phone").val().trim());
            $("#phonenumber-input").val("+" + tel_input.getSelectedCountryData().dialCode + ' ' + $("#phone").val().trim());
        } else { errorInnerHTML = errorMap[tel_input.getValidationError()]; }
    } else { errorInnerHTML = '<?= __('store.mobile_number_is_required') ?>'; }
    $(".checkout-form .text-danger, #shipping-form .text-danger").remove();
    if(!is_valid){
        $("#phone").parents(".form-group").addClass("has-error");
        $("#phone").parents(".form-group").find('> div').after("<span class='text-danger'>" + errorInnerHTML + "</span>");
        return false;
    }
});

var selected_state = '<?= isset($shipping) ? $shipping['state_id'] : '' ?>';
$(document).delegate('[name="country"]', "change", function(){
    $this = $(this);
    $.ajax({
        url: '<?= base_url('store/getState') ?>',
        type: 'POST',
        dataType: 'json',
        data: { id: $this.val() },
        beforeSend: function(){ $this.prop("disabled", true); },
        complete: function(){ $this.prop("disabled", false); },
        success: function(json){
            var html = '<option value=""><?= __('store.select_state') ?? 'Select State' ?></option>';
            $.each(json['states'], function(i,j){
                var s = (selected_state && selected_state == j['id']) ? 'selected' : '';
                if(s) selected_state = 0;
                html += "<option " + s + " value='" + j['id'] + "'>" + j['name'] + "</option>";
            });
            $('[name="state"]').html(html);
        }
    });
});
$('[name="country"]').trigger("change");

function isNumberKey(evt) {
    var charCode = (evt.which) ? evt.which : event.keyCode;
    if (charCode != 46 && charCode != 45 && charCode > 31 && (charCode < 48 || charCode > 57)) return false;
    return true;
}
</script>
