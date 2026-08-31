<div class="container-fluid">
	<div class="card shadow-sm">
		<div class="card-header bg-primary text-white">
			<h5 class="mb-0">
				<i class="fas fa-ticket-alt me-2"></i><?= __('user.create_new_ticket') ?>
			</h5>
		</div>
		<form id="mail-form">
			<div class="card-body">
				<div class="row g-3">
					<div class="col-lg-6">
						<label class="form-label fw-bold">
							<i class="fas fa-user me-1 text-primary"></i><?= __('user.username') ?>
						</label>
						<input type="text" name="fname" class="form-control form-control-lg" value="<?php echo $userdetails['username'] ?>" readonly>
					</div>
					<div class="col-lg-6">
						<label class="form-label fw-bold">
							<i class="fas fa-envelope me-1 text-primary"></i><?= __('user.email') ?>
						</label>
						<input type="text" name="email" class="form-control form-control-lg" value="<?php echo $userdetails['email'] ?>" readonly>
					</div>
				</div>

				<div class="mb-4 mt-4">
					<label class="form-label fw-bold">
						<i class="fas fa-tag me-1 text-primary"></i><?= __('user.subject') ?>
						<span class="text-danger">*</span>
					</label>
					<select name="subject_id" class="form-select form-select-lg" required>
						<option value=""><?=__('user.ticket_subject_selection')?></option>
						<?php foreach ($subjects as $key => $value): ?>
							<option value="<?=$value['id']?>"><?=$value['subject']?></option>
						<?php endforeach ?>
					</select>
				</div>

				<div class="mb-4">
					<label class="form-label fw-bold">
						<i class="fas fa-align-left me-1 text-primary"></i><?= __('user.body') ?>
						<span class="text-danger">*</span>
					</label>
					<textarea name="message" class="form-control summernote-img" rows="8" required></textarea>
				</div>

				<div class="mb-3" id="addmoreAttachment">
					<label class="form-label fw-bold">
						<i class="fas fa-paperclip me-1 text-primary"></i><?= __('user.attachment') ?>
						<small class="text-muted">(<?= __('user.optional') ?>)</small>
					</label>
					<input type="file" id="attachment" name="attachment[]" class="form-control form-control-lg" />
				</div>
				<div class="text-end">
					<button type="button" id="addmore" class="btn btn-info btn-lg">
						<i class="fas fa-plus-circle me-1"></i><?= __('user.tickets_add_more')?>
					</button>
				</div>
			</div>
			<div class="card-footer bg-white text-end">
				<button type="submit" class="btn btn-success btn-lg px-5 btn-submit">
					<i class="fas fa-paper-plane me-2"></i><?= __('user.create_new_ticket') ?>
				</button>
			</div>
		</form>
	</div>
<script type="text/javascript">

	$("#mail-form").on('submit',function(evt){
		evt.preventDefault();	    
		var formData = new FormData($("#mail-form")[0]);  

		$(".btn-submit").btn("loading");
		$this = $("#mail-form");

		$.ajax({
			type:'POST',
			dataType:'json',
			cache:false,
			contentType: false,
			processData: false,
			data:formData,
			success:function(result){
				$(".btn-submit").btn("reset");
				$(".alert-dismissable").remove();

				$this.find(".has-error").removeClass("has-error");
				$this.find(".is-invalid").removeClass("is-invalid");
				$this.find("span.text-danger").remove();	            

				if(result['success']){
					window.location.href = '<?=base_url()?>/usercontrol/tickets';
					$("#mail-form .card-body").prepend('<div class="alert mb-4 alert-success alert-dismissable">'+result['success']+'</div>');
					var body = $("html, body");
					$("#mail-form")[0].reset()
					body.stop().animate({scrollTop:0}, 500, 'swing', function() { });
				}

				if(result['errors']){

					if(typeof result['errors'] == 'string') {
						$("#mail-form .card-body").prepend('<div class="alert mb-4 alert-danger alert-dismissable"><?= __('user.mail_sent_fail') ?></div>');
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
		var attachment_text = '<?= __('user.attachment') ?>';
		var optional_text = '<?= __('user.optional') ?>';
		$("#addmoreAttachment").append(`
			<label class="form-label fw-bold mt-3">
				<i class="fas fa-paperclip me-1 text-primary"></i>`+attachment_text+`
				<small class="text-muted">(`+optional_text+`)</small>
			</label>
			<input type="file" name="attachment[]" class="form-control form-control-lg">
		`);
	});

</script>
</div>