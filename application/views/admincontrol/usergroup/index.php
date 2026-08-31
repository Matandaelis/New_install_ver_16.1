<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            
            <!-- Header Section -->
            <div class="card bg-primary text-white border-0 shadow-sm mb-4">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h4 class="fw-bold text-white mb-1">
                                <i class="fa fa-users me-2"></i><?= __('admin.user_groups_management') ?>
                            </h4>
                            <p class="text-light opacity-75 mb-0"><?= __('admin.manage_user_groups_desc') ?></p>
                        </div>
                        <div class="d-flex align-items-center gap-2">
                            <span class="badge bg-light text-primary px-3 py-2">
                                <?= count($groups) ?> <?= __('admin.groups') ?>
                            </span>
                            <a href="<?= base_url('admincontrol/group_form/') ?>" class="btn btn-light">
                                <i class="fa fa-plus-circle me-2"></i><?= __("admin.add_new_group") ?>
                            </a>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Search and Filter Section -->
            <div class="card shadow-sm border-0 mb-4">
                <div class="card-body">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <div class="input-group">
                                <span class="input-group-text bg-light border-end-0">
                                    <i class="fa fa-search text-muted"></i>
                                </span>
                                <input type="text" class="form-control border-start-0" id="searchGroups" 
                                       placeholder="<?= __('admin.search_groups') ?>">
                            </div>
                        </div>
                        <div class="col-md-4">
                            <select class="form-select" id="filterByStatus">
                                <option value=""><?= __('admin.all_groups') ?></option>
                                <option value="default"><?= __('admin.default_groups') ?></option>
                                <option value="custom"><?= __('admin.custom_groups') ?></option>
                            </select>
                        </div>
                        <div class="col-md-2">
                            <button type="button" class="btn btn-outline-secondary w-100" onclick="clearFilters()">
                                <i class="fa fa-times me-1"></i><?= __('admin.clear') ?>
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Groups Table -->
            <div class="card shadow-sm border-0">
                <div class="card-header bg-light border-0">
                    <h6 class="fw-bold mb-0">
                        <i class="fa fa-table me-2"></i><?= __('admin.groups_list') ?>
                    </h6>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0">
                            <thead class="table-dark">
                                <tr>
                                    <th class="fw-bold"><?= __("admin.sn") ?></th>
                                    <th class="fw-bold"><?= __("admin.group_info") ?></th>
                                    <th class="fw-bold text-center"><?= __("admin.users") ?></th>
                                    <th class="fw-bold text-center"><?= __("admin.tools") ?></th>
                                    <th class="fw-bold"><?= __("admin.description") ?></th>
                                    <th class="fw-bold text-center"><?= __("admin.default") ?></th>
                                    <th class="fw-bold text-center"><?= __("admin.actions") ?></th>
                                </tr>
                            </thead>
                            <tbody id="user-groups">
                                <?php if (!empty($groups)): ?>
                                    <?php foreach($groups as $key => $group): ?>
                                        <tr class="group-row" data-group-name="<?= strtolower($group->group_name) ?>" data-is-default="<?= $group->is_default ?>">
                                            <td>
                                                <span class="badge bg-secondary"><?= (++$key) ?></span>
                                            </td>
                                            <td>
                                                <div class="d-flex align-items-center">
                                                    <div class="me-3">
                                                        <?php $avatar = $group->avatar != '' ? 'site/'.$group->avatar : 'no_image_available.png'; ?>
                                                        <img src="<?= base_url('assets/images/' . $avatar) ?>" 
                                                             class="rounded-circle border" 
                                                             width="50" height="50"
                                                             style="object-fit: cover;">
                                                    </div>
                                                    <div>
                                                        <h6 class="fw-bold mb-1"><?= htmlspecialchars($group->group_name) ?></h6>
                                                        <small class="text-muted">ID: <?= $group->id ?></small>
                                                    </div>
                                                </div>
                                            </td>
                                            <td class="text-center">
                                                <span class="badge bg-info px-3 py-2">
                                                    <i class="fa fa-users me-1"></i><?= $group->users_count ?>
                                                </span>
                                            </td>
                                            <td class="text-center">
                                                <span class="badge bg-success px-3 py-2">
                                                    <i class="fa fa-tools me-1"></i><?= $group->tools_count ?>
                                                </span>
                                            </td>
                                            <td>
                                                <div class="description-cell">
                                                    <?php 
                                                    $description = htmlspecialchars($group->group_description);
                                                    if (strlen($description) > 80): 
                                                    ?>
                                                        <span class="description-short"><?= substr($description, 0, 80) ?>...</span>
                                                        <span class="description-full d-none"><?= $description ?></span>
                                                        <button class="btn btn-link btn-sm p-0 toggle-description" type="button">
                                                            <small><?= __('admin.show_more') ?></small>
                                                        </button>
                                                    <?php else: ?>
                                                        <?= $description ?>
                                                    <?php endif; ?>
                                                </div>
                                            </td>
                                            <td class="text-center">
                                                <div class="form-check form-switch d-flex justify-content-center">
                                                    <input class="form-check-input btn_lang_toggle" 
                                                           type="checkbox" 
                                                           <?= ($group->is_default == 1) ? "checked" : "" ?> 
                                                           data-lang_id="<?= $group->id ?>" 
                                                           data-column="is_default"
                                                           title="<?= __('admin.set_as_default') ?>">
                                                </div>
                                            </td>
                                            <td class="text-center">
                                                <div class="btn-group" role="group">
                                                    <a href="<?= base_url('admincontrol/group_form/'.$group->id) ?>" 
                                                       class="btn btn-sm btn-outline-primary" 
                                                       title="<?= __('admin.edit') ?>">
                                                        <i class="fa fa-edit"></i>
                                                    </a>
                                                    <button class="btn btn-sm btn-outline-danger delete-button" 
                                                            data-id="<?= $group->id ?>"
                                                            data-name="<?= htmlspecialchars($group->group_name) ?>"
                                                            title="<?= __('admin.delete') ?>">
                                                        <i class="fa fa-trash"></i>
                                                    </button>
                                                </div>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                <?php else: ?>
                                    <tr>
                                        <td colspan="7" class="text-center py-5">
                                            <div class="text-muted">
                                                <i class="fa fa-users fs-1 mb-3"></i>
                                                <h5><?= __('admin.no_groups_found') ?></h5>
                                                <p><?= __('admin.create_first_group') ?></p>
                                                <a href="<?= base_url('admincontrol/group_form/') ?>" class="btn btn-primary">
                                                    <i class="fa fa-plus-circle me-2"></i><?= __("admin.add_new_group") ?>
                                                </a>
                                            </div>
                                        </td>
                                    </tr>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <!-- No Results Message -->
            <div class="text-center py-5 d-none" id="noResults">
                <i class="fa fa-search text-muted" style="font-size: 3rem;"></i>
                <h4 class="text-muted mt-3"><?= __('admin.no_results_found') ?></h4>
                <p class="text-muted"><?= __('admin.try_different_search') ?></p>
            </div>

        </div>
    </div>
