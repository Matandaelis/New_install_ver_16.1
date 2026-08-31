<?php
$_ci_form        =& get_instance();
$_form_store     = $_ci_form->Product_model->getSettings('store');
$_form_theme     = (!empty($_form_store['theme']) && $_form_store['theme'] !== '0') ? $_form_store['theme'] : 'default';
$_form_theme_path  = base_url('assets/store/' . $_form_theme . '/');
$_form_default_path = base_url('assets/store/default/');
$_form_logo      = (!empty($_form_store['logo']))
    ? base_url('assets/images/site/' . $_form_store['logo'])
    : base_url('assets/store/default/img/logo.png');
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
<meta name="twitter:card" content="summary_large_image">
<title><?= htmlspecialchars($page) ?></title>
<?php if ($analytics != ''): ?><?= $analytics ?><?php endif; ?>

<!-- Store base: Bootstrap 4 + store theme CSS -->
<link href="<?= base_url('assets/plugins/store/') ?>vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">
<link href="<?= base_url('assets/plugins/store/') ?>shop-homepage.css" rel="stylesheet">
<link rel="stylesheet" href="<?= $_form_default_path ?>fonts/fonts.css">
<link rel="stylesheet" href="<?= $_form_default_path ?>css/placeholder-loading.css">
<link rel="stylesheet" href="<?= base_url('assets/template/css/sweetalert2.min.css') ?>?v=<?= av() ?>">
<link rel="stylesheet" href="<?= $_form_default_path ?>css/nouislider.css">
<link rel="stylesheet" href="<?= $_form_default_path ?>css/style.css">
<!-- Local icon fonts (no CDN) -->
<link rel="stylesheet" href="<?= base_url('assets/plugins/builder_layout/font-awesome.min.css') ?>">
<link rel="stylesheet" href="<?= base_url('assets/plugins/tel/css/intlTelInput.css') ?>?v=<?= av() ?>">
<?php if ($_form_theme !== 'default'): ?>
<link rel="stylesheet" href="<?= $_form_theme_path ?>css/theme.css">
<?php endif; ?>
<!-- Custom checkout styles (no CDN, no inline CSS) -->
<link rel="stylesheet" href="<?= $_form_default_path ?>css/form-checkout.css?v=<?= av() ?>">

<!-- Scripts -->
<script src="<?= $_form_default_path ?>js/jquery.min.js"></script>
<script src="<?= base_url('assets/plugins/store/') ?>vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
<script src="<?= base_url('assets/plugins/store/') ?>jquery.star-rating-svg.js"></script>
<script src="<?= $_form_default_path ?>js/nouislider.min.js"></script>
<script src="<?= base_url('assets/template/js/sweetalert2.all.min.js') ?>?v=<?= av() ?>"></script>
<script src="<?= base_url('assets/plugins/') ?>mustache.js"></script>
<script src="<?= base_url('assets/plugins/tel/js/intlTelInput.js') ?>"></script>
<script>
var tel_inputre = null;
var tel_input   = null;
</script>
</head>

<body>

<!-- ── HEADER ── -->
<header class="fc-header">
  <div class="fc-header-inner">
    <a href="<?= base_url('store/') ?>" class="fc-logo">
      <img src="<?= $_form_logo ?>" alt="Logo">
    </a>
    <div class="fc-header-controls">
      <div class="dropdown"><?= $LanguageHtml ?></div>
      <div class="dropdown"><?= $CurrencyHtml ?></div>
      <?php if ($is_logged): ?>
      <div class="dropdown fc-user-dropdown">
        <a class="nav-link fc-user-toggle" href="javascript:void(0)">
            <?php
            $av = (!empty($is_logged['avatar']))
                ? base_url('assets/images/users/' . $is_logged['avatar'])
                : base_url('assets/images/no-user_image.jpg');
          ?>
          <img src="<?= $av ?>" style="width:28px;height:28px;border-radius:50%;object-fit:cover;" alt="">
          <span class="d-none-mobile"><?= htmlspecialchars($is_logged['firstname']) ?></span>
          <i class="fa fa-chevron-down" style="font-size:.6rem;"></i>
        </a>
        <div class="dropdown-menu">
          <?php $_form_return_url = current_url(); ?>
          <a class="dropdown-item" href="<?= base_url('store/profile?return_to=' . urlencode($_form_return_url)) ?>">
            <i class="fa fa-user"></i> <?= __('store.profile') ?>
          </a>
          <a class="dropdown-item" href="<?= base_url('store/order?return_to=' . urlencode($_form_return_url)) ?>">
            <i class="fa fa-shopping-bag"></i> <?= __('store.my_orders') ?>
          </a>
          <div class="dropdown-divider"></div>
          <a class="dropdown-item text-danger"
             href="<?= base_url('usercontrol/logout?redirect=' . urlencode(current_url())) ?>">
            <i class="fa fa-sign-out"></i> <?= __('store.logout') ?>
          </a>
        </div>
      </div>
      <?php endif; ?>
      <a href="<?= base_url('store/track_order') ?>" class="fc-track-link d-none d-md-flex" style="font-size:13px;margin-right:12px;">
        <i class="fa fa-search"></i> <?= __('store.track_your_order') ?? 'Track order' ?>
      </a>
      <div class="fc-secure-badge d-none d-md-flex">
        <i class="fa fa-lock"></i> <?= __('store.secure_checkout') ?? 'Secure Checkout' ?>
      </div>
    </div>
  </div>
