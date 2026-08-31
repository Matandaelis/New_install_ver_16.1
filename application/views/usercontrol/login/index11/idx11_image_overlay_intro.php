<?php defined('BASEPATH') OR exit('No direct script access allowed');
$idx11_setting = (isset($setting) && is_array($setting)) ? $setting : [];
$idx11_ov_heading = isset($idx11_setting['heading']) ? trim((string) $idx11_setting['heading']) : '';
$idx11_ov_content = isset($idx11_setting['content']) ? (string) $idx11_setting['content'] : '';
?>
<?php if ($idx11_ov_heading !== ''): ?>
	<h3 class="idx11-image-overlay-heading"><?= htmlspecialchars($idx11_ov_heading, ENT_QUOTES, 'UTF-8') ?></h3>
<?php endif; ?>
<div class="idx11-marketing-intro-wrap">
	<div class="idx11-marketing-intro idx11-marketing-intro--collapsible" id="idx11OverlayIntro" role="region">
		<div class="idx11-marketing-intro-inner">
			<?= $idx11_ov_content ?>
		</div>
	</div>
	<button type="button"
			class="btn btn-link idx11-marketing-readmore-btn p-0 mt-1 d-none text-decoration-none fw-semibold"
			id="idx11OverlayReadmoreBtn"
			aria-expanded="false"
			aria-controls="idx11OverlayIntro"
			data-read-more="<?= htmlspecialchars(__('front.idx1_read_more'), ENT_QUOTES, 'UTF-8') ?>"
			data-read-less="<?= htmlspecialchars(__('front.idx1_read_less'), ENT_QUOTES, 'UTF-8') ?>">
		<span class="idx11-marketing-readmore-label"></span>
	</button>
</div>
<script>
(function () {
	function initIdx11OverlayReadMore() {
		var wrap = document.querySelector('.idx11-marketing-intro-wrap');
		if (!wrap) {
			return;
		}
		var inner = wrap.querySelector('.idx11-marketing-intro-inner');
		var root = wrap.querySelector('.idx11-marketing-intro--collapsible');
		var btn = document.getElementById('idx11OverlayReadmoreBtn');
		if (!inner || !root || !btn) {
			return;
		}
		var more = btn.getAttribute('data-read-more') || 'Read more';
		var less = btn.getAttribute('data-read-less') || 'Read less';
		var label = btn.querySelector('.idx11-marketing-readmore-label');
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
	initIdx11OverlayReadMore();
})();
</script>
