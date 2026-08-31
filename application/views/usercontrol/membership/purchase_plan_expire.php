<?php
    $isLifetime      = (isset($is_lifetime_plan) && $is_lifetime_plan);
    $hasPlan         = (isset($plan) && $plan);

    $payment_details = $hasPlan ? json_decode($plan->payment_details ?? '{}') : null;
    $isPendingBank   = ($hasPlan
                        && $plan->strToTimeRemainsOnlyDifference() > 0
                        && $plan->payment_method == "Bank Transfer"
                        && isset($payment_details)
                        && $payment_details->payment_status == "Pending");

    $isExpired       = ($hasPlan && !$isPendingBank && $plan->strToTimeRemainsOnlyDifference() <= 0);
    $isInactive      = ($hasPlan && !$isPendingBank && !$isExpired && $plan->status != 1);
    $isExpireSoon    = ($hasPlan && !$isPendingBank && !$isExpired && !$isInactive
                        && $plan->remainDay() !== 'lifetime' && $plan->remainDay() <= 0);

    $remainRaw       = $hasPlan ? $plan->remainDayOnlyString() : '0';
    $isLifetimeRemain = ($remainRaw === 'lifetime');

    $planName        = ($hasPlan && $plan->plan) ? $plan->plan->name : '';
    $planDesc        = ($hasPlan && $plan->plan) ? $plan->plan->description : '';
    $planDate        = $hasPlan ? dateFormat($plan->started_at, 'd F Y') . " &mdash; " . $plan->expire_text : '';
?>