</header>

<!-- ── PROGRESS STEPS ── -->
<div class="fc-progress">
  <div class="fc-progress-inner">
    <div class="fc-step active" id="prog-cart">
      <span class="fc-step-num">1</span>
      <span class="label"><?= __('store.purchase_of_details') ?></span>
    </div>
    <div class="fc-step-divider"></div>
    <div class="fc-step" id="prog-details">
      <span class="fc-step-num">2</span>
      <span class="label"><?= __('store.personal_details') ?></span>
    </div>
    <div class="fc-step-divider"></div>
    <div class="fc-step" id="prog-payment">
      <span class="fc-step-num">3</span>
      <span class="label"><?= __('store.payment') ?></span>
    </div>
    <div class="fc-step-divider"></div>
    <div class="fc-step" id="prog-confirm">
      <span class="fc-step-num">4</span>
      <span class="label"><?= __('store.confirm_order') ?></span>
    </div>
  </div>
</div>

<!-- ── MAIN ── -->
<div class="fc-main" id="body-checkout">

  <!-- Hero: page title + description -->
  <div class="fc-hero">
    <h1><?= htmlspecialchars($page) ?></h1>
    <div class="fc-hero-desc dynamic-content-body"><?= $description ?></div>
  </div>

  <div class="non-confirm">

    <!-- ── STEP 1: CART ── -->
    <div class="fc-card checkout-setp cart-step">
      <div class="fc-card-header">
        <div class="fc-card-icon"><i class="fa fa-shopping-cart"></i></div>
        <h4><?= __('store.purchase_of_details') ?></h4>
      </div>
      <div class="fc-card-body step-body">
        <div class="cart-loader"></div>
        <div class="cart-body"></div>
        <div class="step-footer"></div>
        <input type="hidden" name="cookies_consent" id="cookies_consent" value="true">
      </div>
    </div>

    <!-- ── STEP 2: AUTH (guests only) ── -->
    <?php if (!$is_logged): ?>
    <div class="fc-card checkout-setp auth-step"<?= isset($_SESSION['guestFlow']) ? ' style="display:none;"' : '' ?>>
      <div class="fc-card-header">
        <div class="fc-card-icon green"><i class="fa fa-user"></i></div>
        <h4><?= __('store.personal_details') ?></h4>
      </div>
      <div class="fc-card-body step-body">

        <!-- Tab switcher -->
        <div class="fc-auth-nav" id="fc-auth-tabs">
          <a class="fc-tab-btn" href="javascript:void(0)" data-target="fc-pane-login">
            <i class="fa fa-sign-in"></i> <?= __('store.login') ?>
          </a>
          <a class="fc-tab-btn active" href="javascript:void(0)" data-target="fc-pane-register">
            <i class="fa fa-user-plus"></i> <?= __('store.register') ?>
          </a>
          <a href="javascript:void(0)" id="btnGuestcontinues" class="fc-tab-btn"
             style="background:#f3f4f6;color:#374151;">
            <i class="fa fa-bolt"></i> <?= __('store.guest_checkout') ?>
          </a>
        </div>

        <!-- Login pane -->
        <div class="fc-auth-pane" id="fc-pane-login">
          <form id="login-form">
            <div class="form-group">
              <label><?= __('store.username') ?></label>
              <input class="form-control" name="username"
                     placeholder="<?= __('store.username') ?>" type="text">
            </div>
            <div class="form-group">
              <label><?= __('store.password') ?></label>
              <input class="form-control" name="password"
                     placeholder="<?= __('store.password') ?>" type="password">
            </div>
            <button type="submit" class="btn-submit">
              <i class="fa fa-sign-in"></i> <?= __('store.login') ?>
            </button>
          </form>
        </div>

        <!-- Register pane -->
        <div class="fc-auth-pane active" id="fc-pane-register">
          <form id="register-form">
            <div class="row">
              <div class="col-sm-6">
                <div class="form-group">
                  <label><?= __('store.first_name') ?></label>
                  <input class="form-control" name="f_name"
                         placeholder="<?= __('store.first_name') ?>" type="text">
                </div>
              </div>
              <div class="col-sm-6">
                <div class="form-group">
                  <label><?= __('store.last_name') ?></label>
                  <input class="form-control" name="l_name"
                         placeholder="<?= __('store.last_name') ?>" type="text">
                </div>
              </div>
            </div>
            <div class="form-group">
              <label><?= __('store.username') ?></label>
              <input class="form-control" name="username"
                     placeholder="<?= __('store.username') ?>" type="text">
            </div>
            <div class="form-group">
              <label><?= __('store.phone_number') ?></label>
              <input type="hidden" name="PhoneNumberInput" id="rephonenumber-input" value="">
              <input id="phoneergister" type="tel" name="phone" value="" class="form-control"
                     onkeypress="return isNumberKey(event);">
              <script type="text/javascript">
              tel_inputre = intlTelInput(document.getElementById('phoneergister'), {
                  initialCountry: 'auto',
                  separateDialCode: true,
                  utilsScript: '<?= base_url('assets/plugins/tel/js/utils.js?1562189064761') ?>',
                  dropdownContainer: document.body,
                  placeholderNumberType: 'MOBILE',
                  autoPlaceholder: 'aggressive',
                  geoIpLookup: function(success) {
                      $.get('https://ipinfo.io', function(){}, 'jsonp').always(function(r) {
                          success((r && r.country) ? r.country : '');
                      });
                  }
              });
              </script>
            </div>
            <div class="form-group">
              <label><?= __('store.email') ?></label>
              <input class="form-control" name="email"
                     placeholder="<?= __('store.email') ?>" type="email">
            </div>
            <div class="row">
              <div class="col-sm-6">
                <div class="form-group">
                  <label><?= __('store.password') ?></label>
                  <input class="form-control" name="password"
                         placeholder="••••••••" type="password">
                </div>
              </div>
              <div class="col-sm-6">
                <div class="form-group">
                  <label><?= __('store.confirm_password') ?></label>
                  <input class="form-control" name="c_password"
                         placeholder="••••••••" type="password">
                </div>
              </div>
            </div>
            <button type="submit" class="btn-submit">
              <i class="fa fa-user-plus"></i> <?= __('store.register') ?>
            </button>
          </form>
        </div>

        <div class="step-footer"></div>
      </div>
    </div>
    <?php endif; ?>

    <!-- ── STEP 2b: BILLING (logged-in, no shipping) ── -->
    <?php if ($is_logged && !$allow_shipping): ?>
    <div class="fc-card checkout-form">
      <div class="fc-card-header">
        <div class="fc-card-icon purple"><i class="fa fa-map-marker"></i></div>
        <h4><?= __('store.billing_address') ?></h4>
      </div>
      <div class="fc-card-body form-checkout-wrapper">
        <div class="row">
          <div class="col-sm-6">
            <div class="form-group">
              <label><?= __('store.enter_your_first_name') ?></label>
              <input type="text" name="firstname" class="form-control"
                     placeholder="<?= __('store.enter_your_first_name') ?>"
                     value="<?= htmlspecialchars($_SESSION['client']['firstname'] ?? '') ?>">
            </div>
          </div>
          <div class="col-sm-6">
            <div class="form-group">
              <label><?= __('store.enter_your_last_name') ?></label>
              <input type="text" name="lastname" class="form-control"
                     placeholder="<?= __('store.enter_your_last_name') ?>"
                     value="<?= htmlspecialchars($_SESSION['client']['lastname'] ?? '') ?>">
            </div>
          </div>
          <div class="col-sm-6">
            <div class="form-group">
              <label><?= __('store.enter_your_email_address') ?></label>
              <input type="email" name="email" class="form-control"
                     placeholder="<?= __('store.enter_your_email_address') ?>"
                     value="<?= htmlspecialchars($_SESSION['client']['email'] ?? '') ?>">
              <input type="hidden" name="classified_checkout" value="1">
            </div>
          </div>
          <div class="col-sm-6">
            <div class="form-group">
              <label><?= __('store.phone') ?></label>
              <input type="hidden" id="phonenumber-input" name="PhoneNumberInput" value="">
              <input id="phoneguest" type="tel" name="phone" class="form-control"
                     placeholder="<?= __('store.phone') ?>"
                     value="<?= htmlspecialchars($_SESSION['client']['phone'] ?? '') ?>"
                     onkeypress="return isNumberKey(event);">
              <script type="text/javascript">
              tel_input = intlTelInput(document.getElementById('phoneguest'), {
                  initialCountry: 'auto',
                  separateDialCode: true,
                  utilsScript: '<?= base_url('assets/plugins/tel/js/utils.js?1562189064761') ?>',
                  dropdownContainer: document.body,
                  placeholderNumberType: 'MOBILE',
                  autoPlaceholder: 'aggressive',
                  geoIpLookup: function(success) {
                      $.get('https://ipinfo.io', function(){}, 'jsonp').always(function(r) {
                          success((r && r.country) ? r.country : '');
                      });
                  }
              });
              </script>
            </div>
          </div>
        </div>
      </div>
    </div>
    <?php endif; ?>

    <!-- ── STEP 2c: SHIPPING / GUEST BILLING ── -->
    <?php if (isset($_SESSION['guestFlow']) || $allow_shipping): ?>
    <div class="fc-card checkout-setp shipping-step"
         <?= (!$is_logged && !isset($_SESSION['guestFlow'])) ? 'style="display:none;"' : '' ?>>
      <div class="fc-card-header">
        <div class="fc-card-icon purple"><i class="fa fa-map-marker"></i></div>
        <h4><?= $allow_shipping == 1 ? __('store.billing_shipping_address') : __('store.billing_address') ?></h4>
        <?php if (!$is_logged && isset($_SESSION['guestFlow'])): ?>
        <a href="?back_to_login=1" class="btn btn-link btn-sm ml-auto" style="font-size:.8rem;">
          <i class="fa fa-arrow-left"></i> <?= __('store.back_to_login') ?>
        </a>
        <?php endif; ?>
      </div>
      <div class="fc-card-body step-body">
        <?php if (isset($show_blue_message) && $show_blue_message): ?>
          <div class="alert alert-info"><?= $shipping_error_message ?></div>
        <?php endif; ?>
        <?php if (!empty($shipping_error_message)): ?>
          <div class="alert alert-danger"><?= htmlspecialchars($shipping_error_message) ?></div>
        <?php endif; ?>
        <div class="cart-loader"></div>
        <div class="cart-body">
          <?php if (!$allow_shipping && !$is_logged): ?>
          <div class="row">
            <div class="col-sm-6">
              <div class="form-group">
                <label><?= __('store.enter_your_first_name') ?></label>
                <input type="text" name="firstname" class="form-control"
                       placeholder="<?= __('store.enter_your_first_name') ?>">
              </div>
            </div>
            <div class="col-sm-6">
              <div class="form-group">
                <label><?= __('store.enter_your_last_name') ?></label>
                <input type="text" name="lastname" class="form-control"
                       placeholder="<?= __('store.enter_your_last_name') ?>">
              </div>
            </div>
            <div class="col-sm-6">
              <div class="form-group">
                <label><?= __('store.enter_your_email_address') ?></label>
                <input type="email" name="email" class="form-control"
                       placeholder="<?= __('store.enter_your_email_address') ?>">
                <input type="hidden" name="classified_checkout" value="1">
              </div>
            </div>
            <div class="col-sm-6">
              <div class="form-group">
                <label><?= __('store.phone') ?></label>
                <input type="hidden" id="phonenumber-input" name="PhoneNumberInput" value="">
                <input id="phoneguest" type="tel" name="phone" class="form-control"
                       placeholder="<?= __('store.phone') ?>"
                       onkeypress="return isNumberKey(event);">
                <script type="text/javascript">
                if (!tel_input) {
                    tel_input = intlTelInput(document.getElementById('phoneguest'), {
                        initialCountry: 'auto',
                        separateDialCode: true,
                        utilsScript: '<?= base_url('assets/plugins/tel/js/utils.js?1562189064761') ?>',
                        dropdownContainer: document.body,
                        placeholderNumberType: 'MOBILE',
                        autoPlaceholder: 'aggressive',
                        geoIpLookup: function(success) {
                            $.get('https://ipinfo.io', function(){}, 'jsonp').always(function(r) {
                                success((r && r.country) ? r.country : '');
                            });
                        }
                    });
                }
                </script>
              </div>
            </div>
          </div>
          <?php endif; ?>
        </div>
        <div class="step-footer"></div>
      </div>
    </div>
    <?php endif; ?>

    <!-- ── STEP 3: PAYMENT ── -->
    <div class="fc-card checkout-setp"
         <?= (!$is_logged && !isset($_SESSION['guestFlow'])) ? 'style="display:none;"' : '' ?>>
      <div class="fc-card-header">
        <div class="fc-card-icon amber"><i class="fa fa-credit-card"></i></div>
        <h4><?= __('store.payment') ?></h4>
      </div>
      <div class="fc-card-body step-body">
        <div class="dynamic-payment"></div>
        <?php if (isset($allow_upload_file) && $allow_upload_file): ?>
        <link rel="stylesheet" href="<?= base_url('assets/template/css/jquery.uploadPreviewer.css') ?>">
        <div class="fc-upload-area mt-3">
          <div class="file-preview-button">
            <i class="fa fa-paperclip"></i> <?= __('store.order_upload_file') ?>
            <input type="file" class="downloadable_file_input" multiple>
          </div>
          <div id="priview-table" class="table-responsive mt-2" style="display:none;">
            <table class="table table-hover"><tbody></tbody></table>
          </div>
        </div>
        <?php endif; ?>
        <div class="fc-checkbox-row mt-3">
          <input type="checkbox" value="1" name="agree" id="agree-chk">
          <label for="agree-chk"><?= __('store.agree_text') ?></label>
        </div>
        <div class="warning-div"></div>
        <div class="step-footer mt-3">
          <button class="confirm-order">
            <i class="fa fa-lock"></i> <?= __('store.confirm_and_pay') ?>
          </button>
        </div>
      </div>
    </div>

  </div><!-- /.non-confirm -->

  <!-- ── STEP 4: CONFIRM ── -->
  <div class="confirm-checkout" style="display:none;">
    <div class="fc-card checkout-setp confirm-step">
      <div class="fc-card-header">
        <div class="fc-card-icon green"><i class="fa fa-check-circle"></i></div>
        <h4><?= __('store.confirm_order') ?></h4>
      </div>
      <div class="fc-card-body step-body">
        <div id="checkout-confirm"></div>
        <div class="step-footer"></div>
      </div>
    </div>
  </div>

  <!-- ── TRUST BADGES ── -->
  <div class="fc-trust">
    <div class="fc-trust-item"><i class="fa fa-lock"></i> <?= __('store.ssl_secured') ?? 'SSL Secured' ?></div>
    <div class="fc-trust-item"><i class="fa fa-shield"></i> <?= __('store.safe_checkout') ?? 'Safe Checkout' ?></div>
    <div class="fc-trust-item"><i class="fa fa-undo"></i> <?= __('store.money_back_guarantee') ?? 'Money-Back Guarantee' ?></div>
    <div class="fc-trust-item"><i class="fa fa-headphones"></i> <?= __('store.support_24_7') ?? '24/7 Support' ?></div>
  </div>

