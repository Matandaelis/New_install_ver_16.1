/* ============================================================
   Theme Index9: hero + right panels + auth offcanvas (login/register/forgot)
   ============================================================ */

(function () {
	'use strict';

	function getOffcanvasEl() {
		return document.getElementById('idx9AuthOffcanvas');
	}

	function idx9UpdateDrawerChrome(view) {
		var oc = getOffcanvasEl();
		if (!oc) {
			return;
		}
		var h = oc.getAttribute('data-idx9-heading-' + view);
		var s = oc.getAttribute('data-idx9-sub-' + view);
		var elH = document.getElementById('idx9AuthOffcanvasLabel');
		var elS = document.getElementById('idx9AuthOffcanvasSubtitle');
		if (elH && h) {
			elH.textContent = h;
		}
		if (elS && s) {
			elS.textContent = s;
		}
	}

	function idx9ShowAuthView(view) {
		var oc = getOffcanvasEl();
		if (!oc) {
			return;
		}
		var target = oc.querySelector('.idx9-auth-panel[data-panel="' + view + '"]');
		if (!target) {
			view = 'login';
			target = oc.querySelector('.idx9-auth-panel[data-panel="login"]');
		}
		if (!target) {
			return;
		}
		oc.querySelectorAll('.idx9-auth-panel[data-panel]').forEach(function (p) {
			var on = p === target;
			p.classList.toggle('d-none', !on);
			p.classList.toggle('is-active', on);
		});
		oc.querySelectorAll('.idx9-auth-mode-tabs .nav-link').forEach(function (btn) {
			var v = btn.getAttribute('data-idx9-auth-view');
			var active = v === view;
			btn.classList.toggle('active', active);
			btn.setAttribute('aria-selected', active ? 'true' : 'false');
		});
		var tabBar = oc.querySelector('.idx9-auth-mode-tabs');
		if (tabBar) {
			tabBar.classList.toggle('d-none', view === 'forgot');
		}
		idx9UpdateDrawerChrome(view);
		if (view === 'register') {
			var regForm = oc.querySelector('form.reg_form');
			if (regForm) {
				var hid = regForm.querySelector('input[name="is_vendor"]');
				idx9VendorSwitch(regForm, !!(hid && hid.value === '1'));
			}
		}
	}

	function idx9RegisterUiScope(form) {
		if (!form) {
			return null;
		}
		return form.closest('.registration-form-container')
			|| form.closest('.idx9-auth-panel[data-panel="register"]')
			|| form.closest('#idx9AuthOffcanvas');
	}

	function idx9VendorSwitch(form, wantVendor) {
		var hid = form.querySelector('input[name="is_vendor"]');
		if (hid) {
			hid.value = wantVendor ? '1' : '0';
		}
		var sf = form.querySelector('.store_fields');
		if (sf) {
			sf.style.display = wantVendor ? '' : 'none';
		}
		var scope = idx9RegisterUiScope(form);
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

	function showPanel(name) {
		document.querySelectorAll('.idx9-panel-section').forEach(function (sec) {
			sec.classList.remove('active');
		});
		var panel = document.querySelector('[data-panel="' + name + '"]');
		if (panel) {
			panel.classList.add('active');
		}
	}

	function initHeroReadMore() {
		var wrap = document.querySelector('.idx9-hero-intro-wrap');
		if (!wrap) {
			return;
		}
		var intro = wrap.querySelector('.idx9-hero-intro--collapsible');
		var inner = wrap.querySelector('.idx9-hero-intro-inner');
		var btn = wrap.querySelector('.idx9-hero-readmore-btn');
		var label = wrap.querySelector('.idx9-hero-readmore-label');
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

		var autoRoot = document.querySelector('[data-idx9-auth-autoshow="1"]');
		if (autoRoot && ocEl && typeof bootstrap !== 'undefined' && bootstrap.Offcanvas) {
			bootstrap.Offcanvas.getOrCreateInstance(ocEl).show();
		}

		if (ocEl) {
			ocEl.addEventListener('show.bs.offcanvas', function () {
				document.body.classList.add('idx9-offcanvas-open');
				var init = ocEl.getAttribute('data-idx9-initial-view') || 'login';
				idx9ShowAuthView(init);
			});
			ocEl.addEventListener('hidden.bs.offcanvas', function () {
				document.body.classList.remove('idx9-offcanvas-open');
			});
		}

		document.querySelectorAll('[data-panel-target]').forEach(function (link) {
			link.addEventListener('click', function (e) {
				e.preventDefault();
				var target = this.getAttribute('data-panel-target');
				showPanel(target);
			});
		});

		document.addEventListener('click', function (e) {
			var trig = e.target.closest('[data-idx9-auth-view]');
			if (!trig || !ocEl || !ocEl.contains(trig)) {
				return;
			}
			var view = trig.getAttribute('data-idx9-auth-view');
			if (!view) {
				return;
			}
			var tag = (trig.tagName || '').toLowerCase();
			if (tag === 'a') {
				e.preventDefault();
			}
			idx9ShowAuthView(view);
		});

		document.addEventListener('click', function (e) {
			var a = e.target.closest('#idx9AuthOffcanvas .aff-reg-tabs a[href*="register"]');
			if (!a || !ocEl || !ocEl.contains(a)) {
				return;
			}
			e.preventDefault();
			var form = ocEl.querySelector('form.reg_form');
			if (!form) {
				return;
			}
			var wantVendor = a.getAttribute('data-registartion_type') === 'ven';
			idx9VendorSwitch(form, wantVendor);
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

		document.querySelectorAll('.idx9-input').forEach(function (input) {
			var group = input.closest('.idx9-input-group');
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
