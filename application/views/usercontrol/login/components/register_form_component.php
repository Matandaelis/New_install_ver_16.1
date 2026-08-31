<?php
defined('BASEPATH') OR exit('No direct script access allowed');
/**
 * Portable registration block — injects dynamic HTML from AuthController ($register_fomm).
 *
 * Required (pass explicitly when loading this view; parent variables are not inherited in CI3):
 *   - store                  array  must include registration_status
 *   - vendor_storestatus     array  must include storestatus
 *   - vendor_marketstatus    array  must include marketvendorstatus
 *   - register_fomm          string HTML from auth/user/templates/register_form (+ inline JS)
 *
 * Optional:
 *   - register_component_variant  string  see switch below (default: index1)
 *   - (Blocks render via parent theme: echo $hook_form_bottom after this view; Theme_blocks_handler.)
 *
 * Variants: index1 … index13 match affiliate login themes; minimal = raw $register_fomm only
 *   (use inside multiple_pages .mp-auth-form.register-form so .reg_form from the builder is unchanged).
 *
 * Business rule (aligned with login nav pills):
 *   Show form only if registration_status is 1 or 3, or (2 and vendor store/market active).
 *
 * AJAX: Inside register_form template — .reg_form → pagebuilder/register (FormData).
 */

$variant = isset($register_component_variant) ? $register_component_variant : 'index1';
$register_idx1_drawer_mode = !empty($register_idx1_drawer_mode);

$reg_status = isset($store['registration_status']) ? (int) $store['registration_status'] : 0;
$vendor_active = (
	isset($vendor_marketstatus['marketvendorstatus']) && (int) $vendor_marketstatus['marketvendorstatus'] === 1
) || !empty($vendor_storestatus['storestatus']);

$registration_allowed = ($reg_status === 1 || $reg_status === 3 || ($reg_status === 2 && $vendor_active));

if (!$registration_allowed) { ?>
	<div class="alert alert-warning mb-0" role="alert"><?= __('front.registration_unavailable') ?></div>
	<p class="text-center mt-3 mb-0">
		<?php if ($register_idx1_drawer_mode && $variant === 'index5'): ?>
			<a href="#" class="idx5-auth-switch-link" data-idx5-auth-view="login" role="button"><?= __('front.login') ?></a>
		<?php elseif ($register_idx1_drawer_mode && $variant === 'index9'): ?>
			<a href="#" class="idx9-auth-switch-link" data-idx9-auth-view="login" role="button"><?= __('front.login') ?></a>
		<?php elseif ($register_idx1_drawer_mode && $variant === 'index13'): ?>
			<a href="#" class="glass-auth-switch-link" data-glass-auth-view="login" role="button"><?= __('front.login') ?></a>
		<?php elseif ($register_idx1_drawer_mode): ?>
			<a href="#" class="idx1-auth-switch-link" data-idx1-auth-view="login" role="button"><?= __('front.login') ?></a>
		<?php else: ?>
			<a href="<?= base_url() ?>"><?= __('front.login') ?></a>
		<?php endif; ?>
	</p>
	<?php
	return;
}

$html = isset($register_fomm) ? $register_fomm : '';
if (trim((string) $html) === '') { ?>
	<div class="alert alert-danger mb-0" role="alert"><?= __('front.registration_unavailable') ?></div>
	<?php
	return;
}

