<style type="text/css">
	.fileUpload-gallery-s3 img {
    width: 100px;
    height: 100px;
    border: solid 2px #ddd;
    padding: 2px;
    margin: 5px;
}
</style>
		<form class="form-horizontal" method="post" action=""  enctype="multipart/form-data">
			<div class="row sh">
				<div class="col-sm-8">
					<div class="card h-100">
						<div class="card-header"><h4 class="mt-0 header-title"><?= __('admin.all_product_images') ?></h4></div>
						<div class="card-body">
							<?php foreach($imageslist as $images){ ?>
								<div class="popup-gallery">
									<a class="pull-left" href="<?= strpos($images['product_media_upload_path'], 'http://') === 0 || strpos($images['product_media_upload_path'], 'https://') === 0 ? $images['product_media_upload_path'] : base_url('assets/images/product/upload/thumb/' . $images['product_media_upload_path']); ?>"
									 >
										<div class="img-responsive">
											<img width="200px" height="200px" src="<?= strpos($images['product_media_upload_path'], 'http://') === 0 || strpos($images['product_media_upload_path'], 'https://') === 0 ? $images['product_media_upload_path'] : base_url('assets/images/product/upload/thumb/' . $images['product_media_upload_path']); ?>" ><br>
										</div>
									</a>
	                                <span class="delete_item" onclick="delete_image(<?php echo $images['product_media_upload_id'];?>);" >&times;</span>
								</div>
							<?php } ?>
						</div>
					</div>
				</div>

				<div class="col-sm-4">
					<div class="card h-100">
						<div class="card-header">
							<h4 class="card-title m-0"><?= __('admin.product_multiple_images') ?></h4>
						</div>
						<div class="card-body">
							<div class="form-group form-image-group">
								<div>
									<label class="control-label"><?= __('admin.product_multiple_images') ?></label><br>
									<div class="col-sm-9">
										<div class="fileUpload btn btn-sm btn-primary">
											<span><?= __('admin.choose_file') ?></span>
											<input id="gallery-photo-add" name="product_multiple_image[]" class="upload" type="file" multiple="">
										</div>
										 <!-- Amazon S3 Button -->
                                <button type="button" class="btn btn-sm btn-primary mt-2" onclick="prepareS3ModalDownloadbale('input[name=\'product_multiple_image_s3[]\']', '.fileUpload-gallery','multipleProduct')">
                                    <?= __('admin.browse_amazon_s3_image') ?>
                                </button>
                                <!-- S3 Bucket Name -->
                                <input name="s3_storage[s3_bucket_name]" value="<?= $s3_setting['s3_bucket_name']; ?>" id="s3_bucket_name" class="form-control" type="hidden">
                                <!-- S3 Region -->
                                <input name="s3_storage[s3_region]" id="s3_region" value="<?= $s3_setting['s3_region']; ?>" class="form-control" type="hidden">
                                <input type="hidden" name="product_multiple_image_s3[]" value="">

										<?php $product_multiple_image = 'no-image.jpg' ; ?>
										<img src="<?php echo base_url();?>assets/images/thumbs/<?php echo $product_multiple_image; ?>" id="multipleimage" class="thumbnail" border="0" width="220px">
									</div>
								</div>
							</div>
							<div class="fileUpload-gallery"></div>
							<div class="fileUpload-gallery-s3 mt-2"></div>
                        	<div class="fileinput-gallery-s3 mt-2"></div>
							<button class="btn btn-block btn-default btn-success" id="update-product" type="submit"><i class="fa fa-save"></i> <?= __('admin.submit') ?></button>
						</div>
					</div>
				</div>
			</div>
		</form>
		
	

	<script type="text/javascript">
var fileArray = [];
		var imagesPreview = function(input, placeToInsertImagePreview) {
	        if (input.files) {
	            var filesAmount = input.files.length;
	            for (i = 0; i < filesAmount; i++) {
	                var reader = new FileReader();

	                reader.onload = function(event) {
	                    $($.parseHTML('<img>')).attr('src', event.target.result).appendTo(placeToInsertImagePreview);
	                }

	                reader.readAsDataURL(input.files[i]);
	            }
	        }
	    };

	    $('#gallery-photo-add').on('change', function() {
	        imagesPreview(this, 'div.fileUpload-gallery');
	    });

        function delete_image(id){
            $.confirm({
                title: '<?= __('admin.delete_image') ?>',
                content: '<?= __('admin.do_you_want_to_delete_this_image') ?>',
                buttons: {
                    confirm: function () {
                        $.ajax({
                            type: "POST",
                            url: "<?php echo base_url();?>usercontrol/delete_image",
                            data:'image_id='+id,
                            success: function(data){
                                location.reload();
                            }
                        });
                    },
                    cancel: function () {
                        $.alert('Canceled!');
                    }
                }
            });
        }
</script>
