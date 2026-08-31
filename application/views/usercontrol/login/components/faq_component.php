<?php
defined('BASEPATH') OR exit('No direct script access allowed');
$items = isset($login_faq_items) && is_array($login_faq_items) ? $login_faq_items : [];
if ($items === []) {
	return;
}
$layout = isset($login_blocks_layout) ? preg_replace('/[^a-z0-9_-]/', '', (string) $login_blocks_layout) : 'centered';
if ($layout === '') {
	$layout = 'centered';
}
$first_open = isset($login_faq_first_open) && $login_faq_first_open;
$acc_id = 'affLoginFaqAcc';
?>
<section class="aff-login-faq aff-theme-blocks aff-theme-blocks--footer aff-theme-blocks--<?= htmlspecialchars($layout, ENT_QUOTES, 'UTF-8') ?> w-100"
	aria-label="<?= htmlspecialchars(__('front.login_promo_faq_section_label'), ENT_QUOTES, 'UTF-8') ?>">
	<div class="aff-login-faq-card rounded-4 overflow-hidden p-3 p-md-4">
		<h2 class="h5 fw-bold mb-3 text-center text-md-start aff-login-faq-heading"><?= htmlspecialchars(__('front.login_promo_faq_heading'), ENT_QUOTES, 'UTF-8') ?></h2>
		<div class="accordion aff-login-faq-accordion rounded-3 overflow-hidden" id="<?= htmlspecialchars($acc_id, ENT_QUOTES, 'UTF-8') ?>">
			<?php foreach ($items as $i => $row):
				$q = (string) ($row['question'] ?? '');
				$a = (string) ($row['answer'] ?? '');
				$h_id = 'affFaqH' . $i;
				$c_id = 'affFaqC' . $i;
				$is_first = ($i === 0);
				$expanded = $is_first && $first_open;
				?>
			<div class="accordion-item border-0 border-bottom">
				<h3 class="accordion-header" id="<?= htmlspecialchars($h_id, ENT_QUOTES, 'UTF-8') ?>">
					<button class="accordion-button fw-semibold <?= $expanded ? '' : 'collapsed' ?> rounded-0"
						type="button"
						data-bs-toggle="collapse"
						data-bs-target="#<?= htmlspecialchars($c_id, ENT_QUOTES, 'UTF-8') ?>"
						aria-expanded="<?= $expanded ? 'true' : 'false' ?>"
						aria-controls="<?= htmlspecialchars($c_id, ENT_QUOTES, 'UTF-8') ?>">
						<?= htmlspecialchars($q, ENT_QUOTES, 'UTF-8') ?>
					</button>
				</h3>
				<div id="<?= htmlspecialchars($c_id, ENT_QUOTES, 'UTF-8') ?>"
					class="accordion-collapse collapse <?= $expanded ? 'show' : '' ?>"
					aria-labelledby="<?= htmlspecialchars($h_id, ENT_QUOTES, 'UTF-8') ?>"
					data-bs-parent="#<?= htmlspecialchars($acc_id, ENT_QUOTES, 'UTF-8') ?>">
					<div class="accordion-body small lh-lg pt-0 pb-4 px-3 px-md-4 aff-login-faq-answer">
						<?= $a !== '' ? nl2br(htmlspecialchars($a, ENT_QUOTES, 'UTF-8')) : '&nbsp;' ?>
					</div>
				</div>
			</div>
			<?php endforeach; ?>
		</div>
	</div>
</section>