</div><!-- /.fc-main -->

<!-- ── FOOTER ── -->
<footer class="fc-footer">
  <div class="fc-footer-inner">
    <?php
      $_sw = (!empty($_form_store['store_custom_logo_size']) && $_form_store['store_custom_logo_size'] == 1)
               ? $_form_store['store_logo_custom_width']  : 90;
      $_sh = (!empty($_form_store['store_custom_logo_size']) && $_form_store['store_custom_logo_size'] == 1)
               ? $_form_store['store_logo_custom_height'] : 28;
    ?>
    <a href="<?= base_url('store/') ?>">
      <img src="<?= $_form_logo ?>" width="<?= $_sw ?>" height="<?= $_sh ?>"
           alt="Logo" style="opacity:.55;">
    </a>
    <div class="fc-footer-payments">
      <?php foreach (get_payment_gateways() as $pmt): if ($pmt['status']): ?>
        <img src="<?= base_url($pmt['icon']) ?>" alt="<?= htmlspecialchars($pmt['title']) ?>"
             title="<?= htmlspecialchars($pmt['title']) ?>"
             onerror="this.onerror=null;this.style.display='none'">
      <?php endif; endforeach; ?>
    </div>
    <p class="fc-footer-copy">
      <?= (!empty($footer)) ? htmlspecialchars($footer) : __('store.all_rights_reserved') . ' ' . date('Y') . '.' ?>
      &nbsp;<a href="<?= base_url('policy') ?>"><?= __('store.policy') ?></a>
    </p>
  </div>
