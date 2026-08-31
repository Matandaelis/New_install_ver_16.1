<?php
/**
 * Default theme — Shipping address management page
 *
 * @contract  Store API v1 — page: shipping
 * @see       Store_cart_payload::page_shipping()
 * @see       /store/api/v1/pages/shipping  (auth required)
 *
 * GLOBALS (always available via Storeapp::view)
 *   $store_setting  array   All store settings key-value map
 *   $client         array   Logged-in customer data
 *   $home_link      string  Absolute URL to store homepage
 *
 * PAGE VARIABLES
 *   $addresses    array   Saved shipping addresses [{id, name, address, city, country, is_default}, ...]
 *   $countries    array   Country list for address form
 *   $shipping_url string  Form action URL for saving/updating address
 */
?>
<?php $acc_active = 'shipping'; include(APPPATH.'views/store/default/_account_nav.php'); ?>

<?php
$hdr_icon  = 'fa fa-truck';
$hdr_title = __('store.shipping_details');
$hdr_sub   = __('store.profile') . ' &rsaquo; ' . __('store.shipping_details');
$hdr_pills = [];
include(APPPATH.'views/store/default/_account_header.php');
?>

<section class="profile-page">
    <div class="container main-container">
        <div class="acc-single-col">
            <form action="<?= base_url('store/shipping') ?>" class="form-horizontal" method="post"
                  id="profile-frm" enctype="multipart/form-data">

                    <div class="acc-form-card">
                        <div class="acc-form-card__header">
                            <i class="fa fa-map-marker me-2"></i><?= __('store.shipping_details') ?>
                        </div>
                        <div class="acc-form-card__body">
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label class="acc-label"><?= __('store.country') ?></label>
                                    <?php $selected = isset($shipping) ? $shipping['country_id'] : '' ?>
                                    <select class="form-control acc-input" name="country">
                                        <?php foreach ($country as $key => $value): ?>
                                        <option <?= $selected == $value->id ? 'selected' : '' ?> value="<?= $value->id ?>"><?= $value->name ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="acc-label"><?= __('store.state') ?></label>
                                    <select class="form-control acc-input" name="state"></select>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="acc-label"><?= __('store.city') ?></label>
                                    <input class="form-control acc-input" name="city" type="text"
                                           value="<?= isset($shipping) ? htmlspecialchars($shipping['city']) : '' ?>">
                                    <?php if ($errors && isset($errors['city'])): ?>
                                    <div class="text-danger small mt-1"><?= $errors['city'] ?></div>
                                    <?php endif; ?>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="acc-label"><?= __('store.postal_code') ?></label>
                                    <input class="form-control acc-input" name="zip_code" type="text"
                                           value="<?= isset($shipping) ? htmlspecialchars($shipping['zip_code']) : '' ?>">
                                    <?php if ($errors && isset($errors['zip_code'])): ?>
                                    <div class="text-danger small mt-1"><?= $errors['zip_code'] ?></div>
                                    <?php endif; ?>
                                </div>
                                <div class="col-12 mb-3">
                                    <label class="acc-label"><?= __('store.phone_number') ?></label>
                                    <link rel="stylesheet" href="<?= base_url('assets/plugins/tel/css/intlTelInput.css') ?>?v=<?= av() ?>">
                                    <script src="<?= base_url('assets/plugins/tel/js/intlTelInput.js') ?>"></script>
                                    <input type="hidden" id="phonenumber-input" name="PhoneNumberInput" value="">
                                    <input onkeypress="return isNumberKey(event);" id="phone"
                                           class="form-control acc-input" type="text" name="phone"
                                           value="<?= isset($shipping) ? htmlspecialchars($shipping['phone']) : '' ?>">
                                    <script>
                                    var tel_input = intlTelInput(document.querySelector("#phone"), {
                                        initialCountry: "auto",
                                        utilsScript: "<?= base_url('/assets/plugins/tel/js/utils.js?1562189064761') ?>",
                                        separateDialCode: true,
                                        dropdownContainer: document.body,
                                        placeholderNumberType: "MOBILE",
                                        autoPlaceholder: "aggressive",
                                        geoIpLookup: function(success, failure) {
                                            $.get("https://ipinfo.io", function() {}, "jsonp").always(function(resp) {
                                                success((resp && resp.country) ? resp.country : "");
                                            });
                                        },
                                    });
                                    </script>
                                    <?php if ($errors && isset($errors['phone'])): ?>
                                    <div class="text-danger small mt-1"><?= $errors['phone'] ?></div>
                                    <?php endif; ?>
                                </div>
                                <div class="col-12 mb-3">
                                    <label class="acc-label"><?= __('store.full_address') ?></label>
                                    <textarea class="form-control acc-input" name="address" rows="3"><?= isset($shipping) ? htmlspecialchars($shipping['address']) : '' ?></textarea>
                                    <?php if ($errors && isset($errors['address'])): ?>
                                    <div class="text-danger small mt-1"><?= $errors['address'] ?></div>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="acc-form-footer">
                        <button class="btn acc-btn-save" id="update-profile" type="submit" style="width:auto;padding:0 32px">
                            <i class="fa fa-check"></i><?= __('client.update_shipping') ?>
                        </button>
                    </div>

            </form>
        </div>
    </div>
</section>

<script>
$('.form-horizontal').submit(function() {
    var errorMap = ['<?= __('store.invalid_number') ?>','<?= __('store.invalid_country_code') ?>','<?= __('store.too_short') ?>','<?= __('store.too_long') ?>','<?= __('store.invalid_number') ?>'];
    var is_valid = false, errorInnerHTML = '';
    if ($("#phone").val().trim()) {
        if (tel_input.isValidNumber()) {
            is_valid = true;
            tel_input.setNumber($("#phone").val().trim());
            $("#phonenumber-input").val("+" + tel_input.getSelectedCountryData().dialCode + ' ' + $("#phone").val().trim());
        } else {
            errorInnerHTML = errorMap[tel_input.getValidationError()];
        }
    } else {
        errorInnerHTML = '<?= __('store.mobile_number_is_required') ?>';
    }
    $(".phone-error").remove();
    if (!is_valid) {
        $("#phone").after("<span class='phone-error text-danger small d-block mt-1'>" + errorInnerHTML + "</span>");
        return false;
    }
});

var selected_state = '<?= isset($shipping) ? $shipping['state_id'] : '' ?>';
$(document).delegate('[name="country"]', "change", function() {
    var $this = $(this);
    $.ajax({
        url: '<?= base_url('store/getState') ?>',
        type: 'POST', dataType: 'json', data: { id: $this.val() },
        beforeSend: function() { $this.prop("disabled", true); },
        complete:   function() { $this.prop("disabled", false); },
        success: function(json) {
            var html = '';
            $.each(json['states'], function(i, j) {
                var s = (selected_state && selected_state == j['id']) ? 'selected' : '';
                if (s) selected_state = 0;
                html += "<option " + s + " value='" + j['id'] + "'>" + j['name'] + "</option>";
            });
            $('[name="state"]').html(html);
        },
    });
});
$('[name="country"]').trigger("change");

function isNumberKey(evt) {
    var charCode = (evt.which) ? evt.which : event.keyCode;
    if (charCode != 46 && charCode != 45 && charCode > 31 && (charCode < 48 || charCode > 57)) return false;
    return true;
}
</script>
