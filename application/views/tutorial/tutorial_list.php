<div class="alert alert-info d-flex align-items-center mb-3">
	<i class="fas fa-info-circle me-2"></i>
	<small><?= __('admin.drag_drop_to_reorder') ?></small>
</div>

<div class="table-responsive">
	<table class="table table-hover table-bordered align-middle">
		<thead class="table-light">
			<tr>
				<th width="5%" class="text-center">#</th>
				<th><?= __('admin.title') ?></th>
				<th><?= __('admin.category') ?></th>
				<th><?= __('admin.language') ?></th>
				<th class="text-center"><?= __('admin.status') ?></th>
				<th><?= __('admin.created_at') ?></th>
				<th width="15%" class="text-center"><?= __('admin.action')?></th>
			</tr>
		</thead>

		<tbody data-whe_column="id" data-pos_column="position" data-table="tutorial_pages" class="sortable">

			<?php if(empty($tutorials)){ ?>
				<tr>
				    <td colspan="7" class="text-center py-5">
				        <div class="text-muted">
				            <i class="fas fa-file-alt fa-3x mb-3 opacity-50"></i>
				            <h5><?= __('admin.no_tutorials_found') ?></h5>
				            <p class="mb-0"><?= __('admin.create_your_first_tutorial') ?></p>
				        </div>
				    </td>
				</tr>
			<?php } ?>

			<?php 
			$counter = 1;
			foreach ($tutorials as $tutorial) { 
				$language_name = 'English';
				if(isset($tutorial['language_name'])) {
					$language_name = $tutorial['language_name'];
				} elseif(isset($tutorial['language_id'])) {
					$language_name = 'Language ' . $tutorial['language_id'];
				}
			?>

			<tr data-id="<?= $tutorial['id'] ?>" class="cursor-move">
				<td class="text-center">
					<div class="d-flex align-items-center justify-content-center">
						<i class="fas fa-grip-vertical text-muted me-2"></i>
						<span class="badge bg-light text-dark border"><?= $counter++ ?></span>
					</div>
				</td>
				<td>
					<div class="d-flex align-items-start">
						<i class="fas fa-file-alt me-2 text-primary mt-1"></i>
						<div>
							<div class="fw-semibold"><?= htmlspecialchars($tutorial['title']) ?></div>
							<?php if(!empty($tutorial['content'])) { ?>
								<small class="text-muted"><?= htmlspecialchars(substr(strip_tags($tutorial['content']), 0, 60)) ?>...</small>
							<?php } ?>
						</div>
					</div>
				</td>
				<td>
					<span class="badge bg-info">
						<i class="fas fa-folder me-1"></i>
						<?= htmlspecialchars($tutorial['name']) ?>
					</span>
				</td>
				<td>
					<span class="badge bg-secondary"><?= htmlspecialchars($language_name) ?></span>
				</td>
				<td class="text-center">
					<?php if($tutorial['status'] == 1) { ?>
						<span class="badge bg-success">
							<i class="fas fa-check-circle me-1"></i>
							<?= __('admin.active') ?>
						</span>
					<?php } else { ?>
						<span class="badge bg-danger">
							<i class="fas fa-times-circle me-1"></i>
							<?= __('admin.inactive') ?>
						</span>
					<?php } ?>
				</td>
				<td>
					<div class="text-muted small">
						<i class="fas fa-calendar-plus me-1"></i>
						<?= date('M d, Y', strtotime($tutorial['created_at'] ?? 'now')) ?>
						<br>
						<small><?= date('H:i', strtotime($tutorial['created_at'] ?? 'now')) ?></small>
					</div>
				</td>
				<td>
					<div class="d-flex gap-2 justify-content-center">
						<a class="btn btn-sm btn-outline-primary" 
						   href="<?= base_url('admincontrol/manage_tutorial/'. $tutorial['id']) ?>" 
						   data-bs-toggle="tooltip" 
						   title="<?= __('admin.edit') ?>">
							<i class="fas fa-edit"></i>
						</a>
						<a class="btn btn-sm btn-outline-danger" 
						   href="<?= base_url('admincontrol/deleteTutorial/'. $tutorial['id']) ?>" 
						   onclick="return onDeleteReview(<?= $tutorial['id'] ?>);" 
						   data-bs-toggle="tooltip" 
						   title="<?= __('admin.delete') ?>">
							<i class="fas fa-trash-alt"></i>
						</a>
					</div>
				</td>
			</tr>

			<?php } ?>

		</tbody>

	</table>
</div>

<script type="text/javascript">
	function onDeleteReview($rating_id)
	{
		if(!confirm("<?= __('admin.are_you_sure') ?>")) 
		return false;
		else
		return true;	 
	}

	$(function() {
		$( ".sortable" ).sortable({
			update: function( event, ui ) {
				let positions = [];

				$(this).children('tr').each(function () {
					if($(this).data('id') != null) {
						positions.push($(this).data('id'));
					}
				});

				$.ajax({
					url: "<?= base_url('themes/change_positions')  ?>",
					type: "POST",
					dataType: "json",
					data: {table:$(this).data('table'), whe_column:$(this).data('whe_column'), pos_column:$(this).data('pos_column'),positions:JSON.stringify(positions)},
					success: function (response) {
						showToast('success', '<?= __('admin.order_updated_successfully') ?>');
					}
				});
			}
		});

		$( ".sortable" ).disableSelection();
		
		// Initialize tooltips
		var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
		var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
			return new bootstrap.Tooltip(tooltipTriggerEl);
		});
	});
</script>
