<?php
$is_vendor = 0;
if (isset($_SESSION) && isset($_SESSION['user']['is_vendor']) && $_SESSION['user']['is_vendor']) {
    $is_vendor = $_SESSION['user']['is_vendor'];
}
?>

<div class="container-fluid py-2">

    <!-- Page Header -->
    <div class="row mb-2">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h5 class="fw-bold mb-0 mship-card-title"><?= __('user.our_membership_plans') ?></h5>
                    <small class="mship-card-subtitle"><?= __('user.choose_the_perfect_plan_for_your_needs') ?></small>
                </div>
            </div>
        </div>
    </div>

    <!-- Current Plan Section -->
    <?php
    $current_plan_name = 'NULL';
    $current_plan_expire = 'NULL';

    if ($MembershipSetting['status'] && isset($plan) && $plan && isset($plan->plan) && $plan->plan) {
        $current_plan_name = $plan->plan->name;
        $current_plan_expire = $plan->expire_at;
    ?>
    <div class="row mb-2">
        <div class="col-12">
            <div class="card border-0 rounded-3 shadow-sm card-animate visible mship-current-plan">
                <div class="card-body px-3 py-2">
                    <?php if (isset($is_lifetime_plan) && $is_lifetime_plan) { ?>
                        <div class="d-flex align-items-center gap-3">
                            <span class="d-inline-flex align-items-center justify-content-center rounded-circle mship-icon-circle flex-shrink-0">
                                <i class="bi bi-infinity text-primary fs-5"></i>
                            </span>
                            <div>
                                <h6 class="fw-bold mb-0 mship-card-title"><?= __('user.lifetime_free_membership') ?></h6>
                                <small class="mship-card-subtitle"><?= __('user.you_have_a_lifetime') ?></small>
                            </div>
                        </div>
                    <?php } else { ?>
                        <div class="d-flex align-items-center flex-wrap gap-3">
                            <div class="d-flex align-items-center gap-2 flex-shrink-0">
                                <span class="d-inline-flex align-items-center justify-content-center rounded-circle mship-icon-circle flex-shrink-0">
                                    <i class="bi bi-crown text-primary fs-5"></i>
                                </span>
                                <div>
                                    <small class="text-uppercase fw-semibold mship-card-subtitle"><?= __('user.active_plan') ?></small>
                                    <h6 class="fw-bold mb-0 mship-card-title"><?= $plan->plan->name ?? '' ?></h6>
                                </div>
                            </div>
                            <div class="d-flex align-items-center gap-2 ms-auto flex-wrap">
                                <div class="rounded-3 px-3 py-2 text-center mship-current-stat">
                                    <small class="d-block fw-semibold mship-card-subtitle"><?= __('user.plan_date') ?></small>
                                    <span class="fw-semibold small mship-card-title">
                                        <?php
                                            $remain = $plan->remainDay();
                                            $planto = ($plan->is_lifetime) ? __('user.lifetime') : dateFormat($plan->expire_at, 'd M Y');
                                        ?>
                                        <?= dateFormat($plan->started_at, 'd M Y') . " - " . $planto ?>
                                    </span>
                                </div>
                                <div class="rounded-3 px-3 py-2 text-center mship-current-stat">
                                    <small class="d-block fw-semibold mship-card-subtitle"><?= __('user.remaining_time') ?></small>
                                    <span class="fw-bold mship-card-title">
                                        <?php
                                            if ($plan->is_lifetime) {
                                                echo '<span class="fs-5 lh-1">&infin;</span>';
                                            } else {
                                                echo "<span data-time-remains='" . $plan->strToTimeRemains() . "' class='fw-bold'>" . $remain . "</span>";
                                            }
                                        ?>
                                    </span>
                                </div>
                                <?php if (!empty($plan->plan->description)): ?>
                                <button class="btn btn-sm btn-outline-primary rounded-pill px-3" type="button" data-bs-toggle="collapse" data-bs-target="#planDescription">
                                    <i class="bi bi-info-circle"></i>
                                </button>
                                <?php endif; ?>
                            </div>
                        </div>
                        <?php if (!empty($plan->plan->description)): ?>
                        <div class="collapse mt-2" id="planDescription">
                            <div class="rounded-3 p-2 mship-plan-features-box small mship-card-subtitle"><?= $plan->plan->description ?? '' ?></div>
                        </div>
                        <?php endif; ?>
                    <?php } ?>
                </div>
            </div>
        </div>
    </div>
    <?php } else { ?>
    <div class="row mb-2">
        <div class="col-12">
            <div class="card border-0 rounded-3 shadow-sm card-animate visible mship-no-plan">
                <div class="card-body px-3 py-2 d-flex align-items-center gap-3">
                    <span class="d-inline-flex align-items-center justify-content-center rounded-circle flex-shrink-0 mship-icon-circle">
                        <i class="bi bi-info-circle text-primary fs-5"></i>
                    </span>
                    <div>
                        <h6 class="fw-semibold mb-0 mship-card-title"><?= __('user.no_active_plan') ?></h6>
                        <small class="mship-card-subtitle"><?= __('user.currently_not_have_paid_plan') ?></small>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <?php } ?>

    <!-- Membership Plans -->
    <?php
        $visiblePlans = [];
        foreach ($plans as $p) {
            if ($is_vendor) { if ($p->user_type == '2') $visiblePlans[] = $p; }
            else { $visiblePlans[] = $p; }
        }
        $planCount = count($visiblePlans);
        $isWide    = ($planCount <= 2);
    ?>

    <div class="row g-3">
    <?php foreach ($visiblePlans as $key => $planItem) {
        if ($is_vendor) {
            if ($planItem->name == $current_plan_name) { $plan_status = '1'; $current_plan_expire_var = $current_plan_expire; }
            else { $plan_status = '0'; $current_plan_expire_var = '0'; }
        } else {
            if ($planItem->name == $current_plan_name) { $plan_status = '1'; $current_plan_expire_var = $current_plan_expire; }
            else if ($current_plan_expire != "" or $current_plan_expire != null) { $plan_status = '1'; $current_plan_expire_var = $current_plan_expire; }
            else { $plan_status = '0'; $current_plan_expire_var = '0'; }
        }
        $planPrice = $planItem->special ? $planItem->special : $planItem->price;
        $expireVal = $is_vendor ? $current_plan_expire_var : strtotime($current_plan_expire_var);
        $colClass  = $isWide ? ($planCount == 1 ? 'col-12' : 'col-lg-6') : 'col-xl-4 col-lg-6';
    ?>

    <?php if ($isWide): ?>
        <!-- ====== WIDE HORIZONTAL PLAN CARD (1-2 plans) ====== -->
        <div class="<?= $colClass ?>">
            <div class="card border-0 rounded-4 shadow-sm mship-plan-card position-relative plan-card card-animate visible h-100">
                <?php if ($planItem->label_text) { ?>
                    <div class="position-absolute top-0 end-0 m-2 mship-plan-label">
                        <span class="badge rounded-pill px-2 py-1 text-white small"
                              style="background-color:<?= $planItem->label_background ?>; color:<?= $planItem->label_color ?> !important;">
                            <?= $planItem->label_text ?>
                        </span>
                    </div>
                <?php } ?>
                <div class="card-body p-0">
                    <div class="row g-0 h-100">
                        <!-- Price Column -->
                        <div class="col-md-3 d-flex">
                            <div class="mship-wide-price-col d-flex flex-column align-items-center justify-content-center text-center p-3 w-100 rounded-start-4">
                                <h5 class="fw-bold text-primary mb-1"><?= $planItem->name ?></h5>
                                <?php if ($planItem->price == 0) { ?>
                                    <span class="mship-plan-price fw-bold text-success"><?= __('user.free') ?></span>
                                <?php } else { ?>
                                    <?php if ($planItem->special) { ?>
                                        <span class="mship-plan-price fw-bold text-primary"><?= c_format($planItem->special) ?></span>
                                        <div>
                                            <span class="text-decoration-line-through text-muted me-1 small"><?= c_format($planItem->price) ?></span>
                                            <?php $percentage = round((($planItem->price - $planItem->special) * 100) / $planItem->price); ?>
                                            <span class="badge bg-success small"><?= __('user.save') ?> <?= $percentage ?>%</span>
                                        </div>
                                    <?php } else { ?>
                                        <span class="mship-plan-price fw-bold text-primary"><?= c_format($planItem->price) ?></span>
                                    <?php } ?>
                                <?php } ?>
                                <small class="text-uppercase fw-semibold mship-plan-period mship-card-subtitle mt-1">
                                    <?php
                                        if ($planItem->billing_period == "lifetime_free") { echo __('user.lifetime'); }
                                        else if ($planItem->billing_period == "custom") { echo $planItem->custom_period . " " . __('user.days'); }
                                        else {
                                            $bp = strtolower($planItem->billing_period);
                                            if ($bp == 'monthly') echo __('user.monthly');
                                            elseif ($bp == 'yearly') echo __('user.yearly');
                                            elseif ($bp == 'weekly') echo __('user.weekly');
                                            elseif ($bp == 'daily') echo __('user.daily');
                                            else echo ucwords($bp);
                                        }
                                    ?>
                                </small>
                                <?php if ($planItem->bonus) { ?>
                                    <div class="d-flex align-items-center gap-1 mt-2 p-2 rounded-3 mship-bonus-box small">
                                        <i class="bi bi-gift text-warning"></i>
                                        <strong><?= __('user.bonus_rate') ?></strong>
                                        <span class="fw-bold text-warning ms-auto"><?= c_format($planItem->bonus) ?></span>
                                    </div>
                                <?php } ?>
                            </div>
                        </div>

                        <!-- Features Column -->
                        <div class="col-md-6 d-flex">
                            <div class="p-3 border-start border-end w-100 d-flex flex-column mship-wide-features-col">
                                <?php if ($planItem->commission_sale_status): ?>
                                    <?php $sale_comission_rate = ($planItem->sale_comission_rate) ? $planItem->sale_comission_rate . '%' : __('user.default') ?>
                                    <div class="alert alert-light border-0 mb-2 py-1 px-2 rounded-3 small">
                                        <i class="bi bi-percent me-1"></i>
                                        <strong><?= __('user.affiliate_commission') ?>:</strong> <?= $sale_comission_rate ?>
                                    </div>
                                <?php endif ?>

                                <?php if ($planItem->user_type == 2): ?>
                                    <div class="d-flex gap-2 mb-2">
                                        <div class="text-center p-1 rounded-3 mship-plan-features-box flex-fill">
                                            <div class="fw-bold text-primary small"><?= isset($planItem->campaign) ? $planItem->campaign : '&infin;' ?></div>
                                            <small class="mship-card-subtitle mship-text-xs"><?= __('user.campaigns') ?></small>
                                        </div>
                                        <div class="text-center p-1 rounded-3 mship-plan-features-box flex-fill">
                                            <div class="fw-bold text-primary small"><?= isset($planItem->product) ? $planItem->product : '&infin;' ?></div>
                                            <small class="mship-card-subtitle mship-text-xs"><?= __('user.products') ?></small>
                                        </div>
                                    </div>
                                <?php endif ?>

                                <div class="mship-plan-desc-wide small flex-grow-1">
                                    <?= $planItem->description ?>
                                </div>
                            </div>
                        </div>

                        <!-- Action Column -->
                        <div class="col-md-3 d-flex">
                            <div class="p-3 d-flex flex-column align-items-center justify-content-center text-center w-100 gap-2">
                                <span class="d-inline-flex align-items-center justify-content-center rounded-circle mship-icon-circle mb-1">
                                    <i class="bi bi-cart-plus text-primary fs-5"></i>
                                </span>
                                <button class="btn btn-primary fw-semibold rounded-pill mship-plan-btn w-100 py-2"
                                        onclick="choosePlan(<?= $planItem->id ?>, <?= $planPrice ?>, <?= $plan_status ?>, '<?= $expireVal ?>')">
                                    <?= __('user.purchase_now') ?>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    <?php else: ?>
        <!-- ====== GRID VERTICAL PLAN CARD (3+ plans) ====== -->
        <div class="<?= $colClass ?>">
            <div class="card h-100 border-0 rounded-4 shadow-sm mship-plan-card position-relative plan-card card-animate visible">
                <?php if ($planItem->label_text) { ?>
                    <div class="position-absolute top-0 end-0 m-2 mship-plan-label">
                        <span class="badge rounded-pill px-2 py-1 text-white small"
                              style="background-color:<?= $planItem->label_background ?>; color:<?= $planItem->label_color ?> !important;">
                            <?= $planItem->label_text ?>
                        </span>
                    </div>
                <?php } ?>

                <div class="card-header border-0 text-center pt-3 pb-0">
                    <h6 class="fw-bold text-primary mb-1"><?= $planItem->name ?></h6>
                    <div class="mb-1">
                        <?php if ($planItem->price == 0) { ?>
                            <span class="mship-plan-price fw-bold text-success"><?= __('user.free') ?></span>
                        <?php } else { ?>
                            <?php if ($planItem->special) { ?>
                                <span class="mship-plan-price fw-bold text-primary"><?= c_format($planItem->special) ?></span>
                                <div>
                                    <span class="text-decoration-line-through text-muted me-1 small"><?= c_format($planItem->price) ?></span>
                                    <?php $percentage = round((($planItem->price - $planItem->special) * 100) / $planItem->price); ?>
                                    <span class="badge bg-success small"><?= __('user.save') ?> <?= $percentage ?>%</span>
                                </div>
                            <?php } else { ?>
                                <span class="mship-plan-price fw-bold text-primary"><?= c_format($planItem->price) ?></span>
                            <?php } ?>
                        <?php } ?>
                        <div class="mship-card-subtitle">
                            <small class="text-uppercase fw-semibold mship-plan-period">
                                <?php
                                    if ($planItem->billing_period == "lifetime_free") { echo __('user.lifetime'); }
                                    else if ($planItem->billing_period == "custom") { echo $planItem->custom_period . " " . __('user.days'); }
                                    else {
                                        $bp = strtolower($planItem->billing_period);
                                        if ($bp == 'monthly') echo __('user.monthly');
                                        elseif ($bp == 'yearly') echo __('user.yearly');
                                        elseif ($bp == 'weekly') echo __('user.weekly');
                                        elseif ($bp == 'daily') echo __('user.daily');
                                        else echo ucwords($bp);
                                    }
                                ?>
                            </small>
                        </div>
                    </div>
                </div>

                <div class="card-body px-3 pt-2 pb-1">
                    <?php if ($planItem->commission_sale_status): ?>
                        <?php $sale_comission_rate = ($planItem->sale_comission_rate) ? $planItem->sale_comission_rate . '%' : __('user.default') ?>
                        <div class="alert alert-light border-0 mb-2 py-1 px-2 rounded-3 small">
                            <i class="bi bi-percent me-1"></i>
                            <strong><?= __('user.affiliate_commission') ?>:</strong> <?= $sale_comission_rate ?>
                        </div>
                    <?php endif ?>

                    <?php if ($planItem->user_type == 2): ?>
                        <div class="mb-2">
                            <div class="row g-2">
                                <div class="col-6">
                                    <div class="text-center p-1 rounded-3 mship-plan-features-box">
                                        <div class="fw-bold text-primary small"><?= isset($planItem->campaign) ? $planItem->campaign : '&infin;' ?></div>
                                        <small class="mship-card-subtitle mship-text-xs"><?= __('user.campaigns') ?></small>
                                    </div>
                                </div>
                                <div class="col-6">
                                    <div class="text-center p-1 rounded-3 mship-plan-features-box">
                                        <div class="fw-bold text-primary small"><?= isset($planItem->product) ? $planItem->product : '&infin;' ?></div>
                                        <small class="mship-card-subtitle mship-text-xs"><?= __('user.products') ?></small>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <?php endif ?>

                    <div class="mship-plan-desc mb-2 small">
                        <?= $planItem->description ?>
                    </div>

                    <?php if ($planItem->bonus) { ?>
                        <div class="d-flex align-items-center justify-content-between p-2 rounded-3 mship-bonus-box mb-1 small">
                            <div>
                                <i class="bi bi-gift text-warning me-1"></i>
                                <strong><?= __('user.bonus_rate') ?></strong>
                            </div>
                            <span class="fw-bold text-warning"><?= c_format($planItem->bonus) ?></span>
                        </div>
                    <?php } ?>
                </div>

                <div class="card-footer border-0 px-3 pb-3 pt-0">
                    <button class="btn btn-primary btn-lg w-100 fw-semibold rounded-pill mship-plan-btn"
                            onclick="choosePlan(<?= $planItem->id ?>, <?= $planPrice ?>, <?= $plan_status ?>, '<?= $expireVal ?>')">
                        <i class="bi bi-cart-plus me-2"></i><?= __('user.purchase_now') ?>
                    </button>
                </div>
            </div>
        </div>
    <?php endif; ?>

    <?php } ?>
    </div>
