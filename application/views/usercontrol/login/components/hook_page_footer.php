<?php
defined('BASEPATH') OR exit('No direct script access allowed');
$layout = isset($login_blocks_layout) ? preg_replace('/[^a-z0-9_-]/', '', (string) $login_blocks_layout) : 'centered';
if ($layout === '') {
	$layout = 'centered';
}
$vid_items = (isset($login_video_items) && is_array($login_video_items)) ? $login_video_items : [];
$vid_cols = isset($login_video_columns) ? (int) $login_video_columns : 1;
if (!in_array($vid_cols, [1, 2, 3], true)) {
	$vid_cols = 1;
}
$vid_max = isset($login_video_max_width) ? (int) $login_video_max_width : 800;
if (!in_array($vid_max, [500, 800, 1100], true)) {
	$vid_max = 800;
}
$vid_count = count($vid_items);
$vid_effective_cols = min($vid_cols, $vid_count);
$vid_gap_px = 16;
if ($vid_effective_cols <= 1) {
	$vid_container_max = $vid_max;
} else {
	$vid_container_max = ($vid_max * $vid_effective_cols) + ($vid_gap_px * ($vid_effective_cols - 1));
}
$features_html = isset($login_features_html) ? (string) $login_features_html : '';
$faq_html = isset($login_faq_html) ? (string) $login_faq_html : '';
$has_features = trim($features_html) !== '';
$has_faq = trim($faq_html) !== '';
$has_video = $vid_items !== [];
if (!$has_features && !$has_video && !$has_faq) {
	return;
}
?>
<?php $containerClass = $layout === 'full' ? 'container-xl' : 'container'; ?>
<section class="aff-login-page-footer-band aff-login-page-footer-band--<?= htmlspecialchars($layout, ENT_QUOTES, 'UTF-8') ?> w-100 flex-shrink-0" role="region" aria-label="<?= htmlspecialchars(__('front.login_promo_footer_region_label'), ENT_QUOTES, 'UTF-8') ?>">
	<div class="<?= $containerClass ?> px-3 pt-4 pb-5 pt-md-4 pb-md-5">
		<div id="aff-hook-page-footer" class="aff-theme-hook aff-theme-hook--footer aff-theme-blocks--<?= htmlspecialchars($layout, ENT_QUOTES, 'UTF-8') ?>" data-aff-layout="<?= htmlspecialchars($layout, ENT_QUOTES, 'UTF-8') ?>">
			<?php if ($has_features): ?>
				<?= $features_html ?>
			<?php endif; ?>
			<?php if ($has_video): ?>
			<?php if ($vid_count === 1 && $vid_cols === 1): ?>
			<div class="aff-login-video-outer mx-auto px-2 px-sm-0 <?= $has_features ? 'mt-4 pt-2 pt-md-3' : '' ?>" style="max-width: <?= (int) $vid_max ?>px; width: 100%;">
			<div class="aff-login-video-block card rounded-4 overflow-hidden">
				<div class="card-body p-3 p-md-4">
					<?php if (trim($vid_items[0]['title']) !== ''): ?>
						<h6 class="aff-login-video-title fw-semibold text-center mb-3"><?= htmlspecialchars($vid_items[0]['title'], ENT_QUOTES, 'UTF-8') ?></h6>
						<?php endif; ?>
						<div class="ratio ratio-16x9 rounded-3 overflow-hidden shadow aff-login-video-frame">
							<iframe src="<?= htmlspecialchars($vid_items[0]['embed_src'], ENT_QUOTES, 'UTF-8') ?>"
								title="<?= htmlspecialchars($vid_items[0]['title'] !== '' ? $vid_items[0]['title'] : __('front.login_promo_video_iframe_title'), ENT_QUOTES, 'UTF-8') ?>"
								allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share"
								allowfullscreen
								loading="lazy"
								class="border-0"></iframe>
						</div>
					</div>
				</div>
			</div>
			<?php else: ?>
			<?php
				$grid_classes = 'row row-cols-1';
				if ($vid_effective_cols >= 2) {
					$grid_classes .= ' row-cols-md-2';
				}
				if ($vid_effective_cols >= 3) {
					$grid_classes .= ' row-cols-lg-3';
				}
			?>
			<div class="aff-login-video-grid-outer <?= $has_features ? 'mt-4 pt-2 pt-md-3' : '' ?>" style="max-width: <?= (int) $vid_container_max ?>px; width: 100%; margin: 0 auto;">
				<div class="<?= $grid_classes ?> aff-login-video-grid">
					<?php foreach ($vid_items as $vitem): ?>
					<div class="col">
						<div class="aff-login-video-block card rounded-4 overflow-hidden h-100">
							<div class="card-body p-3 p-md-4">
								<?php if (trim($vitem['title']) !== ''): ?>
								<h6 class="aff-login-video-title fw-semibold text-center mb-3"><?= htmlspecialchars($vitem['title'], ENT_QUOTES, 'UTF-8') ?></h6>
								<?php endif; ?>
								<div class="ratio ratio-16x9 rounded-3 overflow-hidden shadow aff-login-video-frame">
									<iframe src="<?= htmlspecialchars($vitem['embed_src'], ENT_QUOTES, 'UTF-8') ?>"
										title="<?= htmlspecialchars($vitem['title'] !== '' ? $vitem['title'] : __('front.login_promo_video_iframe_title'), ENT_QUOTES, 'UTF-8') ?>"
										allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share"
										allowfullscreen
										loading="lazy"
										class="border-0"></iframe>
								</div>
							</div>
						</div>
					</div>
					<?php endforeach; ?>
				</div>
			</div>
			<?php endif; ?>
			<?php endif; ?>
			<?php if ($has_faq): ?>
			<div class="<?= ($has_features || $has_video) ? 'mt-4 pt-2 pt-md-3' : '' ?>">
				<?= $faq_html ?>
			</div>
			<?php endif; ?>
		</div>
	</div>
</section>
