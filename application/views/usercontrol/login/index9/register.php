<?php
/**
 * Referral register URLs — same landing as login; offcanvas opens on Register (Theme 1 parity).
 */
include_once "header.php";
?>

<?= $hook_floating_pulse ?? '' ?>

<div class="d-none" data-idx9-auth-autoshow="1" aria-hidden="true"></div>

<div class="idx9-hero-copy">
	<h1 class="idx9-hero-headline"><?= $setting['heading'] ?? '' ?></h1>
	<div class="idx9-hero-intro-wrap">
		<div class="idx9-hero-intro idx9-hero-intro--collapsible">
			<div class="idx9-hero-intro-inner">
				<p class="idx9-hero-body"><?= $setting['content'] ?? '' ?></p>
			</div>
		</div>
		<button type="button"
		        class="btn btn-link idx9-hero-readmore-btn p-0 mt-2 text-decoration-none fw-semibold d-none"
		        data-read-more="<?= htmlspecialchars(__('front.idx1_read_more'), ENT_QUOTES, 'UTF-8') ?>"
		        data-read-less="<?= htmlspecialchars(__('front.idx1_read_less'), ENT_QUOTES, 'UTF-8') ?>">
			<span class="idx9-hero-readmore-label"><?= __('front.idx1_read_more') ?></span>
		</button>
	</div>
	<button type="button"
	        class="btn idx9-btn-primary idx9-cta-hero mt-4"
	        data-bs-toggle="offcanvas"
	        data-bs-target="#idx9AuthOffcanvas"
	        aria-controls="idx9AuthOffcanvas">
		<?= __('front.idx1_join_or_login') ?>
	</button>
</div><!-- /.idx9-hero-copy -->

<?php
$idx9_auth_panel = 'register';
include_once "footer.php";
?>
