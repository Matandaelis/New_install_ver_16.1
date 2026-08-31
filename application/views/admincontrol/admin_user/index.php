<?php
$total_users = count($users ?? []);
$full_access_count = 0; $role_count = 0; $custom_count = 0;
foreach (($users ?? []) as $u) {
    if (!empty($u->admin_role_id))         $role_count++;
    elseif (!empty($u->admin_permissions)) $custom_count++;
    else                                    $full_access_count++;
}
?>
<div class="container-fluid px-4 pb-4 admin-users-page">
    <?php $this->load->view('admincontrol/admin_user/_admin_mgmt_nav'); ?>

    <!-- ── Page Header ─────────────────────────────────────── -->
    <div class="d-flex flex-wrap justify-content-between align-items-center gap-3 mb-4 px-1">
        <div>
            <h4 class="fw-bold mb-0"><?= __('admin.admin_management') ?></h4>
            <p class="text-muted small mb-0"><?= __('admin.manage_admin_users_desc') ?></p>
        </div>
        <a href="<?= base_url('admincontrol/admin_user_form/') ?>" class="btn btn-primary btn-sm">
            <i class="bi bi-plus-circle me-2"></i><?= __('admin.add_new_admin') ?>
        </a>
    </div>

    <!-- ── Stats Row ───────────────────────────────────────── -->
    <div class="row g-3 mb-4">
        <div class="col-6 col-md-3">
            <div class="roles-stat-card">
                <div class="roles-stat-icon bg-primary bg-opacity-10 text-primary"><i class="bi bi-people-fill"></i></div>
                <div><div class="roles-stat-value"><?= $total_users ?></div><div class="roles-stat-label"><?= __('admin.admin_users') ?></div></div>
            </div>
        </div>
        <div class="col-6 col-md-3">
            <div class="roles-stat-card">
                <div class="roles-stat-icon bg-success bg-opacity-10 text-success"><i class="bi bi-shield-fill-check"></i></div>
                <div><div class="roles-stat-value"><?= $full_access_count ?></div><div class="roles-stat-label"><?= __('admin.role_full_access') ?></div></div>
            </div>
        </div>
        <div class="col-6 col-md-3">
            <div class="roles-stat-card">
                <div class="roles-stat-icon bg-warning bg-opacity-10 text-warning"><i class="bi bi-person-badge-fill"></i></div>
                <div><div class="roles-stat-value"><?= $role_count ?></div><div class="roles-stat-label"><?= __('admin.admin_role') ?></div></div>
            </div>
        </div>
        <div class="col-6 col-md-3">
            <div class="roles-stat-card">
                <div class="roles-stat-icon bg-info bg-opacity-10 text-info"><i class="bi bi-sliders"></i></div>
                <div><div class="roles-stat-value"><?= $custom_count ?></div><div class="roles-stat-label"><?= __('admin.role_custom') ?></div></div>
            </div>
        </div>
    </div>

    <!-- ── Inline Filters ─────────────────────────────────── -->
    <div class="admin-filter-bar d-flex flex-wrap align-items-end gap-3 mb-4 p-3 rounded-3 bg-white border">
        <div class="flex-grow-1" style="min-width:200px">
            <label class="form-label fw-semibold small mb-1"><i class="bi bi-search me-1 text-muted"></i><?= __('admin.search') ?></label>
            <input type="text" class="form-control" id="searchAdmins" placeholder="<?= __('admin.search_by_name_email_username') ?>">
        </div>
        <div style="min-width:160px">
            <label class="form-label fw-semibold small mb-1"><i class="bi bi-person-badge me-1 text-muted"></i><?= __('admin.filter_by_role') ?></label>
            <select class="form-select" id="filterByRole">
                <option value=""><?= __('admin.all_roles') ?></option>
                <option value="super"><?= __('admin.super_administrator') ?></option>
                <option value="full"><?= __('admin.role_full_access') ?></option>
                <option value="custom"><?= __('admin.role_custom') ?></option>
                <?php if (!empty($admin_roles)): foreach ($admin_roles as $r): ?>
                <option value="<?= htmlspecialchars($r->slug) ?>"><?= htmlspecialchars($r->name) ?></option>
                <?php endforeach; endif; ?>
            </select>
        </div>
        <div style="min-width:140px">
            <label class="form-label fw-semibold small mb-1"><i class="bi bi-geo-alt me-1 text-muted"></i><?= __('admin.country') ?></label>
            <select class="form-select" id="filterByCountry">
                <option value=""><?= __('admin.all_countries') ?></option>
                <?php
                $countries = [];
                foreach(($users ?? []) as $user) {
                    if (!empty($user->sortname) && !in_array($user->sortname, $countries)) $countries[] = $user->sortname;
                }
                foreach($countries as $c): ?>
                    <option value="<?= strtolower($c) ?>"><?= $c ?></option>
                <?php endforeach; ?>
            </select>
        </div>
        <div style="min-width:130px">
            <label class="form-label fw-semibold small mb-1"><i class="bi bi-sort-alpha-down me-1 text-muted"></i><?= __('admin.sort_by') ?></label>
            <select class="form-select" id="sortBy">
                <option value="name"><?= __('admin.name') ?></option>
                <option value="email"><?= __('admin.email') ?></option>
                <option value="username"><?= __('admin.username') ?></option>
                <option value="role"><?= __('admin.admin_role') ?></option>
            </select>
        </div>
        <div>
            <label class="form-label d-block invisible small mb-1">.</label>
            <button type="button" class="btn btn-outline-secondary" onclick="clearFilters()">
                <i class="bi bi-x-circle me-1"></i><?= __('admin.clear') ?>
            </button>
        </div>
    </div>

    <!-- ── Admin Table ─────────────────────────────────────── -->
    <div class="card border-0 shadow-sm rounded-4 overflow-hidden">
        <div class="card-header bg-white border-bottom border-1 py-3 px-4 d-flex justify-content-between align-items-center">
            <span class="fw-bold"><i class="bi bi-people me-2 text-primary"></i><?= __('admin.admin_users_list') ?></span>
            <a href="<?= base_url('admincontrol/admin_user_form/') ?>" class="btn btn-primary btn-sm rounded-pill px-3">
                <i class="bi bi-plus-circle me-1"></i><?= __('admin.add_admin') ?>
            </a>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0 admin-users-table">
                    <thead>
                        <tr class="table-light border-bottom">
                            <th class="fw-semibold text-muted small text-uppercase ps-4"><?= __('admin.admin_info') ?></th>
                            <th class="fw-semibold text-muted small text-uppercase text-center"><?= __('admin.admin_role') ?></th>
                            <th class="fw-semibold text-muted small text-uppercase text-center"><?= __('admin.location') ?></th>
                            <th class="fw-semibold text-muted small text-uppercase"><?= __('admin.contact_details') ?></th>
                            <th class="fw-semibold text-muted small text-uppercase text-center"><?= __('admin.actions') ?></th>
                        </tr>
                    </thead>
                    <tbody id="admin-users-table">
                    <?php if (empty($users)): ?>
                        <tr>
                            <td colspan="5" class="text-center py-5">
                                <div class="roles-empty-state border-0 shadow-none py-3">
                                    <div class="roles-empty-icon mx-auto"><i class="bi bi-people"></i></div>
                                    <h5 class="fw-bold mt-3 mb-1"><?= __('admin.no_admin_users_found') ?></h5>
                                    <p class="text-muted mb-4"><?= __('admin.create_first_admin') ?></p>
                                    <a href="<?= base_url('admincontrol/admin_user_form/') ?>" class="btn btn-primary px-4">
                                        <i class="bi bi-plus-circle me-2"></i><?= __('admin.add_new_admin') ?>
                                    </a>
                                </div>
                            </td>
                        </tr>
                    <?php else:
                        $role_palette = ['finance'=>'blue','support'=>'amber','marketing'=>'red','full'=>'green','custom'=>'indigo','super'=>'dark'];
                        foreach ($users as $user):
                            $role_display = $role_filter_val = '';
                            $is_system_admin = (int)($user->id ?? 0) === 1;
                            if ($is_system_admin) {
                                $role_display = __('admin.super_administrator'); $role_filter_val = 'super';
                            } elseif (!empty($user->admin_permissions)) {
                                $role_display = __('admin.role_custom'); $role_filter_val = 'custom';
                            } elseif (!empty($user->admin_role_id)) {
                                $role_display = !empty($user->role_name) ? $user->role_name : 'Role #'.$user->admin_role_id;
                                $role_filter_val = !empty($user->role_slug) ? $user->role_slug : 'role';
                            } else {
                                $role_display = __('admin.role_full_access'); $role_filter_val = 'full';
                            }
                            $avatar = !empty($user->avatar) ? 'users/'.$user->avatar : 'no-user_image.jpg';
                    ?>
                        <tr class="admin-row"
                            data-name="<?= strtolower($user->firstname.' '.$user->lastname) ?>"
                            data-email="<?= strtolower($user->email) ?>"
                            data-username="<?= strtolower($user->username) ?>"
                            data-country="<?= strtolower($user->sortname ?? '') ?>"
                            data-role="<?= htmlspecialchars($role_filter_val) ?>">
                            <td class="ps-4">
                                <div class="d-flex align-items-center gap-3">
                                    <div class="position-relative flex-shrink-0">
                                        <img src="<?= base_url('assets/images/'.$avatar) ?>"
                                             class="rounded-circle border border-2 border-light shadow-sm"
                                             width="44" height="44"
                                             alt="<?= htmlspecialchars($user->firstname) ?>"
                                             data-bs-toggle="popover" data-bs-trigger="hover" data-bs-html="true"
                                             data-bs-content="<img src='<?= base_url('assets/images/'.$avatar) ?>' class='img-fluid rounded' style='max-width:200px'>">
                                        <span class="position-absolute bottom-0 end-0 bg-success border border-white rounded-circle"
                                              style="width:11px;height:11px"></span>
                                    </div>
                                    <div>
                                        <div class="fw-semibold text-dark"><?= htmlspecialchars($user->firstname.' '.$user->lastname) ?></div>
                                        <small class="text-muted">@<?= htmlspecialchars($user->username) ?></small>
                                    </div>
                                </div>
                            </td>
                            <td class="text-center">
                                <?php
                                $rc = 'bg-secondary';
                                if ($role_filter_val === 'super')     $rc = 'bg-dark text-white';
                                elseif ($role_filter_val === 'full')  $rc = 'bg-success';
                                elseif ($role_filter_val === 'custom') $rc = 'bg-info';
                                elseif (!empty($user->role_slug)) {
                                    if ($user->role_slug === 'finance')   $rc = 'bg-primary';
                                    elseif ($user->role_slug === 'support')   $rc = 'bg-warning text-dark';
                                    elseif ($user->role_slug === 'marketing') $rc = 'bg-danger';
                                }
                                ?>
                                <span class="badge <?= $rc ?> rounded-pill px-3 py-2"><?= htmlspecialchars($role_display) ?></span>
                            </td>
                            <td class="text-center">
                                <?php if (!empty($user->Country) && !empty($user->sortname)):
                                    $flag_path = 'assets/template/images/flags/'.strtolower($user->sortname).'.png'; ?>
                                    <div class="d-flex flex-column align-items-center gap-1">
                                        <?php if (file_exists(FCPATH.$flag_path)): ?>
                                            <img src="<?= base_url($flag_path) ?>" class="rounded" width="28" height="21" alt="<?= htmlspecialchars($user->sortname) ?>">
                                        <?php else: ?>
                                            <i class="bi bi-geo-alt text-muted"></i>
                                        <?php endif; ?>
                                        <span class="text-muted small"><?= htmlspecialchars($user->sortname) ?></span>
                                        <?php if (!empty($user->City)): ?>
                                        <small class="text-muted" style="font-size:0.72rem"><?= htmlspecialchars($user->City) ?></small>
                                        <?php endif; ?>
                                    </div>
                                <?php else: ?>
                                    <span class="text-muted small">—</span>
                                <?php endif; ?>
                            </td>
                            <td>
                                <div class="d-flex flex-column gap-1">
                                    <a href="mailto:<?= htmlspecialchars($user->email) ?>" class="text-decoration-none text-dark small">
                                        <i class="bi bi-envelope me-1 text-primary"></i><?= htmlspecialchars($user->email) ?>
                                    </a>
                                    <span class="text-muted small">
                                        <i class="bi bi-telephone me-1 text-success"></i>
                                        <?= !empty($user->PhoneNumber) ? htmlspecialchars($user->PhoneNumber) : '<span class="text-muted">'.__('admin.no_phone').'</span>' ?>
                                    </span>
                                </div>
                            </td>
                            <td class="text-center">
                                <div class="d-flex justify-content-center gap-2">
                                    <?php if ($is_system_admin): ?>
                                    <span class="btn btn-sm btn-outline-secondary rounded-pill px-3 disabled" title="<?= __('admin.super_admin_readonly') ?>">
                                        <i class="bi bi-shield-lock me-1"></i><?= __('admin.view_only') ?>
                                    </span>
                                    <?php else: ?>
                                    <a href="<?= base_url('admincontrol/admin_user_form/'.$user->id) ?>"
                                       class="btn btn-sm btn-outline-primary rounded-pill px-3" title="<?= $is_system_admin ? __('admin.edit_profile') : __('admin.edit_admin') ?>">
                                        <i class="bi bi-pencil me-1"></i><?= __('admin.edit') ?>
                                    </a>
                                    <?php endif; ?>
                                    <?php if (!$is_system_admin): ?>
                                    <?php if (defined('ENVIRONMENT') && ENVIRONMENT === 'demo'): ?>
                                    <span class="btn btn-sm btn-outline-secondary rounded-circle disabled" title="<?= __('admin.demo_mode') ?>">
                                        <i class="bi bi-trash"></i>
                                    </span>
                                    <?php else: ?>
                                    <button class="btn btn-sm btn-outline-danger rounded-circle delete-admin-btn"
                                            data-id="<?= $user->id ?>"
                                            data-name="<?= htmlspecialchars($user->firstname.' '.$user->lastname) ?>"
                                            title="<?= __('admin.delete_admin') ?>">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                    <?php endif; ?>
                                    <?php else: ?>
                                    <span class="btn btn-sm btn-outline-secondary rounded-circle disabled" title="<?= __('admin.system_owner_no_delete') ?>">
                                        <i class="bi bi-shield-lock"></i>
                                    </span>
                                    <?php endif; ?>
                                </div>
                            </td>
                        </tr>
                    <?php endforeach; endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- No Results -->
    <div class="text-center py-5 d-none" id="noResults">
        <i class="bi bi-search text-muted" style="font-size:2.5rem"></i>
        <h5 class="text-muted mt-3"><?= __('admin.no_results_found') ?></h5>
        <p class="text-muted small"><?= __('admin.try_different_search') ?></p>
    </div>

