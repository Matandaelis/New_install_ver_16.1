<div class="container-fluid">
	<div class="row">
		<div class="col-12">
			<div class="card mt-3 mb-3 shadow-sm">
				<div class="card-header bg-white border-bottom d-flex justify-content-between align-items-center">
					<h4 class="card-title mb-0"><?= __('user.all_notification') ?></h4>
					<button class="btn btn-danger btn-sm d-none btn-delete-selected">
						<i class="fas fa-trash-alt me-1"></i><?= __('user.delete_selected') ?>
					</button>
				</div>
				<div class="card-body">
					<?php if ($notifications == null) { ?>
						<div class="text-center py-5">
							<i class="fas fa-bell-slash fa-5x text-muted mb-3"></i>
							<h3 class="text-muted"><?= __('user.no_data_found') ?></h3>
						</div>
					<?php } else { ?>
						<div class="table-responsive">
							<table class="table table-hover mb-0">
								<thead class="table-light">
									<tr>
										<th colspan="4" class="border-bottom-0">
											<div class="form-check">
												<input class="form-check-input select_all" type="checkbox" id="selectAll">
												<label class="form-check-label" for="selectAll">
													<?= __('user.select_all') ?>
												</label>
											</div>
										</th>
									</tr>
								</thead>
								<tbody>
									<?php foreach ($notifications as $key => $notification) { ?>
									<tr>
										<td style="width: 50px;">
											<?php if($notification['notification_view_user_id'] == $user_id){ ?>
												<div class="form-check">
													<input class="form-check-input notification_id" type="checkbox" value="<?= $notification['notification_id'] ?>" name="notification[]" id="notif_<?= $notification['notification_id'] ?>">
												</div>
											<?php } ?>
										</td>
										<td style="width: 50px;">
											<div class="bg-primary bg-opacity-10 rounded-circle d-flex align-items-center justify-content-center" style="width: 40px; height: 40px;">
												<i class="fas fa-bell text-primary"></i>
											</div>
										</td>
										<td>
											<div class="fw-bold text-dark mb-1"><?= $notification['notification_title'] ?></div>
											<small class="text-muted"><?= $notification['notification_description'] ?></small>
										</td>
										<td style="width: 100px;" class="text-end">
											<a class="btn btn-primary btn-sm" href="javascript:void(0)" onclick="shownofication(<?= $notification['notification_id'] . ',\'' . base_url('admincontrol') . $notification['notification_url'] . '\'' ?>)">
												<i class="fas fa-eye me-1"></i><?= __('user.details') ?>
											</a>
										</td>
									</tr>
									<?php } ?>
								</tbody>
							</table>
						</div>
					<?php } ?>
				</div>
				<?php if ($notifications != null) { ?>
				<div class="card-footer bg-white border-top d-flex justify-content-end">
					<?= $pagination ?>
				</div>
				<?php } ?>
			</div>
		</div>
	</div>
</div>

<script type="text/javascript">
	$('.btn-delete-selected').on('click',function(){
		var ids = [];
		if($('.notification_id:checked').length > 0){
			var $this = $(this);
			window.confirmDelete("<?= __('user.confirm_delete_notifications') ?>", function(){
				$('.notification_id:checked').each(function(){
					ids.push($(this).val());
				})
				$.ajax({
					type:'POST',
					dataType:'json',
					data:{delete_ids:ids},
					beforeSend:function(){
						$this.prop("disabled", true);
					},
					complete:function(){
						$this.prop("disabled", false);
					},
					success:function(json){
						window.location.reload();
					},
				})
			});
			return false;
		}
		else
		{
			alert("<?= __('user.select_notification') ?>");
		}
	})
	$('.select_all').on('click',function(){
		$('.notification_id').prop("checked", $(this).prop("checked") );
		chnageStatus()
	})
	$('.notification_id').change(function(){
		chnageStatus()
	})

	function chnageStatus() {
		if($('.notification_id:checked').length){
			$(".btn-delete-selected").removeClass("d-none");
		} else{
			$(".btn-delete-selected").addClass("d-none");
		}
	}
</script>