<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>
<div class="glass-drawer-forgot-wrap">
	<div class="glass-info-banner mb-3">
		<div class="d-flex align-items-start">
			<i class="bi bi-info-circle-fill me-3 fs-5 flex-shrink-0"></i>
			<div>
				<h6 class="fw-bold mb-1"><?= __('front.how_it_works') ?></h6>
				<p class="mb-0 small opacity-85"><?= __('front.email_sent_instructions') ?></p>
			</div>
		</div>
	</div>

	<form class="reset-password-form" id="glass-drawer-reset-password-form" autocomplete="off">
		<div class="form-floating glass-input-group mb-3">
			<input type="email"
			       class="form-control glass-input"
			       id="glass-drawer-forgot-email"
			       name="email"
			       placeholder="<?= htmlspecialchars(__('front.enter_email'), ENT_QUOTES, 'UTF-8') ?>"
			       required>
			<label for="glass-drawer-forgot-email">
				<i class="bi bi-envelope-fill me-2"></i><?= __('front.email_address') ?>
			</label>
			<div class="small mt-2 text-white-50">
				<i class="bi bi-shield-check me-1"></i><?= __('front.never_share_email') ?>
			</div>
		</div>

		<div class="mb-3">
			<?= render_recaptcha_html('affiliate_forgot', 'glassdrawer') ?>
		</div>

		<div class="d-grid gap-2">
			<button type="submit" class="btn glass-btn-primary btn-lg py-3">
				<i class="bi bi-send me-2"></i><?= __('front.send_reset_link') ?>
			</button>
			<button type="button"
			        class="btn glass-btn-outline btn-lg py-3"
			        data-glass-auth-view="login">
				<i class="bi bi-arrow-left me-2"></i><?= __('front.back_to_login') ?>
			</button>
		</div>
	</form>

	<p class="text-center text-white-50 small mt-3 mb-0">
		<i class="bi bi-clock me-1"></i><?= __('front.reset_links_expire') ?>
	</p>
</div>
