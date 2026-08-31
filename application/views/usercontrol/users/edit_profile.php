<div class="container-fluid">
		<div class="form-horizontal" method="post" id="profile-frm" enctype="multipart/form-data">
			<div class="row">
				<div class="col-12">
					<div class="card shadow-sm">
						<div class="card-header bg-primary text-white">
							<h5 class="mb-0">
								<i class="fas fa-user-edit me-2"></i><?= __('user.edit_profile') ?>
							</h5>
						</div>
						<div class="card-body">
							<div class="row">
								<div class="col-lg-8">
									<?= $html_form ?>
									<div class="d-grid mt-4">
										<button class="btn btn-success btn-lg" id="update-user" type="submit">
											<i class="fa fa-save me-2"></i><?= __('user.update_profile') ?>
											<span class="loading-submit"></span>
										</button>
									</div>
								</div>
								<div class="col-lg-4">
									<div class="card border-primary">
										<div class="card-header bg-primary bg-opacity-10">
											<h6 class="mb-0 text-primary">
												<i class="fas fa-image me-1"></i><?= __('user.member_image') ?>
											</h6>
										</div>
										<div class="card-body text-center">
											<?php $avatar = $user['avatar'] != '' ? 'assets/images/users/'.$user['avatar'] : 'assets/template/images/avatar-1.jpg' ; ?>
											<img src="<?php echo base_url($avatar); ?>" id="blah" class="img-thumbnail rounded-circle mb-3" alt="Profile" style="width:200px;height:200px;object-fit:cover">
											
											<div class="position-relative d-inline-block">
												<label for="uploadBtn" class="btn btn-primary btn-sm mb-0" style="cursor:pointer">
													<i class="fas fa-camera me-1"></i><?= __('user.choose_file') ?>
												</label>
												<input id="uploadBtn" name="avatar" class="d-none" type="file" accept="image/*">
											</div>
											<p class="text-muted small mt-2 mb-0"><?= __('user.recommended_square_image') ?></p>
										</div>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>



