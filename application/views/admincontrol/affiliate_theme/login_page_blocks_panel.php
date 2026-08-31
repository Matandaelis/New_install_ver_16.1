<?php defined('BASEPATH') OR exit('No direct script access allowed');
$login = isset($login) && is_array($login) ? $login : [];
$blk = isset($login_blocks_admin) && is_array($login_blocks_admin) ? $login_blocks_admin : [];
$is_demo = !empty($blk['is_demo_mode']);
$affiliate_login_page_blocks = [
	['setting_key' => 'block_stats_enabled', 'legacy_key' => 'login_site_stats_visible', 'title' => __('admin.login_page_block_platform_payout_title'), 'description' => __('admin.login_page_block_platform_payout_desc'), 'settings_modal' => 'platform_stats'],
	['setting_key' => 'block_top_earners_enabled', 'legacy_key' => null, 'title' => __('admin.login_page_block_top_earners_title'), 'description' => __('admin.login_page_block_top_earners_desc'), 'settings_modal' => 'top_earners'],
	['setting_key' => 'block_features_enabled', 'legacy_key' => null, 'title' => __('admin.login_page_block_features_title'), 'description' => __('admin.login_page_block_features_desc'), 'settings_modal' => 'feature_icons'],
	['setting_key' => 'block_video_enabled', 'legacy_key' => null, 'title' => __('admin.login_page_block_video_title'), 'description' => __('admin.login_page_block_video_desc'), 'settings_modal' => 'video_block'],
	['setting_key' => 'block_faq_enabled', 'legacy_key' => null, 'title' => __('admin.login_page_block_faq_title'), 'description' => __('admin.login_page_block_faq_desc'), 'settings_modal' => 'faq_block'],
	['setting_key' => 'block_live_pulse_enabled', 'legacy_key' => null, 'title' => __('admin.login_page_block_live_pulse_title'), 'description' => __('admin.login_page_block_live_pulse_desc'), 'settings_modal' => 'live_pulse'],
];
$top_earners_admin_cfg = isset($blk['top_earners_cfg']) ? $blk['top_earners_cfg'] : [];
$te_lim = (int) ($top_earners_admin_cfg['display_limit'] ?? 5);
$stats_admin_cfg = isset($blk['stats_cfg']) ? $blk['stats_cfg'] : [];
$video_admin_cfg = isset($blk['video_cfg']) ? $blk['video_cfg'] : [];
$video_max_w = (int) ($video_admin_cfg['max_width'] ?? 800);
$video_max = isset($blk['video_max']) ? (int) $blk['video_max'] : 4;
$video_cols = (int) ($video_admin_cfg['columns'] ?? 1);
$pulse_admin_cfg = isset($blk['pulse_cfg']) ? $blk['pulse_cfg'] : [];
$pulse_sec = (int) ($pulse_admin_cfg['poll_interval_sec'] ?? 28);
$pulse_pos = (string) ($pulse_admin_cfg['toast_position'] ?? 'bottom-right');
$features_admin_slots = isset($blk['features_slots']) ? $blk['features_slots'] : [];
$features_display_opts = isset($blk['features_display']) ? $blk['features_display'] : [];
$features_max = isset($blk['features_max']) ? (int) $blk['features_max'] : 8;
if (!function_exists('login_page_features_settings_raw_array')) {
	get_instance()->load->helper('login_page_blocks');
}
$features_raw_arr = login_page_features_settings_raw_array($login['block_features_settings'] ?? null);
$features_use_demo_content = !empty($features_raw_arr['use_demo_content']);
$faq_admin_cfg = isset($blk['faq_cfg']) ? $blk['faq_cfg'] : [];
$faq_max = isset($blk['faq_max']) ? (int) $blk['faq_max'] : 12;
$aff_block_settings_demo_features = [
	['icon' => 'bi-lightning-charge-fill', 'title' => __('admin.block_features_demo_1_title'), 'description' => __('admin.block_features_demo_1_desc')],
	['icon' => 'bi-shield-check', 'title' => __('admin.block_features_demo_2_title'), 'description' => __('admin.block_features_demo_2_desc')],
	['icon' => 'bi-graph-up-arrow', 'title' => __('admin.block_features_demo_3_title'), 'description' => __('admin.block_features_demo_3_desc')],
	['icon' => 'bi-headset', 'title' => __('admin.block_features_demo_4_title'), 'description' => __('admin.block_features_demo_4_desc')],
];
$aff_block_settings_demo_faq = [
	['question' => __('admin.block_faq_demo_1_q'), 'answer' => __('admin.block_faq_demo_1_a')],
	['question' => __('admin.block_faq_demo_2_q'), 'answer' => __('admin.block_faq_demo_2_a')],
	['question' => __('admin.block_faq_demo_3_q'), 'answer' => __('admin.block_faq_demo_3_a')],
	['question' => __('admin.block_faq_demo_4_q'), 'answer' => __('admin.block_faq_demo_4_a')],
];
$aff_json_demo_enc = JSON_HEX_TAG | JSON_HEX_AMP | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_UNESCAPED_UNICODE;
$aff_feature_icon_picker_choices = [
	['c' => 'bi-star-fill', 'lang' => 'block_features_icon_choice_star'],
	['c' => 'bi-shield-check', 'lang' => 'block_features_icon_choice_shield'],
	['c' => 'bi-lightning-charge-fill', 'lang' => 'block_features_icon_choice_lightning'],
	['c' => 'bi-wallet2', 'lang' => 'block_features_icon_choice_wallet'],
	['c' => 'bi-headset', 'lang' => 'block_features_icon_choice_support'],
	['c' => 'bi-gear-fill', 'lang' => 'block_features_icon_choice_gear'],
	['c' => 'bi-award-fill', 'lang' => 'block_features_icon_choice_award'],
	['c' => 'bi-graph-up-arrow', 'lang' => 'block_features_icon_choice_growth'],
	['c' => 'bi-people-fill', 'lang' => 'block_features_icon_choice_people'],
	['c' => 'bi-clock-history', 'lang' => 'block_features_icon_choice_clock'],
	['c' => 'bi-globe2', 'lang' => 'block_features_icon_choice_globe'],
	['c' => 'bi-lock-fill', 'lang' => 'block_features_icon_choice_lock'],
	['c' => 'bi-currency-dollar', 'lang' => 'block_features_icon_choice_money'],
	['c' => 'bi-chat-dots-fill', 'lang' => 'block_features_icon_choice_chat'],
	['c' => 'bi-check-circle-fill', 'lang' => 'block_features_icon_choice_check'],
];
$aff_feature_icon_picker_json = [];
foreach ($aff_feature_icon_picker_choices as $row) {
	$aff_feature_icon_picker_json[] = ['c' => $row['c'], 'l' => __('admin.' . $row['lang'])];
}
$pulse_interval_options = [15, 20, 25, 28, 30, 45, 60, 90, 120];
if (!in_array($pulse_sec, $pulse_interval_options, true)) {
	$pulse_interval_options[] = $pulse_sec;
	sort($pulse_interval_options, SORT_NUMERIC);
}
?>
<link rel="stylesheet" href="<?= base_url('assets/template/css/admin-login-page-blocks.css') ?>?v=<?= av() ?>">
<div class="offcanvas offcanvas-end shadow-lg" tabindex="-1" id="affiliate-page-content-offcanvas" aria-labelledby="affiliate-page-content-offcanvas-label">
	<div class="offcanvas-header border-bottom py-3">
		<div>
			<h5 class="offcanvas-title fw-bold mb-0" id="affiliate-page-content-offcanvas-label"><?= __('admin.affiliate_page_content_settings_title') ?></h5>
			<p class="text-muted small mb-0 mt-1"><?= __('admin.affiliate_page_content_settings_intro') ?></p>
		</div>
		<button type="button" class="btn-close" data-bs-dismiss="offcanvas" aria-label="<?= htmlspecialchars(__('admin.close'), ENT_QUOTES, 'UTF-8') ?>"></button>
	</div>
	<div class="offcanvas-body p-0">
		<div class="list-group list-group-flush">
			<?php foreach ($affiliate_login_page_blocks as $block):
				$row_on = login_page_block_enabled($login, $block['setting_key'], $block['legacy_key']);
				$toggle_id = 'toggle_' . preg_replace('/[^a-z0-9_]/i', '_', $block['setting_key']);
				$block_title_esc = htmlspecialchars($block['title'], ENT_QUOTES, 'UTF-8');
				?>
			<div class="list-group-item px-3 py-3">
				<div class="d-flex align-items-start gap-3">
					<div class="flex-grow-1 min-w-0">
						<div class="fw-semibold text-dark"><?= $block_title_esc ?></div>
						<div class="text-muted small mt-1"><?= htmlspecialchars($block['description']) ?></div>
					</div>
					<div class="d-flex align-items-center gap-2 flex-shrink-0 pt-1">
						<div class="form-check form-switch mb-0">
							<input class="form-check-input update_all_settings" type="checkbox" role="switch" id="<?= htmlspecialchars($toggle_id) ?>"
								<?= $row_on ? 'checked' : '' ?>
								<?= $is_demo ? 'disabled' : '' ?>
								data-setting_key="<?= htmlspecialchars($block['setting_key']) ?>"
								data-setting_type="login"
								aria-label="<?= $block_title_esc ?>">
						</div>
						<button type="button" class="btn btn-sm btn-outline-secondary login-page-block-settings login-page-block-settings-gear rounded-circle"
							data-block-title="<?= $block_title_esc ?>"
							data-settings-modal="<?= htmlspecialchars($block['settings_modal'] ?? '') ?>"
							title="<?= htmlspecialchars(__('admin.login_page_block_settings')) ?>"
							aria-label="<?= htmlspecialchars(__('admin.login_page_block_settings')) ?>">
							<i class="fas fa-cog"></i>
						</button>
					</div>
				</div>
			</div>
			<?php endforeach; ?>
		</div>
	</div>