</div>

<script type="text/javascript">
document.addEventListener('DOMContentLoaded', function() {
    const popoverTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="popover"]'));
    popoverTriggerList.map(el => new bootstrap.Popover(el));

    const searchInput  = document.getElementById('searchAdmins');
    const countryFilter= document.getElementById('filterByCountry');
    const roleFilter   = document.getElementById('filterByRole');
    const sortSelect   = document.getElementById('sortBy');
    const adminRows    = document.querySelectorAll('.admin-row');
    const noResults    = document.getElementById('noResults');

    function filterAndSortAdmins() {
        const searchTerm     = searchInput.value.toLowerCase();
        const selectedCountry= countryFilter ? countryFilter.value.toLowerCase() : '';
        const selectedRole   = roleFilter ? roleFilter.value : '';
        const sortBy         = sortSelect ? sortSelect.value : 'name';
        let visibleRows = [];

        adminRows.forEach(row => {
            const matchesSearch  = !searchTerm  || row.dataset.name.includes(searchTerm) || row.dataset.email.includes(searchTerm) || row.dataset.username.includes(searchTerm);
            const matchesCountry = !selectedCountry || row.dataset.country.includes(selectedCountry);
            const matchesRole    = !selectedRole    || row.dataset.role === selectedRole;
            if (matchesSearch && matchesCountry && matchesRole) {
                row.style.display = ''; visibleRows.push(row);
            } else {
                row.style.display = 'none';
            }
        });

        if (visibleRows.length > 0) {
            visibleRows.sort((a, b) => {
                const key = sortBy === 'email' ? 'email' : sortBy === 'username' ? 'username' : sortBy === 'role' ? 'role' : 'name';
                return (a.dataset[key]||'').localeCompare(b.dataset[key]||'');
            });
            const tbody = document.getElementById('admin-users-table');
            visibleRows.forEach(row => tbody.appendChild(row));
        }
        noResults.classList.toggle('d-none', !(visibleRows.length === 0 && adminRows.length > 0));
    }

    if (searchInput)   searchInput.addEventListener('input', filterAndSortAdmins);
    if (countryFilter) countryFilter.addEventListener('change', filterAndSortAdmins);
    if (roleFilter)    roleFilter.addEventListener('change', filterAndSortAdmins);
    if (sortSelect)    sortSelect.addEventListener('change', filterAndSortAdmins);

    window.clearFilters = function() {
        if (searchInput)   searchInput.value = '';
        if (countryFilter) countryFilter.value = '';
        if (roleFilter)    roleFilter.value = '';
        if (sortSelect)    sortSelect.value = 'name';
        filterAndSortAdmins();
    };

    document.addEventListener('click', function(e) {
        const btn = e.target.matches('.delete-admin-btn') ? e.target : e.target.closest('.delete-admin-btn');
        if (!btn) return;
        window.confirmDelete('<?= __('admin.confirm_delete_admin') ?>'.replace('%s', btn.dataset.name), function() {
            btn.disabled = true; btn.innerHTML = '<i class="bi bi-hourglass-split"></i>';
            window.location.href = '<?= base_url('admincontrol/admin_user_delete/') ?>' + btn.dataset.id;
        });
    });

    filterAndSortAdmins();
});
</script>
