<?php
defined('BASEPATH') OR exit('No direct script access allowed');
$layout = isset($login_blocks_layout) ? preg_replace('/[^a-z0-9_-]/', '', (string) $login_blocks_layout) : 'centered';
if ($layout === '') {
	$layout = 'centered';
}
$pulse_on = !empty($login_live_pulse_enabled);
$pulse_url = isset($pulse_fetch_url) ? (string) $pulse_fetch_url : '';
$poll_ms = isset($pulse_poll_ms) ? (int) $pulse_poll_ms : 28000;
$pulse_pos = isset($pulse_toast_position) ? (string) $pulse_toast_position : 'bottom-right';
if (!in_array($pulse_pos, ['bottom-right', 'bottom-left', 'bottom-center'], true)) {
	$pulse_pos = 'bottom-right';
}
if ($poll_ms < 15000) {
	$poll_ms = 15000;
}
if ($poll_ms > 120000) {
	$poll_ms = 120000;
}
?>
<link rel="stylesheet" href="<?= base_url('assets/template/css/login-page-blocks.css') ?>?v=<?= av() ?>">
<div id="aff-hook-floating-pulse" class="aff-theme-hook aff-theme-hook--pulse" data-aff-layout="<?= htmlspecialchars($layout, ENT_QUOTES, 'UTF-8') ?>" hidden aria-hidden="true"></div>
<?php if ($pulse_on && $pulse_url !== ''): ?>
<div id="aff-live-pulse-toast-host" data-aff-pulse-position="<?= htmlspecialchars($pulse_pos, ENT_QUOTES, 'UTF-8') ?>" aria-live="polite" aria-atomic="true"></div>
<script>
(function () {
	var url = <?= json_encode($pulse_url) ?>;
	var pollMs = <?= (int) $poll_ms ?>;
	var host = document.getElementById('aff-live-pulse-toast-host');
	if (!host || !url) return;
	if (window.matchMedia && window.matchMedia('(max-width: 767.98px)').matches) {
		try { host.remove(); } catch (e) {}
		return;
	}
	if (host.parentNode !== document.body) {
		try { document.body.appendChild(host); } catch (e) {}
	}
	var pool = [];
	var poolIdx = 0;
	var busy = false;
	var displayMs = 6500;

	function showNext() {
		if (busy || pool.length === 0) return;
		busy = true;
		var text = pool[poolIdx % pool.length];
		poolIdx++;
		var el = document.createElement('div');
		el.className = 'aff-live-pulse-toast';
		el.setAttribute('role', 'status');
		el.innerHTML = '<span class="aff-pulse-dot" aria-hidden="true"></span><span class="aff-pulse-msg"></span>';
		el.querySelector('.aff-pulse-msg').textContent = text;
		host.appendChild(el);
		requestAnimationFrame(function () { el.classList.add('aff-live-pulse-toast--in'); });
		setTimeout(function () {
			el.classList.remove('aff-live-pulse-toast--in');
			setTimeout(function () {
				try { el.remove(); } catch (e) {}
				busy = false;
				showNext();
			}, 450);
		}, displayMs);
	}

	function setPool(items) {
		if (!items || !items.length) return;
		var seen = {};
		var out = [];
		for (var i = 0; i < items.length; i++) {
			var tx = (items[i] && items[i].text) ? String(items[i].text) : '';
			if (!tx || seen[tx]) continue;
			seen[tx] = 1;
			out.push(tx);
		}
		pool = out;
		poolIdx = 0;
		if (!busy) showNext();
	}

	function fetchActivity() {
		fetch(url, { credentials: 'same-origin', headers: { 'Accept': 'application/json' } })
			.then(function (r) { return r.json(); })
			.then(function (j) {
				if (j && j.ok && j.items && j.items.length) setPool(j.items);
			})
			.catch(function () {});
	}

	fetchActivity();
	setInterval(fetchActivity, pollMs);
})();
</script>
<?php endif; ?>
