<?php
/**
 * Referral URLs (e.g. /register/Mg==/?id=2) use this view — match Theme 1: same landing as login
 * with offcanvas auto-opening on Register (affiliate link flow).
 */
include_once "header.php";
?>

<?= $hook_floating_pulse ?? '' ?>

<div class="d-none" data-idx5-auth-autoshow="1" aria-hidden="true"></div>

                    <div class="col-lg-6 idx5-hero-left">
                        <h1 class="idx5-hero-headline"><?= $setting['heading'] ?? '' ?></h1>
                        <div class="idx5-hero-intro-wrap mt-3">
                            <div class="idx5-hero-intro idx5-hero-intro--collapsible">
                                <div class="idx5-hero-intro-inner">
                                    <p class="idx5-hero-body"><?= $setting['content'] ?? '' ?></p>
                                </div>
                            </div>
                            <button type="button"
                                    class="btn btn-link idx5-hero-readmore-btn p-0 mt-2 text-decoration-none fw-semibold d-none"
                                    data-read-more="<?= htmlspecialchars(__('front.idx1_read_more'), ENT_QUOTES, 'UTF-8') ?>"
                                    data-read-less="<?= htmlspecialchars(__('front.idx1_read_less'), ENT_QUOTES, 'UTF-8') ?>">
                                <span class="idx5-hero-readmore-label"><?= __('front.idx1_read_more') ?></span>
                            </button>
                        </div>
                        <button type="button"
                                class="btn idx5-btn-primary idx5-cta-hero mt-4"
                                data-bs-toggle="offcanvas"
                                data-bs-target="#idx5AuthOffcanvas"
                                aria-controls="idx5AuthOffcanvas">
                            <?= __('front.idx1_join_or_login') ?>
                        </button>
                    </div>
                    <div class="col-lg-6 idx5-hero-right">
                        <div class="idx5-stats-zone">
                            <?= $hook_form_bottom ?? '' ?>
                        </div>
                    </div>

<?php
$idx5_auth_panel = 'register';
include_once "footer.php";
?>