</footer>

<?php include __DIR__ . '/cookies_consent.php'; ?>

<!-- ── RECAPTCHA ── -->
<script src="https://www.google.com/recaptcha/api.js"></script>

<!-- ── MAIN CHECKOUT SCRIPTS ── -->
<script>
/* ── Utility: loading state for buttons ── */
(function ($) {
    $.fn.btn = function (action) {
        var self = $(this);
        if (action === 'loading') {
            if ($(self).attr('disabled') === 'disabled') return;
            $(self).attr('disabled', 'disabled')
                   .attr('data-btn-text', $(self).html())
                   .html('<span class="spinner-border spinner-border-sm mr-1"></span> ' + $(self).text());
        }
        if (action === 'reset') {
            $(self).html($(self).attr('data-btn-text')).removeAttr('disabled');
        }
    };
})(jQuery);

/* ── isNumberKey helper ── */
function isNumberKey(e) {
    var c = e.which ? e.which : e.keyCode;
    return (c >= 48 && c <= 57) || c === 8 || c === 9 || c === 13;
}

$(document).ready(function () {

    /* ── Global dropdown toggle (BS4-compatible) ── */
    $('.dropdown').on('click', '.dropdown-toggle, [data-toggle="dropdown"]', function (e) {
        e.preventDefault(); e.stopPropagation();
        var $m = $(this).closest('.dropdown').find('.dropdown-menu');
        $('.dropdown-menu').not($m).hide().removeClass('show');
        $m.toggle().toggleClass('show');
    });
    /* User avatar dropdown (no data-toggle attr) */
    $(document).on('click', '.fc-user-toggle', function (e) {
        e.preventDefault(); e.stopPropagation();
        var $m = $(this).siblings('.dropdown-menu');
        $('.dropdown-menu').not($m).hide().removeClass('show');
        $m.toggle().toggleClass('show');
    });
    $(document).on('click', function (e) {
        if (!$(e.target).closest('.dropdown').length) {
            $('.dropdown-menu').hide().removeClass('show');
        }
    });

    /* ── Auth tab switcher (pure JS) ── */
    $(document).on('click', '.fc-tab-btn', function (e) {
        e.preventDefault();
        var target = $(this).data('target');
        // Guest checkout has no pane to show — handled by btnGuestcontinues
        if (!target) return;
        $('.fc-tab-btn').removeClass('active');
        $(this).addClass('active');
        $('.fc-auth-pane').removeClass('active').hide();
        $('#' + target).addClass('active').show();
    });

}); /* end ready */


