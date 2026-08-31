/* ============================================================
   Theme Index1 v2: "Clean Elevated" — Premium JavaScript
   ============================================================ */

(function () {
    function getOffcanvasEl() {
        return document.getElementById('idx1AuthOffcanvas');
    }

    function idx1UpdateDrawerChrome(view) {
        var oc = getOffcanvasEl();
        if (!oc) {
            return;
        }
        var h = oc.getAttribute('data-idx1-heading-' + view);
        var s = oc.getAttribute('data-idx1-sub-' + view);
        var elH = document.getElementById('idx1AuthOffcanvasLabel');
        var elS = document.getElementById('idx1AuthOffcanvasSubtitle');
        if (elH && h) {
            elH.textContent = h;
        }
        if (elS && s) {
            elS.textContent = s;
        }
    }

    function idx1ShowAuthView(view) {
        var oc = getOffcanvasEl();
        if (!oc) {
            return;
        }
        var target = oc.querySelector('.idx1-auth-panel[data-panel="' + view + '"]');
        if (!target) {
            view = 'login';
            target = oc.querySelector('.idx1-auth-panel[data-panel="login"]');
        }
        if (!target) {
            return;
        }
        oc.querySelectorAll('.idx1-auth-panel[data-panel]').forEach(function (p) {
            var on = p === target;
            p.classList.toggle('d-none', !on);
            p.classList.toggle('is-active', on);
        });
        oc.querySelectorAll('.idx1-auth-mode-tabs .nav-link').forEach(function (btn) {
            var v = btn.getAttribute('data-idx1-auth-view');
            var active = v === view;
            btn.classList.toggle('active', active);
            btn.setAttribute('aria-selected', active ? 'true' : 'false');
        });
        var tabBar = oc.querySelector('.idx1-auth-mode-tabs');
        if (tabBar) {
            tabBar.classList.toggle('d-none', view === 'forgot');
        }
        idx1UpdateDrawerChrome(view);
        if (view === 'register') {
            var regForm = oc.querySelector('form.reg_form');
            if (regForm) {
                var hid = regForm.querySelector('input[name="is_vendor"]');
                idx1VendorSwitch(regForm, !!(hid && hid.value === '1'));
            }
        }
    }

    /**
     * Affiliate/Vendor tabs live in register_form.php *above* form.reg_form (sibling),
     * so we must scope from .registration-form-container or the register panel — not the form subtree.
     */
    function idx1RegisterUiScope(form) {
        if (!form) {
            return null;
        }
        return form.closest('.registration-form-container')
            || form.closest('.idx1-auth-panel[data-panel="register"]')
            || form.closest('#idx1AuthOffcanvas');
    }

    function idx1VendorSwitch(form, wantVendor) {
        var hid = form.querySelector('input[name="is_vendor"]');
        if (hid) {
            hid.value = wantVendor ? '1' : '0';
        }
        var sf = form.querySelector('.store_fields');
        if (sf) {
            sf.style.display = wantVendor ? '' : 'none';
        }
        var scope = idx1RegisterUiScope(form);
        if (!scope) {
            return;
        }
        scope.querySelectorAll('.aff-reg-tabs a').forEach(function (a) {
            var isVen = a.getAttribute('data-registartion_type') === 'ven';
            var on = isVen === wantVendor;
            a.classList.toggle('active', on);
            a.setAttribute('aria-selected', on ? 'true' : 'false');
        });
    }

    document.addEventListener('DOMContentLoaded', function () {
        var ocEl = getOffcanvasEl();

        /* --- Register landing: open auth drawer once --- */
        var autoRoot = document.querySelector('[data-idx1-auth-autoshow="1"]');
        if (autoRoot && ocEl && typeof bootstrap !== 'undefined' && bootstrap.Offcanvas) {
            bootstrap.Offcanvas.getOrCreateInstance(ocEl).show();
        }

        if (ocEl) {
            ocEl.addEventListener('show.bs.offcanvas', function () {
                document.body.classList.add('idx1-offcanvas-open');
                var init = ocEl.getAttribute('data-idx1-initial-view') || 'login';
                idx1ShowAuthView(init);
            });
            ocEl.addEventListener('hidden.bs.offcanvas', function () {
                document.body.classList.remove('idx1-offcanvas-open');
            });
        }

        /* --- Login / Register / Forgot: in-drawer switching (no full navigation) --- */
        document.addEventListener('click', function (e) {
            var trig = e.target.closest('[data-idx1-auth-view]');
            if (!trig || !ocEl || !ocEl.contains(trig)) {
                return;
            }
            var view = trig.getAttribute('data-idx1-auth-view');
            if (!view) {
                return;
            }
            var tag = (trig.tagName || '').toLowerCase();
            if (tag === 'a') {
                e.preventDefault();
            }
            idx1ShowAuthView(view);
        });

        /* --- Affiliate / Vendor tabs inside drawer (no page reload) --- */
        document.addEventListener('click', function (e) {
            var a = e.target.closest('#idx1AuthOffcanvas .aff-reg-tabs a[href*="register"]');
            if (!a || !ocEl || !ocEl.contains(a)) {
                return;
            }
            e.preventDefault();
            var form = ocEl.querySelector('form.reg_form');
            if (!form) {
                return;
            }
            var wantVendor = a.getAttribute('data-registartion_type') === 'ven';
            idx1VendorSwitch(form, wantVendor);
        });

        /* --- Password toggle (all instances) --- */
        document.querySelectorAll('.toggle-password').forEach(function (btn) {
            btn.addEventListener('click', function (ev) {
                ev.preventDefault();
                var fg = btn.closest('.form-floating') || btn.closest('.position-relative') || btn.closest('form');
                var input = null;
                if (fg) {
                    input = fg.querySelector('input[name="password"]') || fg.querySelector('input[type="password"]');
                }
                if (!input) {
                    return;
                }
                var type = input.getAttribute('type') === 'password' ? 'text' : 'password';
                input.setAttribute('type', type);
                var icon = btn.querySelector('i');
                if (icon) {
                    icon.className = type === 'password' ? 'bi bi-eye' : 'bi bi-eye-slash';
                }
            });
        });

        /* --- Input focus enhancement --- */
        document.querySelectorAll('.idx1-input').forEach(function (input) {
            var group = input.closest('.idx1-input-group');
            if (!group) {
                return;
            }
            input.addEventListener('focus', function () {
                group.classList.add('focused');
            });
            input.addEventListener('blur', function () {
                group.classList.remove('focused');
            });
        });
    });
})();
