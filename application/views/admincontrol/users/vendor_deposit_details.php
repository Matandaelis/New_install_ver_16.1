<?php if($saas_status){ ?>		
<div class="container-fluid px-4 pb-4">
    <?php $this->load->view('admincontrol/setting/_saas_nav'); ?>
    <div class="row">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h4 class="fw-bold mb-0">
                    <i class="fas fa-credit-card me-2"></i><?= __('admin.deposit_requests_details') ?> #<?= $request['vd_id'] ?>
                </h4>
                <a href="<?= base_url('admincontrol/vendor_deposits') ?>" class="btn btn-outline-secondary btn-sm">
                    <i class="fas fa-arrow-left me-1"></i><?= __('admin.back') ?>
                </a>
            </div>
            <div class="card border-0 shadow-sm">
                <div class="card-body p-4">
                    <!-- Request Details -->
                    <div class="row mb-4">
                        <div class="col-lg-4 col-md-6 mb-3">
                            <div class="card h-100 border-0 shadow-sm">
                                <div class="card-header bg-info text-white">
                                    <h6 class="mb-0 fw-semibold">
                                        <i class="fas fa-info-circle me-2"></i><?= __('admin.request_details') ?>
                                    </h6>
                                </div>
                                <div class="card-body">
                                    <div class="table-responsive">
                                        <table class="table table-sm table-borderless mb-0">
                                            <tbody>
                                                <tr>
                                                    <td class="fw-semibold text-muted" style="width: 40%;"><?= __('admin.id') ?></td>
                                                    <td><span class="badge bg-primary">#<?= $request['vd_id'] ?></span></td>
                                                </tr>
                                                <tr>
                                                    <td class="fw-semibold text-muted"><?= __('admin.user') ?></td>
                                                    <td class="fw-medium"><?= $request['username'] ?></td>
                                                </tr>
                                                <tr>
                                                    <td class="fw-semibold text-muted"><?= __('admin.amount_deposited') ?></td>
                                                    <td class="fw-bold text-success fs-5"><?= c_format($request['vd_amount']) ?></td>
                                                </tr>
                                                <tr>
                                                    <td class="fw-semibold text-muted"><?= __('admin.payment_method') ?></td>
                                                    <td><span class="badge bg-secondary"><?= __('admin.'.$request['vd_payment_method']) ?></span></td>
                                                </tr>
                                                <tr>
                                                    <td class="fw-semibold text-muted"><?= __('admin.payment_status') ?></td>
                                                    <td><?= withdrwal_status($request['vd_status']) ?></td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-lg-4 col-md-6 mb-3">
                            <div class="card h-100 border-0 shadow-sm">
                                <div class="card-header bg-success text-white">
                                    <h6 class="mb-0 fw-semibold">
                                        <i class="fas fa-file-alt me-2"></i><?= __('admin.submitted_details') ?>
                                    </h6>
                                </div>
                                <div class="card-body">
                                    <div class="table-responsive">
                                        <table class="table table-sm table-borderless mb-0">
                                            <tbody>
                                                <?php
                                                $data = json_decode($request['vd_meta'],1);
                                                $hasDetails = false;
                                                foreach ($data as $key => $value) {
                                                    if(!empty($value)) {
                                                        $hasDetails = true;
                                                        if($key == 'payment_proof') {
                                                            echo '<tr>
                                                                <td class="fw-semibold text-muted text-capitalize">'.str_replace("_", " ", $key) .'</td>
                                                                <td><a target="_blank" href="'.base_url('assets/user_upload/'.$value).'" class="btn btn-outline-primary btn-sm"><i class="fas fa-download me-1"></i>'.$value.'</a></td>
                                                            </tr>';
                                                            continue;
                                                        }
                                                ?>
                                                <tr>
                                                    <td class="fw-semibold text-muted text-capitalize" style="width: 40%;"><?= str_replace("_", " ", $key) ?></td>
                                                    <td><?= $value ?></td>
                                                </tr>
                                                <?php }} ?>

                                                <?php if(!$hasDetails) { ?>
                                                    <tr>
                                                        <td colspan="2" class="text-center text-muted py-3">
                                                            <i class="fas fa-inbox mb-2 d-block" style="font-size: 2rem; opacity: 0.5;"></i>
                                                            <?= __('admin.no_additional_details') ?>
                                                        </td>
                                                    </tr>
                                                <?php } ?>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-lg-4 col-md-12 mb-3">
                            <div class="card h-100 border-0 shadow-sm">
                                <div class="card-header bg-warning text-dark">
                                    <h6 class="mb-0 fw-semibold">
                                        <i class="fas fa-plus-circle me-2"></i><?= __('admin.add_custom_status_history') ?>
                                    </h6>
                                </div>
                                <div class="card-body">
                                    <form class="add-history-form">
                                        <div class="mb-3">
                                            <label class="form-label fw-semibold"><?= __('admin.status') ?></label>
                                            <select class="form-select form-select-sm" name="status">
                                                <option value=""><?= __('admin.select_status') ?></option>
                                                <?php foreach ($status_list as $key => $value) { ?>
                                                    <option value="<?= $key ?>">
                                                        <?php   
                                                            if ($value == 'Received') {
                                                                echo __('admin.received');
                                                            }elseif ($value == 'Complete') {
                                                                echo __('admin.complete');
                                                            }elseif ($value == 'Total not match') {
                                                                echo __('admin.total_not_match');
                                                            }elseif ($value == 'Denied') {
                                                                echo __('admin.denied');
                                                            }elseif ($value == 'Expired') {
                                                                echo __('admin.expired');
                                                            }elseif ($value == 'Failed') {
                                                                echo __('admin.failed');
                                                            }elseif ($value == 'Processed') {
                                                                echo __('admin.processed');
                                                            }elseif ($value == 'Refunded') {
                                                                echo __('admin.refunded');
                                                            }elseif ($value == 'Reversed') {
                                                                echo __('admin.reversed');
                                                            }elseif ($value == 'Voided') {
                                                                echo __('admin.voided');
                                                            }elseif ($value == 'Canceled Reversal') {
                                                                echo __('admin.cancel_reversal');
                                                            }elseif ($value == 'Waiting For Payment') {
                                                                echo __('admin.waiting_for_payment');
                                                            }elseif ($value == 'Pending') {
                                                                echo __('admin.pending');
                                                            }else{
                                                                echo $value;
                                                            }
                                                        ?>
                                                    </option>
                                                <?php } ?>
                                            </select>
                                        </div>
                                        <div class="mb-3">
                                            <label class="form-label fw-semibold"><?= __('admin.comment') ?></label>
                                            <textarea name="comment" class="form-control form-control-sm" rows="3" placeholder="Enter your comment..."></textarea>
                                        </div>
                                        <div class="d-grid">
                                            <button type="button" class="btn btn-info btn-sm btn-add-status">
                                                <i class="fas fa-plus me-1"></i><?= __('admin.add_status') ?>
                                            </button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Status History -->
                    <div class="row">
                        <div class="col-12">
                            <div class="card border-0 shadow-sm">
                                <div class="card-header bg-secondary text-white">
                                    <h6 class="mb-0 fw-semibold">
                                        <i class="fas fa-history me-2"></i><?= __('admin.status_history') ?>
                                    </h6>
                                </div>
                                <div class="card-body p-0">
                                    <div class="table-responsive">
                                        <table class="table table-hover mb-0">
                                            <thead class="table-light">
                                                <tr>
                                                    <th class="border-0 py-3 px-4">
                                                        <i class="fas fa-info-circle me-2 text-muted"></i><?= __('admin.status') ?>
                                                    </th>
                                                    <th class="border-0 py-3 px-4">
                                                        <i class="fas fa-comment me-2 text-muted"></i><?= __('admin.comment') ?>
                                                    </th>
                                                </tr>
                                            </thead>
                                            <tbody id="history_container">
                                                <tr>
                                                    <td colspan="2" class="text-center py-4">
                                                        <div class="spinner-border text-primary" role="status">
                                                            <span class="visually-hidden">Loading...</span>
                                                        </div>
                                                    </td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script type="text/javascript">
    $(".btn-add-status").on("click", function(){
        $this = $('.add-history-form');
        $.ajax({
            type: 'POST',
            dataType: 'json',
            data: $(".add-history-form :input"),
            beforeSend: function(){
                $('.btn-add-status').prop('disabled', true).html('<span class="spinner-border spinner-border-sm me-2"></span>Loading...');
            },
            complete: function(){
                $('.btn-add-status').prop('disabled', false).html('<i class="fas fa-plus me-1"></i><?= __('admin.add_status') ?>');
            },
            success: function(json){
                $container = $this;
                $container.find(".is-invalid").removeClass("is-invalid");
                $container.find("span.invalid-feedback").remove();
        
                if (json['success']) {
                    if($(".add-history-form select[name=status]").val() == "1"){
                        window.location.reload();
                    } else{
                        getHistory()
                    }
                    $('[name="status"], [name="comment"]').val('')
                }
                
                if(json['errors']){
                    $.each(json['errors'], function(i,j){
                        $ele = $container.find('[name="'+ i +'"]');
                        if($ele){
                            $ele.addClass("is-invalid");
                            if($ele.parent(".input-group").length){
                                $ele.parent(".input-group").after("<span class='invalid-feedback'>"+ j +"</span>");
                            } else{
                                $ele.after("<span class='invalid-feedback'>"+ j +"</span>");
                            }
                        }
                    })
                }
            },
        })
    })

    function getHistory() {
        $this = $(this);
        $.ajax({
            url: '<?= base_url('admincontrol/get_vendor_deposit_history/'. $request['vd_id']) ?>',
            type: 'POST',
            dataType: 'json',
            beforeSend: function(){
                $("#history_container").html('<tr><td colspan="2" class="text-center py-4"><div class="spinner-border text-primary" role="status"><span class="visually-hidden">Loading...</span></div></td></tr>');
            },
            success: function(json){
                $("#history_container").html(json['html']);
            },
        })
    }

    getHistory()

    $(document).delegate('.wallet-popover','click', function(){
        var html = $(this).parents("tr").find(".dpopver-content").html();
        $(this).attr('data-content',html);
        if($('.popover').hasClass('show')){
            $('.popover').remove()
        } else {
            $(this).popover('show');
        }
    });

    $('html').on('click', function(e) {
      if (typeof $(e.target).data('original-title') == 'undefined' &&
         !$(e.target).parents().is('.popover.in')) {
        $('[data-original-title]').popover('hide');
      }
    });

    $(document).ready(function(){
        $(".wallet-popover").popover({
            placement : 'right',
            html : true,
        });
    })
</script>
<?php } else { ?>
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="alert alert-info">
                    <div class="d-flex align-items-center">
                        <i class="fas fa-info-circle me-3" style="font-size: 1.5rem;"></i>
                        <div>
                            <h5 class="mb-1"><?= __('admin.saas_module_is_off') ?></h5>
                            <p class="mb-0"><?= __('admin.admin_click_here_to_activate') ?> <a href="<?= base_url('admincontrol/addons') ?>" class="alert-link"><?= __('admin.click_here') ?></a></p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
<?php } ?>