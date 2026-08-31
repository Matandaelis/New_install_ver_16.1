<?php
$m = $method;
$base = base_url();

$colorMap  = ['s2s' => 'warning', 's2s_direct' => 'info', 'postback' => 'secondary', 'conversion_api' => 'dark'];
$iconMap   = ['s2s' => 'bi-hdd-rack', 's2s_direct' => 'bi-phone', 'postback' => 'bi-arrow-left-right', 'conversion_api' => 'bi-braces'];
$titleMap  = ['s2s' => __('admin.integration_method_s2s'), 's2s_direct' => __('admin.integration_method_mobile'), 'postback' => __('admin.integration_method_postback'), 'conversion_api' => __('admin.integration_method_conv_api')];

$color = isset($colorMap[$m]) ? $colorMap[$m] : 'primary';
$icon  = isset($iconMap[$m]) ? $iconMap[$m] : 'bi-code-slash';
$title = isset($titleMap[$m]) ? $titleMap[$m] : 'Integration';

$api_key    = !empty($tool['api_key']) ? $tool['api_key'] : '';
$campaign_id = $tool['id'];
$s2s_url    = $base . 'integration/s2sConvert';
$click_url  = $base . 'integration/s2sRegisterClick';
$edit_url   = $base . 'integration/integration_tools_form/' . $tool['type'] . '/' . $tool['id'] . '#integration-setup';
?>
<div class="modal-dialog modal-lg modal-dialog-centered modal-dialog-scrollable">
	<div class="modal-content">
		<div class="modal-header bg-<?= $color ?> <?= in_array($color, ['warning','light']) ? 'text-dark' : 'text-white' ?>">
			<h5 class="modal-title"><i class="bi <?= $icon ?> me-2"></i><?= htmlspecialchars($tool['name']) ?></h5>
			<button type="button" class="btn-close <?= in_array($color, ['warning','light']) ? '' : 'btn-close-white' ?>" data-bs-dismiss="modal" aria-label="Close"></button>
		</div>
		<div class="modal-body">

			<div class="d-flex align-items-center gap-2 mb-3">
				<span class="badge bg-<?= $color ?> <?= $color === 'warning' ? 'text-dark' : '' ?>"><?= $title ?></span>
				<span class="text-muted small"><?= __('admin.campaign_id') ?>: <strong><?= $campaign_id ?></strong></span>
			</div>

			<?php if ($m === 's2s' || $m === 's2s_direct'): ?>

				<div class="p-3 bg-light rounded mb-3">
					<small class="text-muted fw-bold d-block mb-2"><i class="bi bi-diagram-3 me-1"></i><?= __('admin.flow_title') ?></small>
					<div class="d-flex flex-wrap align-items-center gap-2">
					<?php if ($m === 's2s_direct'): ?>
						<span class="badge bg-info rounded-pill px-3 py-2"><?= __('admin.flow_mobile_step1') ?></span><i class="bi bi-arrow-right text-muted"></i>
						<span class="badge bg-info rounded-pill px-3 py-2"><?= __('admin.flow_mobile_step2') ?></span><i class="bi bi-arrow-right text-muted"></i>
						<span class="badge bg-info rounded-pill px-3 py-2"><?= __('admin.flow_mobile_step3') ?></span><i class="bi bi-arrow-right text-muted"></i>
						<span class="badge bg-success rounded-pill px-3 py-2"><?= __('admin.flow_mobile_step4') ?></span>
					<?php else: ?>
						<span class="badge bg-warning text-dark rounded-pill px-3 py-2"><?= __('admin.flow_s2s_step1') ?></span><i class="bi bi-arrow-right text-muted"></i>
						<span class="badge bg-warning text-dark rounded-pill px-3 py-2"><?= __('admin.flow_s2s_step2') ?></span><i class="bi bi-arrow-right text-muted"></i>
						<span class="badge bg-warning text-dark rounded-pill px-3 py-2"><?= __('admin.flow_s2s_step3') ?></span><i class="bi bi-arrow-right text-muted"></i>
						<span class="badge bg-warning text-dark rounded-pill px-3 py-2"><?= __('admin.flow_s2s_step4') ?></span><i class="bi bi-arrow-right text-muted"></i>
						<span class="badge bg-success rounded-pill px-3 py-2"><?= __('admin.flow_s2s_step5') ?></span>
					<?php endif; ?>
					</div>
				</div>

				<?php if (empty($api_key)): ?>
					<div class="alert alert-warning py-2">
						<i class="bi bi-exclamation-triangle me-1"></i> <?= __('admin.s2s_save_first') ?>
					</div>
				<?php else: ?>

					<div class="mb-3">
						<label class="form-label fw-semibold small text-uppercase text-muted"><?= __('admin.s2s_api_key') ?></label>
						<div class="input-group">
							<input type="text" class="form-control font-monospace bg-light" value="<?= htmlspecialchars($api_key) ?>" id="modal-api-key" readonly>
							<button class="btn btn-outline-secondary btn-copy-modal" data-target="modal-api-key" type="button"><i class="bi bi-clipboard"></i></button>
						</div>
					</div>

					<div class="mb-3">
						<label class="form-label fw-semibold small text-uppercase text-muted"><?= __('admin.s2s_endpoint') ?></label>
						<div class="input-group">
							<input type="text" class="form-control font-monospace bg-light" value="<?= $s2s_url ?>" id="modal-s2s-url" readonly>
							<button class="btn btn-outline-secondary btn-copy-modal" data-target="modal-s2s-url" type="button"><i class="bi bi-clipboard"></i></button>
						</div>
					</div>

					<?php if ($m === 's2s_direct'): ?>
					<div class="mb-3">
						<label class="form-label fw-semibold small text-uppercase text-muted"><?= __('admin.s2s_click_endpoint') ?></label>
						<div class="input-group">
							<input type="text" class="form-control font-monospace bg-light" value="<?= $click_url ?>" id="modal-click-url" readonly>
							<button class="btn btn-outline-secondary btn-copy-modal" data-target="modal-click-url" type="button"><i class="bi bi-clipboard"></i></button>
						</div>
					</div>
					<?php endif; ?>

					<div class="card bg-light border-0 mt-3">
						<div class="card-body small">
							<h6 class="fw-bold mb-2"><i class="bi bi-send me-1"></i> <?= __('admin.s2s_quick_example') ?></h6>
							<?php if ($m === 's2s_direct'): ?>
							<code class="d-block p-2 bg-white border rounded mb-0" style="white-space:pre-wrap;word-break:break-all;">POST <?= $s2s_url ?>