/* ── Checkout state ── */
var isGuest = '<?= isset($_SESSION['guestFlow']) ? 1 : '' ?>';
var allow_shipping = '<?= $allow_shipping ?>';
var isProcessing = false;

/* ── Progress helper ── */
function setStep(n) {
    ['prog-cart', 'prog-details', 'prog-payment', 'prog-confirm'].forEach(function (id, i) {
        var el = document.getElementById(id);
        if (!el) return;
        el.classList.remove('active', 'done');
        if (i < n) el.classList.add('done');
        else if (i === n) el.classList.add('active');
    });
}

/* ── Cart: remove item ── */
$('.cart-step').delegate('.btn-remove-cart', 'click', function () {
    var $this = $(this);
    $.ajax({ url: $this.attr('data-href'), type: 'POST', dataType: 'json',
        success: function (r) { getCart($('select[name="country"]').val() || undefined); },
        error: function () { getCart($('select[name="country"]').val() || undefined); }
    });
    return false;
});

/* ── Cart: qty change ── */
var xhrQty;
$('.cart-step').delegate('.qty-input', 'change', function () {
    if (xhrQty && xhrQty.readyState !== 4) xhrQty.abort();
    var $this = $(this);
    xhrQty = $.ajax({ url: '<?= $cart_update_url ?>', type: 'POST', dataType: 'json',
        data: $('#checkout-cart-form').serialize(),
        success: function () { getCart($('select[name="country"]').val()); }
    });
    return false;
});

