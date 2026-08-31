<?php
$payment_details = json_decode($plan->payment_details);
?>
<div class="container-fluid py-2">
    <div class="row g-3 mb-2">

        <!-- Purchase Details Card -->
        <div class="col-lg-6">
            <div class="card border-0 rounded-4 shadow-sm h-100 card-animate visible mship-detail-card">
                <div class="card-header d-flex align-items-center gap-2 px-3 py-2">
                    <i class="bi bi-receipt text-primary"></i>
                    <h6 class="card-title mb-0 fw-bold mship-card-title"><?= __('user.purchase_details') ?></h6>
                </div>
                <ul class="list-group list-group-flush">
                    <li class="list-group-item d-flex justify-content-between align-items-center px-3">
                        <span class="mship-card-subtitle"><i class="bi bi-hash me-2 text-primary"></i><?= __('user.id') ?></span>
                        <span class="badge bg-primary rounded-pill"><?= $plan->id ?></span>
                    </li>
                    <li class="list-group-item d-flex justify-content-between align-items-center px-3">
                        <span class="mship-card-subtitle"><i class="bi bi-tag me-2 text-primary"></i><?= __('user.plan_name') ?></span>
                        <span class="fw-bold mship-card-title"><?= ($plan->plan ? $plan->plan->name : '') ?></span>
                    </li>
                    <li class="list-group-item d-flex justify-content-between align-items-center px-3">
                        <span class="mship-card-subtitle"><i class="bi bi-currency-dollar me-2 text-primary"></i><?= __('user.price') ?></span>
                        <span class="fw-bold text-success"><?= c_format(($plan->plan ? $plan->plan->price : 0)) ?></span>
                    </li>
                    <li class="list-group-item d-flex justify-content-between align-items-center px-3">
                        <span class="mship-card-subtitle"><i class="bi bi-percent me-2 text-primary"></i><?= __('user.special_price') ?></span>
                        <span class="fw-bold text-success"><?= c_format(($plan->plan ? $plan->plan->special : 0)) ?></span>
                    </li>
                    <?php
                    $bonus = $plan->bonusData();
                    if ($bonus) {
                    ?>
                    <li class="list-group-item d-flex justify-content-between align-items-center px-3">
                        <span class="mship-card-subtitle"><i class="bi bi-gift me-2 text-primary"></i><?= __('user.bonus') ?></span>
                        <span class="badge bg-warning text-dark"><?= c_format($bonus->amount) ?></span>
                    </li>
                    <?php } else { ?>
                    <li class="list-group-item d-flex justify-content-between align-items-center px-3">
                        <span class="mship-card-subtitle"><i class="bi bi-gift me-2 text-primary"></i><?= __('user.bonus') ?></span>
                        <span class="badge bg-secondary"><?= __('user.no_bonus') ?></span>
                    </li>
                    <?php } ?>
                    <li class="list-group-item d-flex justify-content-between align-items-center px-3">
                        <span class="mship-card-subtitle"><i class="bi bi-info-circle me-2 text-primary"></i><?= __('user.type') ?></span>
                        <span class="badge <?php if ($plan->plan && $plan->plan->type == 'paid') { ?>bg-success<?php } else { ?>bg-info<?php } ?>">
                            <?php
                            if ($plan->plan) {
                                if ($plan->plan->type == 'paid') {
                                    echo __('user.paid');
                                } elseif ($plan->plan->type == 'free') {
                                    echo __('user.free');
                                } else {
                                    echo $plan->plan->type;
                                }
                            } else {
                                echo '';
                            }
                            ?>
                        </span>
                    </li>
                    <li class="list-group-item d-flex justify-content-between align-items-center px-3">
                        <span class="mship-card-subtitle"><i class="bi bi-calendar-check me-2 text-primary"></i><?= __('user.free_trail') ?></span>
                        <span class="fw-bold mship-card-title">
                            <?php
                            if ($plan->plan->have_trail > 0) {
                                echo $plan->plan->free_trail . " " . __('user.days');
                            } else {
                                echo '0' . " " . __('user.days');
                            }
                            ?>
                        </span>
                    </li>
                    <?php if (isset($payment_details)) { ?>
                    <li class="list-group-item d-flex justify-content-between align-items-center px-3">
                        <span class="mship-card-subtitle"><i class="bi bi-check-circle me-2 text-primary"></i><?= __('user.payment_status') ?></span>
                        <span class="badge bg-success"><?= $payment_details->payment_status ?></span>
                    </li>
                    <?php } ?>
                    <li class="list-group-item d-flex justify-content-between align-items-center px-3">
                        <span class="mship-card-subtitle"><i class="bi bi-credit-card me-2 text-primary"></i><?= __('user.payment_method') ?></span>
                        <span class="fw-bold mship-card-title"><?= $plan->payment_method ?></span>
                    </li>
                    <?php if ($plan->status_id == 1) { ?>
                        <?php if (!$plan->is_lifetime) { ?>
                        <li class="list-group-item d-flex justify-content-between align-items-center px-3">
                            <span class="mship-card-subtitle"><i class="bi bi-clock me-2 text-primary"></i><?= __('user.remaining_time') ?></span>
                            <span class="badge bg-info" data-time-remains="<?= $plan->strToTimeRemains(); ?>"><?= $plan->remainDay() ?></span>
                        </li>
                        <?php } ?>
                        <li class="list-group-item d-flex justify-content-between align-items-center px-3">
                            <span class="mship-card-subtitle"><i class="bi bi-play-circle me-2 text-primary"></i><?= __('user.started_on') ?></span>
                            <span class="fw-bold mship-card-title"><?= dateFormat($plan->started_at, 'd F Y, h:i A'); ?></span>
                        </li>
                        <?php if (!$plan->is_lifetime) { ?>
                        <li class="list-group-item d-flex justify-content-between align-items-center px-3">
                            <span class="mship-card-subtitle"><i class="bi bi-stop-circle me-2 text-primary"></i><?= __('user.ending_on') ?></span>
                            <span class="fw-bold text-danger"><?= dateFormat($plan->expire_at, 'd F Y, h:i A'); ?></span>
                        </li>
                        <?php } ?>
                    <?php } else if ($plan->payment_method == "Bank Transfer") { ?>
                        <li class="list-group-item d-flex justify-content-between align-items-center px-3">
                            <span class="mship-card-subtitle"><i class="bi bi-calendar-range me-2 text-primary"></i><?= __('user.plan_date') ?></span>
                            <span class="fw-bold mship-card-title"><?= dateFormat($plan->started_at, 'd F Y') . " to " . $plan->expire_text ?></span>
                        </li>
                    <?php } ?>
                    <?php if (!empty($plan->payment_details) && $plan->payment_details != "[]") {
                        $payment_details = json_decode($plan->payment_details);
                        foreach ($payment_details as $key => $value) {
                            if ($key == 'payment_proof') {
                                ?>
                                <li class="list-group-item d-flex justify-content-between align-items-center px-3">
                                    <span class="mship-card-subtitle"><i class="bi bi-file-earmark me-2 text-primary"></i><?= __('user.payment_proof') ?></span>
                                    <a target="_blank" href="<?php echo base_url('assets/user_upload/' . $value) ?>" class="btn btn-sm btn-outline-primary rounded-pill">
                                        <i class="bi bi-download me-1"></i><?php echo $value; ?>
                                    </a>
                                </li>
                                <?php
                            }
                        }
                    } ?>
                    <li class="list-group-item d-flex justify-content-between align-items-center px-3">
                        <span class="mship-card-subtitle"><i class="bi bi-calendar-plus me-2 text-primary"></i><?= __('user.created_at') ?></span>
                        <span class="small mship-card-subtitle"><?= dateFormat($plan->created_at, 'd F Y, h:i A') ?></span>
                    </li>
                </ul>
            </div>
        </div>

        <!-- Plan Details Card -->
        <div class="col-lg-6">
            <div class="card border-0 rounded-4 shadow-sm h-100 card-animate visible mship-detail-card mship-delay-1">
                <div class="card-header d-flex align-items-center gap-2 px-3 py-2">
                    <i class="bi bi-list-ul text-success"></i>
                    <h6 class="card-title mb-0 fw-bold mship-card-title"><?= __('user.plan_details') ?></h6>
                </div>
                <ul class="list-group list-group-flush">
                    <li class="list-group-item d-flex justify-content-between align-items-center px-3">
                        <span class="mship-card-subtitle"><i class="bi bi-tag me-2 text-success"></i><?= __('user.name') ?></span>
                        <span class="fw-bold mship-card-title"><?= $plan->plan->name ?></span>
                    </li>
                    <li class="list-group-item d-flex justify-content-between align-items-center px-3">
                        <span class="mship-card-subtitle"><i class="bi bi-info-circle me-2 text-success"></i><?= __('user.type') ?></span>
                        <span class="badge <?php if ($plan->plan && $plan->plan->type == 'paid') { ?>bg-success<?php } else { ?>bg-info<?php } ?>">
                            <?php
                            if ($plan->plan) {
                                if ($plan->plan->type == 'paid') {
                                    echo __('user.paid');
                                } elseif ($plan->plan->type == 'free') {
                                    echo __('user.free');
                                } else {
                                    echo $plan->plan->type;
                                }
                            } else {
                                echo '';
                            }
                            ?>
                        </span>
                    </li>
                    <li class="list-group-item d-flex justify-content-between align-items-center px-3">
                        <span class="mship-card-subtitle"><i class="bi bi-currency-dollar me-2 text-success"></i><?= __('user.price') ?></span>
                        <span class="fw-bold text-success"><?= c_format(($plan->plan ? $plan->plan->price : 0)) ?></span>
                    </li>
                    <li class="list-group-item d-flex justify-content-between align-items-center px-3">
                        <span class="mship-card-subtitle"><i class="bi bi-percent me-2 text-success"></i><?= __('user.special_price') ?></span>
                        <span class="fw-bold text-success"><?= c_format(($plan->plan ? $plan->plan->special : 0)) ?></span>
                    </li>
                    <?php if ($plan->commission_sale_status): ?>
                    <li class="list-group-item d-flex justify-content-between align-items-center px-3">
                        <span class="mship-card-subtitle"><i class="bi bi-layers me-2 text-success"></i><?= __('user.level') ?></span>
                        <span class="badge bg-primary"><?= ($plan->level_number) ? $plan->level_number : __('user.default') ?></span>
                    </li>
                    <?php endif ?>
                    <li class="list-group-item d-flex justify-content-between align-items-center px-3">
                        <span class="mship-card-subtitle"><i class="bi bi-person me-2 text-success"></i><?= __('user.user_type') ?></span>
                        <span class="badge <?php if ($plan->plan->user_type == 2) { ?>bg-warning text-dark<?php } else { ?>bg-info<?php } ?>">
                            <?= ($plan->plan->user_type == 2) ? __('user.vendor') : __('user.affiliate') ?>
                        </span>
                    </li>
                    <?php if ($plan->plan->user_type == 2): ?>
                    <li class="list-group-item d-flex justify-content-between align-items-center px-3">
                        <span class="mship-card-subtitle"><i class="bi bi-megaphone me-2 text-success"></i><?= __('user.campaign') ?></span>
                        <span class="badge bg-primary"><?= isset($plan->plan->campaign) ? $plan->plan->campaign : '&infin;' ?></span>
                    </li>
                    <li class="list-group-item d-flex justify-content-between align-items-center px-3">
                        <span class="mship-card-subtitle"><i class="bi bi-box me-2 text-success"></i><?= __('user.product') ?></span>
                        <span class="badge bg-primary"><?= isset($plan->plan->product) ? $plan->plan->product : '&infin;' ?></span>
                    </li>
                    <?php endif ?>
                    <li class="list-group-item px-3">
                        <div class="d-flex align-items-start">
                            <i class="bi bi-text-left me-2 text-success mt-1"></i>
                            <div>
                                <strong class="d-block mb-2 mship-card-title"><?= __('user.description') ?></strong>
                                <div class="mship-card-subtitle"><?= $plan->plan->description ?></div>
                            </div>
                        </div>
                    </li>
                </ul>
            </div>
        </div>

        <!-- Status History Card -->
        <div class="col-12">
            <div class="card border-0 rounded-4 shadow-sm card-animate visible mship-history-card mship-delay-2">
                <div class="card-header d-flex align-items-center gap-2 px-3 py-2">
                    <i class="bi bi-clock-history text-info"></i>
                    <h6 class="card-title mb-0 fw-bold mship-card-title"><?= __('user.status_history') ?></h6>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0">
                            <thead>
                                <tr>
                                    <th class="text-nowrap px-4"><i class="bi bi-info-circle me-2"></i><?= __('user.status') ?></th>
                                    <th class="text-nowrap px-4"><i class="bi bi-chat-left-text me-2"></i><?= __('user.note') ?></th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($history as $key => $value) { ?>
                                    <tr>
                                        <td class="px-4"><?= $value->status_text ?></td>
                                        <td class="px-4 mship-card-subtitle"><?= $value->comment ?></td>
                                    </tr>
                                <?php } ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

    </div>
</div>

<script type="text/javascript">
    $(function() {
        start_plan_expiration_timer();
    });
</script>
