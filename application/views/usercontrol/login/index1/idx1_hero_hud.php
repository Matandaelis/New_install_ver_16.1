<?php
defined('BASEPATH') OR exit('No direct script access allowed');

$idx1_hud_stats = !empty($login_live_stats_visible) && isset($login_live_stats) && is_array($login_live_stats);
$idx1_hud_earner_rows = (isset($login_top_earners) && is_array($login_top_earners)) ? $login_top_earners : [];
$idx1_hud_earners = !empty($login_top_earners_visible) && count($idx1_hud_earner_rows) > 0;
if (!$idx1_hud_stats && !$idx1_hud_earners) {
	return;
}

$s = $idx1_hud_stats ? $login_live_stats : [
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
$idx1_hud_earner_rows = array_slice($idx1_hud_earner_rows, 0, 5);
$hud_aria = htmlspecialchars(__('front.idx1_hud_social_proof_region'), ENT_QUOTES, 'UTF-8');
?>
<div class="idx1-hud-wrap mx-auto ms-lg-0">
	<div class="idx1-hud-blob" aria-hidden="true"></div>
	<div class="idx1-hud-cluster" id="idx1HudCluster" role="region" aria-label="<?= $hud_aria ?>">
		<?php if ($idx1_hud_stats): ?>
			<div class="idx1-hud-floats">
				<div class="idx1-hud-card idx1-hud-card--hero">
					<div class="idx1-hud-card-surface idx1-hud-glass idx1-hud-premium-surface idx1-hud-pad-hero">
						<div class="d-flex align-items-center gap-3">
							<i class="bi bi-people-fill idx1-hud-stat-ico" aria-hidden="true"></i>
							<div class="aff-stat-value idx1-hud-stat-num mb-0 text-break">
								<span class="aff-stat-count aff-stat-count--int" data-aff-target-int="<?= (int) $int_target ?>">0</span>
							</div>
						</div>
						<div class="idx1-hud-stat-cap mt-3 mb-0"><?= $lbl_active ?></div>
					</div>
				</div>
				<div class="idx1-hud-card idx1-hud-card--satellite">
					<div class="idx1-hud-card-surface idx1-hud-glass idx1-hud-premium-surface idx1-hud-pad-sat">
						<div class="d-flex align-items-center gap-2">
							<i class="bi bi-wallet2 idx1-hud-stat-ico idx1-hud-stat-ico--sm" aria-hidden="true"></i>
							<div class="aff-stat-value idx1-hud-stat-num idx1-hud-stat-num--sm mb-0 text-break aff-stat-money-wrap"
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
						<div class="idx1-hud-stat-cap idx1-hud-stat-cap--sm mt-2 mb-0"><?= $lbl_withdrawals ?></div>
					</div>
				</div>
			</div>
		<?php endif; ?>

		<?php if ($idx1_hud_earners): ?>
			<div class="idx1-hud-leaderboard<?= $idx1_hud_stats ? ' idx1-hud-leaderboard--overlap' : '' ?>">
				<div class="idx1-hud-card-surface idx1-hud-lb-surface idx1-hud-premium-surface p-3 p-md-3">
					<div class="d-flex align-items-center gap-2 mb-2 pb-2 idx1-hud-lb-head">
						<i class="bi bi-trophy-fill idx1-hud-lb-ico" aria-hidden="true"></i>
						<span class="idx1-hud-lb-title text-uppercase"><?= htmlspecialchars(__('front.login_top_earners_heading'), ENT_QUOTES, 'UTF-8') ?></span>
					</div>
					<div class="d-flex flex-column gap-2">
						<?php foreach ($idx1_hud_earner_rows as $idx => $row):
							$place = (int) $idx + 1;
							if (!empty($row['display_name'])) {
								$label = (string) $row['display_name'];
							} else {
								$fn = isset($row['firstname']) ? trim((string) $row['firstname']) : '';
								$ln = isset($row['lastname']) ? trim((string) $row['lastname']) : '';
								$label = trim($fn . ' ' . $ln);
							}
							if ($label === '') {
								$label = __('front.login_top_earners_member');
							}
							$amt = isset($row['amount']) ? (float) $row['amount'] : 0.0;
							?>
							<div class="idx1-hud-lb-row d-flex align-items-center rounded-3 px-2 py-2<?= $place <= 3 ? ' idx1-hud-lb-row--p' . $place : '' ?>">
								<span class="idx1-hud-lb-rank flex-shrink-0"><?= (int) $place ?></span>
								<span class="idx1-hud-lb-name text-truncate flex-grow-1 mx-2"><?= htmlspecialchars($label) ?></span>
								<span class="idx1-hud-lb-amt flex-shrink-0"><?= c_format($amt) ?></span>
							</div>
						<?php endforeach; ?>
					</div>
				</div>
			</div>
		<?php endif; ?>
	</div>
</div>
<?php if ($idx1_hud_stats): ?>
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
	function boot() {
		var root = document.getElementById('idx1HudCluster');
		if (!root) return;
		root.querySelectorAll('.aff-stat-count--int').forEach(runInt);
		root.querySelectorAll('.aff-stat-money-wrap').forEach(runMoney);
	}
	if (document.readyState === 'loading') {
		document.addEventListener('DOMContentLoaded', boot);
	} else {
		boot();
	}
})();
</script>
<?php endif; ?>