/* ── Payment gateway change ── */
$('[name="payment_gateway"]').on('change', function () {
    $('.bank-transfer-instruction').toggle($(this).val() === 'bank_transfer');
});

/* ── Coupon ── */
$('.cart-step').delegate('.submit-coupon', 'click', function () {
    var $this = $(this);
    $('.error-coupon-msg').text('');
    $.ajax({ url: '<?= base_url('form/add_coupon') ?>', type: 'POST', dataType: 'json',
        data: { coupon_code: $('.coupon_code').val() },
        beforeSend: function () { $this.btn('loading'); },
        complete:   function () { $this.btn('reset'); },
        success: function (json) {
            if (json.error) { $('.error-coupon-msg').text(json.error); return; }
            getCart($('select[name="country"]').val());
        }
    });
    return false;
});

/* ── Load functions ── */
function getCart(countryId) {
    var url = '<?= base_url('form/checkout_cart') ?>' + (countryId ? '/' + countryId : '');
    $('.cart-step .cart-body').load(url);
}
function getShipping(countryCode) {
    var url = '<?= base_url('form/checkout_shipping') ?>' + (countryCode ? '/' + countryCode : '');
    $('.shipping-step .cart-body').load(url);
}
function getPaymentMethods() {
    $.ajax({ url: '<?= base_url('store/get_payment_mothods') ?>', type: 'POST', dataType: 'json',
        data: { data: $('#checkout-cart-form').serialize() },
        success: function (json) { $('.dynamic-payment').html(json['html']); setStep(2); }
    });
}
function backCheckout() {
    $('#checkout-confirm').html('');
    $('.confirm-checkout').hide();
    $('.non-confirm').show();
    setStep(2);
}

/* ── Initial load ── */
<?php if (!$allow_shipping): ?>getCart();<?php endif; ?>
if (allow_shipping) getShipping();
getPaymentMethods();

/* ── FormData filter (empty file fix) ── */
function formDataFilter(fd) {
    if (!(window.FormData && fd instanceof window.FormData) || !fd.keys) return fd;
    var nfd = new window.FormData();
    Array.from(fd.entries()).forEach(function (e) {
        nfd.append(e[0], (e[1] instanceof window.File && e[1].name === '' && e[1].size === 0)
            ? new window.Blob([]) : e[1]);
    });
    return nfd;
}

/* ── Confirm & Pay ── */
$(document).on('click', '#checkout-confirm button, #checkout-confirm input[type="submit"]', function (e) {
    if (isProcessing) { e.preventDefault(); e.stopImmediatePropagation(); return false; }
    isProcessing = true;
    $(this).prop('disabled', true);
});

