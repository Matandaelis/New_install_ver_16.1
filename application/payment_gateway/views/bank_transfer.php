<?php if($gatewayData['module'] == 'store'){ ?>
	<?php 
		$bank_names = [];
		if(isset($settingData['bank_names']) && !empty($settingData['bank_names'])){
			$bank_names = (array)json_decode($settingData['bank_names'],1);
		} 
	?>

	<div class="card border-0 shadow-sm mt-3">
		<div class="card-header bg-light border-bottom py-3">
			<h6 class="mb-0 fw-semibold">
				<i class="fas fa-university me-2 text-primary"></i><?= __('user.bank_transfer_payment') ?? 'Bank Transfer Payment' ?>
			</h6>
		</div>
		<div class="card-body p-4">

			<!-- Bank Selection -->
			<div class="mb-3">
				<label class="form-label fw-semibold small"><i class="fas fa-building me-1 text-muted"></i><?= __('user.choose_bank') ?? 'Choose Bank' ?></label>
				<select name="bank_method" class="form-select">
					<option value="0"><?= (isset($bank_names[0]) && !empty($bank_names[0])) ? $bank_names[0] : substr($settingData['bank_details'],0,50)."..." ?></option>
					<?php
						if(isset($settingData['additional_bank_details'])){
							$additional_bank_details = (array)json_decode($settingData['additional_bank_details'],1);
							foreach ($additional_bank_details as $key => $value) {
								$Bname = (isset($bank_names[$key+1]) && !empty($bank_names[$key+1])) ? $bank_names[$key+1] : substr($value,0,50)."...";
								echo '<option value="'. ($key+1) .'">'. $Bname .'</option>';
							}
						}
					?>
				</select>
			</div>

			<!-- Bank Details -->
			<div class="checkout-bank-details mb-3">
				<?php $Bname = (isset($bank_names[0]) && !empty($bank_names[0])) ? $bank_names[0]."\r\n" : ""; ?>
				<div class="bank-details-card">
					<div class="border rounded p-3" style="background:rgba(13,110,253,.07);">
						<div class="mb-1">
							<i class="fas fa-info-circle me-1 text-info"></i>
							<small class="fw-semibold"><?= __('user.bank_account_details') ?? 'Bank Account Details' ?></small>
						</div>
						<pre class="mb-0 small text-muted" style="white-space:pre-wrap;word-break:break-word;font-family:inherit;"><?= htmlspecialchars($Bname.$settingData['bank_details']) ?></pre>
					</div>
				</div>
				<?php
					if(isset($settingData['additional_bank_details'])){
						$additional_bank_details = (array)json_decode($settingData['additional_bank_details'],1);
						foreach ($additional_bank_details as $key => $value) {
							$Bname2 = (isset($bank_names[$key+1]) && !empty($bank_names[$key+1])) ? $bank_names[$key+1]."\r\n" : "";
							$displayVal = $Bname2.$value;
							echo '<div class="bank-details-card d-none">
								<div class="border rounded p-3" style="background:rgba(13,110,253,.07);">
									<div class="mb-1">
										<i class="fas fa-info-circle me-1 text-info"></i>
										<small class="fw-semibold">'. (__('user.bank_account_details') ?? 'Bank Account Details') .'</small>
									</div>
									<pre class="mb-0 small text-muted" style="white-space:pre-wrap;word-break:break-word;font-family:inherit;">'. htmlspecialchars($displayVal) .'</pre>
								</div>
							</div>';
						}
					}
				?>
			</div>

			<!-- Payment Proof -->
			<?php if($settingData['proof'] == 1 || $settingData['proof'] == 2){ ?>
			<div class="mb-3">
				<label class="form-label fw-semibold small">
					<i class="fas fa-upload me-1 text-muted"></i><?= __('user.payment_proof') ?>
					<?php if($settingData['proof'] == 2){ ?><span class="text-danger ms-1">*</span><?php } ?>
				</label>
				<input type="file" name="payment_proof" class="form-control" <?= ($settingData['proof'] == 2) ? 'required' : '' ?>>
				<div class="form-text small text-muted">
					<i class="fas fa-info-circle me-1"></i><?= __('user.upload_payment_receipt') ?? 'Upload your payment receipt or screenshot.' ?>
				</div>
			</div>
			<?php } ?>

			<!-- Error area -->
			<div id="bank-store-error" class="mb-3"></div>

			<!-- Actions -->
			<div class="d-flex gap-2">
				<button type="button" class="btn btn-outline-secondary" onclick='backCheckout()'>
					<i class="fas fa-arrow-left me-1"></i><?= __('user.back') ?>
				</button>
				<button id="button-confirm" class="btn btn-primary flex-grow-1">
					<i class="fas fa-check me-1"></i><?= __('user.confirm') ?>
				</button>
			</div>

		</div>
	</div>

	<script type="text/javascript">
		$("select[name=bank_method]").change(function(){
			var val = $(this).val();
			$('.checkout-bank-details .bank-details-card').addClass('d-none');
			$('.checkout-bank-details .bank-details-card').eq(val).removeClass('d-none');
		});

		$("select[name=bank_method]").val('0').trigger("change");

		function showBankStoreError(msg) {
			$('#bank-store-error').html('<div class="alert alert-danger d-flex align-items-start gap-2 py-2 px-3"><i class="fas fa-exclamation-triangle mt-1 flex-shrink-0"></i><span class="flex-grow-1">' + msg + '</span><button type="button" onclick="$(this).parent().remove()" style="background:transparent;border:0;font-size:1.4rem;line-height:1;font-weight:700;opacity:.8;cursor:pointer;padding:0 2px;margin-left:auto;">&times;</button></div>');
			$('html,body').animate({ scrollTop: $('#bank-store-error').offset().top - 80 }, 300);
		}

		$("#button-confirm").click(function(){
			$this = $(this);
			$('#bank-store-error').html('');

			<?php if($settingData['proof'] == 2){ ?>
				if($('input[name="payment_proof"]').val() == "") {
					showBankStoreError('<?= __('user.payment_proof_required'); ?>');
					return false;
				}
			<?php } ?>

			var formData = new FormData();
			formData.append('_serialize_comments', $('[name^="comment"]').serialize());
			$('[name^="comment"]').each(function(){ formData.append($(this).attr('name'), $(this).val()); });
			<?php if($settingData['proof']){ ?>
				formData.append('payment_proof', ($('input[type=file][name=payment_proof]')[0] ? $('input[type=file][name=payment_proof]')[0].files[0] : null));
			<?php } ?>

		$.ajax({
			url:'<?= base_url("store/payment_confirmation") ?>',
			type:'POST',
			dataType:'json',
			data: $('[name^="comment"]').serialize() + (<?= $settingData['proof'] ? '1' : '0' ?> ? '&payment_proof_check=1' : ''),
			beforeSend:function(){$("#button-confirm").btn("loading");},
			complete:function(){$("#button-confirm").btn("reset");},
				success:function(json){
					var $container = $("#checkout-confirm");
					$container.find(".has-error").removeClass("has-error");
					$container.find("span.text-danger").remove();

					if(json['errors']){
						var hasInlineError = false;
						if(json['errors']['comment']){
							$.each(json['errors']['comment'], function(ii,jj){
							    var $ele = $container.find('#comment_textarea'+ ii);
							    if($ele.length){
							        $ele.parents(".form-group").addClass("has-error");
							        $ele.after("<span class='text-danger'>"+ jj +"</span>");
							        hasInlineError = true;
							    } else {
							        showBankStoreError(jj);
							    }
							});
						}
						if(json['errors']['payment_proof']){
							var $ele = $container.find('input[name="payment_proof"]');
							if($ele.length){
								$ele.parents(".form-group").addClass("has-error");
							    $ele.after("<span class='text-danger'>"+ json['errors']['payment_proof'] +"</span>");
							    hasInlineError = true;
							} else {
							    showBankStoreError(json['errors']['payment_proof']);
							}
						}
						return;
					}

					if(json['success']){
						var cookieData = localStorage.getItem("selectedCookies");
						try { cookieData = JSON.parse(cookieData); } catch(e){ cookieData = null; }
						var payFormData = new FormData();
						payFormData.append('bank_method', $('select[name="bank_method"]').val());
						payFormData.append('cookies_consent', (cookieData ? cookieData.cookie1 : ''));
						<?php if($settingData['proof']){ ?>
							payFormData.append('payment_proof', ($('input[type=file][name=payment_proof]')[0] ? $('input[type=file][name=payment_proof]')[0].files[0] : null));
						<?php } ?>

					$.ajax({
						url:'<?= base_url("store/confirm_payment") ?>',
						type:'POST',
						dataType:'json',
						data:payFormData,
						contentType: false,
			    		processData: false,
						beforeSend:function(){$this.btn("loading");},
						complete:function(){$this.btn("reset");},
							success:function(json){
								if(json['redirect']){
									window.location = json['redirect'];
									return;
								}
								if(json['warning']){
									showBankStoreError(json['warning']);
								}
								if(json['errors']){
									var msgs = [];
								    $.each(json['errors'], function(i,j){
								        var $ele = $container.find('[name="'+ i +'"]');
								        if($ele.length){
								            $ele.parents(".form-group").addClass("has-error");
								            $ele.after("<span class='text-danger'>"+ j +"</span>");
								        } else {
								            msgs.push(j);
								        }
								    });
								    if (msgs.length) showBankStoreError(msgs.join('<br>'));
								}
							},
						});
					}
				},
			});
		});
	</script>
<?php } else if($gatewayData['module'] == 'deposit'){ ?>
	<form id="formConfirmation">
		<?php 
			$bank_names = [];

			if(isset($settingData['bank_names']) && ! empty($settingData['bank_names'])){
				$bank_names = (array)json_decode($settingData['bank_names'],1);
			}
		?>

		<div class="form-group">
			<label class="control-label"><?= __('user.choose_bank') ?></label>
			<select name="bank_method" class="form-control">
				<option value="0"><?= (isset($bank_names[0]) && !empty($bank_names[0])) ? $bank_names[0]  : substr($settingData['bank_details'],0,50)."..." ?></option>
				<?php
					if(isset($settingData['additional_bank_details'])){
						$additional_bank_details = (array)json_decode($settingData['additional_bank_details'],1);
						foreach ($additional_bank_details as $key => $value) {
							$Bname = (isset($bank_names[$key+1]) && !empty($bank_names[$key+1])) ? $bank_names[$key+1]  : substr($value,0,50)."...";
							echo '<option value="'. ($key+1) .'">'. $Bname .'</option>';
						}
					}
				?>
			</select>
		</div>

		<div class="checkout-bank-details">
			<?php $Bname = (isset($bank_names[0]) && !empty($bank_names[0])) ? $bank_names[0]."\r\n"  : ""; ?>
			<pre class="well d-none"><?= $Bname.$settingData['bank_details'] ?></pre>
			<?php
				if(isset($settingData['additional_bank_details'])){
					$additional_bank_details = (array)json_decode($settingData['additional_bank_details'],1);
					foreach ($additional_bank_details as $key => $value) {
						$Bname = (isset($bank_names[$key+1]) && !empty($bank_names[$key+1])) ? $bank_names[$key+1]."\r\n"  : "";
						$value = $Bname.$value;
						echo '<pre class="well d-none">'. $value .'</pre>';
					}
				}
			?>
		</div>

		<?php $Bname = (isset($bank_names[0]) && !empty($bank_names[0])) ? $bank_names[0]."\r\n"  : ""; ?>
		<input type="hidden" name="bank_details[]" value="<?= $Bname.$settingData['bank_details'] ?>"/>
		<?php
			if(isset($settingData['additional_bank_details'])){
				$additional_bank_details = (array)json_decode($settingData['additional_bank_details'],1);
				foreach ($additional_bank_details as $key => $value) { 
					$Bname = (isset($bank_names[$key+1]) && !empty($bank_names[$key+1])) ? $bank_names[$key+1]."\r\n"  : "";
					$value = $Bname.$value;
					?>

					<input type="hidden" name="bank_details[]" value="<?= $value ?>"/>
					<?php
				}
			}
		?>

		<?php if($settingData['proof'] == 1){ ?>
			<div class="form-group">
				<label class="control-label"><?= __('user.payment_proof') ?></label>
				<input type="file" name="payment_proof" class="form-control">
			</div>
		<?php } ?>
		<?php if($settingData['proof'] == 2){ ?>
			<div class="form-group">
				<label class="control-label"><?= __('user.payment_proof') ?></label>
				<input type="file" name="payment_proof" class="form-control" required>
			</div>
		<?php } ?>
		</form>
		
		<div class="payment-button-group">
			<button type="button" class="btn btn-default" onclick='backCheckout()'><?= __('user.back') ?></button>
			<button id="button-confirm" class="btn btn-primary"><?= __('user.confirm') ?></button>
		</div>

		<script type="text/javascript">
			$("select[name=bank_method]").change(function(){
				var val = $(this).val();
				$('.checkout-bank-details .well').addClass('d-none');
				$('.checkout-bank-details .well').eq(val).removeClass('d-none');
			});

			$("select[name=bank_method]").val('0').trigger("change");

			$("#button-confirm").click(function(){
				<?php if($settingData['proof'] == 2){ ?>
					if($('input[name="payment_proof"]').val() == "") {
						alert('<?= __('user.payment_proof_required'); ?>');
						return false
					}
				<?php } ?>

				$this = $(this);
				
				$this.prop('disabled',true);
				
			$.ajax({
				url:'<?= base_url("usercontrol/payment_confirmation") ?>',
				type:'POST',
				dataType:'json',
				data:$('#formConfirmation').serialize(),
				beforeSend:function(){$("#button-confirm").prop('disabled', true);},
				complete:function(){$("#button-confirm").prop('disabled', false);},
					success:function(json){
						$container = $("#formConfirmation");
						$container.find(".has-error").removeClass("has-error");
						$container.find("span.text-danger").remove();

					if(json['errors']){
						if(json['errors']['comment']){
							$.each(json['errors']['comment'], function(ii,jj){
							    $ele = $container.find('#comment_textarea'+ ii);
							    if($ele.length){
							        $ele.parents(".form-group").addClass("has-error");
							        $ele.after("<span class='text-danger'>"+ jj +"</span>");
							    }
							});
						}

						$.each(json['errors'], function(ii,jj){
						    $ele = $container.find('[name="'+ ii +'"]');
						    if($ele.length){
						        $ele.parents(".form-group").addClass("has-error");
						        $ele.after("<span class='text-danger'>"+ jj +"</span>");
						    }
						});
					}

						if(json['success']){
							var formData = new FormData();
							
							formData.append('bank_method', $('select[name="bank_method"]').val());
							
							<?php if($settingData['proof']){ ?>
								formData.append('payment_proof', ($('input[type=file][name=payment_proof]')[0] ? $('input[type=file][name=payment_proof]')[0].files[0] : null)); 
							<?php } ?>

							$.ajax({
								url:'<?= base_url("usercontrol/confirm_payment") ?>',
								type:'POST',
								dataType:'json',
								data:formData,
								contentType: false,
					    		processData: false,
								beforeSend:function(){$this.btn("loading");},
								complete:function(){$this.btn("reset");},
								success:function(json){
									if(json['redirect']){
										window.location = json['redirect'];
									}
									if(json['warning']){
										alert(json['warning'])
									}

									$container = $("#formConfirmation");
									$container.find(".has-error").removeClass("has-error");
									$container.find("span.text-danger").remove();
								

								if(json['errors']){
								    $.each(json['errors'], function(i,j){
								        $ele = $container.find('[name="'+ i +'"]');
								        if($ele.length){
								            $ele.parents(".form-group").addClass("has-error");
								            $ele.after("<span class='text-danger'>"+ j +"</span>");
								        }
								    });
								}
								},
							});
						}
					},
				});
			});
		</script>
<?php } else if($gatewayData['module'] == 'membership'){ ?>
	<form method="post" enctype="multipart/form-data">
		<div class="card border-0 shadow-sm">
			<div class="card-header bg-primary text-white py-2">
				<h6 class="mb-0">
					<i class="fas fa-university me-2"></i><?= __('user.bank_transfer_payment') ?>
				</h6>
			</div>
			<div class="card-body p-3">
				<?php 
				$bank_names = [];

				if(isset($settingData['bank_names']) && ! empty($settingData['bank_names'])){
					$bank_names = (array)json_decode($settingData['bank_names'],1);
				}
				?>

				<!-- Bank Selection -->
				<div class="mb-3">
					<label class="form-label fw-semibold small">
						<i class="fas fa-building me-1"></i><?= __('user.choose_bank') ?>
					</label>
					<select name="bank_method" class="form-select">
						<option value="0"><?= (isset($bank_names[0]) && !empty($bank_names[0])) ? $bank_names[0]  : substr($settingData['bank_details'],0,50)."..." ?></option>
						<?php
							if(isset($settingData['additional_bank_details'])){
								$additional_bank_details = (array)json_decode($settingData['additional_bank_details'],1);
								foreach ($additional_bank_details as $key => $value) {
									$Bname = (isset($bank_names[$key+1]) && !empty($bank_names[$key+1])) ? $bank_names[$key+1]  : substr($value,0,50)."...";
									echo '<option value="'. ($key+1) .'">'. $Bname .'</option>';
								}
							}
						?>
					</select>
				</div>

				<!-- Bank Details Display -->
				<div class="checkout-bank-details mb-3">
					<?php $Bname = (isset($bank_names[0]) && !empty($bank_names[0])) ? $bank_names[0]."\r\n"  : ""; ?>
					<div class="bank-details-card d-none">
						<div class="card border-primary">
							<div class="card-header bg-primary text-white py-2">
								<h6 class="mb-0 small">
									<i class="fas fa-info-circle me-1"></i><?= __('user.bank_account_details') ?>
								</h6>
							</div>
							<div class="card-body p-2">
								<pre class="mb-0 text-muted small"><?= $Bname.$settingData['bank_details'] ?></pre>
							</div>
						</div>
					</div>
					<?php
						if(isset($settingData['additional_bank_details'])){
							$additional_bank_details = (array)json_decode($settingData['additional_bank_details'],1);
							foreach ($additional_bank_details as $key => $value) {
								$Bname = (isset($bank_names[$key+1]) && !empty($bank_names[$key+1])) ? $bank_names[$key+1]."\r\n"  : "";
								$value = $Bname.$value;
								echo '<div class="bank-details-card d-none">
									<div class="card border-primary">
										<div class="card-header bg-primary text-white py-2">
											<h6 class="mb-0 small">
												<i class="fas fa-info-circle me-1"></i>'. __('user.bank_account_details') .'
											</h6>
										</div>
										<div class="card-body p-2">
											<pre class="mb-0 text-muted small">'. $value .'</pre>
										</div>
									</div>
								</div>';
							}
						}
					?>
				</div>

				<!-- Hidden Fields -->
				<?php $Bname = (isset($bank_names[0]) && !empty($bank_names[0])) ? $bank_names[0]."\r\n"  : ""; ?>
				<input type="hidden" name="bank_details[]" value="<?= $Bname.$settingData['bank_details'] ?>"/>
				<?php
					if(isset($settingData['additional_bank_details'])){
						$additional_bank_details = (array)json_decode($settingData['additional_bank_details'],1);
						foreach ($additional_bank_details as $key => $value) { 
							$Bname = (isset($bank_names[$key+1]) && !empty($bank_names[$key+1])) ? $bank_names[$key+1]."\r\n"  : "";
							$value = $Bname.$value;
							?>
							<input type="hidden" name="bank_details[]" value="<?= $value ?>"/>
							<?php
						}
					}
				?>

				<!-- Payment Proof Upload -->
				<?php if($settingData['proof'] == 1 || $settingData['proof'] == 2){ ?>
					<div class="mb-3">
						<label class="form-label fw-semibold small">
							<i class="fas fa-upload me-1"></i><?= __('user.payment_proof') ?>
							<?php if($settingData['proof'] == 2){ ?>
								<span class="text-danger">*</span>
							<?php } ?>
						</label>
						<input type="file" name="payment_proof" class="form-control" <?= ($settingData['proof'] == 2) ? 'required' : '' ?>>
						<div class="form-text small">
							<i class="fas fa-info-circle me-1"></i><?= __('user.upload_payment_receipt') ?>
						</div>
					</div>
				<?php } ?>

				<!-- Instructions -->
				<div class="alert alert-info border-0 py-2">
					<div class="d-flex align-items-start">
						<i class="fas fa-lightbulb me-2 mt-1 text-info"></i>
						<div>
							<h6 class="alert-heading mb-1 small"><?= __('user.payment_instructions') ?></h6>
							<p class="mb-0 small"><?= __('user.if_admin_asked_you_send_payment_proof') ?></p>
						</div>
					</div>
				</div>

				<!-- Action Buttons -->
				<div class="d-flex gap-2 justify-content-center">
					<button type="button" class="btn btn-outline-secondary btn-sm" onclick='backCheckout()'>
						<i class="fas fa-arrow-left me-1"></i><?= __('user.back') ?>
					</button>
					<button id="btn-confirm" type="button" class="btn btn-primary btn-sm">
						<i class="fas fa-check me-1"></i><?= __('user.buy_now') ?>
					</button>
				</div>
			</div>
		</div>
	</form>

	<script type="text/javascript">

		$("select[name=bank_method]").change(function(){
			var val = $(this).val();
			$('.checkout-bank-details .bank-details-card').addClass('d-none');
			$('.checkout-bank-details .bank-details-card').eq(val).removeClass('d-none');
		});

		$("select[name=bank_method]").val('0').trigger("change");

		$('#btn-confirm').on('click',function(){
			$this = $(this);

			<?php if($settingData['proof'] == 2){ ?>
				if($('input[name="payment_proof"]').val() == "") {
					alert('<?= __('user.payment_proof_required'); ?>');
					return false
				}
			<?php } ?>

			var formData = new FormData();
			formData.append('payment_proof', ($('input[type=file][name=payment_proof]')[0] ? $('input[type=file][name=payment_proof]')[0].files[0] : null));
 
			$.ajax({
				url:'<?= base_url("membership/confirm_plan") ?>',
				type:'POST',
				dataType:'json',
				data:formData,
				contentType: false,
	    		processData: false,
	    		beforeSend:function(){$this.btn("loading");$this.attr("disabled","disabled");},
				complete:function(){$this.btn("reset");$this.removeAttr("disabled");},
				success:function(json){
 					$this.removeAttr("disabled");
					if(json['redirect'])
						window.location = json['redirect'];

					if(json['warning'])
						alert(json['warning']);
				},
			});  
		})
	</script>
<?php } ?>