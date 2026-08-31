<?php
defined('BASEPATH') OR exit('No direct script access allowed');
/**
 * Central affiliate login form — single source of truth for index1–index13.
 *
 * Pass: login_theme_id (int 1–14). 14 = multiple_pages (mp-auth-form). Omitted or invalid → theme 1.
 *
 * Contract: #login-form, name=username|password, #login_button, render_recaptcha_html('affiliate_login')
 * AJAX: render_recaptcha_scripts(['login','forgot']) + form-handler.js → auth/login
 *
 * Optional hooks after this view: parent theme echoes $hook_form_bottom (Theme_blocks_handler).
 */

$tid = isset($login_theme_id) ? (int) $login_theme_id : 1;
if ($tid < 1 || $tid > 14) {
	$tid = 1;
}

$login_idx1_drawer_mode = !empty($login_idx1_drawer_mode);

/* Bootstrap 5 .form-floating requires a non-empty placeholder for :placeholder-shown; use a space so label is the only visible hint (avoids double text with real placeholders + WebKit -webkit-text-fill). */
$ff_ph = ' ';

$p = 'idx1';
$layout = 'stacked';
$uid = 'username';
$pid = 'password';
$form_class = '';
$form_autocomplete_off = false;
$use_stagger = true;
$btn_extra_class = '';
$password_toggle_attrs = 'tabindex="-1" aria-label="Toggle Password Visibility"';

switch ($tid) {
	case 1:
		$p = 'idx1';
		break;
	case 2:
		$p = 'idx2';
		break;
	case 3:
		$p = 'idx3';
		break;
	case 4:
		$p = 'idx4';
		$layout = 'split_row';
		$uid = 'idx4_username';
		$pid = 'loginpassowrd';
		$password_toggle_attrs = 'tabindex="-1"';
		break;
	case 5:
		$p = 'idx5';
		$layout = 'idx5';
		$uid = 'idx5_username';
		$pid = 'loginpassowrd';
		$form_class = 'mt-3';
		$password_toggle_attrs = 'tabindex="-1"';
		break;
	case 6:
		$p = 'idx6';
		$layout = 'tabbed';
		$uid = 'idx6-username';
		$pid = 'idx6-password';
		$form_autocomplete_off = true;
		$password_toggle_attrs = 'data-target="#idx6-password" aria-label="Toggle password"';
		$btn_extra_class = ' btn-submit';
		break;
	case 7:
		$p = 'idx7';
		$layout = 'tabbed';
		$uid = 'idx7-username';
		$pid = 'idx7-password';
		$form_autocomplete_off = true;
		$password_toggle_attrs = 'data-target="#idx7-password" aria-label="Toggle password"';
		$btn_extra_class = ' btn-submit';
		break;
	case 8:
		$p = 'idx8';
		$layout = 'tabbed';
		$uid = 'idx8-username';
		$pid = 'idx8-password';
		$form_autocomplete_off = true;
		$password_toggle_attrs = 'data-target="#idx8-password" aria-label="Toggle password"';
		$btn_extra_class = ' btn-submit';
		break;
	case 9:
		$p = 'idx9';
		$layout = 'tabbed';
		$uid = 'idx9-username';
		$pid = 'idx9-password';
		$form_autocomplete_off = true;
		$password_toggle_attrs = 'data-target="#idx9-password" aria-label="Toggle password"';
		$btn_extra_class = ' btn-submit';
		break;
	case 10:
		$p = 'idx10';
		$layout = 'tabbed';
		$uid = 'idx10-username';
		$pid = 'idx10-password';
		$form_autocomplete_off = true;
		$password_toggle_attrs = 'data-target="#idx10-password" aria-label="Toggle password"';
		$btn_extra_class = ' btn-submit';
		break;
	case 11:
		$p = 'idx11';
		$layout = 'tabbed';
		$uid = 'idx11-username';
		$pid = 'idx11-password';
		$form_autocomplete_off = true;
		$password_toggle_attrs = 'data-target="#idx11-password" aria-label="Toggle password"';
		$btn_extra_class = ' btn-submit';
		break;
	case 12:
		$p = 'idx12';
		$layout = 'stacked';
		$uid = 'idx12-username';
		$pid = 'idx12-password';
		$form_autocomplete_off = true;
		$btn_extra_class = ' btn-submit';
		break;
	case 13:
		$p = 'glass';
		$layout = 'stacked';
		$use_stagger = false;
		break;
	case 14:
		$layout = 'multiple_pages';
		break;
}

