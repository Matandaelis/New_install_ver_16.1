<?php
$db = &get_instance();
$products = $db->Product_model;
$cookies_consent = $products->getSettings('site', 'cookies_consent');
$cookies_consent_mesag = $products->getSettings('site', 'cookies_consent_mesag');
$tnc = $products->getSettingsWithLanaguage('tnc') ?: [];
$setting = $products->getSettings('site') ?: [];
?>

<!-- Policy Modal (Bootstrap 4) -->
<div class="modal fade" id="termOfUse" tabindex="-1" role="dialog"
     aria-labelledby="termsOfUseTitle" aria-hidden="true">
  <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
    <div class="modal-content">
      <div class="modal-header bg-primary text-white">
        <h5 class="modal-title" id="termsOfUseTitle"><?= $tnc['heading'] ?? '' ?></h5>
        <button type="button" class="close text-white" data-dismiss="modal" aria-label="<?= __('front.close') ?>">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <p class="modal-text"><?= $tnc['content'] ?? '' ?></p>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal"><?= __('front.close') ?></button>
      </div>
    </div>
  </div>
</div>

<!-- About Modal (Bootstrap 4) -->
<div class="modal fade" id="about" tabindex="-1" role="dialog"
     aria-labelledby="aboutTitle" aria-hidden="true">
  <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
    <div class="modal-content">
      <div class="modal-header bg-primary text-white">
        <h5 class="modal-title" id="aboutTitle"><?= __('front.about') ?></h5>
        <button type="button" class="close text-white" data-dismiss="modal" aria-label="<?= __('front.close') ?>">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <p class="modal-text"><?= $setting['about_content'] ?? '' ?></p>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal"><?= __('front.close') ?></button>
      </div>
    </div>
  </div>
</div>

<?php if (!empty($cookies_consent) && $cookies_consent['cookies_consent'] == 1) : ?>
<!-- Cookie consent bar (display controlled only via JS — no d-flex class to avoid !important conflict) -->
<div id="cookie-consent-popup"
     class="fixed-bottom py-3"
     style="display:none; background:rgba(0,0,0,.85); border-top:1px solid rgba(255,255,255,.1); z-index:9990;">
  <div class="container">
    <div class="row align-items-center">
      <div class="col-md-8 mb-2 mb-md-0">
        <h5 class="mb-1 text-white font-weight-bold"><?= __('front.we_value_your_privacy') ?></h5>
        <p class="mb-0 text-white" style="font-size:.9rem;">
          <?php if (!empty($cookies_consent_mesag)): ?>
            <?= $cookies_consent_mesag['cookies_consent_mesag'] ?>
          <?php else: ?>
            <?= __('admin.cookies_consent_default_message') ?>
          <?php endif; ?>
        </p>
      </div>
      <div class="col-md-4 d-flex justify-content-md-end align-items-center flex-wrap" style="gap:6px;">
        <button id="cookie-consent-accept"  class="btn btn-primary btn-sm"><?= __('front.cookie_accept') ?? 'Accept' ?></button>
        <button id="cookie-consent-decline" class="btn btn-outline-light btn-sm"><?= __('front.cookie_decline') ?? 'Decline' ?></button>
        <button id="cookie-consent-edit"    class="btn btn-outline-light btn-sm"
                data-toggle="modal" data-target="#cookie-preferences-modal"><?= __('front.cookie_edit_preferences') ?? 'Edit Preferences' ?></button>
      </div>
    </div>
  </div>
</div>

<!-- Cookie Preferences Modal (Bootstrap 4) -->
<div class="modal fade" id="cookie-preferences-modal" tabindex="-1" role="dialog"
     aria-labelledby="cookiePreferencesLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header bg-secondary text-white">
        <h5 class="modal-title" id="cookiePreferencesLabel"><?= __('front.cookie_preferences') ?></h5>
        <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <form id="cookie-preferences-form">
          <div class="custom-control custom-switch">
            <input class="custom-control-input" type="checkbox" id="cookie1" value="affiliate_id">
            <label class="custom-control-label" for="cookie1"><?= __('front.enable_affiliate_tracking') ?></label>
          </div>
        </form>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal"><?= __('front.close') ?></button>
        <button type="button" class="btn btn-primary" id="cookie-preferences-save"><?= __('front.save_preferences') ?></button>
      </div>
    </div>
  </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function () {
    var cookieCheckbox = document.getElementById('cookie1');

    /* Restore saved checkbox state */
    try {
        var saved = localStorage.getItem('selectedCookies');
        if (!saved) {
            saved = JSON.stringify({ cookie1: true });
            localStorage.setItem('selectedCookies', saved);
        }
        var data = JSON.parse(saved);
        if (data && data['cookie1'] !== undefined) {
            if (typeof $ !== 'undefined') $('#affiliate_cookie').val(data['cookie1']);
            if (cookieCheckbox) cookieCheckbox.checked = !!data['cookie1'];
        }
    } catch (e) {
        console.error('Cookie consent error:', e);
    }

    /* Show bar if consent not yet given */
    if (!localStorage.getItem('cookieConsent')) {
        var popup = document.getElementById('cookie-consent-popup');
        if (popup) {
            popup.style.display = 'flex';
            popup.style.alignItems = 'center';
            popup.style.justifyContent = 'center';
        }
    }

    function dismissConsentBar() {
        var el = document.getElementById('cookie-consent-popup');
        if (el) el.parentNode.removeChild(el);
    }

    /* Accept */
    var btnAccept = document.getElementById('cookie-consent-accept');
    if (btnAccept) {
        btnAccept.addEventListener('click', function () {
            localStorage.setItem('cookieConsent', 'accepted');
            localStorage.setItem('selectedCookies', JSON.stringify({ cookie1: true }));
            if (cookieCheckbox) cookieCheckbox.checked = true;
            dismissConsentBar();
        });
    }

    /* Decline */
    var btnDecline = document.getElementById('cookie-consent-decline');
    if (btnDecline) {
        btnDecline.addEventListener('click', function () {
            localStorage.setItem('cookieConsent', 'declined');
            localStorage.setItem('selectedCookies', JSON.stringify({ cookie1: false }));
            if (cookieCheckbox) cookieCheckbox.checked = false;
            document.cookie = 'cookie1=; expires=Thu, 01 Jan 1970 00:00:00 UTC; path=/;';
            dismissConsentBar();
        });
    }

    /* Save preferences — Bootstrap 4: use $(el).modal('hide') */
    var btnSave = document.getElementById('cookie-preferences-save');
    if (btnSave) {
        btnSave.addEventListener('click', function () {
            var selected = { cookie1: cookieCheckbox ? cookieCheckbox.checked : false };
            localStorage.setItem('cookieConsent', 'custom');
            localStorage.setItem('selectedCookies', JSON.stringify(selected));
            /* Bootstrap 4 modal hide */
            if (typeof $ !== 'undefined') {
                $('#cookie-preferences-modal').modal('hide');
            }
        });
    }
});
</script>
<?php endif; ?>