</div>

<!-- Payment Method Modal -->
<div class="modal fade mship-modal" id="model-payments" tabindex="-1" aria-labelledby="paymentModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 rounded-4 shadow-lg overflow-hidden">
            <div class="modal-header mship-checkout-header border-0 px-4 py-3">
                <h5 class="modal-title text-white" id="paymentModalLabel">
                    <i class="bi bi-credit-card me-2"></i><?= __('user.choose_payment_method') ?>
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-0">
                <?php if (!empty($payment_gateways)) { ?>
                    <div class="list-group list-group-flush">
                        <?php foreach ($payment_gateways as $key => $value) { ?>
                            <a href="javascript:void(0)" onclick="buy('<?= $value['name'] ?>')"
                               class="list-group-item list-group-item-action d-flex align-items-center p-3">
                                <i class="bi bi-wallet2 me-3 text-primary fs-5"></i>
                                <span class="fw-semibold"><?= $value['title'] ?></span>
                                <i class="bi bi-chevron-right ms-auto mship-card-subtitle"></i>
                            </a>
                        <?php } ?>
                    </div>
                <?php } else { ?>
                    <div class="p-4 text-center">
                        <i class="bi bi-exclamation-triangle text-warning fs-1 mb-3 d-block"></i>
                        <h6 class="mb-2"><?= __('user.no_payment_method_available') ?></h6>
                        <p class="mship-card-subtitle mb-3"><?= __('user.please_contact_administrator') ?></p>
                        <a href="<?= base_url('usercontrol/contact-us') ?>" class="btn btn-primary rounded-pill">
                            <i class="bi bi-envelope me-2"></i><?= __('user.contact_admin') ?>
                        </a>
                    </div>
                <?php } ?>
                <div class="alert-error-message m-3"></div>
            </div>
        </div>
    </div>