if ($layout === 'multiple_pages') {
	?>
<form class="mp-auth-form" id="login-form" autocomplete="off">
	<div class="form-group">
		<div class="input-group">
			<span class="input-group-text"><i class="user-icon"></i></span>
			<input class="form-control" type="text" name="username" placeholder="<?= htmlspecialchars(__('front.username_email')) ?>" autocomplete="off">
		</div>
	</div>
	<div class="form-group">
		<div class="input-group">
			<span class="input-group-text"><i class="password-icon"></i></span>
			<input class="form-control" type="password" name="password" placeholder="*************" autocomplete="off">
		</div>
	</div>
	<input class="mp-btn-submit front_button_color front_button_hover_color front_button_text_color" id="login_button" type="submit" value="<?= htmlspecialchars(__('front.login')) ?>">
	<?= render_recaptcha_html('affiliate_login') ?>
</form>
	<?php
	return;
}

$form_tag = '<form id="login-form"' . ($form_class !== '' ? ' class="' . htmlspecialchars($form_class) . '"' : '');
$form_tag .= $form_autocomplete_off ? ' autocomplete="off">' : '>';

if ($layout === 'stacked') {
	echo $form_tag;
	$mb_user = $use_stagger ? ' mb-4 ' : ' mb-4 ';
	$mb_pass = $use_stagger ? ' mb-4 ' : ' mb-4 ';
	$st3 = $use_stagger ? "{$p}-stagger-3" : '';
	$st4 = $use_stagger ? "{$p}-stagger-4" : '';
	$st5 = $use_stagger ? "{$p}-stagger-5" : '';
	$st6 = $use_stagger ? "{$p}-stagger-6" : '';
	$ig = $p . '-input-group';
	$inp = $p . '-input';
	$btn = $p . '-btn-primary';
	$lnk = $p . '-link';
	if ($tid === 12) {
		?>
		<div class="mb-3 <?= htmlspecialchars($st3) ?>">
			<div class="form-floating <?= htmlspecialchars($ig) ?>">
				<input type="text" class="form-control <?= htmlspecialchars($inp) ?>" id="<?= htmlspecialchars($uid) ?>" name="username"
				       placeholder="<?= htmlspecialchars($ff_ph, ENT_QUOTES, 'UTF-8') ?>" required>
				<label for="<?= htmlspecialchars($uid) ?>">
					<i class="bi bi-person-circle me-2" style="color:var(--idx12-primary-light)"></i><?= __('front.username_email') ?>
				</label>
			</div>
		</div>
		<div class="mb-3 <?= htmlspecialchars($st3) ?>">
			<div class="form-floating <?= htmlspecialchars($ig) ?>">
				<input type="password" class="form-control <?= htmlspecialchars($inp) ?>" id="<?= htmlspecialchars($pid) ?>" name="password"
				       placeholder="<?= htmlspecialchars($ff_ph, ENT_QUOTES, 'UTF-8') ?>" required>
				<label for="<?= htmlspecialchars($pid) ?>">
					<i class="bi bi-shield-lock me-2" style="color:var(--idx12-primary-light)"></i><?= __('front.password') ?>
				</label>
				<button type="button" class="toggle-password" <?= $password_toggle_attrs ?>>
					<i class="bi bi-eye"></i>
				</button>
			</div>
		</div>
		<div class="mb-3 text-center idx12-stagger-4" id="login-status-msg"></div>
		<div class="mb-4 idx12-stagger-4"><?= render_recaptcha_html('affiliate_login') ?></div>
		<div class="d-grid mb-3 idx12-stagger-5">
			<button type="submit" id="login_button" class="btn <?= htmlspecialchars($btn) ?> btn-lg py-3<?= htmlspecialchars($btn_extra_class) ?>">
				<i class="bi bi-box-arrow-in-right me-2"></i><?= __('front.login') ?>
			</button>
		</div>
		<div class="text-center idx12-stagger-5">
			<a href="<?= base_url('forget-password') ?>" class="<?= htmlspecialchars($lnk) ?>">
				<i class="bi bi-question-circle"></i><?= __('front.forget_password') ?>?
			</a>
		</div>
		<?php
	} else {
		$user_icon = ($tid === 13) ? 'bi-person-circle' : 'bi-person-circle';
		$pass_icon = 'bi-shield-lock';
		?>
		<div class="form-floating <?= htmlspecialchars($ig) ?><?= htmlspecialchars($mb_user) ?><?= $st3 ? ' ' . htmlspecialchars($st3) : '' ?>">
			<input type="text" class="form-control <?= htmlspecialchars($inp) ?>" id="<?= htmlspecialchars($uid) ?>" name="username"
			       placeholder="<?= htmlspecialchars($ff_ph, ENT_QUOTES, 'UTF-8') ?>" required>
			<label for="<?= htmlspecialchars($uid) ?>">
				<i class="bi <?= htmlspecialchars($user_icon) ?> me-2"></i><?= __('front.username_email') ?>
			</label>
		</div>
		<div class="form-floating <?= htmlspecialchars($ig) ?><?= htmlspecialchars($mb_pass) ?> position-relative<?= $st4 ? ' ' . htmlspecialchars($st4) : '' ?>">
			<input type="password" class="form-control <?= htmlspecialchars($inp) ?>" id="<?= htmlspecialchars($pid) ?>" name="password"
			       placeholder="<?= htmlspecialchars($ff_ph, ENT_QUOTES, 'UTF-8') ?>" required>
			<label for="<?= htmlspecialchars($pid) ?>">
				<i class="bi <?= htmlspecialchars($pass_icon) ?> me-2"></i><?= __('front.password') ?>
			</label>
			<button type="button" class="btn btn-sm toggle-password" <?= $password_toggle_attrs ?>>
				<i class="bi bi-eye"></i>
			</button>
		</div>
		<div class="mb-3 text-center" id="login-status-msg"></div>
		<div class="mb-4<?= $st5 ? ' ' . htmlspecialchars($st5) : '' ?>"><?= render_recaptcha_html('affiliate_login') ?></div>
		<div class="d-grid mb-3<?= $st5 ? ' ' . htmlspecialchars($st5) : '' ?>">
			<button type="submit" id="login_button" class="btn <?= htmlspecialchars($btn) ?> btn-lg<?= $tid === 13 ? ' fw-bold' : '' ?> py-3<?= htmlspecialchars($btn_extra_class) ?>">
				<i class="bi bi-box-arrow-in-right me-2"></i><?= __('front.login') ?>
			</button>
		</div>
		<div class="text-center<?= $st6 ? ' ' . htmlspecialchars($st6) : '' ?>">
			<?php if ($login_idx1_drawer_mode && $tid === 1): ?>
				<a href="#" class="<?= htmlspecialchars($lnk) ?> idx1-auth-switch-link" data-idx1-auth-view="forgot" role="button">
					<i class="bi bi-question-circle me-1"></i><?= __('front.forget_password') ?>?
				</a>
			<?php elseif ($login_idx1_drawer_mode && $tid === 13): ?>
				<a href="#" class="<?= htmlspecialchars($lnk) ?> glass-auth-switch-link" data-glass-auth-view="forgot" role="button">
					<i class="bi bi-question-circle me-1"></i><?= __('front.forget_password') ?>?
				</a>
			<?php else: ?>
				<a href="<?= base_url('forget-password') ?>" class="<?= htmlspecialchars($lnk) ?>">
					<i class="bi bi-question-circle me-1"></i><?= __('front.forget_password') ?>?
				</a>
			<?php endif; ?>
		</div>
		<?php
	}
	echo '</form>';
	return;
}

