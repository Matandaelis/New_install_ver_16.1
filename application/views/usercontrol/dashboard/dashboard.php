<?php
$db =& get_instance();
$CI =& get_instance();
$method = $CI->router->fetch_method();
$userdetails = $db->userdetails();
$products = $db->Product_model;
$notifications_count = $products->getnotificationnew_count('admin', null);
$payment_details = json_decode($plan->payment_details);
$loginUser = $_SESSION['user'];

$user_side_bar_clock_text_color = $products->getSettings('theme','user_side_bar_clock_text_color');
$PrimaryPaymentMethodStatus = $products->getUserPaymentMethodStatus($userdetails['id'],$userdetails['primary_payment_method']);
$paymentlist         = $products->getPaymentWarning();
if(empty($payment_methods) && ($method != 'purchase_plan' && $method !='user_reports')){
$payment_methods = $this->session->userdata('payment_methods');
}
$loginUser = $_SESSION['user'];
if(isset($loginUser['is_vendor']) && $loginUser['is_vendor'] == 1) {
$store_setting =$db->Product_model->getSettings('store');
$vendor_setting = $db->Product_model->getSettings('vendor');
$marketVendorStatus= $db->Product_model->getSettings('market_vendor', 'marketvendorstatus');
$vendoerMinDeposit = $db->Product_model->getSettings('site', 'vendor_min_deposit');
$userdepbal['vendor_min_deposit'] = isset($vendoerMinDeposit['vendor_min_deposit']) ? $vendoerMinDeposit['vendor_min_deposit'] : 0;

$db->load->model('Total_model');
$depbalence = $db->Total_model->getUserBalance($loginUser['id']);

$userdepbal['show_deposit_warning'] = ($depbalence < $userdepbal['vendor_min_deposit']) ? 1 : 0;
$userdepbal['vendor_min_deposit_warning'] = __('user.minimum_deposit_warning');

$vendorDepositStatus = $this->Product_model->getSettings('vendor', 'depositstatus');
$userdepbal['vendor_deposit_status'] = isset($vendorDepositStatus['depositstatus']) ? $vendorDepositStatus['depositstatus'] : 0;
}

/* v15 helpers */
if (!function_exists('_v15_time_ago')) {
    function _v15_time_ago($datetime) {
        $diff = (new DateTime())->diff(new DateTime($datetime));
        if ($diff->days >= 1) return $diff->days . __('user.time_days_short');
        if ($diff->h   >= 1) return $diff->h   . __('user.time_hours_short');
        if ($diff->i   >= 1) return $diff->i   . __('user.time_mins_short');
        return __('user.just_now');
    }
}
if (!function_exists('_v15_compact_number')) {
    function _v15_compact_number($n) {
        if ($n >= 1000000) return round($n / 1000000, 1) . __('user.num_suffix_m');
        if ($n >= 1000)    return round($n / 1000, 1)    . __('user.num_suffix_k');
        return number_format($n);
    }
}

/* Pre-compute today's stats from trends */
$clicks_today      = isset($trends) ? (end($trends['clicks'])      ?: 0) : 0;
$orders_today      = isset($trends) ? (end($trends['orders'])      ?: 0) : 0;
$commissions_today = isset($trends) ? (end($trends['commissions']) ?: 0) : 0;
$conv_rate_today   = $clicks_today > 0 ? round($orders_today / $clicks_today * 100, 1) : 0;

/* Wallet totals shorthand */
$wHold      = $user_totals['wallet_on_hold_amount']  ?? 0;
$wHoldCnt   = $user_totals['wallet_on_hold_count']   ?? 0;
$wPending   = $user_totals['wallet_unpaid_amount']   ?? 0;
$wPendCnt   = $user_totals['wallet_unpaid_count']    ?? 0;
$wBalance   = $user_totals['user_balance']           ?? 0;
$wPaid      = $user_totals['wallet_accept_amount']   ?? 0;

/* Greeting */
$hour = (int)date('H');
if ($hour < 12)      $greeting = __('user.good_morning');
elseif ($hour < 18)  $greeting = __('user.good_afternoon');
else                 $greeting = __('user.good_evening');
?>

<!-- ═══════════════════════════════════════════════════════
     V15 DASHBOARD STYLES
