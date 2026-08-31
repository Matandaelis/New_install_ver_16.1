<?php
/**
 * Default theme — My Courses (LMS) page
 *
 * @contract  Store API v1 — page: my_courses
 * @see       Store_cart_payload::page_my_courses()
 * @see       /store/api/v1/pages/my_courses  (auth required)
 *
 * GLOBALS (always available via Storeapp::view)
 *   $store_setting  array   All store settings key-value map
 *   $client         array   Logged-in customer data
 *   $home_link      string  Absolute URL to store homepage
 *
 * PAGE VARIABLES
 *   $courses  array   Enrolled course list [{id, name, image, progress, link, status, ...}, ...]
 *
 * NOTE  Progress/status is computed locally in this view from course enrollment data.
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

<?php $acc_active = 'my_courses'; include(APPPATH.'views/store/default/_account_nav.php'); ?>

<?php
$hdr_icon  = 'fa fa-graduation-cap';
$hdr_title = __('store.my_courses');
$hdr_sub   = __('store.my_courses') . ' &rsaquo; ' . __('store.my_courses');
$hdr_pills = [
    ['num' => $total_courses,    'lbl' => 'Total',   'color' => '#fff'],
    ['num' => $completed_count,  'lbl' => 'Done',    'color' => '#4ade80'],
    ['num' => $inprogress_count, 'lbl' => 'Active',  'color' => '#fbbf24'],
    ['num' => $locked_count,     'lbl' => 'Pending', 'color' => '#94a3b8'],
];
include(APPPATH.'views/store/default/_account_header.php');
?>

<!-- ③ Courses Grid -->
<div class="mc-section">
    <div class="container">

        <?php if (!empty($courses)): ?>

        <div class="row">
            <?php foreach ($courses as $course):
                $isPaid      = (int)$course['order_status'] === 1;
                $orderUrl    = base_url('store/vieworder/' . $course['order_id']);
                $watchUrl    = base_url('store/vieworderdetails/' . $course['order_id']) . '?referance=' . $course['product_id'];
                $pct         = $course['progress_percent'];
                $isStarted   = ($course['progress_started'] ?? 0) > 0 || $course['progress_completed'] > 0;
                $isCompleted = $course['progress_total'] > 0 && $course['progress_completed'] >= $course['progress_total'];
                $cardState   = !$isPaid ? 'locked' : ($isCompleted ? 'done' : ($isStarted ? 'active' : 'new'));
                $statusCode  = (int)$course['order_status'];
                $statusLabel = isset($_order_status_labels[$statusCode]) ? $_order_status_labels[$statusCode] : __('store.pending_payment');
            ?>
            <div class="col-12 col-sm-6 col-lg-4 col-xl-3 mb-4">
                <article class="mc-card mc-card--<?= $cardState ?>">

                    <!-- Thumbnail -->
                    <div class="mc-thumb">
                        <?php if ($isPaid): ?>
                        <a href="<?= $watchUrl ?>" target="_blank" rel="noopener" class="d-block h-100">
                        <?php endif; ?>
                            <img src="<?= htmlspecialchars($course['image']) ?>"
                                 alt="<?= htmlspecialchars($course['product_name']) ?>"
                                 loading="lazy">
                            <?php if ($isPaid): ?>
                            <div class="mc-overlay">
                                <div class="mc-play-circle">
                                    <i class="fa fa-play"></i>
                                </div>
                            </div>
                            <span class="mc-badge mc-badge--<?= $cardState ?>">
                                <?php if ($isCompleted): ?>
                                    <i class="fa fa-check me-1"></i><?= __('store.completed') ?>
                                <?php elseif ($isStarted): ?>
                                    <?= $pct ?>% done
                                <?php else: ?>
                                    <?= __('store.new') ?>
                                <?php endif; ?>
                            </span>
                            <?php else: ?>
                            <div class="mc-lock-overlay">
                                <div class="mc-lock-circle">
                                    <i class="fa fa-lock"></i>
                                </div>
                                <span class="mc-lock-label"><?= $statusLabel ?></span>
                            </div>
                            <span class="mc-badge mc-badge--locked">
                                <i class="fa fa-lock me-1"></i><?= __('store.locked') ?>
                            </span>
                            <?php endif; ?>
                        <?php if ($isPaid): ?>
                        </a>
                        <?php endif; ?>
                    </div>

                    <!-- Body -->
                    <div class="mc-body">
                        <div class="mc-title">
                            <?php if ($isPaid): ?>
                            <a href="<?= $watchUrl ?>" target="_blank" rel="noopener">
                                <?= htmlspecialchars($course['product_name']) ?>
                            </a>
                            <?php else: ?>
                            <span style="color:#64748b"><?= htmlspecialchars($course['product_name']) ?></span>
                            <?php endif; ?>
                        </div>

                        <?php if ($isPaid): ?>
                        <!-- Progress -->
                        <?php if ($course['progress_total'] > 0): ?>
                        <?php $_display_done = max($course['progress_completed'], $course['progress_started'] ?? 0); ?>
                        <div class="mc-progress-wrap">
                            <div class="mc-progress-meta">
                                <span>
                                    <i class="fa fa-list-ul me-1" style="color:#007BFF;font-size:10px"></i>
                                    <?= $_display_done ?> / <?= $course['progress_total'] ?> <?= __('store.lessons_completed') ?>
                                </span>
                                <span class="mc-progress-pct" style="color:<?= $isCompleted ? '#16a34a' : '#007BFF' ?>">
                                    <?= $pct ?>%
                                </span>
                            </div>
                            <div class="mc-progress-bar-bg">
                                <div class="mc-progress-bar-fill <?= $isCompleted ? 'mc-progress-bar-fill--green' : 'mc-progress-bar-fill--blue' ?>"
                                     style="width:<?= $pct ?>%"></div>
                            </div>
                        </div>
                        <?php else: ?>
                        <p class="mc-not-started"><i class="fa fa-play-circle me-1"></i><?= __('store.ready_to_start') ?></p>
                        <?php endif; ?>

                        <!-- CTA Button -->
                        <a href="<?= $watchUrl ?>" target="_blank" rel="noopener"
                           class="mc-btn <?= $isCompleted ? 'mc-btn--green' : 'mc-btn--blue' ?>">
                            <?php if ($isCompleted): ?>
                                <i class="fa fa-redo"></i> <?= __('store.watch_again') ?>
                            <?php elseif ($isStarted): ?>
                                <i class="fa fa-play-circle"></i> <?= __('store.continue_watching') ?>
                            <?php else: ?>
                                <i class="fa fa-play-circle"></i> <?= __('store.start_course') ?>
                            <?php endif; ?>
                        </a>

                        <?php else: ?>
                        <!-- Locked state -->
                        <p class="mc-not-started" style="color:#94a3b8">
                            <i class="fa fa-clock-o me-1"></i><?= $statusLabel ?>
                        </p>
                        <span class="mc-btn mc-btn--locked">
                            <i class="fa fa-lock"></i> <?= __('store.locked') ?>
                        </span>
                        <a href="<?= $orderUrl ?>" class="mc-pay-link">
                            <i class="fa fa-credit-card"></i> <?= __('store.complete_payment') ?>
                        </a>
                        <?php endif; ?>
                    </div>

                </article>
            </div>
            <?php endforeach; ?>
        </div>

        <?php else: ?>

        <!-- Empty State -->
        <div class="mc-empty">
            <div class="mc-empty-icon">
                <i class="fa fa-graduation-cap"></i>
            </div>
            <h4><?= __('store.no_courses_found') ?></h4>
            <p><?= __('store.no_courses_desc') ?></p>
            <a href="<?= $base_url ?>category" class="mc-btn mc-btn--blue" style="display:inline-flex;max-width:220px;margin:0 auto">
                <i class="fa fa-shopping-bag"></i> <?= __('store.browse_courses') ?>
            </a>
        </div>

        <?php endif; ?>

    </div>
</div>