<script type="text/javascript">
	$( document ).ready(function() {
		$("#update-user").click(function(evt){
			console.log("=== UPDATE BUTTON CLICKED ===");
			$this = $(".reg_form");
			console.log("Selected element:", $this);
			console.log("Element length:", $this.length);
			console.log("Element[0]:", $this[0]);
			evt.preventDefault();

	        var is_valid = 0;
	        var need_valid = 0;

		$(".tel_input").each(function() {

			let this_is_valid = true;
			console.log("Phone field found:", $(this).attr('id'), "Value:", $(this).val());

		    $(this).parents(".form-group").removeClass("has-error");
		    
		    $(this).parents(".form-group").find(".text-danger").remove();

		    if(window["tel_input"+$(this).attr('id')]){
		    	console.log("Phone validator exists for:", $(this).attr('id'));
		        var errorMap = ['<?= __('user.invalid_number') ?>','<?= __('user.invalid_country_code') ?>','<?= __('user.too_short') ?>','<?= __('user.too_long') ?>','<?= __('user.invalid_number') ?>'];
		        var errorInnerHTML = '';
		        
		        if ($(this).val().trim()) {
		        	console.log("Phone has value, checking if field is required...");
		        	if($(this).attr('required') !== undefined) {
		        		console.log("Phone is required, validating...");
		        		need_valid++;
			            if (window["tel_input"+$(this).attr('id')].isValidNumber()) {
			            	console.log("Phone is VALID");
							window["tel_input"+$(this).attr('id')].setNumber($(this).val().trim());

			                is_valid++;
			                this_is_valid = true;
			            } else {
			            	console.log("Phone is INVALID");
			                var errorCode = window["tel_input"+$(this).attr('id')].getValidationError();
			                console.log("Error code:", errorCode);
			                errorInnerHTML = errorMap[errorCode];
			                console.log("Error message:", errorInnerHTML);
			                this_is_valid = false;
			            }
		        	} else {
		        		console.log("Phone is optional, accepting value without strict validation");
		        		this_is_valid = true;
		        	}
		        } else {
		        	console.log("Phone field is empty");
		        	if($(this).attr('required') !== undefined) {
		        		console.log("Phone is required but empty!");
		        		need_valid++;
		                this_is_valid = false;
			        	errorInnerHTML = 'The Mobile Number field is required.'; 
			        } else {
			        	console.log("Phone is optional and empty, skipping validation");
			        }
		        }

		        if(!this_is_valid){
		        	console.log("Phone validation FAILED - adding error message:", errorInnerHTML);
		            $(this).parents(".form-group").addClass("has-error");
		            $(this).parents(".form-group").find('> div').after("<span class='text-danger'>"+ errorInnerHTML +"</span>");
		        }
		    } else {
		    	console.log("No phone validator found for:", $(this).attr('id'));
		    }
		});

			console.log("Validation - is_valid:", is_valid, "need_valid:", need_valid);

			try {
				var formData = new FormData($this[0]);
				console.log("FormData created successfully");
			} catch(e) {
				console.error("FormData creation error:", e);
				alert("Error: Cannot create form data. Element type: " + ($this[0] ? $this[0].tagName : 'undefined'));
				return;
			}

	        formData.append("action", $(this).attr("name"));
	        formData.append("avatar", $("#uploadBtn")[0].files[0]);

	        formData = formDataFilter(formData);

	            $(".tel_input").each(function() {
			        if ($(this).val().trim() && window["tel_input"+$(this).attr('id')].isValidNumber()) {
			        	country_id = window["tel_input"+$(this).attr('id')].getSelectedCountryData().dialCode;
		                formData.append($(this).attr('name')+'_afftel_input_pre', country_id);
			        }
			    });

			console.log("FormData entries:");
			for (var pair of formData.entries()) {
				console.log(pair[0] + ': ' + (pair[1] instanceof File ? 'File: ' + pair[1].name : pair[1]));
			}

			if(is_valid === need_valid){
				console.log("Starting AJAX request...");
				$.ajax({
					url:'',
					type:'POST',
		            dataType:'json',
		            cache:false,
		            contentType: false,
		            processData: false,
		            data:formData,
		            xhr: function (){
		                var jqXHR = null;

		                if ( window.ActiveXObject ){
		                    jqXHR = new window.ActiveXObject( "Microsoft.XMLHTTP" );
		                }else {
		                    jqXHR = new window.XMLHttpRequest();
		                }
		                
		                jqXHR.upload.addEventListener( "progress", function ( evt ){
		                    if ( evt.lengthComputable ){
		                        var percentComplete = Math.round( (evt.loaded * 100) / evt.total );
		                        console.log( 'Uploaded percent', percentComplete );
		                        $('.loading-submit').text(percentComplete + "% "+'<?= __('user.loading') ?>');
		                    }
		                }, false );

		                jqXHR.addEventListener( "progress", function ( evt ){
		                    if ( evt.lengthComputable ){
		                        var percentComplete = Math.round( (evt.loaded * 100) / evt.total );
		                        $('.loading-submit').text('<?= __('user.save') ?>');
		                    }
		                }, false );
		                return jqXHR;
		            },
					beforeSend:function(){
						console.log("AJAX beforeSend");
					},
					complete:function(){
						console.log("AJAX complete");
					},
					success:function(json){
						console.log("AJAX success:", json);
						if(json['location']){
							console.log("Redirecting to:", json['location']);
							window.location = json['location'];
						}

						$this.find(".has-error").removeClass("has-error");
						$this.find("span.text-danger").remove();
						if(json['errors']){
							console.log("Validation errors found:", json['errors']);
						    $.each(json['errors'], function(i,j){
					        	$ele = $this.find('#'+ i);
						        if($ele.hasClass('form-group')){
						            $ele.addClass("has-error");
						            $ele.append("<br><span class='text-danger'>"+ j +"</span>");
						        } else {
						        	$ele.parents(".form-group").addClass("has-error");
						            $ele.after("<span class='text-danger'>"+ j +"</span>");
						        }
						    })
						}	
					},
					error: function(xhr, status, error) {
						console.error("AJAX error:", status, error);
						console.error("Response:", xhr.responseText);
						alert("Error submitting form. Check console for details.");
					}
				})
			} else {
				console.log("Validation failed - not submitting form");
			}
		})
	})
</script>

<script type="text/javascript">
	function readURL(input) {
		if (input.files && input.files[0]) {
		var reader = new FileReader();
			reader.onload = function(e) {
				jQuery('#blah').attr('src', e.target.result);
			}
			reader.readAsDataURL(input.files[0]);
		}
	}
	document.getElementById("uploadBtn").onchange = function () {
		readURL(this);
	};
	var state_id = '<?php echo $user->state ?>';

	$("#Country").on('change',function(){
	    var country = $(this).val();
	    $.ajax({
	        url: '<?php echo base_url('get_state') ?>',
	        type: 'post',
	        dataType: 'json',
	        data: {
	            country_id : country
	        },
	        success: function (json) {
	            if(json){
	                var html = '';
	                $.each(json, function(k,v){
	                    if(v.id == state_id){
	                        html += '<option value="'+v.id+'" selected="selected">'+v.name+'</option>';
	                    }else{
	                        html += '<option value="'+v.id+'">'+v.name+'</option>';
	                    }
	                });
	                $('#states').html(html);
	            }
	        }
	    });
	});
	$("#Country").trigger('change');
</script>
</div>