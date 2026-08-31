<?php if(!empty($categories)): ?>
    <?php foreach($categories as $index => $category): ?>
        <tr class="category-row">
            <td class="text-center align-middle">
                <div class="position-relative">
                    <img class="rounded shadow-sm" width="45px" height="45px" 
                         src="<?= $category['image_url'] ?>" 
                         style="object-fit: cover;"
                         onerror="this.src='<?= base_url('assets/images/no_image_available.png') ?>'">
                </div>
            </td>
            <td class="align-middle">
                <span class="badge bg-light text-dark border"><?= $category['id'] ?></span>
            </td>
            <td class="align-middle">
                <div class="fw-semibold text-dark"><?= $category['name'] ?></div>
                <?php if(!empty($category['description'])): ?>
                    <small class="text-muted text-truncate d-block" style="max-width: 200px;" title="<?= htmlspecialchars(strip_tags($category['description'])) ?>">
                        <?= substr(strip_tags($category['description']), 0, 50) ?>...
                    </small>
                <?php endif; ?>
            </td>
            <td class="align-middle">
                <?php if($category['parent_name']): ?>
                    <span class="badge bg-secondary"><?= $category['parent_name'] ?></span>
                <?php else: ?>
                    <span class="badge bg-primary"><?= __('admin.root_category') ?></span>
                <?php endif; ?>
            </td>
            <td class="text-center align-middle">
                <button class="btn btn-outline-info btn-sm position-relative" 
                        product-category="<?= $category['id'] ?>" 
                        data-category-name="<?= htmlspecialchars($category['name']) ?>">
                    <i class="bi bi-box-seam me-1"></i>
                    <span class="badge bg-info text-white position-absolute top-0 start-100 translate-middle rounded-pill">
                        <?= $category['total_product'] ?>
                    </span>
                    <?= __('admin.view') ?>
                </button>
            </td>
            <td class="align-middle">
                <div class="fw-medium"><?= dateGlobalFormat($category['created_at']) ?></div>
                <small class="text-muted"><?= date('H:i', strtotime($category['created_at'])) ?></small>
            </td>
            <td class="text-center align-middle">
                <div class="btn-group btn-group-sm" role="group">
                    <a href="<?= base_url('admincontrol/store_category_add/'. $category['id']) ?>" 
                       class="btn btn-outline-primary" title="<?= __('admin.edit') ?>">
                        <i class="bi bi-pencil-square"></i>
                    </a>
                    <button class="btn btn-outline-secondary copy-category-btn"
                            data-id="<?= $category['id'] ?>"
                            data-name="<?= htmlspecialchars($category['name']) ?>"
                            title="<?= __('admin.copy_category') ?>">
                        <i class="bi bi-copy"></i>
                    </button>
                    <button class="btn btn-outline-danger delete-category-btn" 
                            data-id="<?= $category['id'] ?>" 
                            title="<?= __('admin.delete') ?>">
                        <i class="bi bi-trash"></i>
                    </button>
                </div>
            </td>
        </tr>
    <?php endforeach; ?>
<?php else: ?>
    <tr>
        <td colspan="7" class="text-center py-5">
            <div class="d-flex justify-content-center align-items-center flex-column">
                <i class="bi bi-tags display-1 text-muted mb-3"></i>
                <h4 class="text-muted mb-2"><?= __('admin.no_data_found') ?></h4>
                <p class="text-muted"><?= __('admin.no_categories_found') ?></p>
                <a href="<?= base_url('admincontrol/store_category_add') ?>" class="btn btn-primary mt-2">
                    <i class="bi bi-plus-circle me-1"></i><?= __('admin.add_category') ?>
                </a>
            </div>
        </td>
    </tr>
<?php endif; ?>