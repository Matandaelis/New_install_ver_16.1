<?php
if (empty($request['id'])) {
	echo '';
	return;
}
$wr_id = (int) $request['id'];
$csrf = isset($wallet_mass_prepare_csrf) ? (string) $wallet_mass_prepare_csrf : '';
$prep = !empty($wallet_mass_prepare_available) && $csrf !== '';
$go = !empty($wallet_mass_go_export_only);
$mass_url = base_url('admincontrol/mass_payout?focus_wr=' . $wr_id);
if (!$prep && !$go) {
	echo '';
	return;
}
?>
<div class="row mt-4">
	<div class="col-12">
		<?php if ($go) { ?>
		<div class="card border-success shadow-sm wallet-mass-strip bg-success bg-opacity-10">
			<div class="card-body p-3 p-md-4">
				<div class="d-flex flex-column flex-md-row align-items-md-center justify-content-between gap-3">
					<div>
						<p class="text-uppercase small fw-bold text-success mb-1 wallet-mass-strip-kicker"><?= __('admin.wallet_mass_strip_step_label') ?></p>
						<h6 class="fw-bold text-dark mb-2 fs-5">
							<i class="fas fa-check-circle me-2 text-success"></i><?= __('admin.wallet_mass_strip_ready_title') ?>
						</h6>
						<p class="mb-0 text-dark"><?= __('admin.wallet_mass_strip_ready_body') ?></p>
					</div>
					<a href="<?= $mass_url ?>" class="btn btn-success btn-lg px-4 py-3 shadow-sm text-center text-wrap" style="max-width: 14rem;">
						<span class="d-block fw-bold"><?= __('admin.wallet_mass_strip_open_export') ?></span>
						<span class="d-block small fw-normal opacity-90 mt-1"><?= __('admin.wallet_mass_strip_ready_button_sub') ?></span>
					</a>
				</div>
			</div>
		</div>
		<?php } elseif ($prep) { ?>
		<div class="card border-success shadow-sm wallet-mass-strip bg-success bg-opacity-10">
			<div class="card-body p-3 p-md-4">
				<div class="d-flex flex-column flex-md-row align-items-stretch align-items-md-center justify-content-between gap-3">
					<div class="flex-grow-1 pe-md-3">
						<p class="text-uppercase small fw-bold text-success mb-1 wallet-mass-strip-kicker"><?= __('admin.wallet_mass_strip_step_label') ?></p>
						<h6 class="fw-bold text-dark mb-2 fs-5">
							<i class="fas fa-mouse-pointer me-2 text-success"></i><?= __('admin.wallet_mass_strip_title_short') ?>
						</h6>
						<p class="mb-0 text-dark"><?= __('admin.wallet_mass_strip_one_line') ?></p>
					</div>
					<form method="post" action="<?= base_url('admincontrol/wallet_request_ready_mass_payout/' . $wr_id) ?>" class="flex-shrink-0 d-flex align-items-center">
						<input type="hidden" name="wallet_mass_prepare_csrf" value="<?= htmlspecialchars($csrf) ?>">
						<input type="hidden" name="target_status" value="12">
						<button type="submit" class="btn btn-success btn-lg px-4 py-3 shadow-sm text-wrap" style="max-width: 14rem;">
							<span class="d-block fw-bold"><?= __('admin.wallet_mass_strip_primary_submit') ?></span>
							<span class="d-block small fw-normal opacity-90 mt-1"><?= __('admin.wallet_mass_strip_button_sub') ?></span>
						</button>
					</form>
				</div>
			</div>
		</div>
		<?php } ?>
	</div>
</div>