</div>

<!-- Free Plan Modal -->
<div class="modal fade mship-modal" id="model-free-plan" tabindex="-1" aria-labelledby="freePlanModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 rounded-4 shadow-lg overflow-hidden">
            <div class="modal-header bg-success border-0 px-4 py-3">
                <h5 class="modal-title text-white" id="freePlanModalLabel">
                    <i class="bi bi-check-circle me-2"></i><?= __('user.membership_notification_alert') ?>
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body text-center p-4">
                <i class="bi bi-gift text-success fs-1 mb-3 d-block"></i>
                <h6 class="mb-3"><?= __('user.register_for_free_plan') ?></h6>
                <div class="alert alert-light rounded-3">
                    <strong><?= __('user.plan_end_at') ?>:</strong><br>
                    <span class="text-primary"><?php echo isset($planto) ? $planto : ''; ?></span>
                </div>
            </div>
            <div class="modal-footer justify-content-center border-0">
                <button type="button" class="btn btn-outline-secondary rounded-pill" data-bs-dismiss="modal">
                    <i class="bi bi-x-lg me-2"></i><?= __('user.close') ?>
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Pending Plan Modal -->
<div class="modal fade mship-modal" id="model-pending-plan" tabindex="-1" aria-labelledby="pendingPlanModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 rounded-4 shadow-lg overflow-hidden">
            <div class="modal-header bg-warning border-0 px-4 py-3">
                <h5 class="modal-title text-dark" id="pendingPlanModalLabel">
                    <i class="bi bi-clock me-2"></i><?= __('user.membership_notification_alert') ?>
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-4">
                <div class="text-center mb-3">
                    <i class="bi bi-hourglass-split text-warning fs-1"></i>
                </div>
                <h6 class="text-warning text-center mb-3"><?= __('user.pending_plan_warning') ?></h6>
                <div class="row g-3">
                    <div class="col-12">
                        <div class="rounded-3 p-3 mship-plan-features-box">
                            <strong><?= __('user.id') ?>:</strong> <span id="pendingplan_id" class="text-primary"></span>
                        </div>
                    </div>
                    <div class="col-12">
                        <div class="rounded-3 p-3 mship-plan-features-box">
                            <strong><?= __('user.plan_name') ?>:</strong> <span id="pendingplan_name" class="text-primary"></span>
                        </div>
                    </div>
                    <div class="col-12">
                        <div class="rounded-3 p-3 mship-plan-features-box">
                            <strong><?= __('user.payment_status') ?>:</strong> <span id="pendingplan_status" class="badge bg-warning"></span>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer justify-content-center border-0">
                <button type="button" class="btn btn-outline-secondary rounded-pill" data-bs-dismiss="modal">
                    <i class="bi bi-x-lg me-2"></i><?= __('user.close') ?>
                </button>
            </div>
        </div>
    </div>
