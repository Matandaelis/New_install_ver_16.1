<div class="modal-dialog">
  <div class="modal-content">
    <div class="modal-header">
      <h5 class="modal-title m-0"><?= __('admin.edit_user_membership') ?></h5>
      <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
    </div>
    <?php if ($MembershipSetting['status']) { ?>
      <nav>
        <div class="nav nav-pills nav-justified" id="TabsNav" role="tablist">
          <li role="presentation" class="active nav-item">
            <a class="nav-link active bg-primary text-white show" id="mmu-currentplan" href="#nav-home" aria-controls="nav-home" role="tab" data-bs-toggle="tab"><?= __('admin.current_plan') ?></a>
          </li>
          <li role="presentation" class="nav-item">
            <a class="nav-link bg-secondary text-white show" id="mmi-newplan" href="#nav-profile" aria-controls="nav-profile" role="tab" data-bs-toggle="tab"><?= __('admin.change_plan') ?></a>
          </li>
        </div>
      </nav>

      <div class="tab-content" id="nav-tabContent">
        <div class="tab-pane fade show active" id="nav-home" role="tabpanel" aria-labelledby="mmu-currentplan">
          <?php if (isset($is_lifetime_plan) && $is_lifetime_plan) { ?>
            <div class="card-body">
              <h4 class="text-center text-success"><?= __('admin.lifetime_free_membership') ?></h4>
              <p class="text-center text-muted"><?= __('admin.user_have_lifetime_free_membership_info') ?></p>
            </div>
          <?php } else if (isset($plan) && $plan) { ?>
            <div class="card-body">
              <h4 class="text-success"><?= __('admin.plan') ?>: <?= $plan->plan ? $plan->plan->name : '' ?></h4>
            </div>
            <ul class="list-group list-group-flush">
              <li class="list-group-item">
                <?= __('admin.plan_date') ?>
                <span class="float-end text-primary">
                  <?= dateFormat($plan->started_at, 'd F Y') . " to " . $plan->expire_text ?>
                </span>
              </li>
              <li class="list-group-item">
                <?= __('admin.remain_days') ?>
                <span class="float-end text-primary">
                  <?php
                  $remain = $plan->remainDay();
                  if ($remain === 'lifetime') {
                    echo '<span class="fs-1">&infin;</span>';
                  } else {
                    echo $remain;
                  }
                  ?>
                </span>
              </li>
              <li class="list-group-item">
                <?= __('admin.plan_status') ?>
                <span class="float-end text-primary">
                  <?= $plan->status_text ?>
                </span>
              </li>
              <li class="list-group-item">
                <?= __('admin.active') ?>
                <span class="float-end text-primary">
                  <?= $plan->active_text ?>
                </span>
              </li>
            </ul>
            <div class="card-body">
            </div>
          <?php } else { ?>
            <div class="modal-body">
              <p class="text-center"><?= __('admin.user_have_no_any_membership_plan') ?></p>
            </div>
          <?php } ?>
        </div>

        <div class="tab-pane fade" id="nav-profile" role="tabpanel" aria-labelledby="mmi-newplan">
          <form class="change-plan-form">
            <ul class="list-group">
              <?php foreach ($plan_lists as $key => $p) { ?>
                <?php if (($p->user_type == 1 && $is_vendor == 0) || ($p->user_type == 2 && $is_vendor == 1)) { ?>
                  <li class="list-group-item">
                    <div class="form-check">
                      <input class="form-check-input" <?= (isset($plan->plan_id) && $plan->plan_id == $p->id) ? 'checked' : '' ?> value="<?= $p->id ?>" type="radio" name="new_planid" id="plan-radio-<?= $p->id ?>">
                      <label class="form-check-label fw-semibold" for="plan-radio-<?= $p->id ?>">
                        <?= $p->name ?>
                      </label>
                    </div>
                    <div class="mt-2">
                      <div class="row g-2">
                        <div class="col-12 col-md-4">
                          <span class="badge bg-light text-dark w-100 text-start">
                            <?= __('admin.price') ?>: <span class="text-success fw-semibold"><?= c_format($p->price) ?></span>
                          </span>
                        </div>
                        <div class="col-12 col-md-4">
                          <span class="badge bg-light text-dark w-100 text-start">
                            <?= __('admin.billing_period') ?>: <span class="fw-semibold"><?= isset($p->billing_period_plain) ? $p->billing_period_plain : '' ?></span>
                          </span>
                        </div>
                        <div class="col-12 col-md-4">
                          <span class="badge bg-light text-dark w-100 text-start">
                            <?= __('admin.bonus') ?>: <span class="text-warning fw-semibold"><?= c_format(isset($p->bonus) ? $p->bonus : 0) ?></span>
                          </span>
                        </div>
                        <div class="col-12 col-md-4">
                          <span class="badge bg-light text-dark w-100 text-start">
                            <?= __('admin.type') ?>: 
                            <?php if(isset($p->type) && $p->type == 'free'){ ?>
                              <span class="badge bg-success ms-1"><?= __('admin.free') ?></span>
                            <?php } elseif(isset($p->type) && $p->type == 'paid'){ ?>
                              <span class="badge bg-primary ms-1"><?= __('admin.paid') ?></span>
                            <?php } else { ?>
                              <span class="badge bg-secondary ms-1"><?= isset($p->type) ? $p->type : '' ?></span>
                            <?php } ?>
                          </span>
                        </div>
                        <?php if(isset($p->commission_sale_status)) { ?>
                        <div class="col-12 col-md-4">
                          <span class="badge bg-light text-dark w-100 text-start">
                            <?= __('admin.commission_sale_status') ?>: 
                            <?php if($p->commission_sale_status) { ?>
                              <span class="badge bg-success ms-1"><?= __('admin.enabled') ?></span>
                            <?php } else { ?>
                              <span class="badge bg-secondary ms-1"><?= __('admin.disabled') ?></span>
                            <?php } ?>
                          </span>
                        </div>
                        <?php } ?>
                        <?php if(isset($p->plan_level) && $p->plan_level) { ?>
                        <div class="col-12 col-md-4">
                          <span class="badge bg-light text-dark w-100 text-start">
                            <?= __('admin.plan_level') ?>: <span class="badge bg-info ms-1"><?= $p->plan_level ?></span>
                          </span>
                        </div>
                        <?php } ?>
                        <div class="col-12 col-md-4">
                          <span class="badge bg-light text-dark w-100 text-start">
                            <?= __('admin.period_days') ?>: <span class="fw-semibold"><?= ($p->billing_period == 'lifetime_free') ? __('admin.life_time') : (isset($p->total_day) ? $p->total_day : '') ?></span>
                          </span>
                        </div>
                      </div>
                    </div>
                  </li>
                <?php } ?>
              <?php } ?>
            </ul>
            <div class="modal-body">
              <input type="hidden" name="user_id" value="<?= $user->id ?>">
              <div class="mb-3">
                <label class="form-label"><?= __('admin.status') ?></label>
                <div class="btn-group flex-wrap" role="group" aria-label="<?= __('admin.status') ?>">
                  <?php 
                    $status_list = App\MembershipPlan::$status_list; 
                    $ordered = [];
                    $activeKey = null;
                    foreach($status_list as $k => $v){ if($v === 'Active'){ $activeKey = $k; break; } }
                    if($activeKey !== null){ $ordered[$activeKey] = $status_list[$activeKey]; unset($status_list[$activeKey]); }
                    $ordered = $ordered + $status_list;
                    foreach ($ordered as $key => $value) { 
                      $label = $value;
                      if ($value == 'Received') { $label = __('admin.received'); }
                      elseif ($value == 'Complete') { $label = __('admin.complete'); }
                      elseif ($value == 'Total not match') { $label = __('admin.total_not_match'); }
                      elseif ($value == 'Denied') { $label = __('admin.denied'); }
                      elseif ($value == 'Expired') { $label = __('admin.expired'); }
                      elseif ($value == 'Failed') { $label = __('admin.failed'); }
                      elseif ($value == 'Processed') { $label = __('admin.processed'); }
                      elseif ($value == 'Refunded') { $label = __('admin.refunded'); }
                      elseif ($value == 'Reversed') { $label = __('admin.reversed'); }
                      elseif ($value == 'Voided') { $label = __('admin.voided'); }
                      elseif ($value == 'Canceled Reversal') { $label = __('admin.cancel_reversal'); }
                      elseif ($value == 'Waiting For Payment') { $label = __('admin.waiting_for_payment'); }
                      elseif ($value == 'Pending') { $label = __('admin.pending'); }
                      elseif ($value == 'Active') { $label = __('admin.active'); }
                      $btnClass = 'btn-outline-secondary';
                      if ($value == 'Active') { $btnClass = 'btn-outline-success'; }
                      elseif (in_array($value, ['Pending','Waiting For Payment'])) { $btnClass = 'btn-outline-warning'; }
                      elseif (in_array($value, ['Complete','Processed'])) { $btnClass = 'btn-outline-primary'; }
                      elseif (in_array($value, ['Denied','Expired','Failed','Voided','Reversed','Canceled Reversal'])) { $btnClass = 'btn-outline-danger'; }
                      elseif (in_array($value, ['Received','Refunded'])) { $btnClass = 'btn-outline-info'; }
                      $inputId = 'status-'.htmlspecialchars($key);
                  ?>
                    <input class="btn-check" type="radio" name="status_id" id="<?= $inputId ?>" value="<?= htmlspecialchars($key) ?>" <?= ($value=='Active' ? 'autofocus' : '') ?>>
                    <label class="btn <?= $btnClass ?> btn-sm mb-2 me-2" for="<?= $inputId ?>"><?= $label ?></label>
                  <?php } ?>
                </div>
              </div>
              <div class="mb-3">
                <label class="form-label"><?= __('admin.comment') ?></label>
                <textarea class="form-control" name="comment"></textarea>
              </div>
            </div>
          </form>
          <div class="modal-footer">
            <button class="btn btn-primary btn-change-plan"><?= __('admin.change_plan') ?></button>
          </div>
        </div>
      </div>
    <?php } else { ?>
      <div class="modal-body">
        <p class="text-center"><?= __('admin.membership_is_not_active') ?></p>
      </div>
    <?php } ?>
  </div>
</div>

<script type="text/javascript">
  $(".btn-change-plan").click(function(){
    $this = $(this);
    $.ajax({
      url: '<?= base_url("membership/user_plan_modal") ?>',
      type: 'POST',
      dataType: 'json',
      data: $(".change-plan-form").serialize(),
      beforeSend: function() {
        $this.addClass("disabled").attr("aria-disabled", true).button('loading');
      },
      complete: function() {
        $this.removeClass("disabled").attr("aria-disabled", false).button('reset');
      },
      success: function(json) {
        $container = $('.change-plan-form');
        $container.find(".is-invalid").removeClass("is-invalid");
        $container.find("span.invalid-feedback").remove();

        if (json['reload']) {
          window.location.reload();
        }

        if (json['errors']) {
          $.each(json['errors'], function(i, j) {
            $ele = $container.find('[name="' + i + '"]');
            if ($ele) {
              $ele.addClass("is-invalid");
              if ($ele.parent(".input-group").length) {
                $ele.parent(".input-group").after("<span class='invalid-feedback'>" + j + "</span>");
              } else {
                $ele.after("<span class='invalid-feedback'>" + j + "</span>");
              }
            }
          });
        }
      },
    });
  });
</script>

