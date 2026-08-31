<?php
/**
 * Starter 2026 — My Courses (LMS) Page
 *
 * @contract  Store API v1 — page: my_courses
 * @auth      required
 *
 * GLOBALS  $store_setting, $client, $home_link, $base_url
 *
 * PAGE VARIABLES
 *   $courses   array   Enrolled courses [{order_id, product_id, product_name, product_image, progress, ...}]
 *   $user      array   Logged-in customer (alias of $client)
 *   $settings  array   Store settings
 */
$total_courses    = count($courses ?? []);
$completed_count  = 0;
$inprogress_count = 0;
$locked_count     = 0;
foreach ($courses ?? [] as $c) {
    if ((int)$c['order_status'] !== 1) { $locked_count++; continue; }
    if ($c['progress_total'] > 0 && $c['progress_completed'] >= $c['progress_total']) $completed_count++;
    elseif (($c['progress_started'] ?? 0) > 0 || $c['progress_completed'] > 0) $inprogress_count++;
}
$_order_status_labels = [
    0 => __('store.pending_payment'),
    2 => __('store.order_status_mismatch'),
    3 => __('store.order_denied'),
    4 => __('store.order_expired'),
    5 => __('store.order_failed'),
];
?>

<!-- Breadcrumb -->
<div class="container">
    <nav class="s26-breadcrumb" aria-label="Breadcrumb">
        <a href="<?= $home_link ?? $base_url ?>"><?= __('store.home') ?? 'Home' ?></a>
        <span class="separator"><i class="fas fa-chevron-right"></i></span>
        <span class="current"><?= __('store.my_courses') ?? 'My Courses' ?></span>
    </nav>
</div>

<!-- Account Navigation -->
<div class="container">
    <div class="s26-account-nav">
        <a href="<?= $base_url ?>profile" class="s26-account-nav__link">
            <i class="fas fa-user"></i> <?= __('store.profile') ?? 'Profile' ?>
        </a>
        <a href="<?= $base_url ?>order" class="s26-account-nav__link">
            <i class="fas fa-gift"></i> <?= __('store.orders') ?? 'Orders' ?>
        </a>
        <a href="<?= $base_url ?>my_courses" class="s26-account-nav__link active">
            <i class="fas fa-graduation-cap"></i> <?= __('store.my_courses') ?? 'My Courses' ?>
        </a>
        <a href="<?= $base_url ?>shipping" class="s26-account-nav__link">
            <i class="fas fa-truck"></i> <?= __('store.shipping') ?? 'Shipping' ?>
        </a>
        <a href="<?= $base_url ?>wishlist" class="s26-account-nav__link">
            <i class="fas fa-heart"></i> <?= __('store.wishlist') ?? 'Wishlist' ?>
        </a>
        <a href="<?= $base_url ?>logout" class="s26-account-nav__link s26-account-nav__link--danger">
            <i class="fas fa-power-off"></i> <?= __('store.logout') ?? 'Logout' ?>
        </a>
    </div>
</div>

<?php
$_s26mc_stats = [
    ['val' => $total_courses,    'lbl' => 'Total',   'color' => '#fff'],
    ['val' => $completed_count,  'lbl' => 'Done',    'color' => '#4ade80'],
    ['val' => $inprogress_count, 'lbl' => 'Active',  'color' => '#fbbf24'],
];
if ($locked_count > 0) {
    $_s26mc_stats[] = ['val' => $locked_count, 'lbl' => 'Pending', 'color' => '#94a3b8'];
}
$s26hdr_icon    = 'fas fa-graduation-cap';
$s26hdr_eyebrow = __('store.profile') . ' &rsaquo; ' . __('store.my_courses');
$s26hdr_title   = __('store.my_courses');
$s26hdr_sub     = $total_courses . ' ' . __('store.my_courses') . ' &bull; ' . $completed_count . ' completed &bull; ' . $inprogress_count . ' in progress';
$s26hdr_stats   = $_s26mc_stats;
include(APPPATH.'views/store/starter2026/_account_header.php');
?>

