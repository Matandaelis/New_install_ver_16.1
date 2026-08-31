<div class="container-fluid py-2">
    <div class="row">
        <div class="col-12">
            <div class="card border-0 rounded-4 shadow-sm card-animate visible mship-history-card">
                <div class="card-header d-flex align-items-center gap-2 px-3 py-2">
                    <i class="bi bi-clock-history text-primary"></i>
                    <h6 class="card-title mb-0 fw-bold mship-card-title"><?= __('user.purchase_history') ?></h6>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0">
                            <thead>
                                <tr>
                                    <th class="text-nowrap px-4"><?= __('user.id') ?></th>
                                    <th class="text-nowrap"><?= __('user.plan_name') ?></th>
                                    <th class="text-nowrap"><?= __('user.price') ?></th>
                                    <th class="text-nowrap"><?= __('user.type') ?></th>
                                    <th class="text-nowrap"><?= __('user.plan_status') ?></th>
                                    <th class="text-nowrap"><?= __('user.payment_method') ?></th>
                                    <th class="text-nowrap"><?= __('user.remaining_time') ?></th>
                                    <th class="text-nowrap"><?= __('user.start_date') ?></th>
                                    <th class="text-nowrap"><?= __('user.end_date') ?></th>
                                    <th class="text-nowrap"><?= __('user.created_at') ?></th>
                                    <th class="text-nowrap"><?= __('user.action') ?></th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if (count($plans) == 0) { ?>
                                    <tr>
                                        <td colspan="11" class="text-center py-5">
                                            <div class="mship-card-subtitle">
                                                <i class="bi bi-inbox fs-1 mb-3 d-block"></i>
                                                <p class="mb-0"><?= __('user.no_records_found') ?></p>
                                            </div>
                                        </td>
                                    </tr>
                                <?php } ?>
                                <?php foreach ($plans as $key => $plan) { ?>
                                    <tr>
                                        <td class="px-4"><span class="badge bg-primary rounded-pill"><?= $plan->id ?></span></td>
                                        <td class="fw-semibold mship-card-title"><?= ($plan->plan ? $plan->plan->name : '') ?></td>
                                        <td class="text-success fw-bold"><?= c_format($plan->total) ?></td>
                                        <td>
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
                                        </td>
                                        <td><?= $plan->active_text ?></td>
                                        <td>
                                            <div class="d-flex flex-column gap-1">
                                                <span class="fw-semibold"><?= $plan->payment_method ?></span>
                                                <?php if ($plan->payment_details) { ?>
                                                    <?php $payment_details = json_decode($plan->payment_details, true); ?>
                                                    <?php if (isset($payment_details['transaction_id'])) { ?>
                                                        <small class="mship-card-subtitle">
                                                            <strong><?= __('user.transaction_id') ?>:</strong> <?= $payment_details['transaction_id'] ?>
                                                        </small>
                                                    <?php } ?>
                                                    <?php if (isset($payment_details['payment_status'])) { ?>
                                                        <span class="badge <?php if (in_array(strtolower($payment_details['payment_status']), array('completed', 'succeeded', 'success', 'complete', 'paid', 'active'))) { ?>bg-success<?php } else { ?>bg-danger<?php } ?>">
                                                            <?= ucfirst($payment_details['payment_status']) ?>
                                                        </span>
                                                    <?php } ?>
                                                <?php } ?>
                                            </div>
                                        </td>
                                        <td>
                                            <?php
                                            if ($plan->status_id == 1 && !($plan->is_lifetime)) {
                                                $strToTimeRemains = $plan->strToTimeRemains();
                                                ?>
                                                <span data-time-remains="<?= $strToTimeRemains; ?>" class="badge bg-info"><?= $plan->remainDay(); ?></span>
                                                <?php
                                            } else {
                                                echo '<span class="badge bg-secondary">' . $plan->remainDay() . '</span>';
                                            }
                                            ?>
                                        </td>
                                        <td class="text-nowrap"><?= dateFormat($plan->started_at, 'd/m/Y') ?></td>
                                        <td><?= $plan->expire_text ?></td>
                                        <td class="text-nowrap small mship-card-subtitle"><?= dateFormat($plan->created_at) ?></td>
                                        <td>
                                            <a href="<?= base_url('usercontrol/membership_purchase_details/' . $plan->id) ?>" class="btn btn-sm btn-primary rounded-pill">
                                                <i class="bi bi-eye me-1"></i><?= __('user.details') ?>
                                            </a>
                                        </td>
                                    </tr>
                                <?php } ?>
                            </tbody>
                        </table>
                    </div>
                </div>
                <?php if ($links) { ?>
                    <div class="card-footer bg-transparent border-top px-4">
                        <div class="d-flex justify-content-between align-items-center">
                            <div><?= $links[1] ?></div>
                            <div>
                                <ul class="pagination mb-0"><?= $links[0] ?></ul>
                            </div>
                        </div>
                    </div>
                <?php } ?>
            </div>
        </div>
    </div>
</div>

<script type="text/javascript">
    $(function() {
        start_plan_expiration_timer();
    });
</script>
