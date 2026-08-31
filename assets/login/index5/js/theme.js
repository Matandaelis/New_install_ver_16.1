/* ============================================================
   Theme Index5: hero read-more + auth offcanvas (login/register/forgot)
   ============================================================ */

(function () {
	'use strict';

	function getOffcanvasEl() {
		return document.getElementById('idx5AuthOffcanvas');
	}

	function idx5UpdateDrawerChrome(view) {
		var oc = getOffcanvasEl();
		if (!oc) {
			return;
		}
		var h = oc.getAttribute('data-idx5-heading-' + view);
		var s = oc.getAttribute('data-idx5-sub-' + view);
		var elH = document.getElementById('idx5AuthOffcanvasLabel');
		var elS = document.getElementById('idx5AuthOffcanvasSubtitle');
		if (elH && h) {
			elH.textContent = h;
		}
		if (elS && s) {
			elS.textContent = s;
		}
	}

	function idx5ShowAuthView(view) {
		var oc = getOffcanvasEl();
		if (!oc) {
			return;
		}
		var target = oc.querySelector('.idx5-auth-panel[data-panel="' + view + '"]');
		if (!target) {
			view = 'login';
			target = oc.querySelector('.idx5-auth-panel[data-panel="login"]');
		}
		if (!target) {
			return;
		}
		oc.querySelectorAll('.idx5-auth-panel[data-panel]').forEach(function (p) {
			var on = p === target;
			p.classList.toggle('d-none', !on);
			p.classList.toggle('is-active', on);
		});
		oc.querySelectorAll('.idx5-auth-mode-tabs .nav-link').forEach(function (btn) {
			var v = btn.getAttribute('data-idx5-auth-view');
			var active = v === view;
			btn.classList.toggle('active', active);
			btn.setAttribute('aria-selected', active ? 'true' : 'false');
		});
		var tabBar = oc.querySelector('.idx5-auth-mode-tabs');
		if (tabBar) {
			tabBar.classList.toggle('d-none', view === 'forgot');
		}
		idx5UpdateDrawerChrome(view);
		if (view === 'register') {
			var regForm = oc.querySelector('form.reg_form');
			if (regForm) {
				var hid = regForm.querySelector('input[name="is_vendor"]');
				idx5VendorSwitch(regForm, !!(hid && hid.value === '1'));
			}
		}
	}

	function idx5RegisterUiScope(form) {
		if (!form) {
			return null;
		}
		return form.closest('.registration-form-container')
			|| form.closest('.idx5-auth-panel[data-panel="register"]')
			|| form.closest('#idx5AuthOffcanvas');
	}

	function idx5VendorSwitch(form, wantVendor) {
		var hid = form.querySelector('input[name="is_vendor"]');
		if (hid) {
			hid.value = wantVendor ? '1' : '0';
		}
		var sf = form.querySelector('.store_fields');
		if (sf) {
			sf.style.display = wantVendor ? '' : 'none';
		}
		var scope = idx5RegisterUiScope(form);
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
		var wrap = document.querySelector('.idx5-hero-intro-wrap');
		if (!wrap) {
			return;
		}
		var intro = wrap.querySelector('.idx5-hero-intro--collapsible');
		var inner = wrap.querySelector('.idx5-hero-intro-inner');
		var btn = wrap.querySelector('.idx5-hero-readmore-btn');
		var label = wrap.querySelector('.idx5-hero-readmore-label');
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

		var autoRoot = document.querySelector('[data-idx5-auth-autoshow="1"]');
		if (autoRoot && ocEl && typeof bootstrap !== 'undefined' && bootstrap.Offcanvas) {
			bootstrap.Offcanvas.getOrCreateInstance(ocEl).show();
		}

		if (ocEl) {
			ocEl.addEventListener('show.bs.offcanvas', function () {
				document.body.classList.add('idx5-offcanvas-open');
				var init = ocEl.getAttribute('data-idx5-initial-view') || 'login';
				idx5ShowAuthView(init);
			});
			ocEl.addEventListener('hidden.bs.offcanvas', function () {
				document.body.classList.remove('idx5-offcanvas-open');
			});
		}

		document.addEventListener('click', function (e) {
			var trig = e.target.closest('[data-idx5-auth-view]');
			if (!trig || !ocEl || !ocEl.contains(trig)) {
				return;
			}
			var view = trig.getAttribute('data-idx5-auth-view');
			if (!view) {
				return;
			}
			var tag = (trig.tagName || '').toLowerCase();
			if (tag === 'a') {
				e.preventDefault();
			}
			idx5ShowAuthView(view);
		});

		document.addEventListener('click', function (e) {
			var a = e.target.closest('#idx5AuthOffcanvas .aff-reg-tabs a[href*="register"]');
			if (!a || !ocEl || !ocEl.contains(a)) {
				return;
			}
			e.preventDefault();
			var form = ocEl.querySelector('form.reg_form');
			if (!form) {
				return;
			}
			var wantVendor = a.getAttribute('data-registartion_type') === 'ven';
			idx5VendorSwitch(form, wantVendor);
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
				var fg = btn.closest('.idx5-input-group');
				var input = fg
					? (fg.querySelector('input[name="password"]') || fg.querySelector('input[type="password"]'))
					: null;
				if (!input) {
					fg = btn.closest('.form-floating') || btn.closest('.position-relative') || btn.closest('form');
					input = fg ? (fg.querySelector('input[name="password"]') || fg.querySelector('input[type="password"]')) : null;
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

		document.querySelectorAll('.idx5-input').forEach(function (input) {
			var group = input.closest('.idx5-input-group');
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