</div>

<script type="text/javascript">
var plan_id = 0;
var userCurrency = '<?= $this->session->userdata('userCurrency'); ?>';

function buy(paymentGateway){
    let paystackAccptCurrencies = ['GHS' , 'NGN', 'USD', 'ZAR' , 'KES'];
    if(paymentGateway == 'paystack' && ! paystackAccptCurrencies.includes(userCurrency)){
        $('.alert-error-message').html('<div class="alert alert-danger"><i class="bi bi-exclamation-triangle me-2"></i>' + '<?= __('user.paystack_accept_only_currency') ?>' + '</div>');
        return false;
    }
    if (paymentGateway == 'xendit' && (userCurrency != 'IDR' && userCurrency != 'PHP')) {
        $('.alert-error-message').html('<div class="alert alert-danger"><i class="bi bi-exclamation-triangle me-2"></i>' + '<?= __('user.xendit_accept_only_currency') ?>' + '</div>');
        return false;
    }
    if(paymentGateway == 'yookassa' && userCurrency != 'RUB'){
        $('.alert-error-message').html('<div class="alert alert-danger"><i class="bi bi-exclamation-triangle me-2"></i>' + '<?= __('user.yookassa_accept_only_currency') ?>' + '</div>');
        return false;
    }
    $('.alert-error-message').empty();
    window.location.href = "<?= base_url('membership/buy_membership/') ?>" + plan_id + '/' + paymentGateway;
}