</div>

<!-- Delete Confirmation Modal -->
<div class="modal fade" id="deleteGroupModal" tabindex="-1" aria-labelledby="deleteGroupModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-danger text-white">
                <h5 class="modal-title" id="deleteGroupModalLabel">
                    <i class="fa fa-exclamation-triangle me-2"></i><?= __('admin.delete_group') ?>
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="alert alert-warning border-0 mb-3">
                    <i class="fa fa-warning me-2"></i>
                    <strong><?= __('admin.warning') ?>:</strong> <?= __('admin.delete_group_warning') ?>
                </div>
                <p class="mb-3">
                    <strong><?= __('admin.group_name') ?>:</strong> <span id="deleteGroupName" class="text-primary fw-bold"></span>
                </p>
                <p class="text-muted mb-0">
                    <i class="fa fa-info-circle me-1"></i>
                    <?= __('admin.delete_group_confirm_message') ?>
                </p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">
                    <i class="fa fa-times me-1"></i><?= __('admin.cancel') ?>
                </button>
                <button type="button" class="btn btn-danger" id="confirmDeleteGroup">
                    <i class="fa fa-trash me-1"></i><?= __('admin.delete') ?>
                </button>
            </div>
        </div>
    </div>
</div>

<script type="text/javascript">
document.addEventListener('DOMContentLoaded', function() {
    const searchInput = document.getElementById('searchGroups');
    const statusFilter = document.getElementById('filterByStatus');
    const groupRows = document.querySelectorAll('.group-row');
    const noResults = document.getElementById('noResults');

    function filterGroups() {
        const searchTerm = searchInput.value.toLowerCase();
        const selectedStatus = statusFilter.value;
        let visibleCount = 0;

        groupRows.forEach(row => {
            const groupName = row.dataset.groupName;
            const isDefault = row.dataset.isDefault;
            const matchesSearch = !searchTerm || groupName.includes(searchTerm);
            let matchesStatus = true;

            if (selectedStatus === 'default') {
                matchesStatus = isDefault === '1';
            } else if (selectedStatus === 'custom') {
                matchesStatus = isDefault === '0';
            }

            if (matchesSearch && matchesStatus) {
                row.style.display = '';
                visibleCount++;
            } else {
                row.style.display = 'none';
            }
        });

        if (visibleCount === 0 && groupRows.length > 0) {
            noResults.classList.remove('d-none');
        } else {
            noResults.classList.add('d-none');
        }
    }

    searchInput.addEventListener('input', filterGroups);
    statusFilter.addEventListener('change', filterGroups);

    window.clearFilters = function() {
        searchInput.value = '';
        statusFilter.value = '';
        filterGroups();
    };

    document.querySelectorAll('.toggle-description').forEach(button => {
        button.addEventListener('click', function() {
            const cell = this.closest('.description-cell');
            const shortText = cell.querySelector('.description-short');
            const fullText = cell.querySelector('.description-full');
            
            if (shortText.classList.contains('d-none')) {
                shortText.classList.remove('d-none');
                fullText.classList.add('d-none');
                this.innerHTML = '<small><?= __('admin.show_more') ?></small>';
            } else {
                shortText.classList.add('d-none');
                fullText.classList.remove('d-none');
                this.innerHTML = '<small><?= __('admin.show_less') ?></small>';
            }
        });
    });
});

