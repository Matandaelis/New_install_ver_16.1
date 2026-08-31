<div class="container-fluid px-4 pb-4">
  <div class="row">
    <div class="col-12">

      <?php get_instance()->load->view('admincontrol/membership/_membership_nav'); ?>

      <div class="d-flex justify-content-between align-items-center mb-3">
        <h4 class="fw-bold mb-0"><?= __('admin.membership_plans') ?></h4>
        <a href="<?= base_url('membership/plan_create') ?>" class="btn btn-success btn-sm">
          <i class="fas fa-plus me-1"></i><?= __('admin.create_new_plan') ?>
        </a>
      </div>

      <form id="form_plan">
        <div class="card border-0 shadow-sm">
          <div class="card-header bg-light border-0 py-3">
            <div class="d-flex justify-content-between align-items-center">
              <h5 class="card-title mb-0">
                <i class="fas fa-list text-info me-2"></i>
                <?= __('admin.plans_list') ?>
              </h5>
              <div class="d-flex align-items-center">
                <span class="badge bg-primary me-2"><?= count($plans) ?> <?= __('admin.total_plans') ?></span>
                <button type="button" class="btn btn-outline-secondary btn-sm" onclick="location.reload()">
                  <i class="fas fa-sync-alt me-1"></i><?= __('admin.refresh') ?>
                </button>
              </div>
            </div>
          </div>
          <div class="card-body p-0">
            <div class="table-responsive">
              <table class="table table-hover mb-0">
                <thead class="table-light">
                  <tr>
                    <th class="border-0 fw-semibold"><?= __('admin.id') ?></th>
                    <th class="border-0 fw-semibold"><?= __('admin.name') ?></th>
                    <th class="border-0 fw-semibold"><?= __('admin.plan_user_type') ?></th>
                    <th class="border-0 fw-semibold"><?= __('admin.price') ?></th>
                    <th class="border-0 fw-semibold"><?= __('admin.bonus') ?></th>
                    <?php if ($award_level['status']){ ?>
                    <th class="border-0 fw-semibold"><?= __('admin.commission_sale_status') ?></th>
                    <th class="border-0 fw-semibold"><?= __('admin.plan_level') ?></th>
                    <?php } ?>
                    <th class="border-0 fw-semibold"><?= __('admin.type') ?></th>
                    <th class="border-0 fw-semibold"><?= __('admin.billing_period') ?></th>
                    <th class="border-0 fw-semibold"><?= __('admin.period_days') ?></th>
                    <th class="border-0 fw-semibold"><?= __('admin.is_display') ?></th>
                    <th class="border-0 fw-semibold"><?= __('admin.updated_at') ?></th>
                    <th class="border-0 fw-semibold text-center"><?= __('admin.action') ?></th>
                  </tr>
                </thead>
                <tbody>
                  <?php if(count($plans) == 0){ ?>
                    <tr>
                      <td colspan="<?= $award_level['status'] ? '13' : '11' ?>" class="text-center py-5">
                        <div class="text-muted">
                          <i class="fas fa-layer-group fa-3x mb-3 d-block"></i>
                          <h5><?= __('admin.no_records_found') ?></h5>
                          <p><?= __('admin.create_first_plan') ?></p>
                          <a href="<?= base_url('membership/plan_create') ?>" class="btn btn-primary mt-2">
                            <i class="fas fa-plus me-2"></i><?= __('admin.create_new_plan') ?>
                          </a>
                        </div>
                      </td>
                    </tr>
                  <?php } ?>

                  <?php foreach ($plans as $key => $plan) { ?>
                    <tr>
                      <td class="align-middle">
                        <span class="badge bg-light text-dark border">#<?= $plan->id ?></span>
                      </td>
                      <td class="align-middle">
                        <strong><?= $plan->name ?></strong>
                      </td>
                      <td class="align-middle">
                        <?php if($plan->user_type == 2) { ?>
                          <span class="badge bg-info"><?= __('admin.vendor') ?></span>
                        <?php } else { ?>
                          <span class="badge bg-primary"><?= __('admin.affiliate') ?></span>
                        <?php } ?>
                      </td>
                      <td class="align-middle">
                        <strong class="text-success"><?= c_format($plan->price) ?></strong>
                      </td>
                      <td class="align-middle">
                        <span class="text-warning"><?= c_format($plan->bonus) ?></span>
                      </td>
                      <?php if ($award_level['status']){ ?>
                        <td class="align-middle">
                          <?php if($plan->commission_sale_status) { ?>
                            <span class="badge bg-success"><?= __('admin.enabled') ?></span>
                          <?php } else { ?>
                            <span class="badge bg-secondary"><?= __('admin.disabled') ?></span>
                          <?php } ?>
                        </td>
                        <td class="align-middle">
                          <?php if($plan->commission_sale_status) { ?>
                            <span class="badge bg-info">
                              <?= $plan->plan_level ? $plan->plan_level : __('admin.default') ?>
                            </span>
                          <?php } else { ?>
                            <span class="badge bg-secondary"><?= __('admin.disabled') ?></span>
                          <?php } ?>
                        </td>
                      <?php } ?>
                      <td class="align-middle">
                        <?php if($plan->type == 'free') { ?>
                          <span class="badge bg-success"><?= __('admin.free') ?></span>
                        <?php } elseif($plan->type == 'paid') { ?>
                          <span class="badge bg-primary"><?= __('admin.paid') ?></span>
                        <?php } else { ?>
                          <span class="badge bg-secondary"><?= $plan->type ?></span>
                        <?php } ?>
                      </td>
                      <td class="align-middle">
                        <small class="text-muted"><?= $plan->billing_period_plain ?></small>
                      </td>
                      <td class="align-middle">
                        <span class="badge bg-light text-dark border">
                          <?= ($plan->billing_period == 'lifetime_free') ? __('admin.life_time') : $plan->total_day ?>
                        </span>
                      </td>
                      <td class="align-middle">
                        <?php if($plan->status) { ?>
                          <span class="badge bg-success"><?= __('admin.yes') ?></span>
                        <?php } else { ?>
                          <span class="badge bg-danger"><?= __('admin.no') ?></span>
                        <?php } ?>
                      </td>
                      <td class="align-middle">
                        <small class="text-muted"><?= dateFormat($plan->updated_at) ?></small>
                      </td>
                      <td class="align-middle text-center">
                        <div class="btn-group" role="group">
                          <button type="button" class="btn btn-outline-info btn-sm plan-user-setting" data-user_type="<?= $plan->user_type ?>" data-campaign="<?= isset($plan->campaign) ? (int) $plan->campaign : 'NULL' ?>" data-product="<?= isset($plan->product) ? (int) $plan->product : 'NULL' ?>" title="<?= __('admin.view_settings') ?>">
                            <i class="fas fa-cog"></i>
                          </button>
                          <a href="<?= base_url('membership/plan_edit/'. $plan->id) ?>" class="btn btn-outline-primary btn-sm" title="<?= __('admin.edit') ?>">
                            <i class="fas fa-edit"></i>
                          </a>
                          <button type="button" onclick="delete_confirm('<?= base_url('membership/plan_delete/'. $plan->id) ?>')" class="btn btn-outline-danger btn-sm" title="<?= __('admin.delete') ?>">
                            <i class="fas fa-trash"></i>
                          </button>
                        </div>
                      </td>
                    </tr>
                  <?php } ?>
                </tbody>
              </table>
            </div>
          </div>
          <?php if(isset($pagination) && $pagination){ ?>
            <div class="card-footer bg-light border-0">
              <div class="d-flex justify-content-between align-items-center flex-wrap gap-2">
                <div class="text-muted">
                  <?= $pagination_summary ?>
                </div>
                <?= $pagination ?>
              </div>
            </div>
          <?php } ?>
        </div>
      </form>
    </div> <!-- End col-12 -->
  </div> <!-- End row -->