$('.confirm-order').on('click', function () {
    /* Phone validation */
    var phoneEl = $('#phone').length ? $('#phone') : ($('#phoneguest').length ? $('#phoneguest') : null);
    if (phoneEl !== null && tel_input !== null) {
        var _invalidNum = '<?= __('store.invalid_number') ?>';
        var errorMap = [
            _invalidNum,
            '<?= __('store.invalid_country_code') ?>',
            '<?= __('store.too_short') ?>',
            '<?= __('store.too_long') ?>',
            _invalidNum,
            _invalidNum  /* code 5 = INVALID_LENGTH */
        ];
        var is_valid = false, errMsg = '';
        if (phoneEl.val().trim()) {
            if (tel_input.isValidNumber()) {
                is_valid = true;
                tel_input.setNumber(phoneEl.val().trim());
                $('#phonenumber-input').val('+' + tel_input.getSelectedCountryData().dialCode + ' ' + phoneEl.val().trim());
            } else {
                errMsg = errorMap[tel_input.getValidationError()] || _invalidNum;
            }
        } else {
            errMsg = '<?= __('store.mobile_number_is_required') ?>';
        }
        $('.text-danger').remove();
        if (!is_valid) {
            phoneEl.closest('.form-group').addClass('has-error')
                   .find('> div').after('<span class="text-danger">' + errMsg + '</span>');
            return false;
        }
    }

    var $this = $(this);
    var $container = $('.checkout-setp, .checkout-form');
    var fd = new FormData();
    $container.find('input[type=text],input[type=email],input[type=tel],input[type=password],input[type=number],input[type=hidden],input[type=file],select,input[type=checkbox]:checked,input[type=radio]:checked,textarea').each(function (i, j) {
        fd.append($(j).attr('name'), $(j).val());
    });
    fd.append('is_form', 1);
    if (typeof fileArray !== 'undefined') {
        $.each(fileArray, function (i, j) { fd.append('downloadable_file[]', j.rawData); });
    }
    fd = formDataFilter(fd);
    setStep(3);

    $.ajax({
        url: '<?= base_url('store/confirm_order') ?>',
        type: 'POST', cache: false, contentType: false, processData: false, data: fd,
        beforeSend: function () { $this.btn('loading'); },
        complete:   function () { $this.btn('reset'); isProcessing = false; },
        success: function (result) {
            $container.find('.has-error').removeClass('has-error');
            $container.find('span.text-danger, .alert-danger').remove();
            if (IsJsonString(result)) {
                var r = $.parseJSON(result);
                if (r['confirm']) {
                    $('#checkout-confirm').html(r['confirm']);
                    $('.confirm-checkout').show();
                    $('.non-confirm').hide();
                    setStep(3);
                }
                if (r.error) {
                    $('.warning-div').html('<div class="alert alert-danger"><i class="fa fa-exclamation-triangle mr-2"></i>' + r['error'] + '</div>');
                    Swal.fire({ icon: 'error', title: 'Error', text: r['error'], confirmButtonColor: '#d33' });
                    setStep(2);
                }
                if (r['errors']) {
                    var ec = Object.keys(r['errors']).length, fel = null, el = [];
                    $.each(r['errors'], function (i, j) {
                        var $e = $container.find('[name="' + i + '"]');
                        if ($e.length) {
                            $e.closest('.form-group').addClass('has-error');
                            $e.after('<span class="text-danger">' + j + '</span>');
                            if (!fel) fel = $e;
                        }
                        el.push('• ' + j);
                    });
                    $('.warning-div').html('<div class="alert alert-danger"><i class="fa fa-exclamation-triangle mr-2"></i><strong>' + ec + ' Error(s):</strong><br>' + el.join('<br>') + '</div>');
                    $('html,body').animate({ scrollTop: (fel ? fel.offset().top - 150 : $('.warning-div').offset().top - 100) }, 600, function () {
                        if (fel) setTimeout(function () { fel.focus(); }, 700);
                    });
                    setStep(2);
                }
            } else {
                $('#checkout-confirm').html(result);
                $('.confirm-checkout').show();
                $('.non-confirm').hide();
                setStep(3);
            }
        }
    });
});

/* ── Login form ── */
$('#login-form').on('submit', function (e) {
    e.preventDefault();
    var $this = $(this);
    $.ajax({ url: '<?= base_url('form/ajax_login') ?>', type: 'POST', dataType: 'json',
        data: $this.serialize(),
        beforeSend: function () { $this.find('.btn-submit').btn('loading'); },
        complete:   function () { $this.find('.btn-submit').btn('reset'); },
        success: function (r) {
            $this.find('.has-error').removeClass('has-error');
            $this.find('span.text-danger, .alert-danger').remove();
            if (r['success']) { window.location.href = window.location.pathname + window.location.search; return; }
            if (r['errors']) {
                var el = [];
                $.each(r['errors'], function (i, j) {
                    var $e = $this.find('[name="' + i + '"]');
                    if ($e.length) { $e.closest('.form-group').addClass('has-error'); $e.after('<span class="text-danger">' + j + '</span>'); }
                    el.push('• ' + j);
                });
                $this.prepend('<div class="alert alert-danger mt-3"><i class="fa fa-exclamation-triangle mr-2"></i>' + el.join('<br>') + '</div>');
            }
        },
        error: function () {
            $this.find('span.text-danger, .alert-danger').remove();
            $this.prepend('<div class="alert alert-danger mt-3"><i class="fa fa-exclamation-triangle mr-2"></i><?= __('store.something_went_wrong') ?></div>');
        }
    });
    return false;
});