if ($layout === 'split_row') {
	echo $form_tag;
	?>
	<div class="<?= htmlspecialchars($p) ?>-input-group form-floating mb-3 <?= htmlspecialchars($p) ?>-stagger-3">
		<input required class="form-control <?= htmlspecialchars($p) ?>-input" name="username" id="<?= htmlspecialchars($uid) ?>" placeholder="<?= htmlspecialchars($ff_ph, ENT_QUOTES, 'UTF-8') ?>">
		<label for="<?= htmlspecialchars($uid) ?>"><i class="bi bi-envelope me-1"></i><?= __('front.username_email') ?></label>
	</div>
	<div class="<?= htmlspecialchars($p) ?>-input-group form-floating mb-3 <?= htmlspecialchars($p) ?>-stagger-4">
		<input required type="password" class="form-control <?= htmlspecialchars($p) ?>-input" name="password" id="<?= htmlspecialchars($pid) ?>" placeholder="<?= htmlspecialchars($ff_ph, ENT_QUOTES, 'UTF-8') ?>">
		<label for="<?= htmlspecialchars($pid) ?>"><i class="bi bi-lock me-1"></i><?= __('front.password') ?></label>
		<button type="button" class="toggle-password" <?= $password_toggle_attrs ?>><i class="bi bi-eye"></i></button>
	</div>
	<?= render_recaptcha_html('affiliate_login') ?>
	<div class="d-flex align-items-center justify-content-between mb-3 <?= htmlspecialchars($p) ?>-stagger-5">
		<button type="submit" class="btn <?= htmlspecialchars($p) ?>-btn-primary py-3 px-4 flex-grow-1 me-3" id="login_button"><?= __('front.login') ?></button>
		<a href="<?= base_url('forget-password') ?>" class="<?= htmlspecialchars($p) ?>-link text-nowrap"><?= __('front.forget_password') ?>?</a>
	</div>
	<div id="login-status-msg" class="<?= htmlspecialchars($p) ?>-stagger-5"></div>
	<?php
	echo '</form>';
	return;
}

