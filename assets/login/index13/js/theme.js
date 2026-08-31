/* ============================================================
   Theme 13: Glass Pro — hero read-more + auth offcanvas (login/register/forgot)
   ============================================================ */

(function () {
	'use strict';

	function getOffcanvasEl() {
		return document.getElementById('glassAuthOffcanvas');
	}

	function glassUpdateDrawerChrome(view) {
		var oc = getOffcanvasEl();
		if (!oc) {
			return;
		}
		var h = oc.getAttribute('data-glass-heading-' + view);
		var s = oc.getAttribute('data-glass-sub-' + view);
		var elH = document.getElementById('glassAuthOffcanvasLabel');
		var elS = document.getElementById('glassAuthOffcanvasSubtitle');
		if (elH && h) {
			elH.textContent = h;
		}
		if (elS && s) {
			elS.textContent = s;
		}
	}

	function glassShowAuthView(view) {
		var oc = getOffcanvasEl();
		if (!oc) {
			return;
		}
		var target = oc.querySelector('.glass-auth-panel[data-panel="' + view + '"]');
		if (!target) {
			view = 'login';
			target = oc.querySelector('.glass-auth-panel[data-panel="login"]');
		}
		if (!target) {
			return;
		}
		oc.querySelectorAll('.glass-auth-panel[data-panel]').forEach(function (p) {
			var on = p === target;
			p.classList.toggle('d-none', !on);
			p.classList.toggle('is-active', on);
		});
		oc.querySelectorAll('.glass-auth-mode-tabs .nav-link').forEach(function (btn) {
			var v = btn.getAttribute('data-glass-auth-view');
			var active = v === view;
			btn.classList.toggle('active', active);
			btn.setAttribute('aria-selected', active ? 'true' : 'false');
		});
		var tabBar = oc.querySelector('.glass-auth-mode-tabs');
		if (tabBar) {
			tabBar.classList.toggle('d-none', view === 'forgot');
		}
		glassUpdateDrawerChrome(view);
		if (view === 'register') {
			var regForm = oc.querySelector('form.reg_form');
			if (regForm) {
				var hid = regForm.querySelector('input[name="is_vendor"]');
				glassVendorSwitch(regForm, !!(hid && hid.value === '1'));
			}
		}
	}

	function glassRegisterUiScope(form) {
		if (!form) {
			return null;
		}
		return form.closest('.registration-form-container')
			|| form.closest('.glass-auth-panel[data-panel="register"]')
			|| form.closest('#glassAuthOffcanvas');
	}

	function glassVendorSwitch(form, wantVendor) {
		var hid = form.querySelector('input[name="is_vendor"]');
		if (hid) {
			hid.value = wantVendor ? '1' : '0';
		}
		var sf = form.querySelector('.store_fields');
		if (sf) {
			sf.style.display = wantVendor ? '' : 'none';
		}
		var scope = glassRegisterUiScope(form);
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

	function initHeroReadMore() {
		var wrap = document.querySelector('.glass-hero-intro-wrap');
		if (!wrap) {
			return;
		}
		var intro = wrap.querySelector('.glass-hero-intro--collapsible');
		var inner = wrap.querySelector('.glass-hero-intro-inner');
		var btn = wrap.querySelector('.glass-hero-readmore-btn');
		var label = wrap.querySelector('.glass-hero-readmore-label');
		if (!intro || !inner || !btn || !label) {
			return;
		}
		var rm = btn.getAttribute('data-read-more') || '';
		var rl = btn.getAttribute('data-read-less') || '';

		function syncLabel() {
			var expanded = intro.classList.contains('is-expanded');
			label.textContent = expanded ? rl : rm;
		}

		function measure() {
			intro.classList.add('is-expanded');
			var fullH = inner.scrollHeight;
			intro.classList.remove('is-expanded');
			var clampH = inner.clientHeight;
			if (fullH > clampH + 4) {
				btn.classList.remove('d-none');
			} else {
				btn.classList.add('d-none');
			}
		}

		btn.addEventListener('click', function () {
			intro.classList.toggle('is-expanded');
			syncLabel();
		});
		syncLabel();
		measure();
		window.addEventListener('resize', function () {
			measure();
		});
	}

	document.addEventListener('DOMContentLoaded', function () {
		var ocEl = getOffcanvasEl();

		var autoRoot = document.querySelector('[data-glass-auth-autoshow="1"]');
		if (autoRoot && ocEl && typeof bootstrap !== 'undefined' && bootstrap.Offcanvas) {
			bootstrap.Offcanvas.getOrCreateInstance(ocEl).show();
		}

		if (ocEl) {
			ocEl.addEventListener('show.bs.offcanvas', function () {
				document.body.classList.add('glass-offcanvas-open');
				var init = ocEl.getAttribute('data-glass-initial-view') || 'login';
				glassShowAuthView(init);
			});
			ocEl.addEventListener('hidden.bs.offcanvas', function () {
				document.body.classList.remove('glass-offcanvas-open');
			});
		}

		document.addEventListener('click', function (e) {
			var trig = e.target.closest('[data-glass-auth-view]');
			if (!trig || !ocEl || !ocEl.contains(trig)) {
				return;
			}
			var view = trig.getAttribute('data-glass-auth-view');
			if (!view) {
				return;
			}
			var tag = (trig.tagName || '').toLowerCase();
			if (tag === 'a') {
				e.preventDefault();
			}
			glassShowAuthView(view);
		});

		document.addEventListener('click', function (e) {
			var a = e.target.closest('#glassAuthOffcanvas .aff-reg-tabs a[href*="register"]');
			if (!a || !ocEl || !ocEl.contains(a)) {
				return;
			}
			e.preventDefault();
			var form = ocEl.querySelector('form.reg_form');
			if (!form) {
				return;
			}
			var wantVendor = a.getAttribute('data-registartion_type') === 'ven';
			glassVendorSwitch(form, wantVendor);
		});

		document.querySelectorAll('.toggle-password').forEach(function (btn) {
			btn.addEventListener('click', function (ev) {
				ev.preventDefault();
				var dt = btn.getAttribute('data-target');
				if (dt) {
					var tEl = document.querySelector(dt);
					if (tEl) {
						var ip = tEl.type === 'password';
						tEl.type = ip ? 'text' : 'password';
						var ic = btn.querySelector('i');
						if (ic) {
							ic.classList.toggle('bi-eye', !ip);
							ic.classList.toggle('bi-eye-slash', ip);
						}
					}
					return;
				}
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

		document.querySelectorAll('.glass-input').forEach(function (input) {
			var group = input.closest('.glass-input-group');
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

		initHeroReadMore();
	});
})();