/* ── Register form ── */
$('#register-form').on('submit', function (e) {
    e.preventDefault();
    var $this = $(this);
    var _invNum = '<?= __('store.invalid_number') ?>';
    var errorMap = [_invNum,'<?= __('store.invalid_country_code') ?>','<?= __('store.too_short') ?>','<?= __('store.too_long') ?>',_invNum,_invNum];
    var is_valid = false, err = '';
    if (tel_inputre !== null) {
        if ($('#phoneergister').val().trim()) {
            if (tel_inputre.isValidNumber()) {
                is_valid = true;
                tel_inputre.setNumber($('#phoneergister').val().trim());
                $this.find('[name="PhoneNumberInput"]').val('+' + tel_inputre.getSelectedCountryData().dialCode + ' ' + $('#phoneergister').val().trim());
            } else {
                err = errorMap[tel_inputre.getValidationError()] || _invNum;
            }
        } else { err = '<?= __('store.mobile_number_is_required') ?>'; }
    } else {
        is_valid = true; /* tel not rendered */
    }
    $('#phoneergister').closest('.form-group').removeClass('has-error');
    $('#register-form .text-danger').remove();
    if (!is_valid) {
        $('#phoneergister').closest('.form-group').addClass('has-error')
                           .find('> div').after('<span class="text-danger">' + err + '</span>');
        return false;
    }
    $.ajax({ url: '<?= base_url('form/ajax_register') ?>', type: 'POST', dataType: 'json',
        data: $this.serialize(),
        beforeSend: function () { $this.find('.btn-submit').btn('loading'); },
        complete:   function () { $this.find('.btn-submit').btn('reset'); },
        success: function (r) {
            $this.find('.has-error').removeClass('has-error');
            $this.find('span.text-danger, .alert-danger').remove();
            if (r['success']) { window.location.href = window.location.pathname + window.location.search; return; }
            if (r['errors']) {
                var el = [];
                $.each(r['errors'], function (i, j) {
                    var $e = $this.find('[name="' + i + '"]');
                    if ($e.length) { $e.closest('.form-group').addClass('has-error'); $e.after('<span class="text-danger">' + j + '</span>'); }
                    el.push('• ' + j);
                });
                $this.prepend('<div class="alert alert-danger mt-3"><i class="fa fa-exclamation-triangle mr-2"></i>' + el.join('<br>') + '</div>');
            }
        }
    });
    return false;
});

/* ── Qty stepper ── */
$(document).delegate('.number-input div span', 'click', function () {
    var v = $(this).closest('.number-input').find('input').val();
    if ($(this).hasClass('plus')) v++; else v--;
    if (v <= 0) v = 1;
    $(this).closest('.number-input').find('input').val(v).trigger('change');
});

/* ── Guest checkout ── */
$('#btnGuestcontinues').on('click', function () {
    var $btn = $(this);
    $.ajax({ url: '<?= base_url() ?>store/guestCheckout', type: 'POST', dataType: 'json',
        beforeSend: function () { $btn.prop('disabled', true); },
        complete: function () { $btn.prop('disabled', false); },
        success: function (r) {
            if (r.status) {
                $('.checkout-form, .checkout-payments').show();
                $('.auth-step').hide();
                window.location.href = window.location.pathname + window.location.search;
            } else if (r.message) {
                Swal.fire({ icon: 'error', title: '<?= __('store.error') ?? 'Error' ?>', text: r.message, confirmButtonColor: '#d33' });
            } else {
                Swal.fire({ icon: 'error', title: '<?= __('store.error') ?? 'Error' ?>', text: '<?= __('store.something_went_wrong') ?? 'Something went wrong. Please try again.' ?>', confirmButtonColor: '#d33' });
            }
        },
        error: function () {
            Swal.fire({ icon: 'error', title: '<?= __('store.error') ?? 'Error' ?>', text: '<?= __('store.something_went_wrong') ?? 'Something went wrong. Please try again.' ?>', confirmButtonColor: '#d33' });
        }
    });
});

/* ── Country/state change ── */
var selected_state = '<?= isset($shipping) ? $shipping->state_id : '' ?>';
$(document).delegate('[name="country"]', 'change', function () {
    var cc = $(this).val();
    if (isGuest) renderStateAndCart(cc); else getShipping(cc);
});
function renderStateAndCart(cid) {
    $.ajax({ url: '<?= base_url('form/getState') ?>', type: 'POST', dataType: 'json', data: { id: cid },
        success: function (j) {
            var h = '<option value=""><?= __("store.select_state") ?? "Select State" ?></option>';
            $.each(j['states'], function (i, s) {
                h += '<option ' + (selected_state && selected_state == s['id'] ? 'selected' : '') + ' value="' + s['id'] + '">' + s['name'] + '</option>';
            });
            $('[name="state"]').html(h);
            getCart(cid);
        }
    });
}

/* ── Wrap iframes in responsive div ── */
$('#body-checkout .dynamic-content-body').find('iframe').each(function (i, v) {
    $(v).before($('<div class="videoWrapper">' + v.outerHTML + '</div>'));
    $(v).remove();
});

/* ── JSON helper ── */
function IsJsonString(str) {
    try { JSON.parse(str); } catch (e) { return false; } return true;
}

/* ── Affiliate local storage ── */
<?php if (isset($_SESSION['setLocalStorageAffiliateAjax'])):
    $aff = json_decode($_SESSION['setLocalStorageAffiliateAjax']);
    $_SESSION['localStorageAffiliate'] = (int) $aff[0];
?>
var _affData = <?= $_SESSION['setLocalStorageAffiliateAjax'] ?>;
setWithExpiry('affiliate_id', _affData[0], _affData[1]);
<?php unset($_SESSION['setLocalStorageAffiliateAjax']); endif; ?>

function setWithExpiry(k, v, ttl) {
    localStorage.setItem(k, JSON.stringify({ value: v, expiry: new Date().getTime() + ttl }));
}
function getWithExpiry(k) {
    var s = localStorage.getItem(k);
    if (!s) return 1;
    var item = JSON.parse(s);
    if (new Date().getTime() > item.expiry) { localStorage.removeItem(k); return 1; }
    return item.value;
}
</script>

</body>
</html>