<!-- Courses Grid -->
<section class="s26mc-section">
    <div class="container">

        <?php if (!empty($courses)): ?>

        <div class="row g-4">
            <?php foreach ($courses as $course):
                $isPaid      = (int)$course['order_status'] === 1;
                $orderUrl    = base_url('store/vieworder/' . $course['order_id']);
                $watchUrl    = base_url('store/vieworderdetails/' . $course['order_id']) . '?referance=' . $course['product_id'];
                $pct         = $course['progress_percent'];
                $isStarted   = ($course['progress_started'] ?? 0) > 0 || $course['progress_completed'] > 0;
                $isCompleted = $course['progress_total'] > 0 && $course['progress_completed'] >= $course['progress_total'];
                $statusCode  = (int)$course['order_status'];
                $statusLabel = isset($_order_status_labels[$statusCode]) ? $_order_status_labels[$statusCode] : __('store.pending_payment');
            ?>
            <div class="col-12 col-sm-6 col-lg-4 col-xl-3">
                <article class="s26mc-card <?= !$isPaid ? 's26mc-card--locked' : ($isCompleted ? 's26mc-card--done' : ($isStarted ? 's26mc-card--active' : '')) ?>">

                    <!-- Thumbnail -->
                    <div class="s26mc-card__thumb">
                        <?php if ($isPaid): ?>
                        <a href="<?= $watchUrl ?>" class="s26mc-card__thumb-link d-block h-100" target="_blank" rel="noopener">
                        <?php endif; ?>
                            <img src="<?= htmlspecialchars($course['image']) ?>"
                                 alt="<?= htmlspecialchars($course['product_name']) ?>"
                                 loading="lazy">
                            <?php if ($isPaid): ?>
                            <div class="s26mc-card__overlay">
                                <div class="s26mc-card__play-btn">
                                    <i class="fas fa-play"></i>
                                </div>
                            </div>
                            <span class="s26mc-badge <?= $isCompleted ? 's26mc-badge--done' : ($isStarted ? 's26mc-badge--active' : 's26mc-badge--new') ?>">
                                <?php if ($isCompleted): ?>
                                    <i class="fas fa-check-circle me-1"></i> <?= __('store.completed') ?? 'Completed' ?>
                                <?php elseif ($isStarted): ?>
                                    <i class="fas fa-spinner me-1"></i> <?= $pct ?>% done
                                <?php else: ?>
                                    <i class="fas fa-star me-1"></i> <?= __('store.new') ?? 'New' ?>
                                <?php endif; ?>
                            </span>
                            <?php else: ?>
                            <div class="s26mc-lock-overlay">
                                <div class="s26mc-lock-circle">
                                    <i class="fas fa-lock"></i>
                                </div>
                                <span class="s26mc-lock-label"><?= $statusLabel ?></span>
                            </div>
                            <span class="s26mc-badge s26mc-badge--locked">
                                <i class="fas fa-lock me-1"></i><?= __('store.locked') ?? 'Locked' ?>
                            </span>
                            <?php endif; ?>
                        <?php if ($isPaid): ?>
                        </a>
                        <?php endif; ?>
                    </div>

                    <!-- Body -->
                    <div class="s26mc-card__body">
                        <h3 class="s26mc-card__title">
                            <?php if ($isPaid): ?>
                            <a href="<?= $watchUrl ?>" target="_blank" rel="noopener"><?= htmlspecialchars($course['product_name']) ?></a>
                            <?php else: ?>
                            <span style="color:#94a3b8"><?= htmlspecialchars($course['product_name']) ?></span>
                            <?php endif; ?>
                        </h3>

                        <?php if ($isPaid): ?>
                        <!-- Progress -->
                        <?php if ($course['progress_total'] > 0): ?>
                        <?php $_s26_display_done = max($course['progress_completed'], $course['progress_started'] ?? 0); ?>
                        <div class="s26mc-progress">
                            <div class="s26mc-progress__meta">
                                <span><i class="fas fa-book-open me-1"></i><?= $_s26_display_done ?>/<?= $course['progress_total'] ?> <?= __('store.lessons_completed') ?? 'lessons' ?></span>
                                <span class="s26mc-progress__pct <?= $isCompleted ? 's26mc-progress__pct--done' : '' ?>"><?= $pct ?>%</span>
                            </div>
                            <div class="s26mc-progress__track">
                                <div class="s26mc-progress__fill <?= $isCompleted ? 's26mc-progress__fill--done' : '' ?>"
                                     style="width:<?= $pct ?>%"></div>
                            </div>
                        </div>
                        <?php else: ?>
                        <p class="s26mc-card__not-started">
                            <i class="fas fa-play-circle me-1"></i> <?= __('store.ready_to_start') ?? 'Ready to start' ?>
                        </p>
                        <?php endif; ?>

                        <!-- CTA -->
                        <a href="<?= $watchUrl ?>" target="_blank" rel="noopener" class="s26mc-card__cta <?= $isCompleted ? 's26mc-card__cta--done' : '' ?>">
                            <?php if ($isCompleted): ?>
                                <i class="fas fa-redo me-2"></i><?= __('store.watch_again') ?? 'Watch Again' ?>
                            <?php elseif ($isStarted): ?>
                                <i class="fas fa-play me-2"></i><?= __('store.continue_watching') ?? 'Continue' ?>
                            <?php else: ?>
                                <i class="fas fa-play me-2"></i><?= __('store.start_course') ?? 'Start Course' ?>
                            <?php endif; ?>
                        </a>

                        <?php else: ?>
                        <!-- Locked state -->
                        <p class="s26mc-card__not-started" style="color:#94a3b8">
                            <i class="fas fa-clock me-1"></i><?= $statusLabel ?>
                        </p>
                        <span class="s26mc-card__cta s26mc-card__cta--locked">
                            <i class="fas fa-lock me-2"></i><?= __('store.locked') ?? 'Locked' ?>
                        </span>
                        <a href="<?= $orderUrl ?>" class="s26mc-pay-link">
                            <i class="fas fa-credit-card me-1"></i><?= __('store.complete_payment') ?? 'Complete Payment' ?>
                        </a>
                        <?php endif; ?>
                    </div>
                </article>
            </div>
            <?php endforeach; ?>
        </div>

        <?php else: ?>

        <div class="s26-empty-state">
            <div class="s26-empty-state__icon">
                <i class="fas fa-graduation-cap"></i>
            </div>
            <h2 class="s26-empty-state__title"><?= __('store.no_courses_found') ?? 'No courses yet' ?></h2>
            <p class="s26-empty-state__text"><?= __('store.no_courses_desc') ?? 'Purchase a course to get started.' ?></p>
            <a href="<?= $base_url ?>category" class="s26-btn-primary">
                <i class="fas fa-shopping-bag me-2"></i><?= __('store.browse_courses') ?? 'Browse Courses' ?>
            </a>
        </div>

        <?php endif; ?>

    </div>
</section>