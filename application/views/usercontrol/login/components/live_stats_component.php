<?php
defined('BASEPATH') OR exit('No direct script access allowed');
$s = isset($login_live_stats) && is_array($login_live_stats) ? $login_live_stats : [
	'active_affiliates' => 0,
	'paid_withdrawals_total' => 0,
	'label_active_affiliates' => '',
	'label_paid_withdrawals' => '',
];
$lbl_active = !empty($s['label_active_affiliates'])
	? htmlspecialchars((string) $s['label_active_affiliates'], ENT_QUOTES, 'UTF-8')
	: __('front.login_live_stats_active_affiliates');
$lbl_withdrawals = !empty($s['label_paid_withdrawals'])
	? htmlspecialchars((string) $s['label_paid_withdrawals'], ENT_QUOTES, 'UTF-8')
	: __('front.login_live_stats_paid_withdrawals');
$int_target = (int) ($s['active_affiliates'] ?? 0);
$money_raw = (float) ($s['paid_withdrawals_total'] ?? 0);
$money_final = c_format($money_raw);

$CI = &get_instance();
$CI->load->library('currency');
$cur_code = '';
if (!empty($_SESSION['userCurrency'])) {
	$cur_code = (string) $_SESSION['userCurrency'];
} else {
	$cur_row = $CI->db->query('SELECT code FROM currency WHERE is_default = 1 LIMIT 1')->row_array();
	if (!empty($cur_row['code'])) {
		$cur_code = (string) $cur_row['code'];
	}
}
$sym_l = $cur_code !== '' ? trim((string) $CI->currency->getSymbolLeft($cur_code)) : '';
$sym_r = $cur_code !== '' ? trim((string) $CI->currency->getSymbolRight($cur_code)) : '';
$dec_places = $cur_code !== '' ? (int) $CI->currency->getDecimalPlace($cur_code) : 2;
if ($dec_places < 0) {
	$dec_places = 2;
}
$zero_num = number_format(0, $dec_places, '.', ',');
?>
<div id="aff-login-live-stats" class="aff-login-live-stats mt-4" role="region" aria-label="<?= htmlspecialchars(__('front.login_live_stats_region_label'), ENT_QUOTES, 'UTF-8') ?>">
	<div class="row g-2 g-sm-3 small">
		<div class="col-6">
			<div class="aff-login-stat-card rounded-4 p-3 h-100">
				<div class="aff-stat-label"><?= $lbl_active ?></div>
				<div class="fs-4 fw-bold aff-stat-value mt-1">
					<span class="aff-stat-count aff-stat-count--int" data-aff-target-int="<?= (int) $int_target ?>">0</span>
				</div>
			</div>
		</div>
		<div class="col-6">
			<div class="aff-login-stat-card rounded-4 p-3 h-100">
				<div class="aff-stat-label"><?= $lbl_withdrawals ?></div>
				<div class="fs-5 fw-bold aff-stat-value mt-1 text-break aff-stat-money-wrap"
					data-aff-money-raw="<?= htmlspecialchars((string) $money_raw, ENT_QUOTES, 'UTF-8') ?>"
					data-aff-dec="<?= (int) $dec_places ?>">
					<span class="aff-stat-money-anim d-inline-flex align-items-baseline flex-wrap gap-0">
						<?php if ($sym_l !== ''): ?>
							<span class="aff-stat-currency-prefix"><?= htmlspecialchars($sym_l, ENT_QUOTES, 'UTF-8') ?></span>
						<?php endif; ?>
						<span class="aff-stat-count aff-stat-count--money-running"><?= htmlspecialchars($zero_num, ENT_QUOTES, 'UTF-8') ?></span>
						<?php if ($sym_r !== ''): ?>
							<span class="aff-stat-currency-suffix"><?= htmlspecialchars($sym_r, ENT_QUOTES, 'UTF-8') ?></span>
						<?php endif; ?>
					</span>
					<span class="aff-stat-count aff-stat-count--money-final d-none"><?= $money_final ?></span>
				</div>
			</div>
		</div>
	</div>
</div>
<script>
(function () {
	function runInt(el) {
		var target = parseInt(el.getAttribute('data-aff-target-int'), 10) || 0;
		var start = performance.now();
		var dur = 900;
		function tick(now) {
			var p = Math.min(1, (now - start) / dur);
			var eased = 1 - Math.pow(1 - p, 3);
			el.textContent = String(Math.round(target * eased));
			if (p < 1) requestAnimationFrame(tick);
			else el.textContent = String(target);
		}
		requestAnimationFrame(tick);
	}
	function runMoney(wrap) {
		var anim = wrap.querySelector('.aff-stat-money-anim');
		var run = wrap.querySelector('.aff-stat-count--money-running');
		var fin = wrap.querySelector('.aff-stat-count--money-final');
		if (!anim || !run || !fin) return;
		var target = parseFloat(wrap.getAttribute('data-aff-money-raw'));
		var dec = parseInt(wrap.getAttribute('data-aff-dec'), 10);
		if (isNaN(dec) || dec < 0) dec = 2;
		if (isNaN(target)) {
			anim.classList.add('d-none');
			fin.classList.remove('d-none');
			return;
		}
		fin.classList.add('d-none');
		anim.classList.remove('d-none');
		var start = performance.now();
		var dur = 1100;
		function tick(now) {
			var p = Math.min(1, (now - start) / dur);
			var eased = 1 - Math.pow(1 - p, 3);
			var cur = target * eased;
			run.textContent = cur.toLocaleString(undefined, { minimumFractionDigits: dec, maximumFractionDigits: dec });
			if (p < 1) requestAnimationFrame(tick);
			else {
				anim.classList.add('d-none');
				fin.classList.remove('d-none');
			}
		}
		requestAnimationFrame(tick);
	}
	document.querySelectorAll('#aff-login-live-stats .aff-stat-count--int').forEach(runInt);
	document.querySelectorAll('#aff-login-live-stats .aff-stat-money-wrap').forEach(runMoney);
})();
</script>
