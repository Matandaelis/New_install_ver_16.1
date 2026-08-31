<div class="container-fluid script-details-page">
<div class="row">
<div class="col-12">

<?php if (ENVIRONMENT !== 'demo'): ?>
<div class="card shadow-sm border-0 mb-4">
	<div class="card-header bg-primary text-white py-3">
		<div class="d-flex align-items-center">
			<i class="bi bi-key-fill me-2 fs-4"></i>
			<h4 class="mb-0 fw-bold"><?= strtolower($licence['license']) == 'company site' ? __('admin.company_site') : 'Codecanyon' ?> <?= __("admin.license_details") ?></h4>
		</div>
	</div>
	<div class="card-body p-4">
		<div class="license-details row g-4">
			<div class="col-md-4">
				<div class="border rounded p-3 h-100">
					<label class="text-muted small mb-2 d-block"><?= __("admin.license_code") ?></label>
					<span class="fw-bold text-break"><?= $licence['code'] ?></span>
				</div>
			</div>
		    <div class="col-md-4">
		    	<div class="border rounded p-3 h-100">
					<label class="text-muted small mb-2 d-block"><?= __("admin.purchase_amount") ?></label>
					<span class="fw-bold"><?= (float)$licence['amount'] ?> USD</span>
				</div>
			</div>
		    <div class="col-md-4">
		    	<div class="border rounded p-3 h-100">
					<label class="text-muted small mb-2 d-block"><?= __("admin.support_amount") ?></label>
					<span class="fw-bold"><?= (float)$licence['support_amount'] ?> USD</span>
				</div>
			</div>
		    <div class="col-md-4">
		    	<div class="border rounded p-3 h-100">
					<label class="text-muted small mb-2 d-block"><?= __("admin.sold_at") ?></label>
					<span class="fw-bold"><?= $licence['sold_at'] ?></span>
				</div>
			</div>
		    <div class="col-md-4">
		    	<div class="border rounded p-3 h-100">
					<label class="text-muted small mb-2 d-block"><?= __("admin.license_type") ?></label>
					<span class="fw-bold"><?= $licence['license'] ?></span>
				</div>
			</div>
		    <div class="col-md-4">
		    	<div class="border rounded p-3 h-100">
					<label class="text-muted small mb-2 d-block"><?= __("admin.supported_until") ?></label>
					<span class="fw-bold"><?= $licence['supported_until'] ? $licence['supported_until'] : __("admin.free") ?></span>
				</div>
			</div>
		    <div class="col-md-4">
		    	<div class="border rounded p-3 h-100">
					<label class="text-muted small mb-2 d-block"><?= __("admin.buyer_username") ?></label>
					<span class="fw-bold"><?= $licence['buyer'] ?></span>
				</div>
			</div>
			<?php if (!empty($licence['domain'])): ?>
			<div class="col-md-4">
		    	<div class="border rounded p-3 h-100">
					<label class="text-muted small mb-2 d-block"><?= __("admin.activated_domain") ?></label>
					<span class="fw-bold text-break small"><?= $licence['domain'] ?></span>
				</div>
			</div>
			<?php endif; ?>
			<?php if (!empty($licence['activated_at'])): ?>
			<div class="col-md-4">
		    	<div class="border rounded p-3 h-100">
					<label class="text-muted small mb-2 d-block"><?= __("admin.activated_date") ?></label>
					<span class="fw-bold"><?= $licence['activated_at'] ?></span>
				</div>
			</div>
			<?php endif; ?>
			<?php if (!empty($licence['status'])): ?>
			<div class="col-md-4">
		    	<div class="border rounded p-3 h-100">
					<label class="text-muted small mb-2 d-block"><?= __("admin.license_status") ?></label>
					<span class="badge bg-<?= $licence['status'] === 'active' ? 'success' : 'warning' ?>"><?= ucfirst($licence['status']) ?></span>
				</div>
			</div>
			<?php endif; ?>
			<?php if (!empty($licence['customer_email'])): ?>
			<div class="col-md-4">
		    	<div class="border rounded p-3 h-100">
					<label class="text-muted small mb-2 d-block"><?= __("admin.customer_email") ?></label>
					<span class="fw-bold text-break small"><?= $licence['customer_email'] ?></span>
				</div>
			</div>
			<?php endif; ?>

			<div class="col-md-4">
		    	<div class="border rounded p-3 h-100 d-flex flex-column justify-content-between">
					<label class="text-muted small mb-2 d-block"><?= __("admin.uninstall_script") ?></label>
					<button class="btn btn-danger btn-sm uninstall-script"><i class="bi bi-trash me-1"></i><?= __("admin.un_install") ?></button>
				</div>
			</div>
			
		</div>
	</div>
</div>
<?php endif; ?>

<div class="card shadow-sm border-0 mb-4">
	<div class="card-header bg-primary text-white py-3">
		<div class="d-flex align-items-center justify-content-between">
			<div class="d-flex align-items-center">
				<i class="bi bi-clock-history me-2 fs-4"></i>
				<h4 class="mb-0 fw-bold"><?= $product['name'] ?> <?= __("admin.changelog") ?></h4>
			</div>
			<a href="https://affiliatepro.org/affiliatepro-saas-logs/" target="_blank" class="btn btn-light btn-sm">
				<i class="bi bi-box-arrow-up-right me-1"></i> <?= __("admin.view_full_changelog") ?>
			</a>
		</div>
	</div>
	<div class="card-body p-4 text-center">
		<i class="bi bi-journal-text fs-1 text-muted mb-3 d-block"></i>
		<h5 class="mb-2"><?= __("admin.view_complete_version_history") ?></h5>
		<p class="text-muted mb-3"><?= __("admin.check_all_updates_improvements") ?> <?= $product['name'] ?></p>
		<a href="https://affiliatepro.org/affiliatepro-saas-logs/" target="_blank" class="btn btn-primary">
			<i class="bi bi-box-arrow-up-right me-2"></i> <?= __("admin.open_changelog") ?>
		</a>
	</div>