{
  "api_key": "<?= htmlspecialchars($api_key) ?>",
  "affiliate_id": 123,
  "campaign_id": <?= $campaign_id ?>,
  "amount": 99.99,
  "order_id": "ORD-001"
}</code>
							<?php else: ?>
							<code class="d-block p-2 bg-white border rounded mb-0" style="white-space:pre-wrap;word-break:break-all;">POST <?= $s2s_url ?>

{
  "api_key": "<?= htmlspecialchars($api_key) ?>",
  "click_token": "&lt;token_from_click&gt;",
  "amount": 99.99,
  "order_id": "ORD-001"
}</code>
							<?php endif; ?>
						</div>
					</div>

				<?php endif; ?>

			<?php elseif ($m === 'postback'): ?>

				<div class="p-3 bg-light rounded mb-3">
					<small class="text-muted fw-bold d-block mb-2"><i class="bi bi-diagram-3 me-1"></i><?= __('admin.flow_title') ?></small>
					<div class="d-flex flex-wrap align-items-center gap-2">
						<span class="badge bg-secondary rounded-pill px-3 py-2"><?= __('admin.flow_postback_step1') ?></span><i class="bi bi-arrow-right text-muted"></i>
						<span class="badge bg-secondary rounded-pill px-3 py-2"><?= __('admin.flow_postback_step2') ?></span><i class="bi bi-arrow-right text-muted"></i>
						<span class="badge bg-secondary rounded-pill px-3 py-2"><?= __('admin.flow_postback_step3') ?></span><i class="bi bi-arrow-right text-muted"></i>
						<span class="badge bg-success rounded-pill px-3 py-2"><?= __('admin.flow_postback_step4') ?></span>
					</div>
				</div>

				<div class="alert alert-info border-0 py-2 mb-3 small">
					<strong><i class="bi bi-info-circle me-1"></i> <?= __('admin.postback_how_it_works') ?></strong><br>
					<?= __('admin.postback_how_it_works_desc') ?>
				</div>
				<div class="alert alert-light border-start border-secondary border-3 py-2 mb-3 small">
					<i class="bi bi-lightbulb text-warning me-1"></i> <?= __('admin.postback_use_case') ?>
				</div>

				<?php
					$postback = json_decode($tool['marketpostback'], true);
					$pb_url = isset($postback['url']) ? $postback['url'] : '';
					$pb_status = isset($postback['status']) ? $postback['status'] : 'disabled';
				?>
				<div class="mb-3">
					<label class="form-label fw-semibold small text-uppercase text-muted"><?= __('admin.postback_status_label') ?></label>
					<div>
						<?php if ($pb_status === 'custom' && !empty($pb_url)): ?>
							<span class="badge bg-success"><i class="bi bi-check-circle me-1"></i><?= __('admin.active') ?></span>
						<?php else: ?>
							<span class="badge bg-secondary"><i class="bi bi-x-circle me-1"></i><?= __('admin.not_configured') ?></span>
						<?php endif; ?>
					</div>
				</div>
				<?php if (!empty($pb_url)): ?>
				<div class="mb-3">
					<label class="form-label fw-semibold small text-uppercase text-muted"><?= __('admin.postback_url_label') ?></label>
					<div class="input-group">
						<input type="text" class="form-control font-monospace bg-light small" value="<?= htmlspecialchars($pb_url) ?>" id="modal-pb-url" readonly>
						<button class="btn btn-outline-secondary btn-copy-modal" data-target="modal-pb-url" type="button"><i class="bi bi-clipboard"></i></button>
					</div>
				</div>
				<?php else: ?>
				<div class="alert alert-info py-2 small">
					<i class="bi bi-info-circle me-1"></i> <?= __('admin.postback_not_set') ?>
				</div>
				<?php endif; ?>

			<?php elseif ($m === 'conversion_api'): ?>

				<div class="p-3 bg-light rounded mb-3">
					<small class="text-muted fw-bold d-block mb-2"><i class="bi bi-diagram-3 me-1"></i><?= __('admin.flow_title') ?></small>
					<div class="d-flex flex-wrap align-items-center gap-2">
						<span class="badge bg-dark rounded-pill px-3 py-2"><?= __('admin.flow_convapi_step1') ?></span><i class="bi bi-arrow-right text-muted"></i>
						<span class="badge bg-dark rounded-pill px-3 py-2"><?= __('admin.flow_convapi_step2') ?></span><i class="bi bi-arrow-right text-muted"></i>
						<span class="badge bg-dark rounded-pill px-3 py-2"><?= __('admin.flow_convapi_step3') ?></span><i class="bi bi-arrow-right text-muted"></i>
						<span class="badge bg-success rounded-pill px-3 py-2"><?= __('admin.flow_convapi_step4') ?></span>
					</div>
				</div>

				<div class="alert alert-info border-0 py-2 mb-3 small">
					<strong><i class="bi bi-info-circle me-1"></i> <?= __('admin.conv_api_how_it_works') ?></strong><br>
					<?= __('admin.conv_api_how_it_works_desc') ?>
				</div>
				<div class="alert alert-light border-start border-dark border-3 py-2 mb-3 small">
					<i class="bi bi-lightbulb text-warning me-1"></i> <?= __('admin.conv_api_use_case') ?>
				</div>

				<h6 class="fw-bold small mb-2"><i class="bi bi-signpost-2 me-2"></i><?= __('admin.conv_api_available_endpoints') ?></h6>
				<div class="row g-2 mb-3">
					<div class="col-md-6">
						<div class="card border h-100">
							<div class="card-body py-2">
								<div class="d-flex align-items-center mb-1">
									<span class="badge bg-warning text-dark me-2">POST</span>
									<small class="fw-bold"><?= __('admin.conv_api_click_endpoint') ?></small>
								</div>
								<code class="small d-block text-break"><?= $click_url ?></code>
							</div>
						</div>
					</div>
					<div class="col-md-6">
						<div class="card border h-100">
							<div class="card-body py-2">
								<div class="d-flex align-items-center mb-1">
									<span class="badge bg-success me-2">POST</span>
									<small class="fw-bold"><?= __('admin.conv_api_sale_endpoint') ?></small>
								</div>
								<code class="small d-block text-break"><?= $s2s_url ?></code>
							</div>
						</div>
					</div>
				</div>
				<div class="alert alert-light border py-2 small">
					<i class="bi bi-book me-1"></i> <?= __('admin.conv_api_modal_hint') ?>
				</div>

			<?php endif; ?>

		</div>
		<div class="modal-footer bg-light py-2">
			<a href="<?= $edit_url ?>" class="btn btn-sm btn-outline-<?= $color ?>"><i class="bi bi-pencil me-1"></i><?= __('admin.setup_modal_full_settings') ?></a>
			<button type="button" class="btn btn-sm btn-secondary" data-bs-dismiss="modal"><?= __('admin.close') ?></button>
		</div>
	</div>
</div>

<script>
$(function() {
	$('.btn-copy-modal').on('click', function() {
		var targetId = $(this).data('target');
		var input = document.getElementById(targetId);
		input.select();
		input.setSelectionRange(0, 99999);
		var $btn = $(this);
		navigator.clipboard.writeText(input.value).then(function() {
			$btn.html('<i class="bi bi-check-lg text-success"></i>');
			setTimeout(function() { $btn.html('<i class="bi bi-clipboard"></i>'); }, 1500);
		});
	});
});
</script>