if ($layout === 'idx5') {
	echo $form_tag;
	?>
	<div class="<?= htmlspecialchars($p) ?>-input-group form-floating mb-3 <?= htmlspecialchars($p) ?>-stagger-3">
		<input required class="form-control <?= htmlspecialchars($p) ?>-input" name="username" id="<?= htmlspecialchars($uid) ?>" placeholder="<?= htmlspecialchars($ff_ph, ENT_QUOTES, 'UTF-8') ?>">
		<label for="<?= htmlspecialchars($uid) ?>"><i class="bi bi-envelope me-1"></i><?= __('front.username_email') ?></label>
	</div>
	<div class="<?= htmlspecialchars($p) ?>-input-group form-floating mb-3 <?= htmlspecialchars($p) ?>-stagger-3">
		<input required type="password" class="form-control <?= htmlspecialchars($p) ?>-input" name="password" id="<?= htmlspecialchars($pid) ?>" placeholder="<?= htmlspecialchars($ff_ph, ENT_QUOTES, 'UTF-8') ?>">
		<label for="<?= htmlspecialchars($pid) ?>"><i class="bi bi-lock me-1"></i><?= __('front.password') ?></label>
		<button type="button" class="toggle-password" <?= $password_toggle_attrs ?>><i class="bi bi-eye"></i></button>
	</div>
	<div class="d-flex align-items-center justify-content-end mb-3 <?= htmlspecialchars($p) ?>-stagger-4">
		<?php if ($login_idx1_drawer_mode && $tid === 5): ?>
			<a href="#" class="<?= htmlspecialchars($p) ?>-link idx5-auth-switch-link" data-idx5-auth-view="forgot" role="button"><?= __('front.forget_password') ?>?</a>
		<?php else: ?>
			<a href="<?= base_url('forget-password') ?>" class="<?= htmlspecialchars($p) ?>-link"><?= __('front.forget_password') ?>?</a>
		<?php endif; ?>
	</div>
	<?= render_recaptcha_html('affiliate_login') ?>
	<div class="d-grid <?= htmlspecialchars($p) ?>-stagger-4">
		<button type="submit" class="btn <?= htmlspecialchars($p) ?>-btn-primary py-3" id="login_button"><?= __('front.login') ?></button>
	</div>
	<div id="login-status-msg" class="mt-2 <?= htmlspecialchars($p) ?>-stagger-5"></div>
	<?php
	echo '</form>';
	return;
}

