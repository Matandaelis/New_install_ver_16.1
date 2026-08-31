<div class="container-fluid">
	<div class="card shadow-sm">
		<div class="card-header bg-primary text-white">
			<h5 class="mb-0">
				<i class="fab fa-aws me-2"></i><?= __('user.amazon_s3_settings') ?>
			</h5>
		</div>
		<div class="card-body">
			<form class="form-horizontal" method="post" action="" enctype="multipart/form-data" id="s3-setting-form">
				<div class="mb-4">
					<label class="form-label fw-bold">
						<i class="fas fa-database me-1 text-primary"></i><?= __('user.s3_bucket_name') ?>
					</label>
					<input type="text" id="s3_bucket_name" class="form-control form-control-lg" name="s3_bucket_name" value="<?= $setting['s3_bucket_name'] ?? ''; ?>" placeholder="<?= __('user.enter_your_s3_bucket_name') ?>">
				</div>

				<div class="mb-4">
					<label class="form-label fw-bold">
						<i class="fas fa-globe me-1 text-primary"></i><?= __('user.s3_region') ?>
					</label>
					<input type="text" id="s3_region" class="form-control form-control-lg" name="s3_region" value="<?= $setting['s3_region'] ?? ''; ?>" placeholder="<?= __('user.enter_your_s3_region') ?>">
				</div>

				<div class="d-grid gap-2 d-md-flex justify-content-md-end">
					<button type="submit" class="btn btn-primary btn-lg px-4 btn-submit">
						<i class="fas fa-save me-1"></i><?= __('user.save_settings') ?>
					</button>
					<button type="button" class="btn btn-info btn-lg px-4" onclick="loadS3Images()">
						<i class="fas fa-vial me-1"></i><?= __('user.test_amazon_data') ?>
					</button>
				</div>
			</form>
		</div>
	</div>
</div>

<script type="text/javascript">
	$(".btn-submit").on('click', function(evt) {
		evt.preventDefault();
		var formData = new FormData($("#s3-setting-form")[0]);

		$(".btn-submit").btn("loading");
		formData = formDataFilter(formData);
		$this = $("#s3-setting-form");

		$.ajax({
			type: 'POST',
			dataType: 'json',
			cache: false,
			contentType: false,
			processData: false,
			data: formData,
			success: function(result) {
				$(".btn-submit").btn("reset");
				$(".alert-dismissable").remove();

				$this.find(".has-error").removeClass("has-error");
				$this.find("span.text-danger").remove();

				if (result['location']) {
					window.location = result['location'];
				}

				if (result['success']) {
					showToast('success', result['success']);
					var body = $("html, body");
					body.stop().animate({scrollTop: 0}, 500, 'swing', function() {});
				}

				if (result['errors']) {
					$.each(result['errors'], function(i, j) {
						$ele = $this.find('[name="' + i + '"]');
						if ($ele) {
							$ele.parents(".mb-4").addClass("has-error");
							$ele.after("<span class='d-block text-danger mt-1'>" + j + "</span>");
						}
					});
				}
			},
		})
		return false;
	});
</script>