function choosePlan(pID, price, plan_status, plan_expire) {
    var today = new Date();
    var datetime = Date.parse(today) / 1000;

    if (price > 0) {
        plan_id = pID;
        $.ajax({
            url: '<?= base_url("membership/checkPendingPlan") ?>',
            type: 'POST',
            dataType: 'json',
            data: { 'action': 'pendingplan' },
            success: function (json) {
                if (json.status == 'true') {
                    $("#model-payments").modal("show");
                } else if (json.status == 'no_plan') {
                    $("#model-payments").modal("show");
                } else if (json.plan_id != '') {
                    $("#pendingplan_id").html(json.plan_id);
                    $("#pendingplan_name").html(json.name);
                    $("#pendingplan_status").html(json.payment_status);
                    $("#model-pending-plan").modal("show");
                } else {
                    var message = '<?= __("user.something_went_wrong_please_try_again") ?>';
                    Swal.fire({
                        icon: 'error',
                        text: message,
                    });
                }
            },
        });
    } else {
        if (plan_status == '1') {
            if (datetime > plan_expire) {
                window.location.href = "<?= base_url('membership/buy_membership') ?>/" + pID;
            } else {
                $("#model-free-plan").modal("show");
            }
        } else {
            window.location.href = "<?= base_url('membership/buy_membership') ?>/" + pID;
        }
    }
}

$(document).ready(function() {
    document.querySelectorAll('.card-animate').forEach(function(el, i) {
        setTimeout(function() { el.classList.add('visible'); }, i * 80);
    });
});
</script>
