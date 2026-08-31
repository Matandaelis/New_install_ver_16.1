<?php
defined('BASEPATH') OR exit('No direct script access allowed');
$items = isset($login_features_items) && is_array($login_features_items) ? $login_features_items : [];
if ($items === []) {
	return;
}
$layout = isset($login_blocks_layout) ? preg_replace('/[^a-z0-9_-]/', '', (string) $login_blocks_layout) : 'centered';
if ($layout === '') {
	$layout = 'centered';
}
$opt = isset($login_features_display) && is_array($login_features_display)
	? array_merge(login_page_features_display_defaults(), $login_features_display)
	: login_page_features_display_defaults();
$colsSm = max(1, min(4, (int) ($opt['columns_sm'] ?? 2)));
$colsMd = max(1, min(4, (int) ($opt['columns_md'] ?? 4)));
$colsLg = max(1, min(4, (int) ($opt['columns_lg'] ?? 4)));
$variant = ($opt['variant'] ?? 'cards') === 'plain' ? 'plain' : 'cards';
$showDesc = !empty($opt['show_description']);
$iconStyle = (string) ($opt['icon_style'] ?? 'circle');
if (!in_array($iconStyle, ['none', 'soft', 'circle'], true)) {
	$iconStyle = 'circle';
}
$rowCols = 'row-cols-1 row-cols-sm-' . $colsSm . ' row-cols-md-' . $colsMd . ' row-cols-lg-' . $colsLg;
$sectionClass = 'aff-login-features aff-theme-blocks aff-theme-blocks--footer aff-theme-blocks--' . htmlspecialchars($layout, ENT_QUOTES, 'UTF-8')
	. ' aff-login-features--elevated py-5 position-relative z-1';
?>
<div class="<?= $sectionClass ?>"
	role="region"
	aria-label="<?= htmlspecialchars(__('front.login_promo_features_section_label'), ENT_QUOTES, 'UTF-8') ?>">
	<div class="row <?= htmlspecialchars($rowCols, ENT_QUOTES, 'UTF-8') ?> g-4 g-lg-4 justify-content-center w-100">
			<?php foreach ($items as $feat):
				$icon = isset($feat['icon']) ? (string) $feat['icon'] : 'bi-star-fill';
				$title = isset($feat['title']) ? (string) $feat['title'] : '';
				$desc = isset($feat['description']) ? (string) $feat['description'] : '';
				$cellClass = $variant === 'cards'
					? 'aff-login-feature-cell card h-100 rounded-4 text-center p-4 p-lg-4 aff-login-feature-card-bg'
					: 'aff-login-feature-cell aff-login-feature-cell--plain h-100 text-center px-2 py-3';
				?>
			<div class="col d-flex">
				<div class="<?= htmlspecialchars($cellClass, ENT_QUOTES, 'UTF-8') ?> w-100 d-flex flex-column align-items-center">
					<?php if ($iconStyle === 'none'): ?>
					<div class="aff-login-feature-icon-slot mb-3">
						<i class="bi <?= htmlspecialchars($icon, ENT_QUOTES, 'UTF-8') ?> aff-login-feature-ico display-5 text-primary" aria-hidden="true"></i>
					</div>
					<?php elseif ($iconStyle === 'soft'): ?>
					<div class="aff-login-feature-icon-slot mb-3">
						<span class="d-inline-flex align-items-center justify-content-center rounded-3 bg-primary bg-opacity-10 px-3 py-3">
							<i class="bi <?= htmlspecialchars($icon, ENT_QUOTES, 'UTF-8') ?> aff-login-feature-ico display-6 text-primary lh-1" aria-hidden="true"></i>
						</span>
					</div>
					<?php else: ?>
					<div class="aff-login-feature-icon-slot mb-3">
						<span class="d-inline-flex align-items-center justify-content-center rounded-circle bg-primary bg-opacity-10 text-primary aff-login-feature-icon-circle">
							<i class="bi <?= htmlspecialchars($icon, ENT_QUOTES, 'UTF-8') ?> aff-login-feature-ico fs-2 lh-1" aria-hidden="true"></i>
						</span>
					</div>
					<?php endif; ?>
					<h3 class="h5 fw-bold mb-2 aff-login-feature-title"><?= htmlspecialchars($title, ENT_QUOTES, 'UTF-8') ?></h3>
					<?php if ($showDesc && $desc !== ''): ?>
					<p class="small mb-0 lh-base aff-login-feature-desc"><?= nl2br(htmlspecialchars($desc, ENT_QUOTES, 'UTF-8')) ?></p>
					<?php endif; ?>
				</div>
			</div>
			<?php endforeach; ?>
	</div>
</div>
