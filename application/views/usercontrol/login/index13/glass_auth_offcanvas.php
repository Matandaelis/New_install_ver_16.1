<?php
defined('BASEPATH') OR exit('No direct script access allowed');

$initial = (isset($glass_auth_panel) && $glass_auth_panel === 'register') ? 'register' : 'login';
$reg_status = isset($store['registration_status']) ? (int) $store['registration_status'] : 0;
$vendor_active = (
	(isset($vendor_marketstatus['marketvendorstatus']) && (int) $vendor_marketstatus['marketvendorstatus'] === 1)
	|| !empty($vendor_storestatus['storestatus'])
);
$show_register_nav = ($reg_status === 1 || $reg_status === 3 || ($reg_status === 2 && $vendor_active));
if ($initial === 'register' && !$show_register_nav) {
	$initial = 'login';
}
$include_forgot = !isset($glass_drawer_include_forgot) || $glass_drawer_include_forgot !== false;

$base = rtrim((string) base_url(), '/');
?>
<div class="offcanvas offcanvas-end glass-auth-offcanvas"
     tabindex="-1"
     id="glassAuthOffcanvas"
     aria-labelledby="glassAuthOffcanvasLabel"
     data-glass-base-url="<?= htmlspecialchars($base, ENT_QUOTES, 'UTF-8') ?>"
     data-glass-initial-view="<?= htmlspecialchars($initial, ENT_QUOTES, 'UTF-8') ?>"
     data-glass-heading-login="<?= htmlspecialchars(__('front.idx1_drawer_heading_login'), ENT_QUOTES, 'UTF-8') ?>"
     data-glass-heading-register="<?= htmlspecialchars(__('front.create_account'), ENT_QUOTES, 'UTF-8') ?>"
     data-glass-heading-forgot="<?= htmlspecialchars(__('front.reset_your_password'), ENT_QUOTES, 'UTF-8') ?>"
     data-glass-sub-login="<?= htmlspecialchars(__('front.enter_your_credentials'), ENT_QUOTES, 'UTF-8') ?>"
     data-glass-sub-register="<?= htmlspecialchars(__('front.join_us_get_started'), ENT_QUOTES, 'UTF-8') ?>"
     data-glass-sub-forgot="<?= htmlspecialchars(__('front.will_send_reset_link'), ENT_QUOTES, 'UTF-8') ?>">
	<div class="offcanvas-header glass-auth-offcanvas-header border-0 flex-column align-items-stretch pb-0">
		<div class="d-flex align-items-start justify-content-between gap-2 w-100">
			<div class="pe-2">
				<?php if ($initial === 'register' && $show_register_nav): ?>
					<h5 class="offcanvas-title fw-bold mb-1" id="glassAuthOffcanvasLabel"><?= __('front.create_account') ?></h5>
					<p class="small mb-0 glass-auth-offcanvas-sub text-white-50" id="glassAuthOffcanvasSubtitle"><?= __('front.join_us_get_started') ?></p>
				<?php else: ?>
					<h5 class="offcanvas-title fw-bold mb-1" id="glassAuthOffcanvasLabel"><?= __('front.idx1_drawer_heading_login') ?></h5>
					<p class="small mb-0 glass-auth-offcanvas-sub text-white-50" id="glassAuthOffcanvasSubtitle"><?= __('front.enter_your_credentials') ?></p>
				<?php endif; ?>
			</div>
			<button type="button"
			        class="btn-close flex-shrink-0 mt-1"
			        data-bs-dismiss="offcanvas"
			        aria-label="<?= htmlspecialchars(__('front.close'), ENT_QUOTES, 'UTF-8') ?>"></button>
		</div>
	</div>
	<div class="offcanvas-body glass-auth-offcanvas-body pt-2">
		<div class="glass-auth-offcanvas-card">
			<ul class="nav nav-pills glass-nav-pills justify-content-center mb-3 glass-auth-mode-tabs" role="tablist">
				<li class="nav-item flex-fill" role="presentation">
					<button type="button"
					        class="nav-link w-100 <?= $initial === 'login' ? 'active' : '' ?>"
					        data-glass-auth-view="login"
					        role="tab"
					        aria-selected="<?= $initial === 'login' ? 'true' : 'false' ?>">
						<i class="bi bi-box-arrow-in-right me-1"></i><?= __('front.login') ?>
					</button>
				</li>
				<?php if ($show_register_nav): ?>
					<li class="nav-item flex-fill" role="presentation">
						<button type="button"
						        class="nav-link w-100 <?= $initial === 'register' ? 'active' : '' ?>"
						        data-glass-auth-view="register"
						        role="tab"
						        aria-selected="<?= $initial === 'register' ? 'true' : 'false' ?>">
							<i class="bi bi-person-plus me-1"></i><?= __('front.register') ?>
						</button>
					</li>
				<?php endif; ?>
			</ul>

			<div class="glass-auth-panels position-relative">
				<div class="glass-auth-panel glass-auth-panel--fade <?= $initial === 'login' ? 'is-active' : 'd-none' ?>"
				     data-panel="login"
				     role="tabpanel">
					<?php $this->load->view('usercontrol/login/components/login_form_component', [
						'login_theme_id' => 13,
						'login_idx1_drawer_mode' => $include_forgot,
					]); ?>
				</div>

				<?php if ($show_register_nav): ?>
					<div class="glass-auth-panel glass-auth-panel--fade <?= $initial === 'register' ? 'is-active' : 'd-none' ?>"
					     data-panel="register"
					     role="tabpanel">
						<?php $this->load->view('usercontrol/login/components/register_form_component', [
							'store' => $store,
							'vendor_storestatus' => $vendor_storestatus,
							'vendor_marketstatus' => $vendor_marketstatus,
							'register_fomm' => isset($register_fomm) ? $register_fomm : '',
							'register_component_variant' => 'index13',
							'register_idx1_drawer_mode' => true,
						]); ?>
					</div>
				<?php endif; ?>

				<?php if ($include_forgot): ?>
					<div class="glass-auth-panel glass-auth-panel--fade d-none" data-panel="forgot" role="tabpanel">
						<?php include __DIR__ . '/glass_drawer_forgot_form.php'; ?>
					</div>
				<?php endif; ?>
			</div>

			<div class="text-center mt-4 pt-2">
				<small class="text-white-50">
					<i class="bi bi-shield-check me-1"></i><?= __('front.your_information_secure') ?>
				</small>
			</div>
		</div>
	</div>
</div>
