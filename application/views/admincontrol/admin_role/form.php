<div class="container-fluid px-4 pb-4">
<?php $this->load->view('admincontrol/admin_user/_admin_mgmt_nav'); ?>
    <div class="row">
        <div class="col-12">

            <!-- Sticky top bar: page title + name/slug fields -->
            <div class="role-form-topbar mb-4">
                <form id="role-form" novalidate>
                <input type="hidden" name="role_id" value="<?= (int)($role->id ?? 0) ?>">
                <div class="d-flex flex-wrap align-items-center gap-3">
                    <div class="role-form-title-area">
                        <h4 class="fw-bold mb-0 text-dark">
                            <?= !empty($role->id) ? __('admin.edit_role') : __('admin.create_role') ?>
                        </h4>
                        <span class="text-muted small"><?= __('admin.role_form_desc') ?></span>
                    </div>
                    <div class="d-flex flex-wrap gap-3 flex-grow-1">
                        <div class="role-topbar-field flex-grow-1">
                            <label class="form-label fw-semibold small mb-1">
                                <?= __('admin.role_name') ?> <span class="text-danger">*</span>
                            </label>
                            <input type="text" name="name" class="form-control" 
                                   placeholder="<?= __('admin.enter_role_name') ?>"
                                   value="<?= htmlspecialchars($role->name ?? '') ?>" required>
                            <div class="invalid-feedback"></div>
                        </div>
                        <div class="role-topbar-field" style="min-width:200px">
                            <label class="form-label fw-semibold small mb-1">
                                <?= __('admin.role_slug') ?>
                            </label>
                            <input type="text" name="slug" class="form-control"
                                   placeholder="<?= __('admin.role_slug_placeholder') ?>"
                                   value="<?= htmlspecialchars($role->slug ?? '') ?>">
                            <div class="invalid-feedback"></div>
                        </div>
                    </div>
                    <div class="d-flex gap-2 align-items-end pb-1">
                        <a href="<?= base_url('admincontrol/admin_roles') ?>" class="btn btn-outline-secondary">
                            <i class="bi bi-arrow-left me-1"></i><?= __('admin.back') ?>
                        </a>
                        <div class="loading-submit d-none">
                            <div class="d-flex align-items-center text-primary">
                                <div class="spinner-border spinner-border-sm me-2" role="status"></div>
                            </div>
                        </div>
                        <button type="button" class="btn btn-outline-primary btn-submit-stay" data-stay="1">
                            <i class="bi bi-check2-square me-1"></i><?= __('admin.save_and_stay') ?>
                        </button>
                        <button type="button" class="btn btn-primary btn-submit">
                            <i class="bi bi-check-circle me-1"></i>
                            <?= !empty($role->id) ? __('admin.update_role') : __('admin.create_role') ?>
                        </button>
                    </div>
                </div>
            </div><!-- /.role-form-topbar, form continues below -->

            <div class="row">
                <div class="col-12">
                    <div class="card border-0" style="background:transparent">
                        <div class="card-body p-0">
                            <div>

                                <div class="mb-4 perm-selector-modern">
                                    <div class="perm-action-bar d-flex flex-wrap justify-content-between align-items-center gap-3 mb-4 p-3 rounded-3 bg-light border border-1">
                                        <div>
                                            <h5 class="fw-bold text-dark mb-0">
                                                <i class="bi bi-shield-fill-check text-primary me-2"></i><?= __('admin.select_permissions') ?>
                                            </h5>
                                            <span class="text-muted small"><?= __('admin.permissions_from_config') ?></span>
                                        </div>
                                        <div class="d-flex flex-wrap align-items-center gap-3">
                                            <span class="badge bg-primary fs-6 rounded-pill px-4 py-2 shadow-sm" id="permCountBadge" aria-live="polite">0 / 0</span>
                                            <div class="btn-group shadow" role="group">
                                                <button type="button" class="btn btn-success px-4 py-2 fw-semibold" id="btnSelectAllPerms">
                                                    <i class="bi bi-check-all me-2"></i><?= __('admin.select_all_permissions') ?>
                                                </button>
                                                <button type="button" class="btn btn-outline-secondary px-4 py-2 fw-semibold" id="btnDeselectAllPerms">
                                                    <i class="bi bi-x-lg me-2"></i><?= __('admin.deselect_all_permissions') ?>
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                    <?php
                                    $perm_groups = $perm_groups ?? [];
                                    $role_perms = $role_perms ?? [];
                                    $group_meta = [
                                        'Dashboard & Overview'    => ['icon' => 'bi-speedometer2',             'color' => 'perm-color-blue'],
                                        'Users & Team'            => ['icon' => 'bi-people-fill',              'color' => 'perm-color-purple'],
                                        'Reports & Financial'     => ['icon' => 'bi-bar-chart-line-fill',      'color' => 'perm-color-green'],
                                        'Marketing & Sales'       => ['icon' => 'bi-megaphone-fill',           'color' => 'perm-color-amber'],
                                        'Settings & Configuration'=> ['icon' => 'bi-gear-wide-connected',      'color' => 'perm-color-indigo'],
                                        'System & Tools'          => ['icon' => 'bi-terminal-fill',            'color' => 'perm-color-red'],
                                    ];
                                    ?>
                                    <div class="row row-cols-1 row-cols-md-2 row-cols-xl-3 g-4">
                                    <?php foreach ($perm_groups as $group_name => $perms):
                                        if (empty($perms)) { continue; }
                                        $perm_group_keys = $perm_group_keys ?? [];
                                        $group_label = (isset($perm_group_keys[$group_name]) ? __('admin.' . $perm_group_keys[$group_name]) : $group_name);
                                        $meta = $group_meta[$group_name] ?? ['icon' => 'bi-grid-3x3-gap', 'color' => 'perm-color-blue'];
                                    ?>
                                        <div class="col">
                                            <div class="card perm-group-card <?= $meta['color'] ?> h-100 rounded-4 border-0">
                                                <div class="perm-card-header d-flex align-items-center justify-content-between gap-2 flex-wrap px-4 py-3">
                                                    <div class="d-flex align-items-center gap-3">
                                                        <span class="perm-icon-wrap">
                                                            <i class="bi <?= $meta['icon'] ?>"></i>
                                                        </span>
                                                        <span class="perm-group-label fw-bold"><?= htmlspecialchars($group_label) ?></span>
                                                    </div>
                                                    <div class="d-flex gap-1">
                                                        <button type="button" class="btn perm-pill-btn perm-group-select-all">
                                                            <i class="bi bi-check-all me-1"></i><?= __('admin.select_all_in_group') ?>
                                                        </button>
                                                        <button type="button" class="btn perm-pill-btn perm-group-deselect-all">
                                                            <i class="bi bi-x-lg me-1"></i><?= __('admin.deselect_all_in_group') ?>
                                                        </button>
                                                    </div>
                                                </div>
                                                <div class="card-body pt-2 pb-3 px-4">
                                                    <div class="list-group list-group-flush">
                                                    <?php
                                                    $perm_label_keys = $perm_label_keys ?? [];
                                                    foreach ($perms as $slug => $label):
                                                        $display_label = isset($perm_label_keys[$slug]) ? __('admin.' . $perm_label_keys[$slug]) : $label;
                                                    ?>
                                                        <div class="perm-row list-group-item border-0 px-0 py-2 d-flex align-items-center">
                                                            <div class="form-check form-switch mb-0 flex-grow-1">
                                                                <input class="form-check-input perm-checkbox" type="checkbox" name="admin_perm[]" value="<?= htmlspecialchars($slug) ?>" id="perm_<?= htmlspecialchars($slug) ?>"
                                                                    <?= in_array($slug, $role_perms, true) ? 'checked' : '' ?>>
                                                                <label class="form-check-label" for="perm_<?= htmlspecialchars($slug) ?>"><?= htmlspecialchars($display_label) ?></label>
                                                            </div>
                                                        </div>
                                                    <?php endforeach; ?>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    <?php endforeach; ?>
                                    </div>
                                    <p class="form-text mt-3 mb-2"><i class="bi bi-info-circle me-1"></i><?= __('admin.role_permissions_hint') ?></p>
                                </div>
                            </div><!-- /.empty wrapper -->
                        </div>
                    </div>
                </div>
            </div>
            </form><!-- /#role-form ends here -->

        </div>
    </div>
