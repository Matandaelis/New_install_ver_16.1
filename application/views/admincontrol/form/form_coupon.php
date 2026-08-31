<link href="<?php echo base_url('assets/template/css/datepicker.css'); ?>" rel="stylesheet" type="text/css" />
<script src="<?php echo base_url('assets/template/js/bootstrap-datepicker.js'); ?>"></script>

<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card shadow-sm">
                <div class="card-header bg-primary text-white py-3">
                    <div class="d-flex align-items-center">
                        <i class="bi bi-ticket-perforated me-2"></i>
                        <h5 class="mb-0 fw-semibold"><?= __('admin.form_coupon_manage'); ?></h5>
                    </div>
                </div>
                <div class="card-body p-4">
                    <form id="form_coupon_form">
                        <input type="hidden" class="form-control" name="id" value="<?= (int)$form_coupon['form_coupon_id'] ?>">

                        <div class="row g-3 mb-4">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label fw-medium"><?= __('admin.form_coupon_name'); ?></label>
                                    <input type="text" class="form-control" name="name" value="<?= $form_coupon['name'] ?>" placeholder="<?= __('admin.enter_coupon_name') ?>">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label fw-medium"><?= __('admin.form_coupon_code'); ?></label>
                                    <input type="text" class="form-control" name="code" value="<?= $form_coupon['code'] ?>" placeholder="<?= __('admin.enter_coupon_code') ?>">
                                </div>
                            </div>
                        </div>

                        <div class="row g-3 mb-4">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label fw-medium"><?= __('admin.type'); ?></label>
                                    <select class="form-select" name="type">
                                        <option value="P" <?= $form_coupon['type'] == 'P' ? 'selected' : '' ?>><?= __('admin.percentage') ?></option>
                                        <option value="F" <?= $form_coupon['type'] == 'F' ? 'selected' : '' ?>><?= __('admin.fixed_amount') ?></option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label fw-medium"><?= __('admin.discount'); echo $form_coupon['type'] == "F" ? ' (' . $_SESSION['userCurrency'] . ')' : ' (%)'; ?></label>
                                    <input type="text" class="form-control" name="discount" value="<?= getDecimalNumberFormat($form_coupon['discount'], $_SESSION['userDecimalPlace']); ?>" placeholder="<?= __('admin.enter_discount_amount') ?>">
                                </div>
                            </div>
                        </div>

                        <div class="row g-3 mb-4">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label fw-medium"><?= __('admin.date_start'); ?></label>
                                    <input type="text" class="form-control datepicker" name="date_start" value="<?= $form_coupon['date_start'] ?>" placeholder="<?= __('admin.select_start_date') ?>">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label fw-medium"><?= __('admin.date_end'); ?></label>
                                    <input type="text" class="form-control datepicker" name="date_end" value="<?= $form_coupon['date_end'] ?>" placeholder="<?= __('admin.select_end_date') ?>">
                                </div>
                            </div>
                        </div>

                        <div class="row g-3 mb-4">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label fw-medium"><?= __('admin.uses_per_customer'); ?></label>
                                    <input type="number" class="form-control" name="uses_total" value="<?= $form_coupon['uses_total'] ?>" placeholder="<?= __('admin.enter_usage_limit') ?>">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label fw-medium"><?= __('admin.status'); ?></label>
                                    <select class="form-select" name="status">
                                        <option value="1"><?= __('admin.enable'); ?></option>
                                        <option value="0" <?= $form_coupon['allow_for'] == '0' ? 'selected' : '' ?>><?= __('admin.disable'); ?></option>
                                    </select>
                                </div>
                            </div>
                        </div>

                        <div class="d-flex justify-content-end gap-2">
                            <a href="<?= base_url('admincontrol/listproduct') ?>" class="btn btn-outline-secondary">
                                <i class="bi bi-arrow-left me-1"></i><?= __('admin.back') ?>
                            </a>
                            <button type="submit" class="btn btn-primary btn-submit">
                                <i class="bi bi-check-circle me-1"></i><?= __('admin.save'); ?>
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script type="text/javascript">
    $(".datepicker").datepicker({ 
        autoclose: true, 
        todayHighlight: true,
        format:"dd-mm-yyyy"
    })
    $('[name="allow_for"]').on('change',function(){
        $(".select-product").hide();
        if($(this).val() == 'S') $(".select-product").show();
    });
    $('[name="allow_for"]').trigger("change");
    $(".datepicker").each(function(){
        var d= $(this).val().split("-");
        if(d[0]){
            var date = d[1]  + "-" + d[2] + "-" + d[0];
            $(this).datepicker('update', new Date(date))
        }
        else{ $(this).val(''); }
    })
    
    $("#form_coupon_form").on('submit',function(){
        $this = $(this);
        $.ajax({
            url:'<?= base_url('admincontrol/save_form_coupon') ?>',
            type:'POST',
            dataType:'json',
            data:$this.serialize(),
            beforeSend:function(){$this.find(".btn-submit").button("loading");},
            complete:function(){$this.find(".btn-submit").button("reset");},
            success:function(result){
                $this.find(".has-error").removeClass("has-error");
                $this.find("span.text-danger").remove();
                
                if(result['location']){
                    window.location = result['location'];
                }
                if(result['errors']){
                
                    $.each(result['errors'], function(i,j){
                        $ele = $this.find('[name="'+ i +'"]');
                        if($ele){
                            $ele.parents(".form-group").addClass("has-error");
                            $ele.after("<span class='text-danger'>"+ j +"</span>");
                        }
                    })
                }
            },
        })
        return false;
    })
</script>