<div class="row">
	<div class="col-12">
		<div class="card m-b-30">
			<div class="card-header d-flex justify-content-between align-items-center">
				<h4 class="card-title mb-0"><?= __('admin.home_content') ?></h4>
				<a class="btn btn-primary" href="<?= base_url('themes/multiple_theme/')  ?>"><?= __('admin.cancel') ?></a>
			</div>
			<div class="card-body">
				<form id="admin-form">
					<input type="hidden" name="hhomeContent_id" value="<?= (int)$homeContent->id ?>">
					<input type="hidden" name="hidden_image" id="hidden_image" value="<?= $homeContent->image ?>">

					<div class="row">
						<div class="col-md-4">
							<div class="form-group">
					            <label class="form-label"><?= __('admin.select_language') ?></label>
					            <select class="form-select" name="language_id" id="drpLanguage">
					                <?php 
					                if(isset($languages))
					                {
					                    foreach($languages as $language)
					                    {?>
					                    <option <?php 

					                    if($language['is_default']==1) {echo 'selected';} ?> value="<?=$language['id']?>"><?=$language['name'] ?></option>
					                  
					                   <?php  }     
					                }?>
					                
					            </select>
					        </div>    
				    	</div>
						<div class="col-sm-12">
							<div class="form-group">
								<label class="form-label"><?= __('admin.title') ?></label>
								<input placeholder="<?= __('admin.title') ?>" name="title" value="<?php echo $homeContent->title; ?>" class="form-control" type="text">
							</div>
						</div>
						<div class="col-sm-12">
							<div class="form-group">
								<label class="form-label"><?= __('admin.description') ?></label>
								<textarea id="summernote" placeholder="<?= __('admin.description') ?>" name="description" class="form-control" value="<?php echo $homeContent->description; ?>" type="text"></textarea>
							</div>
						</div>
        				<div>
        				    <div class="form-group">
								<label class="form-label"><?= __('admin.status') ?></label>
								<div>
									<div class="form-check form-check-inline">
										<input class="form-check-input" type="radio" <?php echo ($homeContent->status == 1) ? "checked" : "" ?>  name="status" value="1" id="status_active">
										<label class="form-check-label" for="status_active"><?= __('admin.active') ?></label>
									</div>
									<div class="form-check form-check-inline">
										<input class="form-check-input" type="radio" <?php echo ($homeContent->status == 0) ? "checked" : "" ?>  name="status" value="0" id="status_inactive">
										<label class="form-check-label" for="status_inactive"><?= __('admin.inactive') ?></label>
									</div>
								</div>
							</div>
        				</div>
					</div>
					<div class="form-group">
						<label class="form-label"><?= __('admin.content_image') ?></label>
						
						<div class="fileUpload btn btn-sm btn-primary">
							<span><?= __('admin.choose_file') ?></span>
							<input id="uploadBtn" name="avatar" class="upload" type="file">
						</div>
						<?php $avatar = $homeContent->image != '' ? $homeContent->image : 'no-image-available.gif' ; ?>
						<?php 
						    if($homeContent->image != '') {
						        echo '<img src="'.base_url().'assets/images/theme_images/'.$avatar.'" id="blah" class="thumbnail" border="0" width="220px">';
						    }
						    else {
						        echo '<img src="'.base_url().'assets/images/'.$avatar.'" id="blah" class="thumbnail" border="0" width="220px">';
						    }
						?>
						
					</div>
					<div class="form-group">
						<button type="button" class="btn btn-primary btn-submit"> <?= __('admin.submit') ?> </button>
						<span class="loading-submit"></span>
					</div>
				</form>
			</div>
		</div> 
	</div> 
</div>

<script type="text/javascript">

	$(document).ready(function() {	
		$('#summernote').summernote({
			minHeight: 300,
			toolbar: [
						['style', ['bold', 'italic', 'underline', 'clear']],
						['font', ['strikethrough', 'superscript', 'subscript']],
						['fontsize', ['fontsize']],
						['color', ['color']],
						['para', ['ul', 'ol', 'paragraph']],
						['height', ['height']],
						['insert', ['link', 'picture', 'video']],
						['view', ['fullscreen', 'codeview', 'help']]
					]	
		});
	});


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
		$('#hidden_image').val();
	};

	$(".btn-submit").on('click',function(evt){
	    $("#linkError").empty();
        $this = $("#admin-form");
        $(".btn-submit").prop('disabled', true).html('<span class="spinner-border spinner-border-sm me-2"></span>Loading...');
		$('.loading-submit').show();
        
        evt.preventDefault();
        var formData = new FormData($("#admin-form")[0]);

        formData = formDataFilter(formData);
        
        $.ajax({
            url:'<?= base_url('themes/save_homecontent') ?>',
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
                        $('.loading-submit').text(percentComplete + "% "+'<?= __('admin.loading') ?>');
                    }
                }, false );

                jqXHR.addEventListener( "progress", function ( evt ){
                    if ( evt.lengthComputable ){
                        var percentComplete = Math.round( (evt.loaded * 100) / evt.total );
                        $('.loading-submit').text('<?= __('admin.save') ?>');
                    }
                }, false );
                return jqXHR;
            },
            complete:function(result){
            	$(".btn-submit").prop('disabled', false).html($(this).data('original-text') || 'Submit');
            },
            success:function(result){
                $('.loading-submit').hide();
                $this.find(".has-error").removeClass("has-error");
                $this.find("span.text-danger").remove();
                
                if(result['location']){
                    window.location = result['location'];
                }
                console.log(result['errors']);
                if(result['errors']){
                    $.each(result['errors'], function(i,j){
                        $ele = $this.find('[name="'+ i +'"]');
						$ele.parents(".form-group").addClass("has-error");
						if(i == 'avatar')
							$ele.parent().parent().append("<span class='text-danger'>"+ j +"</span>");
						else
							$ele.after("<span class='text-danger'>"+ j +"</span>");
                    });
                }
            },
        })
        return false;
    });
</script>