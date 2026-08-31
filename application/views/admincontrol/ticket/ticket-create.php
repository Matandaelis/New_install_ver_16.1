<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card shadow-sm border-0">
                <div class="card-header bg-primary text-white">
                    <h5 class="card-title mb-0">
                        <i class="bi bi-plus-circle me-2"></i>
                        <?= __('admin.create_new_ticket') ?>
                    </h5>
                </div>
                <form id="mail-form">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-lg-12">
                                <div class="mb-3">
                                    <label class="form-label"><?= __('admin.ticket_create_user') ?></label>
                                    <select class="form-select" name="user_id" required>
                                        <option value=""><?= __('admin.ticket_create_user') ?></option>
                                        <?php foreach ($users as $key => $value) { ?>
                                            <option <?= isset($user_id) && $user_id == $value['id'] ? 'selected' : '' ?> value="<?= $value['id'] ?>"><?= $value['username'] ?></option>	
                                        <?php } ?>
                                    </select>
                                </div>

                                <div class="mb-3">
                                    <label class="form-label"><?= __('admin.subject') ?></label>
                                    <select name="subject_id" class="form-select">
                                        <option value=""><?=__('admin.ticket_subject_selection')?></option>
                                        <?php foreach ($subjects as $key => $value): ?>
                                            <option value="<?=$value['id']?>"><?=$value['subject']?></option>
                                        <?php endforeach ?>
                                    </select>
                                </div>

                                <div class="mb-3">
                                    <label class="form-label"><?= __('admin.body') ?></label>
                                    <textarea name="message" class="form-control summernote" rows="8"></textarea>
                                </div>

                                <div class="mb-3" id="addmoreAttachment">
                                    <label class="form-label"><?= __('admin.attachment') ?> <span class="text-muted">(<?= __('admin.optional') ?>)</span></label>
                                    <input type="file" id="attachment" name="attachment[]" class="form-control" />
                                </div>
                                
                                <div class="d-flex justify-content-end">
                                    <button type="button" id="addmore" class="btn btn-outline-info">
                                        <i class="bi bi-plus-circle me-1"></i>
                                        <?= __('admin.tickets_add_more')?>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="card-footer bg-light border-0">
                        <div class="d-flex justify-content-end gap-2">
                            <a href="<?= base_url('admincontrol/tickets') ?>" class="btn btn-outline-secondary">
                                <i class="bi bi-arrow-left me-1"></i>
                                <?= __('admin.back') ?>
                            </a>
                            <button class="btn btn-primary btn-submit">
                                <i class="bi bi-send me-1"></i>
                                <?= __('admin.create_new_ticket') ?>
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<script type="text/javascript">

	var attachment_text = '<?= __('admin.attachment')?>';
	$( document ).ready(function() {
		$('.summernote').summernote({
			tabsize: 2,
			height: 400,
			toolbar: [
			['style', ['bold', 'italic', 'underline', 'clear']],
			['font', ['strikethrough', 'superscript', 'subscript']],
			['fontsize', ['fontsize']],
			['color', ['color']],
			['para', ['ul', 'ol', 'paragraph']],
			['height', ['height']]
			]
		});
	});

	$("#mail-form").on('submit',function(evt){
		evt.preventDefault();	    
		var formData = new FormData($("#mail-form")[0]);  

		$(".btn-submit").prop('disabled', true).html('<span class="spinner-border spinner-border-sm me-2"></span>Loading...');
		$this = $("#mail-form");

		$.ajax({
			type:'POST',
			dataType:'json',
			url: '<?=base_url()?>/tickets/create_ticket',
			cache:false,
			contentType: false,
			processData: false,
			data:formData,
			success:function(result){
				$(".btn-submit").prop('disabled', false).html($(this).data('original-text') || 'Submit');
				$(".alert-dismissable").remove();

				$this.find(".has-error").removeClass("has-error");
				$this.find(".is-invalid").removeClass("is-invalid");
				$this.find("span.text-danger").remove();	            

				if(result['success']){
					$redirecturl='<?=base_url()?>/admincontrol/tickets';
					showPrintMessage(result['success'],'success',$redirecturl);
					var body = $("html, body");
					$("#mail-form")[0].reset()
					body.stop().animate({scrollTop:0}, 500, 'swing', function() { });
				}

				if(result['errors']){

					if(typeof result['errors'] == 'string') {
						$("#mail-form .card-body").prepend('<div class="alert mb-4 alert-danger alert-dismissable"><?= __('admin.mail_sent_fail') ?></div>');
						var body = $("html, body");
						body.stop().animate({scrollTop:0}, 500, 'swing', function() { });
					} else {
						$.each(result['errors'], function(i,j){
							$ele = $this.find('[name="'+ i +'"]');
							if(!$ele.length){ 
								$ele = $this.find('.'+ i);
							}
							if($ele.length){
								$ele.addClass("is-invalid");
								$ele.parents(".form-group").addClass("has-error");
								$ele.after("<span class='d-block text-danger'>"+ j +"</span>");
							}
						});

						errors = result['errors'];
						$('.formsetting_error').text(errors['formsetting_recursion_custom_time']);
						$('.productsetting_error').text(errors['productsetting_recursion_custom_time']);
					}

					
				}
			},
		});
		
		return false;
	});
	$("#addmore").click(function(event) {
		$("#addmoreAttachment").append(`<label>`+attachment_text+`</label><input type="file" name="attachment[]" id="attachment" class="form-control">`);
	});
</script>