if ($variant === 'minimal') {
	echo $html;
} else {
	switch ($variant) {
	case 'index2':
		?>
		<div class="idx2-info-banner mb-4 idx2-stagger-3">
			<div class="d-flex align-items-start">
				<i class="bi bi-info-circle-fill me-3 fs-5 flex-shrink-0"></i>
				<div>
					<h6 class="fw-bold mb-1"><?= __('front.getting_started') ?></h6>
					<p class="mb-0 small"><?= __('front.enter_your_information_to_setup_a_new_account') ?></p>
				</div>
			</div>
		</div>
		<div class="idx2-register-container idx2-stagger-4">
			<?= $html ?>
		</div>
		<?php
		break;

	case 'index3':
		?>
		<div class="idx3-info-banner mb-4 idx3-stagger-3">
			<div class="d-flex align-items-start">
				<i class="bi bi-info-circle-fill me-3 fs-5 flex-shrink-0"></i>
				<div>
					<h6 class="fw-bold mb-1"><?= __('front.getting_started') ?></h6>
					<p class="mb-0 small"><?= __('front.enter_your_information_to_setup_a_new_account') ?></p>
				</div>
			</div>
		</div>
		<div class="idx3-register-container idx3-stagger-4">
			<?= $html ?>
		</div>
		<?php
		break;

	case 'index4':
		?>
		<div class="idx4-info-banner d-flex align-items-start gap-2 mb-3 idx4-stagger-3">
			<i class="bi bi-info-circle-fill mt-1"></i>
			<div>
				<h6 class="mb-0"><?= __('front.register') ?></h6>
				<p class="mb-0"><?= __('front.enter_your_information_to_setup_a_new_account') ?></p>
			</div>
		</div>
		<div class="idx4-register-container idx4-stagger-4">
			<?= $html ?>
		</div>
		<?php
		break;

	case 'index5':
		?>
		<div class="idx5-register-container mt-3 idx5-stagger-3">
			<?= $html ?>
		</div>
		<?php
		break;

	case 'index6':
		?>
		<div class="idx6-stagger-4">
			<?= $html ?>
		</div>
		<?php
		break;

	case 'index7':
		?>
		<div class="idx7-stagger-3">
			<?= $html ?>
		</div>
		<?php
		break;

	case 'index8':
		?>
		<div class="idx8-stagger-3">
			<?= $html ?>
		</div>
		<?php
		break;

	case 'index9':
		?>
		<div class="idx9-stagger-3">
			<?= $html ?>
		</div>
		<?php
		break;

	case 'index10':
		?>
		<div class="idx10-stagger-3">
			<?= $html ?>
		</div>
		<?php
		break;

	case 'index11':
		?>
		<div class="idx11-stagger-3">
			<?= $html ?>
		</div>
		<?php
		break;

	case 'index12':
		?>
		<div class="idx12-info-alert mb-4 idx12-stagger-3">
			<div class="d-flex align-items-start">
				<i class="bi bi-info-circle-fill me-3 fs-5 flex-shrink-0"></i>
				<div>
					<h6><?= __('front.getting_started') ?></h6>
					<p><?= __('front.enter_your_information_to_setup_a_new_account') ?></p>
				</div>
			</div>
		</div>
		<div class="idx12-register-container idx12-stagger-4">
			<?= $html ?>
		</div>
		<?php
		break;

	case 'index13':
		?>
		<div class="glass-info-banner mb-4">
			<div class="d-flex align-items-start">
				<i class="bi bi-info-circle-fill me-3 fs-5 flex-shrink-0"></i>
				<div>
					<h6 class="fw-bold mb-1"><?= __('front.getting_started') ?></h6>
					<p class="mb-0 small opacity-85"><?= __('front.enter_your_information_to_setup_a_new_account') ?></p>
				</div>
			</div>
		</div>
		<div class="registration-form-container">
			<?= $html ?>
		</div>
		<?php
		break;

	case 'index1':
	default:
		?>
		<div class="idx1-info-banner mb-4 idx1-stagger-3">
			<div class="d-flex align-items-start">
				<i class="bi bi-info-circle-fill me-3 fs-5 flex-shrink-0"></i>
				<div>
					<h6 class="fw-bold mb-1"><?= __('front.getting_started') ?></h6>
					<p class="mb-0 small"><?= __('front.enter_your_information_to_setup_a_new_account') ?></p>
				</div>
			</div>
		</div>
		<div class="registration-form-container idx1-stagger-4">
			<?= $html ?>
		</div>
		<?php
		break;
	}
}