═══════════════════════════════════════════════════════ -->
<style>
/* Pipeline cards */
.v15-pipe-card {
  border-radius: 16px;
  border: 1.5px solid rgba(0,0,0,.06);
  background: #fff;
  padding: 20px 22px;
  position: relative;
  transition: box-shadow .2s, transform .2s;
  overflow: hidden;
}
.v15-pipe-card:hover { box-shadow: 0 8px 28px rgba(0,0,0,.1); transform: translateY(-2px); }
.v15-pipe-card::before {
  content: '';
  position: absolute;
  top: 0; left: 0; right: 0;
  height: 4px;
  border-radius: 16px 16px 0 0;
}
.v15-pipe-hold::before    { background: #94a3b8; }
.v15-pipe-pending::before { background: #f59e0b; }
.v15-pipe-avail::before   { background: #3b82f6; }
.v15-pipe-paid::before    { background: #22c55e; }

.v15-pipe-card .pipe-icon {
  width: 44px; height: 44px;
  border-radius: 12px;
  display: flex; align-items: center; justify-content: center;
  font-size: 18px;
  margin-bottom: 14px;
}
.v15-pipe-card .pipe-amount {
  font-size: 1.6rem;
  font-weight: 800;
  letter-spacing: -.5px;
  line-height: 1;
  margin-bottom: 4px;
}
.v15-pipe-card .pipe-label {
  font-size: .72rem;
  font-weight: 600;
  text-transform: uppercase;
  letter-spacing: .6px;
}
.v15-pipe-card .pipe-count {
  position: absolute;
  top: 16px; right: 16px;
  font-size: .7rem; font-weight: 700;
  background: rgba(0,0,0,.06);
  border-radius: 20px;
  padding: 2px 8px;
}
.pipe-arrow {
  display: flex;
  align-items: center;
  justify-content: center;
  color: #cbd5e1;
  font-size: 1.2rem;
  padding: 0 4px;
  flex-shrink: 0;
  padding-top: 8px; /* optical align with card center */
}

/* Today pills */
.v15-today-pill {
  border-radius: 14px;
  padding: 16px 20px;
  display: flex;
  align-items: center;
  gap: 14px;
  transition: box-shadow .2s;
}
.v15-today-pill:hover { box-shadow: 0 4px 16px rgba(0,0,0,.08); }
.v15-today-pill .pill-icon {
  width: 46px; height: 46px; border-radius: 12px;
  display: flex; align-items: center; justify-content: center;
  font-size: 18px; flex-shrink: 0;
}
.v15-today-pill .pill-val {
  font-size: 1.5rem; font-weight: 800; letter-spacing: -.5px; line-height: 1;
}
.v15-today-pill .pill-lbl {
  font-size: .7rem; font-weight: 600; text-transform: uppercase;
  letter-spacing: .5px; margin-top: 3px; opacity: .65;
}

/* Activity feed */
.v15-activity-item {
  display: flex; align-items: center; gap: 14px;
  padding: 13px 0;
  border-bottom: 1px solid #f1f5f9;
}
.v15-activity-item:last-child { border-bottom: none; }
.v15-activity-icon {
  width: 40px; height: 40px; border-radius: 50%;
  display: flex; align-items: center; justify-content: center;
  flex-shrink: 0; font-size: 14px;
}

/* Quick action rows */
.v15-action-item {
  display: flex; align-items: center; gap: 14px;
  padding: 13px 16px; border-radius: 12px;
  text-decoration: none; transition: background .15s, transform .15s;
  cursor: pointer;
}
.v15-action-item:hover { background: #f8fafc; transform: translateX(3px); }
.v15-action-icon {
  width: 44px; height: 44px; border-radius: 12px;
  display: flex; align-items: center; justify-content: center;
  font-size: 16px; flex-shrink: 0;
}

/* Section header */
.v15-section-hdr {
  display: flex; align-items: center; justify-content: space-between;
  padding: 18px 22px 12px;
  border-bottom: 1px solid #f1f5f9;
}
.v15-section-hdr h6 { margin: 0; font-weight: 700; font-size: .9rem; }

/* Chart card */
.v15-chart-card {
  border-radius: 16px;
  border: 1.5px solid rgba(0,0,0,.06);
  background: #fff;
  overflow: hidden;
}

/* Hero subtitle */
.v15-hero-stat {
  display: inline-flex; align-items: center; gap: 6px;
  background: rgba(255,255,255,.18); border-radius: 20px;
  padding: 4px 12px; font-size: .8rem; font-weight: 600;
}
</style>

<div class="container-fluid pb-4">
  <div class="row g-4">

    <!-- ══════════════════════════════════════════════
         ROW 1 — HERO
    ══════════════════════════════════════════════ -->
    <div class="col-12">
      <div class="text-white border-0 shadow-lg rounded-4 hero-animated-gradient position-relative overflow-hidden" style="background: var(--v14-hero-affiliate);">
        <div style="position:absolute;top:0;right:0;width:50%;height:100%;background:url('data:image/svg+xml,<svg xmlns=%22http://www.w3.org/2000/svg%22 viewBox=%220 0 200 200%22><circle cx=%22150%22 cy=%2250%22 r=%22100%22 fill=%22rgba(255,255,255,0.04)%22/><circle cx=%22180%22 cy=%22150%22 r=%2260%22 fill=%22rgba(255,255,255,0.03)%22/></svg>') no-repeat center;pointer-events:none;"></div>
        <div class="p-4 p-md-5 position-relative">
          <div class="row align-items-center g-3">
            <div class="col-lg-8 col-md-7">
              <div class="d-flex align-items-center gap-3 mb-3">
                <img src="<?= $products->getAvatar($userdetails['avatar']); ?>" alt="" class="rounded-circle border border-white border-opacity-30" width="60" height="60" />
                <div>
                  <h2 class="mb-1 fw-bold fs-3">
                    <?= $greeting ?>, <?= htmlspecialchars($userdetails['firstname']) ?>!
                  </h2>
                  <div class="d-flex flex-wrap gap-2 mt-1">
                    <span class="v15-hero-stat">
                      <i class="fas fa-check-circle text-success"></i> <?= __('user.active') ?>
                    </span>
                    <?php if ($wBalance > 0): ?>
                    <span class="v15-hero-stat">
                      <i class="fas fa-wallet"></i> <?= $fun_c_format($wBalance) ?> <?= __('user.available_balance') ?>
                    </span>
                    <?php endif; ?>
                    <?php if ($clicks_today > 0): ?>
                    <span class="v15-hero-stat">
                      <i class="fas fa-mouse-pointer"></i> <?= number_format($clicks_today) ?> <?= __('user.today_clicks') ?>
                    </span>
                    <?php endif; ?>
                  </div>
                </div>
              </div>
            </div>
            <div class="col-lg-4 col-md-5 text-lg-end text-center">
              <div class="d-flex gap-2 justify-content-lg-end justify-content-center flex-wrap">
                <a href="<?= base_url('usercontrol/analytics_dashboard') ?>" class="btn btn-light btn-lg px-4 fw-semibold">
                  <i class="fas fa-chart-line me-2"></i><?= __('user.analytics') ?>
                </a>
                <a href="<?= base_url('usercontrol/mywallet') ?>" class="btn btn-outline-light btn-lg px-4 fw-semibold">
                  <i class="fas fa-wallet me-2"></i><?= __('user.page_title_wallet') ?>
                </a>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- Trust / health score (affiliate standing) -->
    <div class="col-12 col-lg-5">
      <div class="card border-0 shadow-sm h-100 rounded-4 overflow-hidden">
        <div class="card-body p-4">
          <div class="d-flex align-items-center justify-content-between mb-2">
            <h6 class="mb-0 fw-semibold text-dark">
              <i class="fas fa-user-shield text-primary me-2"></i><?= __('user.trust_score_card_title') ?>
            </h6>
          </div>
          <p class="text-muted small mb-3"><?= __('user.trust_score_card_subtitle') ?></p>
          <?php
            $th = isset($userdetails['health_score']) && $userdetails['health_score'] !== null && $userdetails['health_score'] !== '' ? (float)$userdetails['health_score'] : null;
          if ($th === null) {
            echo '<div class="alert alert-light border small mb-0">' . __('user.trust_score_not_calculated_hint') . '</div>';
          } else {
            $ring = $th >= 80 ? 'success' : ($th >= 50 ? 'warning' : 'danger');
            echo '<div class="d-flex align-items-center gap-3">';
            echo '<div class="rounded-circle d-flex align-items-center justify-content-center fw-bold text-white bg-' . $ring . '" style="width:72px;height:72px;font-size:1.35rem;">' . number_format($th, 0) . '</div>';
            echo '<div class="flex-grow-1">';
            echo '<div class="d-flex align-items-center gap-2 flex-wrap">';
            echo '<div class="fw-bold fs-5 text-dark mb-0">' . number_format($th, 1) . '<span class="text-muted fs-6">/100</span></div>';
            echo '<button type="button" class="btn btn-link p-0 align-baseline text-primary" id="trust-score-info-btn" data-bs-toggle="tooltip" data-bs-placement="top" data-bs-container="body" title="' . htmlspecialchars(__('user.trust_score_tooltip'), ENT_QUOTES, 'UTF-8') . '" style="line-height:1;min-width:1.5rem;">';
            echo '<i class="fas fa-info-circle" aria-hidden="true"></i><span class="visually-hidden">' . htmlspecialchars(__('user.trust_score_info_short'), ENT_QUOTES, 'UTF-8') . '</span>';
            echo '</button></div>';
            if ($th >= 80) {
              echo '<span class="badge bg-success">' . __('user.trust_score_badge_strong') . '</span>';
            } elseif ($th >= 50) {
              echo '<span class="badge bg-warning text-dark">' . __('user.trust_score_badge_fair') . '</span>';
            } else {
              echo '<span class="badge bg-danger">' . __('user.trust_score_badge_needs_attention') . '</span>';
            }
            echo '<p class="small text-muted mt-2 mb-0">' . __('user.trust_score_summary_hint') . '</p>';
            echo '</div></div>';
          }
          ?>
        </div>
      </div>
    </div>

    <!-- ══════════════════════════════════════════════
         ROW 2 — WALLET PIPELINE (4 equal cols, no arrow divs)
    ══════════════════════════════════════════════ -->
    <div class="col-12">
      <div class="row g-3">

        <div class="col-6 col-lg-3">
          <div class="v15-pipe-card v15-pipe-hold h-100">
            <?php if ($wHoldCnt > 0): ?><span class="pipe-count text-secondary"><?= $wHoldCnt ?></span><?php endif; ?>
            <div class="pipe-icon" style="background:#f1f5f9; color:#64748b;">
              <i class="fas fa-pause-circle"></i>
            </div>
            <div class="pipe-amount text-secondary"><?= _v15_compact_number($wHold) ?></div>
            <div class="pipe-label text-secondary"><?= __('user.on_hold') ?></div>
            <small class="text-muted d-block mt-1" style="font-size:.72rem"><?= __('user.under_review') ?></small>
          </div>
        </div>

        <div class="col-6 col-lg-3">
          <div class="v15-pipe-card v15-pipe-pending h-100">
            <?php if ($wPendCnt > 0): ?><span class="pipe-count" style="color:#d97706;background:#fef3c7"><?= $wPendCnt ?></span><?php endif; ?>
            <div class="pipe-icon" style="background:#fef3c7; color:#d97706;">
              <i class="fas fa-clock"></i>
            </div>
            <div class="pipe-amount" style="color:#92400e"><?= _v15_compact_number($wPending) ?></div>
            <div class="pipe-label" style="color:#b45309"><?= __('user.pending') ?></div>
            <small class="text-muted d-block mt-1" style="font-size:.72rem"><?= __('user.awaiting_payment') ?></small>
          </div>
        </div>

        <div class="col-6 col-lg-3">
          <div class="v15-pipe-card v15-pipe-avail h-100" style="background: linear-gradient(145deg,#eff6ff,#fff);">
            <div class="pipe-icon" style="background:#dbeafe; color:#2563eb;">
              <i class="fas fa-wallet"></i>
            </div>
            <div class="pipe-amount" style="color:#1d4ed8"><?= _v15_compact_number($wBalance) ?></div>
            <div class="pipe-label" style="color:#2563eb"><?= __('user.available_balance') ?></div>
            <small style="font-size:.72rem; color:#3b82f6; display:block; margin-top:4px"><?= __('user.ready_to_withdraw') ?></small>
          </div>
        </div>

        <div class="col-6 col-lg-3">
          <div class="v15-pipe-card v15-pipe-paid h-100" style="background: linear-gradient(145deg,#f0fdf4,#fff);">
            <div class="pipe-icon" style="background:#dcfce7; color:#16a34a;">
              <i class="fas fa-check-double"></i>
            </div>
            <div class="pipe-amount" style="color:#15803d"><?= _v15_compact_number($wPaid) ?></div>
            <div class="pipe-label" style="color:#16a34a"><?= __('user.total_paid') ?></div>
            <small style="font-size:.72rem; color:#22c55e; display:block; margin-top:4px"><?= __('user.all_time') ?></small>
          </div>
        </div>

      </div>

      <!-- Flow indicator (desktop only, purely decorative) -->
      <div class="d-none d-lg-flex justify-content-around px-2 mt-1" style="pointer-events:none">
        <?php for($i=0;$i<4;$i++): ?>
        <div style="flex:1; position:relative; text-align:center">
          <?php if($i < 3): ?>
          <div style="position:absolute;right:-10px;top:0;color:#cbd5e1;font-size:.75rem;font-weight:600;">
            <i class="fas fa-arrow-right"></i>
          </div>
          <?php endif; ?>
        </div>
        <?php endfor; ?>
      </div>
    </div>

    <!-- ══════════════════════════════════════════════
         ROW 3 — TODAY'S PERFORMANCE
    ══════════════════════════════════════════════ -->
    <?php if (isset($trends)): ?>
    <div class="col-12">
      <div class="v15-chart-card">
        <div class="v15-section-hdr">
          <h6><i class="fas fa-sun text-warning me-2"></i><?= __('user.todays_performance') ?></h6>
          <span class="badge bg-light text-muted fw-normal"><?= date('d M Y') ?></span>
        </div>
        <div class="row g-0">

          <div class="col-6 col-md-3 border-end border-bottom border-md-bottom-0">
            <div class="v15-today-pill m-3">
              <div class="pill-icon" style="background:#f0f0ff; color:#7c3aed"><i class="fas fa-mouse-pointer"></i></div>
              <div>
                <div class="pill-val" style="color:#4c1d95"><?= number_format($clicks_today) ?></div>
                <div class="pill-lbl" style="color:#7c3aed"><?= __('user.today_clicks') ?></div>
              </div>
            </div>
          </div>

          <div class="col-6 col-md-3 border-end border-bottom border-md-bottom-0">
            <div class="v15-today-pill m-3">
              <div class="pill-icon" style="background:#f0fdf4; color:#15803d"><i class="fas fa-check-circle"></i></div>
              <div>
                <div class="pill-val" style="color:#14532d"><?= number_format($orders_today) ?></div>
                <div class="pill-lbl" style="color:#16a34a"><?= __('user.today_conversions') ?></div>
              </div>
            </div>
          </div>

          <div class="col-6 col-md-3 border-end">
            <div class="v15-today-pill m-3">
              <div class="pill-icon" style="background:#eff6ff; color:#1d4ed8"><i class="fas fa-coins"></i></div>
              <div>
                <div class="pill-val" style="color:#1e3a8a"><?= $fun_c_format($commissions_today) ?></div>
                <div class="pill-lbl" style="color:#2563eb"><?= __('user.today_earned') ?></div>
              </div>
            </div>
          </div>

          <div class="col-6 col-md-3">
            <div class="v15-today-pill m-3">
              <div class="pill-icon" style="background:#fefce8; color:#ca8a04"><i class="fas fa-percentage"></i></div>
              <div>
                <div class="pill-val" style="color:#78350f"><?= $conv_rate_today ?>%</div>
                <div class="pill-lbl" style="color:#d97706"><?= __('user.conv_rate') ?></div>
              </div>
            </div>
          </div>

        </div>
      </div>
    </div>
    <?php endif; ?>

    <!-- ══════════════════════════════════════════════
         ROW 4 — CHARTS (7-day + ring, side by side)
    ══════════════════════════════════════════════ -->
    <?php if (isset($trends)): ?>
    <div class="col-lg-8">
      <div class="v15-chart-card h-100">
        <div class="v15-section-hdr">
          <h6><i class="fas fa-chart-area text-primary me-2"></i><?= __('user.seven_day_trends') ?></h6>
          <div class="d-flex gap-2">
            <span class="badge rounded-pill" style="background:#eff6ff; color:#3b82f6"><i class="fas fa-circle me-1" style="font-size:.5rem"></i><?= __('user.earnings') ?></span>
            <span class="badge rounded-pill" style="background:#f5f3ff; color:#8b5cf6"><i class="fas fa-circle me-1" style="font-size:.5rem"></i><?= __('user.clicks') ?></span>
            <span class="badge rounded-pill" style="background:#f0fdf4; color:#22c55e"><i class="fas fa-circle me-1" style="font-size:.5rem"></i><?= __('user.conversions') ?></span>
          </div>
        </div>
        <div class="p-4">
          <div class="row g-3">
            <div class="col-md-4">
              <div class="p-3 rounded-3" style="background:var(--v14-stat-blue)">
                <div class="d-flex justify-content-between align-items-center mb-2">
                  <small class="fw-semibold text-primary"><?= __('user.earnings') ?></small>
                  <small class="fw-bold text-primary"><?= $fun_c_format(array_sum($trends['commissions'])) ?></small>
                </div>
                <div class="sparkline-container"><canvas id="userSparkEarnings"></canvas></div>
              </div>
            </div>
            <div class="col-md-4">
              <div class="p-3 rounded-3" style="background:var(--v14-stat-purple)">
                <div class="d-flex justify-content-between align-items-center mb-2">
                  <small class="fw-semibold" style="color:#8b5cf6"><?= __('user.clicks') ?></small>
                  <small class="fw-bold" style="color:#7c3aed"><?= number_format(array_sum($trends['clicks'])) ?></small>
                </div>
                <div class="sparkline-container"><canvas id="userSparkClicks"></canvas></div>
              </div>
            </div>
            <div class="col-md-4">
              <div class="p-3 rounded-3" style="background:var(--v14-stat-green)">
                <div class="d-flex justify-content-between align-items-center mb-2">
                  <small class="fw-semibold text-success"><?= __('user.conversions') ?></small>
                  <small class="fw-bold text-success"><?= number_format(array_sum($trends['orders'])) ?></small>
                </div>
                <div class="sparkline-container"><canvas id="userSparkConversions"></canvas></div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>

    <div class="col-lg-4">
      <div class="v15-chart-card h-100">
        <div class="v15-section-hdr">
          <h6><i class="fas fa-chart-pie me-2" style="color:#8b5cf6"></i><?= __('user.performance_overview') ?></h6>
        </div>
        <div class="p-4 d-flex flex-column align-items-center justify-content-center">
          <div class="ring-chart-container mx-auto">
            <canvas id="userRingChart"></canvas>
            <div class="ring-chart-center-text">
              <div class="ring-value" style="color:var(--v14-text-primary)"><?= $fun_c_format($wBalance) ?></div>
              <div class="ring-label text-muted"><?= __('user.balance') ?></div>
            </div>
          </div>
          <div class="row g-2 w-100 mt-3">
            <div class="col-4 text-center">
              <div class="fw-bold text-primary" style="font-size:.85rem"><?= $fun_c_format($user_totals['external_sale_total'] ?? 0) ?></div>
              <div class="text-muted" style="font-size:.65rem; text-transform:uppercase; letter-spacing:.4px"><?= __('user.sale_commission') ?></div>
            </div>
            <div class="col-4 text-center">
              <div class="fw-bold" style="font-size:.85rem; color:#8b5cf6"><?= $fun_c_format($user_totals['external_click_total'] ?? 0) ?></div>
              <div class="text-muted" style="font-size:.65rem; text-transform:uppercase; letter-spacing:.4px"><?= __('user.click_commission') ?></div>
            </div>
            <div class="col-4 text-center">
              <div class="fw-bold text-success" style="font-size:.85rem"><?= $fun_c_format($user_totals['localstore_sale_total'] ?? 0) ?></div>
              <div class="text-muted" style="font-size:.65rem; text-transform:uppercase; letter-spacing:.4px"><?= __('user.store_commission') ?></div>
            </div>
          </div>
        </div>
      </div>
    </div>
    <?php endif; ?>

    <!-- ══════════════════════════════════════════════
         ROW 5 — TOP AFFILIATES LEADERBOARD
    ══════════════════════════════════════════════ -->
    <?php
    $_show_top_aff = false;
    if (isset($userdashboard_settings['top_affiliate'])) {
        $_show_top_aff = ((int)($userdashboard_settings['top_affiliate']['setting_value'] ?? 0) === 1);
    }
    if ($_show_top_aff && !empty($populer_users)):
    ?>
    <div class="col-12">
      <div class="v15-chart-card">
        <div class="v15-section-hdr">
          <h6><i class="fas fa-trophy text-warning me-2"></i><?= __('user.top_affiliates') ?></h6>
          <span class="badge rounded-pill bg-warning text-dark small"><?= __('user.leaderboard') ?></span>
        </div>
        <div class="table-responsive px-3 pb-3">
          <table class="table table-hover align-middle mb-0">
            <thead>
              <tr style="font-size:.72rem; text-transform:uppercase; letter-spacing:.5px" class="text-muted">
                <th class="border-0 ps-2" style="width:44px">#</th>
                <th class="border-0"><?= __('user.affiliate') ?></th>
                <th class="border-0 d-none d-md-table-cell"><?= __('user.country') ?></th>
                <th class="border-0 text-end"><?= __('user.earnings') ?></th>
                <th class="border-0 text-end d-none d-sm-table-cell"><?= __('user.conversions') ?></th>
                <th class="border-0 text-end d-none d-sm-table-cell"><?= __('user.clicks') ?></th>
              </tr>
            </thead>
            <tbody>
            <?php foreach ($populer_users as $paIdx => $pu):
              $paPos    = $paIdx + 1;
              $paName   = trim(($pu['firstname'] ?? '') . ' ' . ($pu['lastname'] ?? ''));
              if ($paName === '') $paName = __('user.affiliate');
              $paAvatar = $products->getAvatar($pu['avatar'] ?? '');
              $paMedal  = ['', 'text-warning', 'text-secondary', 'text-danger'];
            ?>
            <tr>
              <td class="ps-2">
                <?php if ($paPos <= 3): ?>
                <span class="fw-bold <?= $paMedal[$paPos] ?>" style="font-size:1rem"><i class="fas fa-medal"></i></span>
                <?php else: ?>
                <span class="text-muted fw-semibold" style="font-size:.8rem">#<?= $paPos ?></span>
                <?php endif; ?>
              </td>
              <td>
                <div class="d-flex align-items-center gap-2">
                  <img src="<?= $paAvatar ?>" alt="" class="rounded-circle flex-shrink-0"
                       style="width:32px; height:32px; object-fit:cover">
                  <span class="fw-semibold" style="font-size:.85rem"><?= htmlspecialchars($paName) ?></span>
                </div>
              </td>
              <td class="d-none d-md-table-cell text-muted" style="font-size:.8rem">
                <?php if (!empty($pu['country_code'])): ?>
                <span class="badge rounded-pill bg-light text-dark me-1"><?= htmlspecialchars($pu['country_code']) ?></span>
                <?php endif; ?>
                <?= htmlspecialchars($pu['country_name'] ?? '') ?>
              </td>
              <td class="text-end fw-bold text-primary" style="font-size:.85rem"><?= $fun_c_format($pu['amount'] ?? 0) ?></td>
              <td class="text-end text-muted d-none d-sm-table-cell" style="font-size:.8rem"><?= number_format((int)($pu['total_conversions'] ?? 0)) ?></td>
              <td class="text-end text-muted d-none d-sm-table-cell" style="font-size:.8rem"><?= number_format((int)($pu['total_clicks'] ?? 0)) ?></td>
            </tr>
            <?php endforeach; ?>
            </tbody>
          </table>
        </div>
      </div>
    </div>
    <?php endif; ?>

    <!-- ══════════════════════════════════════════════
         ROW 6 — AFFILIATE LINKS (unchanged)
    ══════════════════════════════════════════════ -->
    <div class="col-12">
      <div class="card border-0 shadow-sm rounded-4">
        <?php
        if($store['status'] || $refer_status):
        $invitationlinkid = 0;
        if(isset($userdashboard_settings) && isset($userdashboard_settings['invitation_link_id'])) {
        $invitationlinkidarray = $userdashboard_settings['invitation_link_id'];
        $invitationlinkid = $invitationlinkidarray['setting_value'];
        }
        if($invitationlinkid == 0):
        ?>
        <div class="card-header bg-warning text-dark border-0 py-3 px-4">
          <div class="d-flex justify-content-between align-items-center">
            <h5 class="card-title mb-0 d-flex align-items-center fw-bold">
              <i class="fas fa-link me-3"></i>
              <?= __('user.affiliate_name') ?>: <span class="ms-3 badge bg-light text-dark"><?= $userdetails['id'] ?></span>
            </h5>
            <div class="form-check form-switch mb-0">
              <input class="form-check-input" type="checkbox" id="show_my_id">
              <label class="form-check-label fw-medium" for="show_my_id"><?= __('user.show_my_id') ?></label>
            </div>
          </div>
        </div>
        <?php endif; endif; ?>
        <div class="card-body p-4">
          <?php if ($store['status'] || $refer_status): ?>

          <?php if ($store['status']): ?>
          <?php
          $base     = $store_slug ?: 'store/' . base64_encode($userdetails['id']);
          $tinyUrl  = base_url($base);
          $megaUrl  = $tinyUrl . '/?id=' . $userdetails['id'];
          $tinyHide = $invitationlinkid == 1 ? 'd-none' : '';
          $megaHide = $invitationlinkid == 0 ? 'd-none' : '';
          ?>
          <div class="mb-4 show-tiny-link <?= $tinyHide ?>">
            <label class="form-label fw-bold fs-6 mb-2"><i class="fas fa-store text-primary me-2"></i><?= __('user.store_url') ?></label>
            <?php linkRow('primary', $tinyUrl, 'input-store-url-0', 'store'); ?>
          </div>
          <div class="mb-4 show-mega-link <?= $megaHide ?>">
            <label class="form-label fw-bold fs-6 mb-2"><i class="fas fa-store text-primary me-2"></i><?= __('user.store_url') ?></label>
            <?php linkRow('primary', $megaUrl, 'input-store-mega-url-0', 'store'); ?>
          </div>
          <?php endif; ?>

          <?php if (!empty($vendor_store_slug)): ?>
          <?php
          $base     = 'store/' . $vendor_store_slug . '/' . base64_encode($userdetails['id']);
          $tinyUrl  = base_url($base);
          $megaUrl  = $tinyUrl . '/?id=' . $userdetails['id'];
          $tinyHide = $invitationlinkid == 1 ? 'd-none' : '';
          $megaHide = $invitationlinkid == 0 ? 'd-none' : '';
          ?>
          <div class="mb-4 show-tiny-link <?= $tinyHide ?>">
            <label class="form-label fw-bold fs-6 mb-2"><i class="fas fa-shopping-basket text-success me-2"></i><?= __('user.share_your_vendor_store') ?></label>
            <?php linkRow('success', $tinyUrl, 'input-storepage-url-0', 'vendor_store'); ?>
          </div>
          <div class="mb-4 show-mega-link <?= $megaHide ?>">
            <label class="form-label fw-bold fs-6 mb-2"><i class="fas fa-shopping-basket text-success me-2"></i><?= __('user.share_your_vendor_store') ?></label>
            <?php linkRow('success', $megaUrl, 'input-storepage-mega-url-0', 'vendor_store'); ?>
          </div>
          <?php endif; ?>

          <?php if ($userdetails['is_vendor'] == 1 && $refer_status && allowMarketVendorPanelSections($marketvendorpanelmode,$userdetails['is_vendor'])): ?>
          <?php
          $base     = 'register/vendor/' . base64_encode($userdetails['id']);
          $tinyUrl  = base_url($base);
          $megaUrl  = $tinyUrl . '/?id=' . $userdetails['id'];
          $tinyHide = $invitationlinkid == 1 ? 'd-none' : '';
          $megaHide = $invitationlinkid == 0 ? 'd-none' : '';
          ?>
          <div class="mb-4 show-tiny-link <?= $tinyHide ?>">
            <label class="form-label fw-bold fs-6 mb-2"><i class="fas fa-user-tie text-warning me-2"></i><?= __('user.invite_people_to_become_vendors') ?></label>
            <?php linkRow('warning', $tinyUrl, 'input-vendor-url-0', 'vendor'); ?>
          </div>
          <div class="mb-4 show-mega-link <?= $megaHide ?>">
            <label class="form-label fw-bold fs-6 mb-2"><i class="fas fa-user-tie text-warning me-2"></i><?= __('user.invite_people_to_become_vendors') ?></label>
            <?php linkRow('warning', $megaUrl, 'input-vendor-mega-url-0', 'vendor'); ?>
          </div>
          <?php endif; ?>

          <?php if ($refer_status && allowMarketVendorPanelSections($marketvendorpanelmode,$userdetails['is_vendor'])): ?>
          <?php
          $base     = $register_slug ?: 'register/' . base64_encode($userdetails['id']);
          $tinyUrl  = base_url($base);
          $megaUrl  = $tinyUrl . '/?id=' . $userdetails['id'];
          $tinyHide = $invitationlinkid == 1 ? 'd-none' : '';
          $megaHide = $invitationlinkid == 0 ? 'd-none' : '';
          ?>
          <div class="mb-4 show-tiny-link <?= $tinyHide ?>">
            <label class="form-label fw-bold fs-6 mb-2"><i class="fas fa-user-plus text-danger me-2"></i><?= __('user.invite_people_to_become_affiliates') ?></label>
            <?php linkRow('danger', $tinyUrl, 'input-register-url-0', 'register'); ?>
          </div>
          <div class="mb-4 show-mega-link <?= $megaHide ?>">
            <label class="form-label fw-bold fs-6 mb-2"><i class="fas fa-user-plus text-danger me-2"></i><?= __('user.invite_people_to_become_affiliates') ?></label>
            <?php linkRow('danger', $megaUrl, 'input-register-mega-url-0', 'register'); ?>
          </div>
          <?php endif; ?>

          <?php endif; ?>
        </div>

        <?php
        function linkRow($color, $url, $inputCls, $type){
        global $userdetails;
        ?>
        <div class="input-group input-group-lg">
          <input type="text" readonly value="<?= $url ?>" class="form-control bg-light <?= $inputCls ?> fs-6">
          <button class="btn btn-<?= $color ?> copy-btn px-3" data-clipboard="<?= $url ?>"><i class="far fa-copy"></i></button>
          <button class="btn btn-<?= $color ?> dropdown-toggle dropdown-toggle-split px-3" data-bs-toggle="dropdown"></button>
          <ul class="dropdown-menu dropdown-menu-end">
            <li><a class="dropdown-item qrcode" href="javascript:void(0)" data-id="<?= $url ?>"><i class="fas fa-qrcode me-2"></i><?= __('user.generate_qr_code') ?></a></li>
            <li><a class="dropdown-item target-share-link" href="<?= $url ?>" target="_blank"><i class="fas fa-external-link-alt me-2"></i><?= __('user.open_link') ?></a></li>
            <li><a class="dropdown-item dashboard-model-slug" href="javascript:void(0)" data-type="<?= $type ?>" data-related-id="0" data-input-class="<?= $inputCls ?>"><i class="fas fa-cog me-2"></i><?= __('user.customize') ?></a></li>
            <li><a class="dropdown-item" href="javascript:void(0)" data-social-share data-share-url="<?= $url ?>?id=<?= $userdetails['id'] ?>"><i class="fas fa-share-alt me-2"></i><?= __('user.share') ?></a></li>
          </ul>
        </div>
        <?php } ?>
      </div>
    </div>

    <!-- ══════════════════════════════════════════════
         ROW 6 — RECENT ACTIVITY + QUICK ACTIONS
    ══════════════════════════════════════════════ -->
    <div class="col-lg-7">
      <div class="v15-chart-card h-100">
        <div class="v15-section-hdr">
          <h6><i class="fas fa-history text-primary me-2"></i><?= __('user.recent_activity') ?></h6>
          <a href="<?= base_url('usercontrol/mywallet') ?>" class="btn btn-sm btn-outline-primary rounded-pill px-3 py-1" style="font-size:.78rem">
            <?= __('user.view_all') ?> <i class="fas fa-arrow-right ms-1"></i>
          </a>
        </div>
        <div class="px-4 py-2">
          <?php
          $wallet_type_meta = [
              'click_commission'              => ['icon' => 'fa-mouse-pointer',     'bg' => '#eff0ff', 'color' => '#4f46e5'],
              'external_click_commission'     => ['icon' => 'fa-external-link-alt', 'bg' => '#eff0ff', 'color' => '#4f46e5'],
              'external_click_comm_admin'     => ['icon' => 'fa-external-link-alt', 'bg' => '#eff0ff', 'color' => '#4f46e5'],
              'sale_commission'               => ['icon' => 'fa-shopping-cart',     'bg' => '#f0fdf4', 'color' => '#16a34a'],
              'refer_sale_commission'         => ['icon' => 'fa-shopping-cart',     'bg' => '#f0fdf4', 'color' => '#16a34a'],
              'form_click_commission'         => ['icon' => 'fa-file-alt',          'bg' => '#f0f9ff', 'color' => '#0284c7'],
              'refer_registration_commission' => ['icon' => 'fa-user-plus',         'bg' => '#fefce8', 'color' => '#ca8a04'],
              'welcome_bonus'                 => ['icon' => 'fa-gift',              'bg' => '#fdf4ff', 'color' => '#a21caf'],
              'admin_transaction'             => ['icon' => 'fa-user-shield',       'bg' => '#f8fafc', 'color' => '#475569'],
              'membership_plan_bonus'         => ['icon' => 'fa-crown',             'bg' => '#fefce8', 'color' => '#d97706'],
              'award_level_comission'         => ['icon' => 'fa-trophy',            'bg' => '#fef9c3', 'color' => '#ca8a04'],
          ];
          $status_cfg = [
              'accept'  => ['label' => __('user.paid'),    'bg' => '#dcfce7', 'color' => '#15803d'],
              'pending' => ['label' => __('user.pending'), 'bg' => '#fef3c7', 'color' => '#b45309'],
              'hold'    => ['label' => __('user.hold'),    'bg' => '#f1f5f9', 'color' => '#475569'],
          ];
          ?>

          <?php if (!empty($recent_wallet)): ?>
            <?php foreach ($recent_wallet as $row):
              $meta   = $wallet_type_meta[$row['type']] ?? ['icon' => 'fa-coins', 'bg' => '#f8fafc', 'color' => '#64748b'];
              $sc     = $status_cfg[$row['status']] ?? ['label' => ucfirst($row['status']), 'bg' => '#f1f5f9', 'color' => '#475569'];
              $label  = __('user.' . $row['type']) ?: ucwords(str_replace('_', ' ', $row['type']));
              $ago    = _v15_time_ago($row['created_at']);
            ?>
            <div class="v15-activity-item">
              <div class="v15-activity-icon" style="background:<?= $meta['bg'] ?>; color:<?= $meta['color'] ?>">
                <i class="fas <?= $meta['icon'] ?>"></i>
              </div>
              <div class="flex-grow-1 min-w-0">
                <div class="fw-semibold text-dark" style="font-size:.88rem; white-space:nowrap; overflow:hidden; text-overflow:ellipsis"><?= htmlspecialchars($label) ?></div>
                <div class="d-flex align-items-center gap-2 mt-1">
                  <span class="badge rounded-pill" style="background:<?= $sc['bg'] ?>; color:<?= $sc['color'] ?>; font-size:.65rem; padding:2px 8px"><?= $sc['label'] ?></span>
                  <span class="text-muted" style="font-size:.72rem"><?= $ago ?></span>
                </div>
              </div>
              <div class="text-end flex-shrink-0">
                <div class="fw-bold text-success" style="font-size:.95rem">+<?= $fun_c_format($row['amount']) ?></div>
              </div>
            </div>
            <?php endforeach; ?>
          <?php else: ?>
          <div class="text-center py-5 text-muted">
            <div style="width:56px;height:56px;background:#f1f5f9;border-radius:50%;display:flex;align-items:center;justify-content:center;margin:0 auto 12px;">
              <i class="fas fa-receipt fs-4 text-muted opacity-50"></i>
            </div>
            <div style="font-size:.9rem"><?= __('user.no_recent_activity') ?></div>
          </div>
          <?php endif; ?>
        </div>
      </div>
    </div>

    <div class="col-lg-5">
      <div class="v15-chart-card h-100">
        <div class="v15-section-hdr">
          <h6><i class="fas fa-bolt text-warning me-2"></i><?= __('user.quick_actions') ?></h6>
        </div>
        <div class="p-3 d-flex flex-column gap-1">

          <?php if ($wBalance > 0): ?>
          <a href="<?= base_url('usercontrol/mywallet') ?>" class="v15-action-item text-decoration-none">
            <div class="v15-action-icon" style="background:#dbeafe; color:#1d4ed8"><i class="fas fa-paper-plane"></i></div>
            <div class="flex-grow-1">
              <div class="fw-semibold text-dark" style="font-size:.88rem"><?= __('user.request_withdrawal') ?></div>
              <div class="text-muted" style="font-size:.75rem"><?= $fun_c_format($wBalance) ?> <?= __('user.available') ?></div>
            </div>
            <i class="fas fa-chevron-right text-muted opacity-40" style="font-size:.75rem"></i>
          </a>
          <?php endif; ?>

          <a href="<?= base_url('usercontrol/integration_tools') ?>" class="v15-action-item text-decoration-none">
            <div class="v15-action-icon" style="background:#dcfce7; color:#15803d"><i class="fas fa-plus-circle"></i></div>
            <div class="flex-grow-1">
              <div class="fw-semibold text-dark" style="font-size:.88rem"><?= __('user.add_campaign') ?></div>
              <div class="text-muted" style="font-size:.75rem"><?= __('user.create_tracking_link') ?></div>
            </div>
            <i class="fas fa-chevron-right text-muted opacity-40" style="font-size:.75rem"></i>
          </a>

          <?php if ($refer_status && allowMarketVendorPanelSections($marketvendorpanelmode, $userdetails['is_vendor'])): ?>
          <a href="<?= base_url('usercontrol/my_network') ?>" class="v15-action-item text-decoration-none">
            <div class="v15-action-icon" style="background:#ede9fe; color:#7c3aed"><i class="fas fa-users"></i></div>
            <div class="flex-grow-1">
              <div class="fw-semibold text-dark" style="font-size:.88rem"><?= __('user.my_network') ?></div>
              <div class="text-muted" style="font-size:.75rem"><?= __('user.view_referrals') ?></div>
            </div>
            <i class="fas fa-chevron-right text-muted opacity-40" style="font-size:.75rem"></i>
          </a>
          <?php endif; ?>

          <a href="<?= base_url('usercontrol/analytics_dashboard') ?>" class="v15-action-item text-decoration-none">
            <div class="v15-action-icon" style="background:#fef3c7; color:#d97706"><i class="fas fa-chart-bar"></i></div>
            <div class="flex-grow-1">
              <div class="fw-semibold text-dark" style="font-size:.88rem"><?= __('user.full_analytics') ?></div>
              <div class="text-muted" style="font-size:.75rem"><?= __('user.detailed_reports') ?></div>
            </div>
            <i class="fas fa-chevron-right text-muted opacity-40" style="font-size:.75rem"></i>
          </a>

          <a href="<?= base_url('usercontrol/mywallet') ?>" class="v15-action-item text-decoration-none">
            <div class="v15-action-icon" style="background:#f0fdf4; color:#15803d"><i class="fas fa-history"></i></div>
            <div class="flex-grow-1">
              <div class="fw-semibold text-dark" style="font-size:.88rem"><?= __('user.page_title_wallet') ?></div>
              <div class="text-muted" style="font-size:.75rem"><?= __('user.view_all_transactions') ?></div>
            </div>
            <i class="fas fa-chevron-right text-muted opacity-40" style="font-size:.75rem"></i>
          </a>

        </div>
      </div>
    </div>

  </div><!-- /row -->
</div><!-- /container -->

<!-- ═══════════════════════════════
     MODALS (unchanged)
═══════════════════════════════ -->

<!-- QR Code Modal -->
<div class="modal fade" id="model-codemodal" tabindex="-1">
  <div class="modal-dialog modal-dialog-centered scanner">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title exampleModalLabel2"><?= __('user.scanner') ?></h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body py-4"></div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal"><i class="fas fa-times me-2"></i><?= __('user.close') ?></button>
        <button type="button" class="btn btn-primary" id="download-qr-btn"><i class="fas fa-download me-2"></i><?= __('user.download') ?></button>
        <button type="button" class="btn btn-success" id="print-qr-btn"><i class="fas fa-print me-2"></i><?= __('user.print') ?></button>
      </div>
    </div>
  </div>
</div>

<!-- Slug Customization Modal -->
<div class="modal fade" id="slugtting" data-backdrop="static" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <form action="<?= base_url('/usercontrol/create_slug') ?>" method="post">
        <div class="modal-header">
          <h5 class="modal-title exampleModalLabel1"><?= __('user.create_slug'); ?></h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
          <div class="slug-wrapp">
            <div class="form-group">
              <label class="form-label"><?= __('user.slug'); ?></label>
              <input type="text" name="slug" class="form-control" placeholder="<?= __('user.enter_slug_here') ?>">
              <input type="hidden" name="type" />
              <input type="hidden" name="related_id" />
              <input type="hidden" name="target" />
            </div>
            <div class="link-area align-items-center slug-url d-flex gap-2">
              <input type="text" readonly="readonly" class="form-control">
              <a class="bt-all btn btn-warning" href="javascript:void(0)" title="<?= __('user.copied'); ?>">
                <span class="btn-inner"><i class="far fa-copy" alt="<?= __('user.copy') ?>"></i></span>
              </a>
            </div>
          </div>
        </div>
        <div class="modal-footer">
          <button type="submit" class="btn btn-secondary"><?= __('user.create'); ?></button>
          <button type="button" class="btn btn-primary" data-bs-dismiss="modal"><?= __('user.close'); ?></button>
          <button type="button" class="btn btn-primary btn-delete-slug"><?= __('user.delete'); ?></button>
        </div>
      </form>
    </div>
  </div>
</div>

<!-- Welcome Modal -->
<?php
if (isset($welcome) && $welcome['show_popup'] == 'enable'):
    $welcome_shown = $this->session->userdata('dashboard_welcome_shown');
    $force_show = isset($_GET['show_welcome']) && $_GET['show_welcome'] == '1';
    if (!$welcome_shown || $force_show):
        $video_link = $welcome['video_link'] ?? '';
        $embed_url = '';
        if (!empty($video_link)) {
            if (preg_match('/(?:youtube\.com\/watch\?v=|youtu\.be\/|youtube\.com\/embed\/)([^&\?\/]+)/', $video_link, $matches)) {
                $embed_url = 'https://www.youtube.com/embed/' . $matches[1];
            }
        }
?>
<div class="modal fade" id="welcomeModal" tabindex="-1" aria-labelledby="welcomeModalLabel" aria-hidden="true" data-bs-backdrop="static" data-bs-keyboard="false">
  <div class="modal-dialog modal-dialog-centered modal-lg">
    <div class="modal-content border-0 shadow-lg">
      <div class="modal-header bg-primary text-white border-0">
        <h5 class="modal-title fw-bold" id="welcomeModalLabel">
          <i class="fas fa-hand-wave me-2"></i><?= !empty($welcome['heading']) ? htmlspecialchars($welcome['heading']) : __('user.welcome_to_dashboard') ?>
        </h5>
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body p-4">
        <?php if (!empty($embed_url)): ?>
        <div class="ratio ratio-16x9 mb-4">
          <iframe src="<?= $embed_url ?>" title="<?= htmlspecialchars($welcome['heading']) ?>" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen class="rounded"></iframe>
        </div>
        <?php endif; ?>
        <?php if (!empty($welcome['content'])): ?>
        <div class="alert alert-info border-0 mb-0">
          <div class="d-flex align-items-start">
            <i class="fas fa-info-circle fs-4 me-3 mt-1 flex-shrink-0"></i>
            <div><?= nl2br(htmlspecialchars($welcome['content'])) ?></div>
          </div>
        </div>
        <?php endif; ?>
      </div>
      <div class="modal-footer border-0 bg-light">
        <button type="button" class="btn btn-secondary" id="welcomeDontShowAgain"><i class="fas fa-times-circle me-1"></i><?= __('user.dont_show_again') ?></button>
        <button type="button" class="btn btn-primary" data-bs-dismiss="modal"><i class="fas fa-check me-1"></i><?= __('user.get_started') ?></button>
      </div>
    </div>
  </div>
</div>
<script>
$(document).ready(function() {
    $('#welcomeModal').modal('show');
    $('#welcomeDontShowAgain').on('click', function() {
        $.post('<?= base_url("usercontrol/set_welcome_shown") ?>', function() {
            $('#welcomeModal').modal('hide');
        });
    });
});
</script>
<?php
    endif;
endif;
?>

<!-- AI Suggestion Panel -->
<?php
$hide_forever = $this->session->userdata('hide_ai_box_forever');
$settings_ai  = $this->Product_model->getSettings('userdashboard');
$ai_enabled   = isset($settings_ai['ai_suggestion_enabled']) ? $settings_ai['ai_suggestion_enabled'] : '1';
if (!$hide_forever && $ai_enabled == '1'):
?>
<button type="button" id="ai-suggestion-toggle" class="btn btn-primary rounded-circle position-fixed d-flex align-items-center justify-content-center shadow-lg" style="bottom:24px;right:24px;width:52px;height:52px;z-index:1050;">
  <i class="fas fa-robot"></i>
</button>
<div id="ai-suggestion-panel" class="position-fixed top-0 end-0 bg-white shadow-lg" style="width:350px;max-width:100%;height:100vh;z-index:1060;transform:translateX(100%);transition:transform .3s ease-in-out;">
  <div class="bg-primary text-white px-3 py-3 d-flex align-items-center justify-content-between">
    <div class="d-flex align-items-center"><i class="fas fa-robot me-2"></i><span class="fw-bold"><?= __('user.assistant_title') ?></span></div>
    <button type="button" class="btn-close btn-close-white" id="ai-suggestion-close"></button>
  </div>
  <div class="p-3">
    <div id="ai-suggestion-text" class="mb-3">
      <div class="d-flex align-items-center text-muted"><div class="spinner-border spinner-border-sm me-2" role="status"></div><span><?= __('user.loading') ?></span></div>
    </div>
    <div class="text-center mb-2"><span class="badge bg-light text-muted fst-italic px-3 py-2"><?= __('user.powered') ?></span></div>
  </div>
  <div class="border-top p-3">
    <div class="row g-2">
      <div class="col-6"><button type="button" id="ai-suggestion-refresh" class="btn btn-outline-primary btn-sm w-100"><i class="fas fa-sync-alt me-1"></i><?= __('user.new_suggestion') ?></button></div>
      <div class="col-6"><button type="button" id="ai-suggestion-dismiss" class="btn btn-outline-secondary btn-sm w-100"><i class="fas fa-times me-1"></i><?= __('user.dismiss') ?></button></div>
    </div>
    <div class="text-center mt-2">
      <button type="button" id="ai-suggestion-hide-forever" class="btn btn-link btn-sm text-muted"><?= __('user.hide_forever') ?></button>
    </div>
  </div>
</div>
<?php endif; ?>

<!-- Social Share Modal -->
<?= $social_share_modal ?>

<!-- ═══════════════════════════════
     SCRIPTS (unchanged logic)
═══════════════════════════════ -->
<script>
document.addEventListener('DOMContentLoaded', function() {
const aiToggle   = document.getElementById('ai-suggestion-toggle');
const aiPanel    = document.getElementById('ai-suggestion-panel');
const aiClose    = document.getElementById('ai-suggestion-close');
const aiText     = document.getElementById('ai-suggestion-text');
const aiCounter  = document.getElementById('ai-suggestion-counter');
const aiRefresh  = document.getElementById('ai-suggestion-refresh');
const aiDismiss  = document.getElementById('ai-suggestion-dismiss');
const aiHideForever = document.getElementById('ai-suggestion-hide-forever');
let currentSuggestionIndex = -1;
let isLoading = false;
if (!aiPanel || !aiToggle) return;

function openPanel()  { aiPanel.style.transform = 'translateX(0)'; loadSuggestion(); }
function closePanel() { aiPanel.style.transform = 'translateX(100%)'; }

function loadSuggestion() {
  if (isLoading) return; isLoading = true;
  aiText.innerHTML = `<div class="d-flex align-items-center text-muted"><div class="spinner-border spinner-border-sm me-2"></div><span><?= __('user.loading') ?></span></div>`;
  const fd = new FormData(); fd.append('action','get');
  fetch('<?= base_url('usercontrol/ai_suggestion') ?>',{method:'POST',body:fd})
  .then(r=>r.json()).then(data=>{
    isLoading=false;
    if(data.error){aiText.innerHTML=`<p class="text-danger">${data.error}</p>`;return;}
    currentSuggestionIndex=data.index; aiText.innerHTML=`<p>${data.suggestion}</p>`;
    if(data.total>0&&aiCounter) aiCounter.textContent=`${data.index+1} <?= __('user.of') ?> ${data.total}`;
  }).catch(()=>{ isLoading=false; aiText.innerHTML=`<p class="text-danger"><?= __('user.loading_error') ?></p>`; });
}
function refreshSuggestion() {
  if(isLoading) return; isLoading=true;
  aiText.innerHTML=`<div class="d-flex align-items-center text-muted"><div class="spinner-border spinner-border-sm me-2"></div><span><?= __('user.loading_new') ?></span></div>`;
  const fd=new FormData(); fd.append('action','refresh'); fd.append('current_index',currentSuggestionIndex);
  fetch('<?= base_url('usercontrol/ai_suggestion') ?>',{method:'POST',body:fd})
  .then(r=>r.json()).then(data=>{
    isLoading=false;
    if(data.error){aiText.innerHTML=`<p class="text-danger">${data.error}</p>`;return;}
    currentSuggestionIndex=data.index; aiText.innerHTML=`<p>${data.suggestion}</p>`;
    if(data.total>0&&aiCounter) aiCounter.textContent=`${data.index+1} <?= __('user.of') ?> ${data.total}`;
  }).catch(()=>{ isLoading=false; aiText.innerHTML=`<p class="text-danger"><?= __('user.refresh_error') ?></p>`; });
}
function dismissSuggestion()    { const fd=new FormData(); fd.append('action','dismiss'); fetch('<?= base_url('usercontrol/ai_suggestion') ?>',{method:'POST',body:fd}); closePanel(); }
function hideSuggestionForever(){ const fd=new FormData(); fd.append('action','hide_forever'); fetch('<?= base_url('usercontrol/ai_suggestion') ?>',{method:'POST',body:fd}); closePanel(); aiToggle.style.display='none'; }

aiToggle.addEventListener('click', openPanel);
aiClose.addEventListener('click', closePanel);
aiRefresh?.addEventListener('click', refreshSuggestion);
aiDismiss?.addEventListener('click', dismissSuggestion);
aiHideForever?.addEventListener('click', hideSuggestionForever);

$(document).ready(function() {
  let currentQRCode = null, currentQRUrl = '';
  $(document).on('click', '.qrcode', function() {
    currentQRUrl = $(this).attr('data-id');
    $('#model-codemodal .modal-body').html(`<div class="d-flex flex-column align-items-center"><div class="spinner-border text-primary mb-2"></div><small class="text-muted"><?= __('user.generating_qr_code') ?></small></div>`);
    $('#model-codemodal').modal('show');
    setTimeout(()=>{
      try {
        $('#model-codemodal .modal-body').html(`<div id="qr-code-container" class="mb-3 d-flex justify-content-center" style="min-height:200px"></div><div id="qr-url-display" class="text-muted small px-3 text-break mb-2">${currentQRUrl}</div><div class="text-muted small"><i class="fas fa-info-circle me-1"></i><?= __('user.scan_with_phone_camera') ?></div>`);
        const qrSize = window.innerWidth<768?180:220;
        currentQRCode = new QRCode(document.getElementById('qr-code-container'),{text:currentQRUrl,width:qrSize,height:qrSize,colorDark:'#000',colorLight:'#fff',correctLevel:QRCode.CorrectLevel.H});
      } catch(e){ $('#model-codemodal .modal-body').html(`<div class="alert alert-danger"><i class="fas fa-exclamation-triangle me-2"></i><?= __('user.qr_generation_failed') ?></div>`); }
    },300);
  });
  $(document).on('click','#download-qr-btn',function(){
    if(!currentQRCode) return;
    const canvas=$('#qr-code-container canvas')[0];
    if(canvas){ const a=document.createElement('a'); a.download='qrcode-'+Date.now()+'.png'; a.href=canvas.toDataURL(); a.click(); }
  });
  $(document).on('click','#print-qr-btn',function(){
    if(!currentQRCode) return;
    const canvas=$('#qr-code-container canvas')[0];
    if(canvas){ const w=window.open('','_blank'); w.document.write(`<html><head><title>QR</title><style>body{text-align:center;margin:40px;font-family:Arial}</style></head><body><h2><?= __('user.qr_code') ?></h2><img src="${canvas.toDataURL()}"/><div style="margin-top:20px;word-break:break-all;color:#666">${currentQRUrl}</div></body></html>`); w.document.close(); w.print(); }
  });
  $('#show_my_id').change(function(){
    if($(this).prop('checked')){ $('.show-mega-link').removeClass('d-none'); $('.show-tiny-link').addClass('d-none'); }
    else { $('.show-mega-link').addClass('d-none'); $('.show-tiny-link').removeClass('d-none'); }
  });
});
});
</script>

<!-- Chart.js -->
<script src="<?= base_url('assets/template/js/chart.umd.min.js') ?>?v=<?= av() ?>"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    var _trustInfo = document.getElementById('trust-score-info-btn');
    if (_trustInfo && typeof bootstrap !== 'undefined' && bootstrap.Tooltip) {
        bootstrap.Tooltip.getOrCreateInstance(_trustInfo);
    }
    <?php if(isset($trends)): ?>
    var trendData = <?= json_encode($trends) ?>;
    createSparkline('userSparkEarnings',    trendData.commissions, '#0d6efd', 'rgba(13,110,253,0.15)');
    createSparkline('userSparkClicks',      trendData.clicks,      '#8b5cf6', 'rgba(139,92,246,0.15)');
    createSparkline('userSparkConversions', trendData.orders,      '#198754', 'rgba(25,135,84,0.15)');
    var saleComm  = <?= (float)($user_totals['external_sale_total']  ?? 0) ?>;
    var clickComm = <?= (float)($user_totals['external_click_total'] ?? 0) ?>;
    var storeComm = <?= (float)($user_totals['localstore_sale_total'] ?? 0) ?>;
    createRingChart('userRingChart',
        ['<?= addslashes(__('user.sale_commission')) ?>', '<?= addslashes(__('user.click_commission')) ?>', '<?= addslashes(__('user.store_commission')) ?>'],
        [saleComm, clickComm, storeComm],
        ['#3b82f6','#8b5cf6','#22c55e']
    );
    <?php endif; ?>
});
</script>
