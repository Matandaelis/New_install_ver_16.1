<div class="table-responsive">
	<table class="table table-hover table-bordered align-middle">
		<thead class="table-light">
			<tr>
				<th width="5%" class="text-center">#</th>
				<th><?= __('admin.name') ?></th>
				<th><?= __('admin.language') ?></th>
				<th class="text-center"><?= __('admin.tutorials_count') ?></th>
				<th><?= __('admin.created_at') ?></th>
				<th><?= __('admin.updated_at') ?></th>
				<th width="15%" class="text-center"><?= __('admin.action')?></th>
			</tr>
		</thead>

		<tbody data-whe_column="id" data-pos_column="position" data-table="tutorial_categories" class="sortable">

			<?php if(empty($categories)){ ?>
				<tr>
				    <td colspan="7" class="text-center py-5">
				        <div class="text-muted">
				            <i class="fas fa-folder-open fa-3x mb-3 opacity-50"></i>
				            <h5><?= __('admin.no_categories_found') ?></h5>
				            <p class="mb-0"><?= __('admin.create_your_first_category') ?></p>
				        </div>
				    </td>
				</tr>
			<?php } ?>

			<?php 
			$counter = 1;
			foreach ($categories as $category) { 
				$tutorial_count = 0;
				if(isset($category['tutorial_count'])) {
					$tutorial_count = $category['tutorial_count'];
				}
			?>

			<tr data-id="<?= $category['id'] ?>" class="cursor-move">
				<td class="text-center">
					<div class="d-flex align-items-center justify-content-center">
						<i class="fas fa-grip-vertical text-muted me-2"></i>
						<span class="badge bg-light text-dark border"><?= $counter++ ?></span>
					</div>
				</td>
				<td>
					<div class="d-flex align-items-start">
						<i class="fas fa-folder me-2 text-primary mt-1"></i>
						<div>
							<div class="fw-semibold"><?= htmlspecialchars($category['name']) ?></div>
							<?php if(!empty($category['slug'])) { ?>
								<small class="text-muted"><?= htmlspecialchars($category['slug']) ?></small>
							<?php } ?>
						</div>
					</div>
				</td>
				<td>
					<?php 
					$language_name = 'English';
					if(isset($category['language_name'])) {
						$language_name = $category['language_name'];
					} elseif(isset($category['language_id'])) {
						$language_name = 'Language ' . $category['language_id'];
					}
					?>
					<span class="badge bg-info"><?= htmlspecialchars($language_name) ?></span>
				</td>
				<td class="text-center">
					<div class="d-flex align-items-center justify-content-center">
						<i class="fas fa-file-alt me-2 <?= $tutorial_count > 0 ? 'text-success' : 'text-muted' ?>"></i>
						<span class="fw-semibold <?= $tutorial_count > 0 ? 'text-success' : 'text-muted' ?>">
							<?= $tutorial_count ?>
						</span>
					</div>
				</td>
				<td>
					<div class="text-muted small">
						<i class="fas fa-calendar-plus me-1"></i>
						<?= date('M d, Y', strtotime($category['created_at'])) ?>
						<br>
						<small><?= date('H:i', strtotime($category['created_at'])) ?></small>
					</div>
				</td>
				<td>
					<?php if(!empty($category['updated_at'])) { ?>
						<div class="text-muted small">
							<i class="fas fa-calendar-check me-1"></i>
							<?= date('M d, Y', strtotime($category['updated_at'])) ?>
							<br>
							<small><?= date('H:i', strtotime($category['updated_at'])) ?></small>
						</div>
					<?php } else { ?>
						<span class="text-muted">-</span>
					<?php } ?>
				</td>
				<td>
					<div class="d-flex gap-2 justify-content-center">
						<a class="btn btn-sm btn-outline-primary" 
						   href="<?= base_url('admincontrol/manage_tutorial_catgory/'. $category['id']) ?>" 
						   data-bs-toggle="tooltip" 
						   title="<?= __('admin.edit') ?>">
							<i class="fas fa-edit"></i>
						</a>
						<a class="btn btn-sm btn-outline-danger" 
						   href="<?= base_url('admincontrol/deleteTutorialCategory/'. $category['id']) ?>" 
						   onclick="return onDeleteCategory(<?= $category['id'] ?>);" 
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
	function onDeleteCategory($rating_id)
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
