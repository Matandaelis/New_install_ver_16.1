<link href="<?= base_url('assets/template/css/datepicker.css') ?>" rel="stylesheet" type="text/css" />
<script src="<?= base_url('assets/template/js/bootstrap-datepicker.js') ?>"></script>

<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-primary text-white">
                    <div class="d-flex justify-content-between align-items-center">
                        <h5 class="mb-0 fw-bold">
                            <i class="fas fa-ticket-alt me-2"></i><?= __('admin.coupon_manage') ?>
                        </h5>
                        <a href="<?= base_url('usercontrol/store_coupon') ?>" class="btn btn-light btn-sm">
                            <i class="fas fa-arrow-left me-1"></i><?= __('admin.back') ?>
                        </a>
                    </div>
                </div>
                <div class="card-body p-4">
                    <form id="coupon_form">
                        <input type="hidden" name="id" value="<?= (int)$coupon['coupon_id'] ?>">

                        <div class="mb-3">
                            <label class="form-label fw-semibold">
                                <i class="fas fa-tag text-primary me-1"></i><?= __('admin.coupon_name') ?>
                            </label>
                            <input type="text" class="form-control" name="name" value="<?= htmlspecialchars($coupon['name']) ?>" placeholder="<?= __('admin.coupon_name') ?>">
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-semibold">
                                <i class="fas fa-barcode text-primary me-1"></i><?= __('admin.coupon_code') ?>
                            </label>
                            <input type="text" class="form-control" name="code" value="<?= htmlspecialchars($coupon['code']) ?>" placeholder="<?= __('admin.coupon_code') ?>">
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-semibold">
                                    <i class="fas fa-percentage text-primary me-1"></i><?= __('admin.type') ?>
                                </label>
                                <select class="form-select" name="type">
                                    <option value="P" <?= $coupon['type'] =='P' ? 'selected':'' ?>><?= __('admin.percentage') ?></option>
                                    <option value="F" <?= $coupon['type'] =='F' ? 'selected':'' ?>><?= __('admin.fixed_amount') ?></option>
                                </select>
                            </div>

                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-semibold">
                                    <i class="fas fa-box text-primary me-1"></i><?= __('admin.allow_for_product') ?>
                                </label>
                                <select class="form-select" name="allow_for">
                                    <option value="A" <?= $coupon['allow_for'] =='A' ? 'selected':'' ?>><?= __('admin.all') ?></option>
                                    <option value="S" <?= $coupon['allow_for'] =='S' ? 'selected':'' ?>><?= __('admin.selected_only') ?></option>
                                </select>
                            </div>
                        </div>

                        <div class="mb-3 select-product" style="display: none;">
                            <label class="form-label fw-semibold">
                                <i class="fas fa-check-square text-primary me-1"></i><?= __('admin.select') ?> <?= __('admin.products') ?>
                            </label>
                            <div class="card border">
                                <div class="card-body" style="max-height: 300px; overflow-y: auto;">
                                    <?php 
                                    $ids = explode(",", $coupon['products']);
                                    foreach ($product as $key => $p) { ?>
                                        <div class="form-check mb-2">
                                            <input class="form-check-input" type="checkbox" <?= in_array($p['product_id'], $ids) ? 'checked' : '' ?> name="products[]" value="<?= $p['product_id'] ?>" id="product_<?= $p['product_id'] ?>">
                                            <label class="form-check-label" for="product_<?= $p['product_id'] ?>">
                                                <?= htmlspecialchars($p['product_name']) ?>
                                            </label>
                                        </div>
                                    <?php } ?>
                                </div>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-semibold">
                                <i class="fas fa-dollar-sign text-primary me-1"></i><?php echo __('admin.discount'); echo $coupon['type']=="F" ? ' ('.$_SESSION['userCurrency'].')' :' (%)';?>
                            </label>
                            <input type="text" class="form-control" name="discount" value="<?= getDecimalNumberFormat($coupon['discount'],$_SESSION['userDecimalPlace']) ?>" placeholder="0.00">
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-semibold">
                                    <i class="fas fa-calendar-alt text-primary me-1"></i><?= __('admin.date_start') ?>
                                </label>
                                <input type="text" class="form-control datepicker" name="date_start" value="<?= $coupon['date_start'] ?>" placeholder="dd-mm-yyyy">
                            </div>

                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-semibold">
                                    <i class="fas fa-calendar-check text-primary me-1"></i><?= __('admin.date_end') ?>
                                </label>
                                <input type="text" class="form-control datepicker" name="date_end" value="<?= $coupon['date_end'] ?>" placeholder="dd-mm-yyyy">
                            </div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-semibold">
                                <i class="fas fa-users text-primary me-1"></i><?= __('admin.uses_per_customer') ?>
                            </label>
                            <input type="text" class="form-control" name="uses_total" value="<?= $coupon['uses_total'] ?>" placeholder="0">
                        </div>

                        <div class="mb-4">
                            <label class="form-label fw-semibold">
                                <i class="fas fa-toggle-on text-primary me-1"></i><?= __('admin.status') ?>
                            </label>
                            <select class="form-select" name="status">
                                <option value="1"><?= __('admin.enable') ?></option>
                                <option value="0" <?= $coupon['status'] == '0' ? 'selected': '' ?>><?= __('admin.disable') ?></option>
                            </select>
                        </div>

                        <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                            <a href="<?= base_url('usercontrol/store_coupon') ?>" class="btn btn-outline-secondary">
                                <i class="fas fa-times me-1"></i><?= __('admin.cancel') ?>
                            </a>
                            <button type="submit" class="btn btn-primary btn-submit">
                                <i class="fas fa-save me-1"></i><?= __('admin.save') ?>
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script type="text/javascript">
$(document).ready(function() {
    $(".datepicker").datepicker({ 
        autoclose: true, 
        todayHighlight: true,
        format:"dd-mm-yyyy"
    });

    $('[name="allow_for"]').on('change',function(){
        $(".select-product").hide();
        if($(this).val() == 'S') {
            $(".select-product").show();
        }
    });
    $('[name="allow_for"]').trigger("change");

    $(".datepicker").each(function(){
        var d = $(this).val().split("-");
        if(d[0]){
            var pattern = /(\d{4})\-(\d{2})\-(\d{2})/;
            var date = new Date($(this).val().replace(pattern,'$1-$2-$3'));
            $(this).datepicker('update', new Date(date));
        } else { 
            $(this).val(''); 
        }
    });
    
    $("#coupon_form").on('submit', function(e) {
        e.preventDefault();
        
        var $form = $(this);
        var $btn = $form.find(".btn-submit");
        var originalText = $btn.html();
        
        $.ajax({
            url: '<?= base_url("usercontrol/save_coupon") ?>',
            type: 'POST',
            dataType: 'json',
            data: $form.serialize(),
            beforeSend: function() { 
                $btn.prop('disabled', true).html('<span class="spinner-border spinner-border-sm me-1"></span><?= __('admin.saving') ?>...');
            },
            complete: function() { 
                $btn.prop('disabled', false).html(originalText);
            },
            success: function(result) {
                $form.find(".is-invalid").removeClass("is-invalid");
                $form.find(".invalid-feedback").remove();

                if (result['location']) {
                    window.location = result['location'];
                }
                
                if (result['errors']) {
                    $.each(result['errors'], function(i, j) {
                        var $ele = $form.find('[name="' + i + '"]');
                        if ($ele.length) {
                            $ele.addClass("is-invalid");
                            $ele.after("<div class='invalid-feedback d-block'>" + j + "</div>");
                        }
                    });
                }
            },
        });
    });
});
</script>