<div class="container-fluid py-3">

    <!-- ========= COMPACT HERO STRIP ========= -->
    <?php
        $heroClass = '';
        $heroIcon  = '';
        $heroTitle = '';
        $heroSub   = '';

        if ($isPendingBank) {
            $heroClass = 'mship-hero--pending';
            $heroIcon  = 'bi-hourglass-split';
            $heroTitle = __('user.wait_to_approves');
            $heroSub   = __('user.pending_bank_transfer_desc');
        } elseif ($isExpired) {
            $heroClass = 'mship-hero--expired';
            $heroIcon  = 'bi-exclamation-triangle-fill';
            $heroTitle = __('user.your_plan_is_expired');
            $heroSub   = __('user.please_purchase_new_plan');
        } elseif ($isInactive) {
            $heroClass = 'mship-hero--inactive';
            $heroIcon  = 'bi-clock-history';
            $heroTitle = __('user.your_plan_status_is') . ': ' . strip_tags($plan->status_text);
            $heroSub   = __('user.please_wait_while_your_plan_status_change');
        } elseif ($isExpireSoon) {
            $heroClass = 'mship-hero--expired';
            $heroIcon  = 'bi-arrow-repeat';
            $heroTitle = __('user.your_plan_expire');
            $heroSub   = '';
        } elseif ($isLifetime) {
            $heroClass = 'mship-hero--lifetime';
            $heroIcon  = 'bi-infinity';
            $heroTitle = __('user.lifetime_free_membership');
            $heroSub   = __('user.you_have_a_lifetime');
        }
    ?>

    <?php if ($heroClass): ?>
    <div class="card border-0 rounded-4 overflow-hidden mb-3 card-animate visible fade-in-up mship-hero <?= $heroClass ?>">
        <div class="card-body py-3 px-4">
            <div class="d-flex align-items-center gap-3">
                <span class="d-inline-flex align-items-center justify-content-center rounded-circle mship-hero-icon">
                    <i class="bi <?= $heroIcon ?> fs-4"></i>
                </span>
                <div class="flex-grow-1">
                    <h5 class="fw-bold mb-0"><?= $heroTitle ?></h5>
                    <?php if ($heroSub): ?>
                        <small class="opacity-75"><?= $heroSub ?></small>
                    <?php endif; ?>
                    <?php if ($isExpireSoon): ?>
                        <small class="opacity-75">
                            <a class="text-white text-decoration-underline fw-semibold" href="<?= base_url('/usercontrol/purchase_plan/') ?>">
                                <?= __('user.click_here') ?>
                            </a> <?= __('user.to_renew_plan') ?>
                        </small>
                    <?php endif; ?>
                </div>
                <a class="btn btn-light btn-sm rounded-pill fw-semibold px-4 flex-shrink-0 d-none d-md-inline-flex align-items-center"
                   href="<?= base_url('usercontrol/purchase_plan') ?>">
                    <i class="bi bi-bag-plus me-1"></i><?= __('user.buy_new_plan') ?>
                </a>
            </div>
        </div>
    </div>
    <?php endif; ?>

    <!-- ========= MAIN CONTENT — 3-column layout ========= -->
    <?php if ($hasPlan): ?>
    <div class="row g-3">

        <!-- Col 1: Plan Info Card -->
        <div class="col-lg-5">
            <div class="card border-0 rounded-4 shadow-sm card-hover-lift card-animate visible mship-card h-100">
                <div class="card-header bg-transparent border-bottom d-flex align-items-center gap-2 px-3 py-2">
                    <i class="bi bi-shield-check text-primary"></i>
                    <h6 class="mb-0 fw-semibold mship-card-title"><?= __('user.membership_plan') ?></h6>
                    <?php if ($isPendingBank): ?>
                        <span class="badge bg-warning text-dark ms-auto rounded-pill px-2 py-1 small">
                            <i class="bi bi-hourglass-split me-1"></i><?= $payment_details->payment_status ?? '' ?>
                        </span>
                    <?php elseif ($isExpired || $isExpireSoon): ?>
                        <span class="badge bg-danger ms-auto rounded-pill px-2 py-1 small">
                            <i class="bi bi-x-circle me-1"></i><?= __('user.expired') ?>
                        </span>
                    <?php elseif ($isInactive): ?>
                        <span class="badge bg-secondary ms-auto rounded-pill px-2 py-1 small">
                            <i class="bi bi-pause-circle me-1"></i><?= strip_tags($plan->status_text) ?>
                        </span>
                    <?php else: ?>
                        <span class="badge bg-success ms-auto rounded-pill px-2 py-1 small">
                            <i class="bi bi-check-circle me-1"></i><?= $plan->active_text ?>
                        </span>
                    <?php endif; ?>
                </div>
                <div class="card-body px-3 py-3">
                    <!-- Plan Name -->
                    <div class="d-flex align-items-center gap-3 mb-3">
                        <span class="d-inline-flex align-items-center justify-content-center rounded-circle mship-icon-circle flex-shrink-0">
                            <i class="bi bi-gem text-primary fs-5"></i>
                        </span>
                        <div>
                            <h5 class="fw-bold mb-0 mship-card-title"><?= $planName ?></h5>
                            <?php if ($planDesc): ?>
                                <small class="mship-card-subtitle"><?= $planDesc ?></small>
                            <?php endif; ?>
                        </div>
                    </div>

                    <!-- Detail rows -->
                    <div class="list-group list-group-flush rounded-3 overflow-hidden mship-detail-list">
                        <div class="list-group-item d-flex align-items-center gap-2 px-3 py-2 border-0 mship-detail-item">
                            <span class="d-inline-flex align-items-center justify-content-center rounded-circle flex-shrink-0 mship-icon-circle mship-icon-circle--sm">
                                <i class="bi bi-calendar3 text-primary small"></i>
                            </span>
                            <div class="flex-grow-1">
                                <small class="d-block fw-semibold mship-detail-label"><?= __('user.plan_date') ?></small>
                                <span class="small fw-medium mship-detail-value"><?= $planDate ?></span>
                            </div>
                        </div>
                        <div class="list-group-item d-flex align-items-center gap-2 px-3 py-2 border-0 mship-detail-item">
                            <span class="d-inline-flex align-items-center justify-content-center rounded-circle flex-shrink-0 mship-icon-circle mship-icon-circle--sm mship-icon-circle--green">
                                <i class="bi bi-activity text-success small"></i>
                            </span>
                            <div class="flex-grow-1">
                                <small class="d-block fw-semibold mship-detail-label"><?= __('user.plan_status') ?></small>
                                <span class="small fw-medium mship-detail-value">
                                    <?php
                                        if (isset($payment_details) && isset($payment_details->payment_status) && $isPendingBank)
                                            echo $payment_details->payment_status;
                                        else if ($plan->isExpire() || !$plan->strToTimeRemains() > 0)
                                            echo __('user.expired');
                                        else
                                            echo $plan->active_text;
                                    ?>
                                </span>
                            </div>
                        </div>
                        <?php if ($isPendingBank && isset($payment_details->payment_status)): ?>
                        <div class="list-group-item d-flex align-items-center gap-2 px-3 py-2 border-0 mship-detail-item">
                            <span class="d-inline-flex align-items-center justify-content-center rounded-circle flex-shrink-0 mship-icon-circle mship-icon-circle--sm mship-icon-circle--orange">
                                <i class="bi bi-credit-card text-warning small"></i>
                            </span>
                            <div class="flex-grow-1">
                                <small class="d-block fw-semibold mship-detail-label"><?= __('user.payment_status') ?></small>
                                <span class="small fw-medium mship-detail-value"><?= $payment_details->payment_status ?></span>
                            </div>
                        </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>

        <!-- Col 2: Remaining Days -->
        <div class="col-lg-4">
            <div class="card border-0 rounded-4 shadow-sm card-hover-lift card-animate visible mship-card h-100 mship-delay-1">
                <div class="card-body p-3 d-flex flex-column justify-content-center text-center">
                    <?php if ($isLifetimeRemain): ?>
                        <div class="rounded-4 p-3 mship-remain-box">
                            <div class="mship-remain-number fw-bold text-success mb-1">&infin;</div>
                            <small class="text-uppercase fw-semibold mship-remain-label"><?= __('user.remain_days') ?></small>
                        </div>
                    <?php elseif (!$isLifetime): ?>
                        <?php
                            $remainNum = max(0, (int)$remainRaw);
                            $checkDay  = isset($MembershipSetting) ? max((int)$MembershipSetting['notificationbefore'], 1) : 30;
                            $totalDays = max($remainNum, $checkDay, 1);
                            $pct       = min(100, round(($remainNum / $totalDays) * 100));
                            $barColor  = $remainNum <= 0 ? 'bg-danger' : ($remainNum <= 7 ? 'bg-warning' : 'bg-success');
                        ?>
                        <div class="rounded-4 p-3 mship-remain-box">
                            <div class="mship-remain-number fw-bold mb-1 <?= $remainNum <= 0 ? 'text-danger' : ($remainNum <= 7 ? 'text-warning' : 'text-success') ?>">
                                <?= $remainNum ?>
                            </div>
                            <small class="text-uppercase fw-semibold d-block mb-2 mship-remain-label">
                                <?= __('user.remain_days') ?>
                            </small>
                            <div class="progress rounded-pill mship-progress">
                                <div class="progress-bar rounded-pill <?= $barColor ?>"
                                     role="progressbar"
                                     style="width:<?= $pct ?>%"
                                     aria-valuenow="<?= $pct ?>" aria-valuemin="0" aria-valuemax="100">
                                </div>
                            </div>
                        </div>
                    <?php else: ?>
                        <div class="rounded-4 p-3 mship-remain-box">
                            <div class="mship-remain-number fw-bold text-success mb-1">&infin;</div>
                            <small class="text-uppercase fw-semibold mship-remain-label"><?= __('user.remain_days') ?></small>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>

        <!-- Col 3: CTA -->
        <div class="col-lg-3">
            <div class="card border-0 rounded-4 shadow-sm card-animate visible h-100 mship-card mship-delay-2">
                <div class="card-body p-3 d-flex flex-column align-items-center justify-content-center text-center gap-3">
                    <span class="d-inline-flex align-items-center justify-content-center rounded-circle mship-icon-circle">
                        <i class="bi bi-bag-plus text-primary fs-5"></i>
                    </span>
                    <a class="btn btn-primary rounded-pill px-4 py-2 fw-semibold shadow-sm btn-micro mship-cta-btn w-100"
                       href="<?= base_url('usercontrol/purchase_plan') ?>">
                        <?= __('user.buy_new_plan') ?>
                    </a>
                </div>
            </div>
        </div>

    </div>
    <?php else: ?>
        <!-- No plan data - just CTA -->
        <div class="text-center mt-3 card-animate visible">
            <a class="btn btn-lg btn-primary rounded-pill px-5 py-3 fw-semibold shadow btn-micro mship-cta-btn"
               href="<?= base_url('usercontrol/purchase_plan') ?>">
                <i class="bi bi-bag-plus me-2"></i><?= __('user.buy_new_plan') ?>
            </a>
        </div>
    <?php endif; ?>

</div>
