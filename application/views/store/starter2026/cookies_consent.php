<?php
/**
 * Starter 2026 — Cookie Consent Banner (partial)
 *
 * @contract  Store API v1 — shared partial: cookies_consent (included by layout.php)
 *
 * GLOBALS  $SiteSetting
 *
 * Reads from $SiteSetting:
 *   $SiteSetting['cookies_consent']        string  "1" to enable the banner
 *   $SiteSetting['cookies_consent_mesag']  string  Consent message text (HTML allowed)
 */
$consent_enabled = false;
$consent_message = 'We use cookies to enhance your browsing experience, serve personalized content, and analyze our traffic. By clicking "Accept", you consent to our use of cookies.';

if (isset($cookies_consent) && !empty($cookies_consent)) {
    if (is_array($cookies_consent)) {
        $consent_enabled = !empty($cookies_consent['cookies_consent']);
        if (!empty($cookies_consent['cookies_consent_mesag'])) {
            $msg = $cookies_consent['cookies_consent_mesag'];
            $consent_message = is_array($msg) ? implode(' ', $msg) : (string)$msg;
        }
    } else {
        $consent_enabled = true;
    }
}
if (isset($cookies_consent_mesag) && !empty($cookies_consent_mesag)) {
    if (is_array($cookies_consent_mesag)) {
        $consent_message = implode(' ', $cookies_consent_mesag);
    } elseif (is_string($cookies_consent_mesag)) {
        $consent_message = $cookies_consent_mesag;
    }
}
?>

<?php if ($consent_enabled): ?>
<!-- ═══════════ Cookie Consent Banner ═══════════ -->
<div class="s26-cookie-consent" id="cookie-consent-popup" style="display:none">
    <div class="s26-cookie-consent__inner">
        <div class="s26-cookie-consent__content">
            <div class="s26-cookie-consent__icon">
                <i class="fas fa-cookie-bite"></i>
            </div>
            <div class="s26-cookie-consent__text">
                <h6 class="s26-cookie-consent__title"><?= __('store.cookie_notice') ?? 'Cookie Notice' ?></h6>
                <p class="s26-cookie-consent__message"><?= $consent_message ?></p>
            </div>
        </div>
        <div class="s26-cookie-consent__actions">
            <button type="button" class="s26-cookie-btn s26-cookie-btn--accept" id="cookie-consent-accept">
                <i class="fas fa-check"></i>
                <?= __('store.accept') ?? 'Accept' ?>
            </button>
            <button type="button" class="s26-cookie-btn s26-cookie-btn--decline" id="cookie-consent-decline">
                <?= __('store.decline') ?? 'Decline' ?>
            </button>
            <button type="button" class="s26-cookie-btn s26-cookie-btn--edit" id="cookie-consent-edit">
                <i class="fas fa-cog"></i>
                <?= __('store.edit_preferences') ?? 'Edit' ?>
            </button>
        </div>
    </div>
</div>

<!-- ═══════════ Cookie Preferences Modal ═══════════ -->
<div class="modal fade" id="cookie-preferences-modal" tabindex="-1" role="dialog" aria-labelledby="cookiePreferencesLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content" style="border:none;border-radius:var(--s26-radius-lg, 12px);overflow:hidden;">
            <div class="modal-header" style="background:var(--s26-dark, #0f172a);color:#fff;border:none;padding:16px 24px;">
                <h5 class="modal-title" id="cookiePreferencesLabel" style="font-weight:700;font-size:16px;">
                    <i class="fas fa-cookie-bite me-2" style="margin-right:8px;"></i>
                    <?= __('store.cookie_preferences') ?? 'Cookie Preferences' ?>
                </h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close" style="color:#fff;opacity:0.8;text-shadow:none;font-size:24px;">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body" style="padding:24px;">
                <p style="font-size:14px;color:var(--s26-text-muted, #64748b);margin-bottom:20px;">
                    <?= __('store.cookie_preferences_desc') ?? 'Choose which cookies you want to allow. You can change these settings at any time.' ?>
                </p>
                <form id="cookie-preferences-form">
                    <div class="form-check d-flex align-items-center gap-2" style="padding:12px 16px;background:var(--s26-light, #f8fafc);border-radius:var(--s26-radius, 8px);">
                        <input class="form-check-input" type="checkbox" id="cookie1" value="affiliate_id" style="width:18px;height:18px;margin-top:0;">
                        <label class="form-check-label" for="cookie1" style="font-size:14px;font-weight:600;cursor:pointer;">
                            <?= __('store.enable_affiliate_tracking') ?? 'Enable Affiliate Tracking' ?>
                        </label>
                    </div>
                </form>
            </div>
            <div class="modal-footer" style="border-top:1px solid var(--s26-border, #e2e8f0);padding:12px 24px;">
                <button type="button" class="s26-btn-outline" data-dismiss="modal" style="font-size:13px;padding:8px 16px;">
                    <?= __('store.close') ?? 'Close' ?>
                </button>
                <button type="button" class="s26-btn-primary" id="cookie-preferences-save" style="font-size:13px;padding:8px 16px;">
                    <i class="fas fa-save" style="margin-right:4px;"></i>
                    <?= __('store.save_preferences') ?? 'Save Preferences' ?>
                </button>
            </div>
        </div>
    </div>
