<?php foreach($categories as $index => $category) { ?>
	<tr id="category-row-<?= $category['id'] ?>">
		<td><span class="badge bg-secondary"><?= $category['id'] ?></span></td>
		<td>
			<div class="fw-bold"><?= htmlspecialchars($category['name']) ?></div>
		</td>
		<td>
			<?php if (!empty($category['parent_name'])) { ?>
				<span class="badge bg-info"><?= htmlspecialchars($category['parent_name']) ?></span>
			<?php } else { ?>
				<span class="text-muted">
					<i class="bi bi-dash-circle me-1"></i><?= __('admin.no_parent_available') ?>
				</span>
			<?php } ?>
		</td>
		<td>
			<span class="text-muted"><?= date('M d, Y', strtotime($category['created_at'])) ?></span>
		</td>
		<td class="text-center">
			<div class="btn-group" role="group">
				<a class="btn btn-outline-primary btn-sm" href="<?= base_url('integration/integration_category_add/'. $category['id']) ?>" title="<?= __('admin.edit') ?>">
					<i class="bi bi-pencil-square"></i>
				</a>
				<button class="btn btn-outline-danger btn-sm delete-category" data-id="<?= $category['id'] ?>" title="<?= __('admin.delete') ?>">
					<i class="bi bi-trash"></i>
				</button>
			</div>
		</td>
	</tr>
<?php } ?>

<?php if(empty($categories)) { ?>
<tr>
    <td colspan="5" class="text-center py-5">
        <div class="d-flex justify-content-center align-items-center flex-column">
            <i class="bi bi-folder display-4 text-muted d-block mb-3"></i>
            <h4 class="text-muted"><?= __('admin.no_data_found') ?></h4>
            <p class="text-muted"><?= __('admin.no_categories_found_message') ?></p>
            <a href="<?= base_url('integration/integration_category_add') ?>" class="btn btn-primary btn-sm rounded-pill">
                <i class="bi bi-plus-lg me-1"></i><?= __('admin.add_first_category') ?>
            </a>
        </div>
    </td>
</tr>
<?php } ?>