</div>

<script type="text/javascript">
document.addEventListener('DOMContentLoaded', function() {
    var form = document.getElementById('role-form');
    var submitBtn = document.querySelector('.btn-submit');
    var submitStayBtn = document.querySelector('.btn-submit-stay');
    var loadingSubmit = document.querySelector('.loading-submit');
    var permCheckboxes = document.querySelectorAll('.perm-checkbox');

    function setLoadingState(loading) {
        if (loading) {
            submitBtn.disabled = true;
            if (submitStayBtn) submitStayBtn.disabled = true;
            submitBtn.innerHTML = '<i class="bi bi-hourglass-split me-2"></i><?= __('admin.saving') ?>...';
            loadingSubmit.classList.remove('d-none');
        } else {
            submitBtn.disabled = false;
            if (submitStayBtn) submitStayBtn.disabled = false;
            submitBtn.innerHTML = '<i class="bi bi-check-circle me-2"></i><?= !empty($role->id) ? __('admin.update_role') : __('admin.create_role') ?>';
            loadingSubmit.classList.add('d-none');
        }
    }

    function doSubmit(stayOnForm) {
        var nameEl = form.querySelector('[name="name"]');
        if (!nameEl.value.trim()) {
            nameEl.classList.add('is-invalid');
            return;
        }
        nameEl.classList.remove('is-invalid');

        var formData = new FormData(form);
        if (stayOnForm) formData.append('stay_on_form', '1');

        setLoadingState(true);
        fetch('<?= base_url('admincontrol/admin_role_form/' . (isset($role) && !empty($role->id) ? (int)$role->id : 0)) ?>', {
            method: 'POST',
            body: formData
        })
        .then(function(r) { return r.json(); })
        .then(function(result) {
            setLoadingState(false);
            if (result.location) {
                if (typeof showToast === 'function') {
                    showToast('<?= __('admin.success') ?>', '<?= __('admin.admin_saved_successfully') ?>', 'success', 2000);
                }
                setTimeout(function() { window.location = result.location; }, 800);
            } else if (result.errors) {
                Object.keys(result.errors).forEach(function(k) {
                    var el = form.querySelector('[name="' + k + '"]');
                    if (el) {
                        el.classList.add('is-invalid');
                        var fb = el.nextElementSibling;
                        if (fb && fb.classList.contains('invalid-feedback')) fb.textContent = result.errors[k];
                    }
                });
            } else if (result.success) {
                if (typeof showToast === 'function') showToast('<?= __('admin.success') ?>', '<?= __('admin.admin_saved_successfully') ?>', 'success', 2500);
            }
        })
        .catch(function() {
            setLoadingState(false);
            if (typeof showToast === 'function') showToast('<?= __('admin.error') ?>', '<?= __('admin.something_wrong_try_again') ?>', 'error', 4000);
        });
    }

    submitBtn.addEventListener('click', function(e) { e.preventDefault(); doSubmit(false); });
    if (submitStayBtn) submitStayBtn.addEventListener('click', function(e) { e.preventDefault(); doSubmit(true); });

    function updatePermCount() {
        var total = permCheckboxes.length;
        var checked = 0;
        permCheckboxes.forEach(function(cb) { if (cb.checked) checked++; });
        var badge = document.getElementById('permCountBadge');
        if (badge) badge.textContent = checked + ' / ' + total;
    }
    updatePermCount();
    permCheckboxes.forEach(function(cb) { cb.addEventListener('change', updatePermCount); });
    document.getElementById('btnSelectAllPerms').addEventListener('click', function() {
        permCheckboxes.forEach(function(cb) { cb.checked = true; });
        updatePermCount();
    });
    document.getElementById('btnDeselectAllPerms').addEventListener('click', function() {
        permCheckboxes.forEach(function(cb) { cb.checked = false; });
        updatePermCount();
    });

    document.querySelectorAll('.perm-group-select-all').forEach(function(btn) {
        btn.addEventListener('click', function() {
            var card = this.closest('.perm-group-card');
            if (card) {
                card.querySelectorAll('.perm-checkbox').forEach(function(cb) { cb.checked = true; });
                updatePermCount();
            }
        });
    });
    document.querySelectorAll('.perm-group-deselect-all').forEach(function(btn) {
        btn.addEventListener('click', function() {
            var card = this.closest('.perm-group-card');
            if (card) {
                card.querySelectorAll('.perm-checkbox').forEach(function(cb) { cb.checked = false; });
                updatePermCount();
            }
        });
    });

    var nameInput = form.querySelector('[name="name"]');
    var slugInput = form.querySelector('[name="slug"]');
    if (nameInput && slugInput && !slugInput.value) {
        nameInput.addEventListener('blur', function() {
            if (!slugInput.value && nameInput.value) {
                slugInput.value = nameInput.value.toLowerCase().replace(/[^a-z0-9]+/g, '_').replace(/^_|_$/g, '');
            }
        });
    }
});
</script>