if ($layout === 'tabbed') {
	echo $form_tag;
	$st_user = "{$p}-stagger-3";
	$st_pass = ($tid === 6) ? "{$p}-stagger-4" : "{$p}-stagger-3";
	$st_status = ($tid === 6) ? "{$p}-stagger-5" : "{$p}-stagger-4";
	$st_btn = "{$p}-stagger-5";
	?>
	<div class="mb-3 <?= htmlspecialchars($st_user) ?>">
		<div class="form-floating <?= htmlspecialchars($p) ?>-input-group">
			<input required class="form-control <?= htmlspecialchars($p) ?>-input" name="username" id="<?= htmlspecialchars($uid) ?>" placeholder="<?= htmlspecialchars($ff_ph, ENT_QUOTES, 'UTF-8') ?>">
			<label for="<?= htmlspecialchars($uid) ?>"><i class="bi bi-person me-1"></i> <?= __('front.username_email') ?></label>
		</div>
	</div>
	<div class="mb-3 <?= htmlspecialchars($st_pass) ?>">
		<div class="form-floating <?= htmlspecialchars($p) ?>-input-group">
			<input required class="form-control <?= htmlspecialchars($p) ?>-input" type="password" name="password" id="<?= htmlspecialchars($pid) ?>" placeholder="<?= htmlspecialchars($ff_ph, ENT_QUOTES, 'UTF-8') ?>">
			<label for="<?= htmlspecialchars($pid) ?>"><i class="bi bi-lock me-1"></i> <?= __('front.password') ?></label>
			<button type="button" class="toggle-password" <?= $password_toggle_attrs ?>><i class="bi bi-eye"></i></button>
		</div>
	</div>
	<div class="d-flex justify-content-between align-items-center mb-3 <?= htmlspecialchars($st_status) ?>">
		<div id="login-status-msg"></div>
		<?php if ($login_idx1_drawer_mode && $tid === 9): ?>
			<a href="#" class="<?= htmlspecialchars($p) ?>-link idx9-auth-switch-link" data-idx9-auth-view="forgot" role="button"><?= __('front.forget_password') ?>?</a>
		<?php else: ?>
			<a href="<?= base_url('forget-password') ?>" class="<?= htmlspecialchars($p) ?>-link"><?= __('front.forget_password') ?>?</a>
		<?php endif; ?>
	</div>
	<?= render_recaptcha_html('affiliate_login') ?>
	<div class="d-grid gap-2 <?= htmlspecialchars($st_btn) ?>">
		<button type="submit" class="btn <?= htmlspecialchars($p) ?>-btn-primary py-3<?= htmlspecialchars($btn_extra_class) ?>" id="login_button">
			<i class="bi bi-box-arrow-in-right me-2"></i><?= __('front.login') ?>
		</button>
	</div>
	<?php
	echo '</form>';
	return;
}
