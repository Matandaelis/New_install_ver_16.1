<?php
defined('BASEPATH') OR exit('No direct script access allowed');

$initial = (isset($idx9_auth_panel) && $idx9_auth_panel === 'register') ? 'register' : 'login';
$reg_status = isset($store['registration_status']) ? (int) $store['registration_status'] : 0;
$vendor_active = (
	(isset($vendor_marketstatus['marketvendorstatus']) && (int) $vendor_marketstatus['marketvendorstatus'] === 1)
	|| !empty($vendor_storestatus['storestatus'])
);
$show_register_nav = ($reg_status === 1 || $reg_status === 3 || ($reg_status === 2 && $vendor_active));
if ($initial === 'register' && !$show_register_nav) {
	$initial = 'login';
}
$include_forgot = !isset($idx9_drawer_include_forgot) || $idx9_drawer_include_forgot !== false;

$base = rtrim((string) base_url(), '/');
?>
<div class="offcanvas offcanvas-end idx9-auth-offcanvas"
     tabindex="-1"
     id="idx9AuthOffcanvas"
     aria-labelledby="idx9AuthOffcanvasLabel"
     data-idx9-base-url="<?= htmlspecialchars($base, ENT_QUOTES, 'UTF-8') ?>"
     data-idx9-initial-view="<?= htmlspecialchars($initial, ENT_QUOTES, 'UTF-8') ?>"
     data-idx9-heading-login="<?= htmlspecialchars(__('front.idx1_drawer_heading_login'), ENT_QUOTES, 'UTF-8') ?>"
     data-idx9-heading-register="<?= htmlspecialchars(__('front.create_account'), ENT_QUOTES, 'UTF-8') ?>"
     data-idx9-heading-forgot="<?= htmlspecialchars(__('front.reset_your_password'), ENT_QUOTES, 'UTF-8') ?>"
     data-idx9-sub-login="<?= htmlspecialchars(__('front.enter_your_credentials'), ENT_QUOTES, 'UTF-8') ?>"
     data-idx9-sub-register="<?= htmlspecialchars(__('front.join_us_get_started'), ENT_QUOTES, 'UTF-8') ?>"
     data-idx9-sub-forgot="<?= htmlspecialchars(__('front.will_send_reset_link'), ENT_QUOTES, 'UTF-8') ?>">
	<div class="offcanvas-header idx9-auth-offcanvas-header border-0 flex-column align-items-stretch pb-0">
		<div class="d-flex align-items-start justify-content-between gap-2 w-100">
			<div class="pe-2">
				<?php if ($initial === 'register' && $show_register_nav): ?>
					<h5 class="offcanvas-title fw-bold mb-1" id="idx9AuthOffcanvasLabel"><?= __('front.create_account') ?></h5>
					<p class="text-muted small mb-0 idx9-auth-offcanvas-sub" id="idx9AuthOffcanvasSubtitle"><?= __('front.join_us_get_started') ?></p>
				<?php else: ?>
					<h5 class="offcanvas-title fw-bold mb-1" id="idx9AuthOffcanvasLabel"><?= __('front.idx1_drawer_heading_login') ?></h5>
					<p class="text-muted small mb-0 idx9-auth-offcanvas-sub" id="idx9AuthOffcanvasSubtitle"><?= __('front.enter_your_credentials') ?></p>
				<?php endif; ?>
			</div>
			<button type="button"
			        class="btn-close text-reset flex-shrink-0 mt-1"
			        data-bs-dismiss="offcanvas"
			        aria-label="<?= htmlspecialchars(__('front.close'), ENT_QUOTES, 'UTF-8') ?>"></button>
		</div>
	</div>
	<div class="offcanvas-body idx9-auth-offcanvas-body pt-2">
		<div class="idx9-card idx9-auth-offcanvas-card border-0 shadow-none">
			<ul class="nav nav-pills idx9-nav-pills justify-content-center mb-3 idx9-auth-mode-tabs" role="tablist">
				<li class="nav-item flex-fill" role="presentation">
					<button type="button"
					        class="nav-link w-100 <?= $initial === 'login' ? 'active' : '' ?>"
					        data-idx9-auth-view="login"
					        role="tab"
					        aria-selected="<?= $initial === 'login' ? 'true' : 'false' ?>">
						<i class="bi bi-box-arrow-in-right me-1"></i><?= __('front.login') ?>
					</button>
				</li>
				<?php if ($show_register_nav): ?>
					<li class="nav-item flex-fill" role="presentation">
						<button type="button"
						        class="nav-link w-100 <?= $initial === 'register' ? 'active' : '' ?>"
						        data-idx9-auth-view="register"
						        role="tab"
						        aria-selected="<?= $initial === 'register' ? 'true' : 'false' ?>">
							<i class="bi bi-person-plus me-1"></i><?= __('front.register') ?>
						</button>
					</li>
				<?php endif; ?>
			</ul>

			<div class="idx9-auth-panels position-relative">
				<div class="idx9-auth-panel idx9-auth-panel--fade <?= $initial === 'login' ? 'is-active' : 'd-none' ?>"
				     data-panel="login"
				     role="tabpanel">
					<?php $this->load->view('usercontrol/login/components/login_form_component', [
						'login_theme_id' => 9,
						'login_idx1_drawer_mode' => $include_forgot,
					]); ?>
				</div>

				<?php if ($show_register_nav): ?>
					<div class="idx9-auth-panel idx9-auth-panel--fade <?= $initial === 'register' ? 'is-active' : 'd-none' ?>"
					     data-panel="register"
					     role="tabpanel">
						<?php $this->load->view('usercontrol/login/components/register_form_component', [
							'store' => $store,
							'vendor_storestatus' => $vendor_storestatus,
							'vendor_marketstatus' => $vendor_marketstatus,
							'register_fomm' => isset($register_fomm) ? $register_fomm : '',
							'register_component_variant' => 'index9',
							'register_idx1_drawer_mode' => true,
						]); ?>
					</div>
				<?php endif; ?>

				<?php if ($include_forgot): ?>
					<div class="idx9-auth-panel idx9-auth-panel--fade d-none" data-panel="forgot" role="tabpanel">
						<?php include __DIR__ . '/idx9_drawer_forgot_form.php'; ?>
					</div>
				<?php endif; ?>
			</div>

			<div class="idx9-card-footer text-center mt-3 pt-2">
				<small class="text-muted">
					<i class="bi bi-shield-check me-1"></i><?= __('front.your_information_secure') ?>
				</small>
			</div>
		</div>
	</div>
</div>