$(document).on('change', ".btn_lang_toggle", function(){
    let $this = $(this);
    let id = $this.data('lang_id');
    let column = $this.data('column');
    let checked = $this.prop('checked');
    let status = checked ? 1 : 0;

    $this.prop('disabled', true);

    $.ajax({
        url: "<?= base_url('admincontrol/group_status_toggle')?>",
        type: "POST",
        dataType: "json",
        data: {
            id: id,
            status: status,
            column: column
        },
        success: function (response) {
            showToast('<?= __('admin.success') ?>', '<?= __('admin.group_status_updated') ?>', 'success', 3000);
            $this.prop('disabled', false);
        },
        error: function() {
            $this.prop('checked', !checked);
            $this.prop('disabled', false);
            showToast('<?= __('admin.error') ?>', '<?= __('admin.something_wrong_try_again') ?>', 'error', 4000);
        }
    });
});

let currentDeleteButton = null;
let currentDeleteRow = null;

$(document).on('click', ".delete-button", function(){
    currentDeleteButton = $(this);
    currentDeleteRow = currentDeleteButton.closest('tr');
    let groupId = currentDeleteButton.data('id');
    let groupName = currentDeleteButton.data('name');
    
    $('#deleteGroupName').text(groupName);
    $('#deleteGroupModal').modal('show');
});

$('#confirmDeleteGroup').on('click', function(){
    if(!currentDeleteButton) return;
    
    let groupId = currentDeleteButton.data('id');
    let $this = currentDeleteButton;
    let $row = currentDeleteRow;

    $this.prop("disabled", true);
    $this.html('<i class="fa fa-spinner fa-spin"></i>');
    $('#deleteGroupModal').modal('hide');

    $.ajax({
        url: '<?= base_url("admincontrol/delete_user_group") ?>',
        type: 'POST',
        dataType: 'json',
        data: {id: groupId},
        success: function(json) {
            if(json.status == 1) {
                $row.fadeOut(500, function() {
                    $(this).remove();
                    updateRowNumbers();
                });
                showToast('<?= __('admin.success') ?>', json.message, 'success', 3000);
            } else {
                showToast('<?= __('admin.warning') ?>', json.message, 'error', 4000);
                $this.prop("disabled", false);
                $this.html('<i class="fa fa-trash"></i>');
            }
        },
        error: function() {
            showToast('<?= __('admin.error') ?>', '<?= __('admin.something_wrong_try_again') ?>', 'error', 4000);
            $this.prop("disabled", false);
            $this.html('<i class="fa fa-trash"></i>');
        }
    });
    
    currentDeleteButton = null;
    currentDeleteRow = null;
});

function updateRowNumbers() {
    $('#user-groups tr:visible').each(function(index) {
        $(this).find('.badge:first').text(index + 1);
    });
}
</script>