</div>
<div class="modal fade" id="login-page-block-settings-modal" tabindex="-1" aria-hidden="true" aria-labelledby="login-page-block-settings-modal-title">
	<div class="modal-dialog modal-dialog-centered modal-lg" id="login-page-block-settings-modal-dialog">
		<div class="modal-content border-0 shadow">
			<div class="modal-header border-bottom-0 pb-0">
				<h5 class="modal-title fw-bold" id="login-page-block-settings-modal-title"></h5>
				<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="<?= htmlspecialchars(__('admin.close'), ENT_QUOTES, 'UTF-8') ?>"></button>
			</div>
			<div class="modal-body pt-2" id="login-page-block-settings-modal-body">
				<div id="login-page-block-settings-generic" class="d-none">
					<p class="text-muted mb-0" id="login-page-block-settings-generic-text"></p>
				</div>
				<div id="login-page-block-settings-platform-stats" class="d-none">
					<p class="aff-fi-lead small text-secondary mb-3"><?= htmlspecialchars(__('admin.block_stats_modal_lead')) ?></p>
					<div class="d-flex flex-wrap align-items-center justify-content-between gap-2 mb-2">
						<div class="aff-fi-section-label mb-0"><?= htmlspecialchars(__('admin.block_stats_section_labels')) ?></div>
						<button type="button" class="btn btn-sm btn-outline-primary flex-shrink-0" id="platform-stats-load-demo" title="<?= htmlspecialchars(__('admin.block_stats_load_demo_hint')) ?>">
							<i class="bi bi-magic me-1"></i><?= htmlspecialchars(__('admin.block_stats_load_demo')) ?>
						</button>
					</div>
					<div class="mb-3">
						<label for="platform-stats-active-label" class="form-label fw-semibold text-dark"><?= __('admin.block_stats_active_label') ?></label>
						<input type="text" class="form-control" id="platform-stats-active-label" maxlength="120" value="<?= htmlspecialchars($stats_admin_cfg['active_label']) ?>" placeholder="<?= htmlspecialchars(__('admin.block_stats_active_label_placeholder')) ?>">
					</div>
					<div class="mb-0">
						<label for="platform-stats-withdrawals-label" class="form-label fw-semibold text-dark"><?= __('admin.block_stats_withdrawals_label') ?></label>
						<input type="text" class="form-control" id="platform-stats-withdrawals-label" maxlength="120" value="<?= htmlspecialchars($stats_admin_cfg['withdrawals_label']) ?>" placeholder="<?= htmlspecialchars(__('admin.block_stats_withdrawals_label_placeholder')) ?>">
					</div>
					<p class="text-muted small mt-3 mb-0"><?= __('admin.block_stats_labels_hint') ?></p>
					<div class="form-check form-switch mt-3">
						<input class="form-check-input" type="checkbox" role="switch" id="platform-stats-use-demo-values" <?= !empty($stats_admin_cfg['demo_values']) ? 'checked' : '' ?>>
						<label class="form-check-label text-dark" for="platform-stats-use-demo-values"><?= __('admin.block_stats_demo_values_switch') ?></label>
					</div>
					<p class="text-muted small mt-2 mb-0"><?= __('admin.block_stats_demo_values_help') ?></p>
				</div>
				<div id="login-page-block-settings-top-earners" class="d-none">
					<p class="aff-fi-lead small text-secondary mb-3"><?= htmlspecialchars(__('admin.top_earners_modal_lead')) ?></p>
					<div class="d-flex flex-wrap align-items-center justify-content-between gap-2 mb-2">
						<div class="aff-fi-section-label mb-0"><?= htmlspecialchars(__('admin.block_top_earners_section_list')) ?></div>
						<button type="button" class="btn btn-sm btn-outline-primary flex-shrink-0" id="top-earners-load-demo" title="<?= htmlspecialchars(__('admin.block_top_earners_load_demo_hint')) ?>">
							<i class="bi bi-magic me-1"></i><?= htmlspecialchars(__('admin.block_top_earners_load_demo')) ?>
						</button>
					</div>
					<div class="mb-3">
						<label for="top-earners-display-limit" class="form-label fw-semibold text-dark"><?= __('admin.top_earners_display_limit') ?></label>
						<select class="form-select" id="top-earners-display-limit">
							<option value="3" <?= $te_lim === 3 ? 'selected' : '' ?>>3</option>
							<option value="5" <?= $te_lim === 5 ? 'selected' : '' ?>>5</option>
							<option value="10" <?= $te_lim === 10 ? 'selected' : '' ?>>10</option>
						</select>
					</div>
					<div class="form-check form-switch mb-3">
						<input class="form-check-input" type="checkbox" role="switch" id="top-earners-privacy-mode" <?= !empty($top_earners_admin_cfg['privacy_mode']) ? 'checked' : '' ?>>
						<label class="form-check-label text-dark" for="top-earners-privacy-mode"><?= __('admin.top_earners_privacy_mode') ?></label>
					</div>
					<div class="form-check form-switch">
						<input class="form-check-input" type="checkbox" role="switch" id="top-earners-use-demo-rows" <?= !empty($top_earners_admin_cfg['demo_rows']) ? 'checked' : '' ?>>
						<label class="form-check-label text-dark" for="top-earners-use-demo-rows"><?= __('admin.block_top_earners_demo_rows_switch') ?></label>
					</div>
					<p class="text-muted small mt-2 mb-0"><?= __('admin.block_top_earners_demo_rows_help') ?></p>
				</div>
				<div id="login-page-block-settings-video" class="d-none">
					<p class="aff-fi-lead small text-secondary mb-3"><?= htmlspecialchars(__('admin.block_video_modal_lead')) ?></p>
					<div class="d-flex flex-wrap align-items-center justify-content-between gap-2 mb-2">
						<div class="aff-fi-section-label mb-0"><?= htmlspecialchars(__('admin.block_video_section_videos')) ?></div>
						<button type="button" class="btn btn-sm btn-outline-primary flex-shrink-0" id="video-block-load-demo" title="<?= htmlspecialchars(__('admin.block_video_load_demo_hint')) ?>">
							<i class="bi bi-play-circle me-1"></i><?= __('admin.block_video_load_demo') ?>
						</button>
					</div>
					<div id="video-repeater-list" class="mb-2"></div>
					<button type="button" class="btn btn-sm btn-light border w-100 mb-3" id="video-repeater-add-btn"><i class="fas fa-plus me-1"></i><?= htmlspecialchars(__('admin.block_video_add_btn')) ?></button>
					<div class="row g-2 mb-3">
						<div class="col-sm-6">
							<label for="block-video-columns" class="form-label fw-semibold text-dark"><?= __('admin.block_video_columns') ?></label>
							<select class="form-select" id="block-video-columns">
								<option value="1" <?= $video_cols === 1 ? 'selected' : '' ?>><?= __('admin.block_video_columns_1') ?></option>
								<option value="2" <?= $video_cols === 2 ? 'selected' : '' ?>><?= __('admin.block_video_columns_2') ?></option>
								<option value="3" <?= $video_cols === 3 ? 'selected' : '' ?>><?= __('admin.block_video_columns_3') ?></option>
							</select>
						</div>
						<div class="col-sm-6">
							<label for="block-video-max-width" class="form-label fw-semibold text-dark"><?= __('admin.block_video_display_size') ?></label>
							<select class="form-select" id="block-video-max-width">
								<option value="500" <?= $video_max_w === 500 ? 'selected' : '' ?>><?= __('admin.block_video_size_small') ?></option>
								<option value="800" <?= $video_max_w === 800 ? 'selected' : '' ?>><?= __('admin.block_video_size_medium') ?></option>
								<option value="1100" <?= $video_max_w === 1100 ? 'selected' : '' ?>><?= __('admin.block_video_size_large') ?></option>
							</select>
						</div>
					</div>
					<div class="form-check form-switch">
						<input class="form-check-input" type="checkbox" role="switch" id="block-video-autoplay" <?= !empty($video_admin_cfg['autoplay']) ? 'checked' : '' ?>>
						<label class="form-check-label text-dark" for="block-video-autoplay"><?= __('admin.block_video_autoplay') ?></label>
					</div>
					<div class="form-check form-switch mt-3">
						<input class="form-check-input" type="checkbox" role="switch" id="block-video-use-demo-content" <?= !empty($video_admin_cfg['use_demo_content']) ? 'checked' : '' ?>>
						<label class="form-check-label text-dark" for="block-video-use-demo-content"><?= __('admin.login_page_block_sample_content_switch') ?></label>
					</div>
					<p class="text-muted small mt-2 mb-0"><?= __('admin.login_page_block_sample_content_help') ?></p>
					<script type="application/json" id="aff-video-initial"><?php
						echo json_encode([
							'items' => isset($video_admin_cfg['items']) ? $video_admin_cfg['items'] : [],
							'max' => $video_max,
							'columns' => $video_cols,
							'autoplay' => !empty($video_admin_cfg['autoplay']) ? 1 : 0,
							'max_width' => $video_max_w,
							'use_demo_content' => !empty($video_admin_cfg['use_demo_content']) ? 1 : 0,
						], $aff_json_demo_enc);
					?></script>
				</div>
				<div id="login-page-block-settings-live-pulse" class="d-none">
					<div class="mb-3">
						<label for="live-pulse-poll-interval" class="form-label fw-semibold text-dark"><?= __('admin.block_live_pulse_poll_interval') ?></label>
						<select class="form-select" id="live-pulse-poll-interval">
							<?php foreach ($pulse_interval_options as $sec): ?>
							<option value="<?= (int) $sec ?>" <?= $pulse_sec === (int) $sec ? 'selected' : '' ?>><?= (int) $sec ?> s</option>
							<?php endforeach; ?>
						</select>
					</div>
					<div class="mb-0">
						<label for="live-pulse-toast-position" class="form-label fw-semibold text-dark"><?= __('admin.block_live_pulse_toast_position') ?></label>
						<select class="form-select" id="live-pulse-toast-position">
							<option value="bottom-right" <?= $pulse_pos === 'bottom-right' ? 'selected' : '' ?>><?= __('admin.block_live_pulse_pos_bottom_right') ?></option>
							<option value="bottom-left" <?= $pulse_pos === 'bottom-left' ? 'selected' : '' ?>><?= __('admin.block_live_pulse_pos_bottom_left') ?></option>
							<option value="bottom-center" <?= $pulse_pos === 'bottom-center' ? 'selected' : '' ?>><?= __('admin.block_live_pulse_pos_bottom_center') ?></option>
						</select>
						<p class="text-muted small mt-2 mb-0"><?= __('admin.block_live_pulse_toast_position_help') ?></p>
					</div>
					<div class="form-check form-switch mt-3">
						<input class="form-check-input" type="checkbox" role="switch" id="live-pulse-use-demo-content" <?= !empty($pulse_admin_cfg['use_demo_content']) ? 'checked' : '' ?>>
						<label class="form-check-label text-dark" for="live-pulse-use-demo-content"><?= __('admin.login_page_block_sample_content_switch') ?></label>
					</div>
					<p class="text-muted small mt-2 mb-0"><?= __('admin.login_page_block_sample_content_help') ?></p>
					<script type="application/json" id="aff-live-pulse-initial"><?php
						echo json_encode([
							'poll_interval_sec' => $pulse_sec,
							'toast_position' => $pulse_pos,
							'use_demo_content' => !empty($pulse_admin_cfg['use_demo_content']) ? 1 : 0,
						], $aff_json_demo_enc);
					?></script>
				</div>
				<div id="login-page-block-settings-feature-icons" class="d-none">
					<p class="aff-fi-lead small text-secondary mb-3"><?= htmlspecialchars(__('admin.block_features_modal_lead')) ?></p>
					<script type="application/json" id="aff-feature-icons-demo-json"><?= json_encode($aff_block_settings_demo_features, $aff_json_demo_enc) ?></script>
					<div class="d-flex flex-wrap align-items-center justify-content-between gap-2 mb-2">
						<div class="aff-fi-section-label mb-0"><?= htmlspecialchars(__('admin.block_features_section_benefits')) ?></div>
						<button type="button" class="btn btn-sm btn-outline-primary flex-shrink-0" id="feature-icons-load-demo" title="<?= htmlspecialchars(__('admin.block_features_load_demo_hint')) ?>">
							<i class="fas fa-magic me-1"></i><?= htmlspecialchars(__('admin.block_features_load_demo')) ?>
						</button>
					</div>
					<div class="form-check form-switch mb-2">
						<input class="form-check-input" type="checkbox" role="switch" id="feature-icons-use-demo-content" <?= $features_use_demo_content ? 'checked' : '' ?>>
						<label class="form-check-label text-dark" for="feature-icons-use-demo-content"><?= __('admin.login_page_block_sample_content_switch') ?></label>
					</div>
					<p class="text-muted small mb-3"><?= __('admin.login_page_block_sample_content_help') ?></p>
					<div id="feature-icons-repeater-list" class="mb-2"></div>
					<button type="button" class="btn btn-sm btn-light border w-100 mb-3" id="feature-icons-add-btn"><i class="fas fa-plus me-1"></i><?= htmlspecialchars(__('admin.block_features_add_feature')) ?></button>
					<div class="accordion border-0" id="aff-fi-layout-accordion">
						<div class="accordion-item border-0 bg-transparent">
							<h2 class="accordion-header">
								<button class="accordion-button collapsed rounded" type="button" data-bs-toggle="collapse" data-bs-target="#aff-fi-layout-collapse"><?= htmlspecialchars(__('admin.block_features_layout_accordion_title')) ?></button>
							</h2>
							<div id="aff-fi-layout-collapse" class="accordion-collapse collapse" data-bs-parent="#aff-fi-layout-accordion">
								<div class="accordion-body px-0 pt-2">
									<div class="row g-2 mb-2">
										<div class="col-4">
											<label class="aff-fi-field-label" for="aff-fi-disp-cols-sm"><?= htmlspecialchars(__('admin.block_features_columns_sm')) ?></label>
											<select class="form-select form-select-sm" id="aff-fi-disp-cols-sm"><?php for ($n = 1; $n <= 4; $n++): ?><option value="<?= $n ?>" <?= (int) $features_display_opts['columns_sm'] === $n ? 'selected' : '' ?>><?= $n ?></option><?php endfor; ?></select>
										</div>
										<div class="col-4">
											<label class="aff-fi-field-label" for="aff-fi-disp-cols-md"><?= htmlspecialchars(__('admin.block_features_columns_md')) ?></label>
											<select class="form-select form-select-sm" id="aff-fi-disp-cols-md"><?php for ($n = 1; $n <= 4; $n++): ?><option value="<?= $n ?>" <?= (int) $features_display_opts['columns_md'] === $n ? 'selected' : '' ?>><?= $n ?></option><?php endfor; ?></select>
										</div>
										<div class="col-4">
											<label class="aff-fi-field-label" for="aff-fi-disp-cols-lg"><?= htmlspecialchars(__('admin.block_features_columns_lg')) ?></label>
											<select class="form-select form-select-sm" id="aff-fi-disp-cols-lg"><?php for ($n = 1; $n <= 4; $n++): ?><option value="<?= $n ?>" <?= (int) $features_display_opts['columns_lg'] === $n ? 'selected' : '' ?>><?= $n ?></option><?php endfor; ?></select>
										</div>
									</div>
									<div class="row g-2">
										<div class="col-sm-6">
											<label class="aff-fi-field-label" for="aff-fi-disp-variant"><?= htmlspecialchars(__('admin.block_features_variant_label')) ?></label>
											<select class="form-select form-select-sm" id="aff-fi-disp-variant">
												<option value="cards" <?= ($features_display_opts['variant'] ?? '') === 'cards' ? 'selected' : '' ?>><?= htmlspecialchars(__('admin.block_features_variant_cards')) ?></option>
												<option value="plain" <?= ($features_display_opts['variant'] ?? '') === 'plain' ? 'selected' : '' ?>><?= htmlspecialchars(__('admin.block_features_variant_plain')) ?></option>
											</select>
										</div>
										<div class="col-sm-6">
											<label class="aff-fi-field-label" for="aff-fi-disp-icon-style"><?= htmlspecialchars(__('admin.block_features_icon_style_label')) ?></label>
											<select class="form-select form-select-sm" id="aff-fi-disp-icon-style">
												<option value="circle" <?= ($features_display_opts['icon_style'] ?? '') === 'circle' ? 'selected' : '' ?>><?= htmlspecialchars(__('admin.block_features_icon_style_circle')) ?></option>
												<option value="soft" <?= ($features_display_opts['icon_style'] ?? '') === 'soft' ? 'selected' : '' ?>><?= htmlspecialchars(__('admin.block_features_icon_style_soft')) ?></option>
												<option value="none" <?= ($features_display_opts['icon_style'] ?? '') === 'none' ? 'selected' : '' ?>><?= htmlspecialchars(__('admin.block_features_icon_style_none')) ?></option>
											</select>
										</div>
										<div class="col-12">
											<div class="form-check form-switch">
												<input class="form-check-input" type="checkbox" role="switch" id="aff-fi-disp-show-desc" <?= !empty($features_display_opts['show_description']) ? 'checked' : '' ?>>
												<label class="form-check-label small" for="aff-fi-disp-show-desc"><?= htmlspecialchars(__('admin.block_features_show_description')) ?></label>
											</div>
										</div>
									</div>
								</div>
							</div>
						</div>
					</div>
					<script type="application/json" id="aff-feature-icons-initial"><?php
						echo json_encode([
							'items' => $features_admin_slots,
							'max' => $features_max,
							'display' => $features_display_opts,
							'use_demo_content' => $features_use_demo_content ? 1 : 0,
						], $aff_json_demo_enc);
					?></script>
				</div>
				<div id="login-page-block-settings-faq" class="d-none">
					<p class="aff-fi-lead small text-secondary mb-3"><?= htmlspecialchars(__('admin.block_faq_modal_lead')) ?></p>
					<script type="application/json" id="aff-faq-demo-json"><?= json_encode($aff_block_settings_demo_faq, $aff_json_demo_enc) ?></script>
					<div class="d-flex flex-wrap align-items-center justify-content-between gap-2 mb-2">
						<div class="aff-fi-section-label mb-0"><?= htmlspecialchars(__('admin.block_faq_section_items')) ?></div>
						<button type="button" class="btn btn-sm btn-outline-primary flex-shrink-0" id="faq-repeater-load-demo" title="<?= htmlspecialchars(__('admin.block_faq_load_demo_hint')) ?>">
							<i class="fas fa-magic me-1"></i><?= htmlspecialchars(__('admin.block_faq_load_demo')) ?>
						</button>
					</div>
					<div class="form-check form-switch mb-2">
						<input class="form-check-input" type="checkbox" role="switch" id="aff-faq-use-demo-content" <?= !empty($faq_admin_cfg['use_demo_content']) ? 'checked' : '' ?>>
						<label class="form-check-label text-dark" for="aff-faq-use-demo-content"><?= __('admin.login_page_block_sample_content_switch') ?></label>
					</div>
					<p class="text-muted small mb-3"><?= __('admin.login_page_block_sample_content_help') ?></p>
					<div id="faq-repeater-list" class="mb-2"></div>
					<button type="button" class="btn btn-sm btn-light border w-100 mb-3" id="faq-repeater-add-btn"><i class="fas fa-plus me-1"></i><?= htmlspecialchars(__('admin.block_faq_add_item')) ?></button>
					<div class="aff-faq-switch-row d-flex align-items-center justify-content-between gap-3 rounded-3 px-3 py-2">
						<label class="small text-body-secondary mb-0 flex-grow-1 pe-2" for="aff-faq-first-open"><?= htmlspecialchars(__('admin.block_faq_first_open_label')) ?></label>
						<div class="form-check form-switch aff-faq-switch-only flex-shrink-0">
							<input class="form-check-input" type="checkbox" role="switch" id="aff-faq-first-open" <?= !empty($faq_admin_cfg['first_item_open']) ? 'checked' : '' ?> aria-label="<?= htmlspecialchars(__('admin.block_faq_first_open_label')) ?>">
						</div>
					</div>
					<script type="application/json" id="aff-faq-initial"><?php
						echo json_encode([
							'items' => isset($faq_admin_cfg['items']) ? $faq_admin_cfg['items'] : [],
							'max' => $faq_max,
							'first_item_open' => !empty($faq_admin_cfg['first_item_open']),
							'use_demo_content' => !empty($faq_admin_cfg['use_demo_content']) ? 1 : 0,
						], $aff_json_demo_enc);
					?></script>
				</div>
			</div>
			<div class="modal-footer border-top-0 pt-0">
				<button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal"><?= __('admin.close') ?></button>
				<button type="button" class="btn btn-primary d-none" id="login-page-block-settings-save-btn"><?= __('admin.save_block_settings') ?></button>
			</div>
		</div>
	</div>
</div>
<?php $this->load->view('admincontrol/affiliate_theme/login_page_blocks_panel_scripts', [
	'aff_feature_icon_picker_json' => $aff_feature_icon_picker_json,
	'is_demo_mode' => $is_demo,
]); ?>
