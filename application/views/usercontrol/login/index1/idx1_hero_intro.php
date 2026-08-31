<?php defined('BASEPATH') OR exit('No direct script access allowed');
$idx1_intro = isset($setting['content']) ? (string) $setting['content'] : '';
$idx1_intro_align = !empty($idx1_show_hud) ? 'mx-auto mx-lg-0' : 'mx-auto';
?>
<div class="idx1-saas-intro-wrap text-muted mt-3 <?= htmlspecialchars($idx1_intro_align, ENT_QUOTES, 'UTF-8') ?>">
	<div class="idx1-saas-subtext idx1-saas-subtext--collapsible" id="idx1SaasIntro" role="region">
		<div class="idx1-saas-subtext-inner">
			<?= $idx1_intro ?>
		</div>
	</div>
	<button type="button"
			class="btn btn-link idx1-saas-readmore-btn p-0 mt-2 d-none text-decoration-none fw-semibold"
			id="idx1SaasReadmoreBtn"
			aria-expanded="false"
			aria-controls="idx1SaasIntro"
			data-read-more="<?= htmlspecialchars(__('front.idx1_read_more'), ENT_QUOTES, 'UTF-8') ?>"
			data-read-less="<?= htmlspecialchars(__('front.idx1_read_less'), ENT_QUOTES, 'UTF-8') ?>">
		<span class="idx1-saas-readmore-label"></span>
	</button>
</div>
<script>
(function () {
	function initIdx1IntroReadMore() {
		var wrap = document.querySelector('.idx1-saas-intro-wrap');
		if (!wrap) {
			return;
		}
		var inner = wrap.querySelector('.idx1-saas-subtext-inner');
		var root = wrap.querySelector('.idx1-saas-subtext--collapsible');
		var btn = document.getElementById('idx1SaasReadmoreBtn');
		if (!inner || !root || !btn) {
			return;
		}
		var more = btn.getAttribute('data-read-more') || 'Read more';
		var less = btn.getAttribute('data-read-less') || 'Read less';
		var label = btn.querySelector('.idx1-saas-readmore-label');
		function syncLabel() {
			if (!label) {
				return;
			}
			label.textContent = root.classList.contains('is-expanded') ? less : more;
		}
		function isClamped() {
			if (root.classList.contains('is-expanded')) {
				return false;
			}
			return inner.scrollHeight > inner.clientHeight + 2;
		}
		function refresh() {
			if (root.classList.contains('is-expanded')) {
				btn.classList.remove('d-none');
				btn.setAttribute('aria-expanded', 'true');
				syncLabel();
				return;
			}
			if (isClamped()) {
				btn.classList.remove('d-none');
			} else {
				btn.classList.add('d-none');
				root.classList.remove('is-expanded');
			}
			btn.setAttribute('aria-expanded', 'false');
			syncLabel();
		}
		btn.addEventListener('click', function () {
			root.classList.toggle('is-expanded');
			btn.setAttribute('aria-expanded', root.classList.contains('is-expanded') ? 'true' : 'false');
			syncLabel();
			btn.classList.remove('d-none');
		});
		function refreshSoon() {
			requestAnimationFrame(function () {
				requestAnimationFrame(refresh);
			});
		}
		if (document.readyState === 'loading') {
			document.addEventListener('DOMContentLoaded', refreshSoon);
		} else {
			refreshSoon();
		}
		if (document.fonts && document.fonts.ready) {
			document.fonts.ready.then(refreshSoon);
		}
		var t;
		window.addEventListener('resize', function () {
			clearTimeout(t);
			t = setTimeout(refresh, 150);
		});
	}
	initIdx1IntroReadMore();
})();
</script>