</div> <!-- End container-fluid -->


<!-- Plan User Settings Modal -->
<div id="plan-user-setting" class="modal fade" tabindex="-1" role="dialog" data-bs-backdrop="static" data-bs-keyboard="false" aria-labelledby="planUserSettingTitle" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered" role="document">
    <div class="modal-content border-0 shadow">
      <div class="modal-header bg-light border-0">
        <h5 class="modal-title" id="planUserSettingTitle">
          <i class="fas fa-info-circle text-info me-2"></i>
          <?= __('admin.plan_user_setting') ?>
        </h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="<?= __('admin.close') ?>"></button>
      </div>
      <div class="modal-body p-4">
        <div class="row g-3">
          <div class="col-12">
            <div class="border rounded p-3 bg-light">
              <small class="text-muted d-block mb-1"><?= __('admin.user_type') ?></small>
              <strong class="user-type fs-6"></strong>
            </div>
          </div>
          <div class="col-12 campaign-group">
            <div class="border rounded p-3 bg-light">
              <small class="text-muted d-block mb-1"><?= __('admin.campaign') ?></small>
              <strong class="campaign fs-6"></strong>
            </div>
          </div>
          <div class="col-12 product-group">
            <div class="border rounded p-3 bg-light">
              <small class="text-muted d-block mb-1"><?= __('admin.product') ?></small>
              <strong class="product fs-6"></strong>
            </div>
          </div>
        </div>
      </div>
      <div class="modal-footer bg-light border-0">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
          <i class="fas fa-times me-2"></i><?= __('admin.close') ?>
        </button>
      </div> 
    </div>
  </div>
</div>


<script type="text/javascript">

	function delete_confirm(url) {

		Swal.fire({

			title: '<?= __('admin.are_you_sure') ?>',

			text: "<?= __('admin.you_want_be_able_to_revert_this') ?>",

			icon: 'warning',

			showCancelButton: true,

			confirmButtonText: '<?= __('admin.yes_delete_it') ?>',

			cancelButtonText: '<?= __('admin.no_cancel') ?>',

			reverseButtons: true

		}).then((result) => {

			if (result.value) {

				window.location.href = url;

			}

		})

		return false;

	}

	$(".plan-user-setting").on('click',function(){
		if($(this).data('user_type') == 2){
			$("#plan-user-setting .user-type").text('<?= __('admin.vendor') ?>');

			let campaign = ($(this).data('campaign') != 'NULL') ? $(this).data('campaign') : '<?= __('admin.unlimited') ?>';
			$("#plan-user-setting .campaign").text(campaign);
			$("#plan-user-setting .campaign-group").removeClass('d-none');

			let product = ($(this).data('product') != 'NULL') ? $(this).data('product') : '<?= __('admin.unlimited') ?>';
			$("#plan-user-setting .product").text(product);
			$("#plan-user-setting .product-group").removeClass('d-none');
		} else {
			$("#plan-user-setting .user-type").text('<?= __('admin.affiliate') ?>');
			$("#plan-user-setting .campaign-group").addClass('d-none');
			$("#plan-user-setting .product-group").addClass('d-none');
		}

		$("#plan-user-setting").modal("show");
	})
</script>