<div class="container-fluid py-2">
    <div class="row justify-content-center">
        <div class="col-12">

            <!-- Page Header -->
            <div class="text-center mb-2 card-animate visible">
                <h5 class="fw-bold mship-card-title mb-0"><?= __('user.membership_purchase') ?></h5>
                <small class="mship-card-subtitle"><?= __('user.complete_your_purchase') ?></small>
            </div>

            <!-- Checkout Card -->
            <div class="card border-0 rounded-4 shadow-sm card-animate visible mship-checkout-card mship-delay-1">
                <div class="card-header mship-checkout-header rounded-top-4 border-0 py-2 px-4">
                    <div class="d-flex align-items-center justify-content-center gap-2">
                        <i class="bi bi-crown fs-5"></i>
                        <div class="text-center">
                            <h6 class="mb-0 text-white"><?= $plan->name ?></h6>
                            <small class="opacity-75"><?= __('user.selected_plan') ?></small>
                        </div>
                    </div>
                </div>

                <div class="card-body p-3">
                    <div class="row g-3 align-items-stretch">

                        <!-- Plan Pricing Section -->
                        <div class="col-md-4">
                            <div class="rounded-3 p-3 text-center mship-remain-box h-100 d-flex flex-column justify-content-center">
                                <?php if ($plan->price == 0) { ?>
                                    <div class="mship-plan-price fw-bold text-success mb-1"><?= __('user.free') ?></div>
                                <?php } else { ?>
                                    <?php if ($plan->special) { ?>
                                        <div class="mship-plan-price fw-bold text-primary mb-1"><?= c_format($plan->special) ?></div>
                                        <div class="mb-2">
                                            <span class="text-decoration-line-through text-muted me-2"><?= c_format($plan->price) ?></span>
                                            <?php $percentage = round((($plan->price - $plan->special) * 100) / $plan->price); ?>
                                            <span class="badge bg-success"><?= __('user.save') ?> <?= $percentage ?>%</span>
                                        </div>
                                    <?php } else { ?>
                                        <div class="mship-plan-price fw-bold text-primary mb-1"><?= c_format($plan->price) ?></div>
                                    <?php } ?>
                                <?php } ?>

                                <div class="mship-card-subtitle">
                                    <small class="text-uppercase fw-semibold mship-plan-period">
                                        <?php
                                            if ($plan->billing_period == "lifetime_free") {
                                                echo __('user.lifetime');
                                            } else if ($plan->billing_period == "custom") {
                                                echo $plan->custom_period . " " . __('user.days');
                                            } else {
                                                if (strtolower($plan->billing_period) == 'monthly') {
                                                    echo __('user.monthly');
                                                } elseif (strtolower($plan->billing_period) == 'yearly') {
                                                    echo __('user.yearly');
                                                } elseif (strtolower($plan->billing_period) == 'weekly') {
                                                    echo __('user.weekly');
                                                } elseif (strtolower($plan->billing_period) == 'daily') {
                                                    echo __('user.daily');
                                                } else {
                                                    echo ucwords(strtolower($plan->billing_period));
                                                }
                                            }
                                        ?>
                                    </small>
                                </div>

                                <?php if ($plan->bonus) { ?>
                                    <div class="mt-3 d-flex align-items-center justify-content-between p-2 rounded-3 mship-bonus-box">
                                        <div>
                                            <i class="bi bi-gift text-warning me-1"></i>
                                            <strong class="small"><?= __('user.bonus_rate') ?></strong>
                                        </div>
                                        <span class="fw-bold text-warning"><?= c_format($plan->bonus) ?></span>
                                    </div>
                                <?php } ?>
                            </div>
                        </div>

                        <!-- Plan Features -->
                        <div class="col-md-4">
                            <small class="fw-semibold text-primary d-block mb-1">
                                <i class="bi bi-list-check me-1"></i><?= __('user.plan_features') ?>
                            </small>
                            <div class="rounded-3 p-2 small mship-plan-features-box h-100">
                                <?= $plan->description ?>
                            </div>
                        </div>

                        <!-- Payment Section -->
                        <div class="col-md-4">
                            <small class="fw-semibold text-primary d-block mb-1">
                                <i class="bi bi-credit-card me-1"></i><?= __('user.payment_details') ?>
                            </small>
                            <div class="payment-module-membership">
                                <?php
                                    if (isset($confirm))
                                        echo $confirm;
                                ?>
                            </div>
                        </div>

                    </div>
                </div>
            </div>

            <!-- Back Button -->
            <div class="text-center mt-2 card-animate visible mship-delay-2">
                <button type="button" class="btn btn-sm btn-outline-secondary rounded-pill px-3" onclick="backCheckout()">
                    <i class="bi bi-arrow-left me-1"></i><?= __('user.back_to_plans') ?>
                </button>
            </div>
        </div>
    </div>
</div>

<script type="text/javascript">
    function backCheckout() {
        window.location.href = '<?= base_url('usercontrol/purchase_plan') ?>';
    }
</script>