</div>

<script type="text/javascript">
	$(document).ready(function () {
	    $(".change-history ul li").each(function(i,el){
	    	$(el).html($(el).html().replace(new RegExp('NEW', 'gi'), "<strong class='text-success'>"+'<?= __('admin.new') ?>'+"</strong>"));
	    	$(el).html($(el).html().replace(new RegExp('ADDED', 'gi'), "<strong class='text-info'>"+'<?= __('admin.added') ?>'+"</strong>"));
	    	$(el).html($(el).html().replace(new RegExp('IMPROVED', 'gi'), "<strong class='text-danger'>"+'<?= __('admin.improved') ?>'+"</strong>"));
	    	$(el).html($(el).html().replace(new RegExp('FIXED', 'gi'), "<strong class='text-primary'>"+'<?= __('admin.fixed') ?>'+"</strong>"));
	    });

	    
	    $(document).on('change','input[name="licence"]', function(){
		    $this = $(this);
		    $.ajax({
        url:'<?= base_url("installversion/ajax_validate_license"); ?>',
		        type:'POST',
		        dataType:'json',
		        data:{
		            code: $this.val()
		        },
		        success:function(json){
		            $($this).parent().removeClass("has-error");
		            $($this).parent().find("span.text-danger").remove();                
		            if(json['errors']){
		                $('[name="username"]').val('');
		                $.each(json['errors'], function(i,j){
	                       $($this).parent().addClass("has-error");
	                       $($this).parent().append("<span class='text-danger'>"+ j +"</span>");
		                })
		            }else{
		                if(json.response.buyer){
		                	$('.swal2-confirm').removeAttr('disabled');
		                    $('input[name="username"]').val(json.response.buyer);
		                }
		            }
		        },
		    })

		    return false;
		});
	});

$(".uninstall-script").on("click",function(){
    Swal.fire({
        title: '<h1 class="modal-title text-center"><span class="badge bg-danger text-white"><?= __("admin.uninstall_warning_attention") ?></span></h1>',
        html: `
            <ul class="list-unstyled">
                <li class="text-start mt-3"><strong><?= __("admin.uninstall_warning_attention_info1") ?></strong> <span class="badge bg-warning"><?= __("admin.first_uninstall") ?></span> <?= __("admin.uninstall_warning_attention_info2") ?></li>
                <li class="text-start mt-2"><strong><?= __("admin.uninstall_warning_attention_info3") ?></strong> <span class="badge bg-warning"><?= __("admin.first_uninstall") ?></span> <?= __("admin.uninstall_warning_attention_info4") ?></li>
            </ul>
            <div class="frame"><span class="badge bg-success"><?= __("admin.site_data_is_safe") ?></span> <span class="badge bg-success"></span>
            <br><span class="fs-5"><strong><?= __("admin.uninstall_warning_attention_info5") ?></strong></span></div>
            <br>
            <div class="text-start uninstall-script-form">
                <div class="mb-3">
                    <label class="form-label"><?= __("admin.admin_password") ?></label>
                    <input type="password" name="password" class="form-control" autocomplete="off">
                </div>
                <div class="mb-3">
                    <label class="form-label"><?= __("admin.Enter_license") ?></label>
                    <input type="text" name="licence" class="form-control" autocomplete="off">
                </div>
                <div class="mb-3">
                    <label class="form-label"><?= __("admin.user_name") ?></label>
                    <input type="text" name="username" class="form-control" readonly="true" autocomplete="off">
                </div>
            </div>
        `,
        showCancelButton: true,
        onOpen: function (){
            $('.swal2-confirm').attr('disabled', true);
        },
        confirmButtonText: '<?= __("admin.uninstall") ?>',
        showLoaderOnConfirm: true,
        preConfirm:  (login)  => {
            var data = {
                password: btoa($(".uninstall-script-form input[name=password]").val()),
                licence: btoa($(".uninstall-script-form input[name=licence]").val()),
            };

            if(data.password == "") {
                Swal.showValidationMessage('<?= __("admin.password_should_not_be_empty") ?>');
                return false;
            }
            
            return fetch('<?= base_url("Installversion/uninstall_script") ?>/' + data.password + "/" + (data.licence ? data.licence : "00-00"))
                .then(async response => {
                    let json = await response.json();

                    if(json['status'] === 'error') {
                        Swal.showValidationMessage(json['message']);
                        return false;
                    }
                    else if (json["errors"]) {
                        $.each(json["errors"], function(i, j){
                            Swal.showValidationMessage(j);
                        });
                        return false;
                    }
                    else if(json['warning']) {
                        Swal.showValidationMessage(json['warning']);
                        return false;
                    }
                    else if(json['success']) {
                        return json;
                    } else {
                        Swal.showValidationMessage('Unknown error occurred');
                        return false;
                    }
                })
                .catch(error => {
                    Swal.showValidationMessage('Request failed: ' + error.message);
                    return false;
                });
        },
        allowOutsideClick: () => !Swal.isLoading()
    }).then((result) => {
        if(result.value && result.value.success){
            window.location.href = '<?= base_url("/install") ?>';
        }
    });
});
</script>

</div>
</div>
</div>