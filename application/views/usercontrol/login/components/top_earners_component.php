<?php
defined('BASEPATH') OR exit('No direct script access allowed');
$rows = isset($login_top_earners) && is_array($login_top_earners) ? $login_top_earners : [];
if (count($rows) === 0) {
	return;
}
?>
<div id="aff-login-top-earners" class="aff-login-top-earners mt-4" role="region" aria-label="<?= htmlspecialchars(__('front.login_top_earners_region_label')) ?>">
	<div class="aff-login-top-earners-card rounded-4 p-3">
		<div class="d-flex align-items-center gap-2 mb-2">
			<span class="aff-te-trophy" aria-hidden="true">🏆</span>
			<div class="aff-te-heading"><?= __('front.login_top_earners_heading') ?></div>
		</div>
		<div class="d-flex flex-column gap-1">
			<?php foreach ($rows as $idx => $row):
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
			<div class="aff-te-row d-flex align-items-center rounded-3 px-3 py-2<?= $place <= 3 ? ' aff-te-r' . $place : '' ?>">
				<span class="aff-te-rank flex-shrink-0 me-3"><?= (int) $place ?></span>
				<span class="aff-te-name text-truncate flex-grow-1 me-2"><?= htmlspecialchars($label) ?></span>
				<span class="aff-te-amount flex-shrink-0"><?= c_format($amt) ?></span>
			</div>
			<?php endforeach; ?>
		</div>
	</div>
</div>