</div>

<!-- ═══════════ Cookie Consent Script ═══════════ -->
<script type="text/javascript">
(function() {
    var popup = document.getElementById('cookie-consent-popup');
    if (!popup) return;

    /* ── Restore saved cookie preferences ── */
    var savedCookies = null;
    try {
        savedCookies = localStorage.getItem('selectedCookies');
        if (!savedCookies) {
            savedCookies = JSON.stringify({cookie1: true});
            localStorage.setItem('selectedCookies', savedCookies);
        }
        savedCookies = JSON.parse(savedCookies);
    } catch(e) { savedCookies = {cookie1: true}; }

    var cookieCheckbox = document.getElementById('cookie1');
    if (cookieCheckbox && savedCookies && typeof savedCookies.cookie1 !== 'undefined') {
        cookieCheckbox.checked = savedCookies.cookie1;
        if (typeof $ !== 'undefined' && $('#affiliate_cookie').length) {
            $('#affiliate_cookie').val(savedCookies.cookie1);
        }
    }

    /* ── Show banner if no consent yet ── */
    if (!localStorage.getItem('cookieConsent')) {
        popup.style.display = 'flex';
    }

    function hidePopup() {
        popup.style.animation = 'none';
        popup.style.transition = 'transform 0.4s ease, opacity 0.4s ease';
        popup.style.transform = 'translateY(100%)';
        popup.style.opacity = '0';
        setTimeout(function() { popup.remove(); }, 500);
    }

    /* ── Accept ── */
    var acceptBtn = document.getElementById('cookie-consent-accept');
    if (acceptBtn) {
        acceptBtn.addEventListener('click', function() {
            localStorage.setItem('cookieConsent', 'accepted');
            var selected = {cookie1: true};
            localStorage.setItem('selectedCookies', JSON.stringify(selected));
            if (cookieCheckbox) cookieCheckbox.checked = true;
            hidePopup();
        });
    }

    /* ── Decline ── */
    var declineBtn = document.getElementById('cookie-consent-decline');
    if (declineBtn) {
        declineBtn.addEventListener('click', function() {
            localStorage.setItem('cookieConsent', 'declined');
            var declined = {cookie1: false};
            localStorage.setItem('selectedCookies', JSON.stringify(declined));
            if (cookieCheckbox) cookieCheckbox.checked = false;
            document.cookie = 'cookie1=; expires=Thu, 01 Jan 1970 00:00:00 UTC; path=/;';
            hidePopup();
        });
    }

    /* ── Edit Preferences ── */
    var editBtn = document.getElementById('cookie-consent-edit');
    if (editBtn) {
        editBtn.addEventListener('click', function() {
            $('#cookie-preferences-modal').modal('show');
        });
    }

    /* ── Save Preferences ── */
    var saveBtn = document.getElementById('cookie-preferences-save');
    if (saveBtn) {
        saveBtn.addEventListener('click', function() {
            var selected = {};
            selected.cookie1 = cookieCheckbox ? cookieCheckbox.checked : false;
            localStorage.setItem('cookieConsent', 'custom');
            localStorage.setItem('selectedCookies', JSON.stringify(selected));
            $('#cookie-preferences-modal').modal('hide');
            hidePopup();
        });
    }
})();
</script>
<?php endif; ?>
