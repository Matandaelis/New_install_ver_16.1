<div class="container-fluid px-4 pb-4">
<?php $this->load->view('admincontrol/admin_user/_admin_mgmt_nav'); ?>
<div class="row">
	<div class="col-12">
            
            <!-- Header Section -->
            <div class="card border-0 shadow-sm mb-4 overflow-hidden" style="background: linear-gradient(135deg, var(--bs-primary) 0%, #0d6efd 50%, #0a58ca 100%);">
                <div class="card-body py-4">
                    <div class="d-flex flex-wrap justify-content-between align-items-center gap-3">
                        <div>
                            <h4 class="fw-bold text-white mb-1">
                                <i class="bi bi-shield-check me-2"></i>
                                <?= !empty($user->id) ? __('admin.edit_admin_user') : __('admin.create_admin_user') ?>
                            </h4>
                            <p class="text-white opacity-90 mb-0 small">
                                <?= !empty($user->id) ? __('admin.update_admin_details') : __('admin.create_new_admin_desc') ?>
                            </p>
                        </div>
                        <div class="d-flex flex-wrap gap-2 align-items-center">
                            <?php if (empty($user->id)): ?>
                            <button type="button" class="btn btn-warning btn-sm" id="btnFillTestData" title="<?= __('admin.fill_test_data_help') ?>">
                                <i class="bi bi-lightning-charge me-1"></i><?= __('admin.fill_test_data') ?>
                            </button>
                            <?php endif; ?>
                            <a class="btn btn-light btn-sm" href="<?= base_url('admincontrol/admin_user/') ?>">
                                <i class="bi bi-arrow-left me-1"></i><?= __('admin.back_to_admins') ?>
                            </a>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Form Section -->
            <div class="row justify-content-center">
                <div class="col-lg-9">
                    <div class="card shadow-sm border-0">
                        <div class="card-header bg-light border-0 py-3">
                            <h6 class="fw-bold mb-0">
                                <i class="bi bi-person-gear me-2"></i><?= __('admin.admin_information') ?>
                            </h6>
			</div>
                        <div class="card-body p-4">
                            <form id="admin-form" novalidate>
					<input type="hidden" name="user_id" value="<?= (int)$user->id ?>">

                                <!-- Personal Information -->
                                <div class="mb-4">
                                    <h6 class="fw-bold text-primary mb-3">
                                        <i class="bi bi-person me-2"></i><?= __('admin.personal_information') ?>
                                    </h6>
                                    
                                    <div class="row g-3 mb-3">
                                        <div class="col-md-6">
                                            <label class="form-label fw-bold">
                                                <?= __('admin.first_name') ?> <span class="text-danger">*</span>
                                            </label>
                                            <input type="text" name="firstname" class="form-control form-control-lg" 
                                                   placeholder="<?= __('admin.enter_your_first_name') ?>"
                                                   value="<?= htmlspecialchars($user->firstname ?? '') ?>" required>
                                        </div>
                                        <div class="col-md-6">
                                            <label class="form-label fw-bold">
                                                <?= __('admin.last_name') ?> <span class="text-danger">*</span>
                                            </label>
                                            <input type="text" name="lastname" class="form-control form-control-lg" 
                                                   placeholder="<?= __('admin.enter_your_last_name') ?>"
                                                   value="<?= htmlspecialchars($user->lastname ?? '') ?>" required>
							</div>
						</div>

                                    <div class="mb-3">
                                        <label class="form-label fw-bold">
                                            <i class="bi bi-telephone me-1"></i><?= __('admin.phone_number') ?>
                                        </label>
                                        <input type="tel" name="PhoneNumber" class="form-control" 
                                               placeholder="<?= __('admin.enter_your_mobile_number') ?>"
                                               value="<?= htmlspecialchars($user->PhoneNumber ?? '') ?>">
						</div>
					</div>

                                <!-- Account Information -->
                                <div class="mb-4">
                                    <h6 class="fw-bold text-primary mb-3">
                                        <i class="bi bi-key me-2"></i><?= __('admin.account_information') ?>
                                    </h6>
                                    
                                    <div class="mb-3">
                                        <label class="form-label fw-bold">
                                            <i class="bi bi-envelope me-1"></i><?= __('admin.email_address') ?> <span class="text-danger">*</span>
                                        </label>
                                        <input type="email" name="email" class="form-control form-control-lg" 
                                               placeholder="<?= __('admin.enter_your_email_address') ?>"
                                               value="<?= htmlspecialchars($user->email ?? '') ?>" required>
					</div>

                                    <div class="mb-3">
                                        <label class="form-label fw-bold">
                                            <i class="bi bi-person-badge me-1"></i><?= __('admin.username') ?> <span class="text-danger">*</span>
                                        </label>
                                        <input type="text" name="username" class="form-control form-control-lg" 
                                               placeholder="<?= __('admin.enter_username_address') ?>"
                                               value="<?= htmlspecialchars($user->username ?? '') ?>" required>
					</div>

                                    <div class="row g-3">
                                        <div class="col-md-6">
                                            <label class="form-label fw-bold">
                                                <i class="bi bi-lock me-1"></i><?= __('admin.password') ?>
                                                <?= empty($user->id) ? '<span class="text-danger">*</span>' : '' ?>
                                            </label>
                                            <div class="input-group">
                                                <input type="password" name="password" id="inputPassword" class="form-control" 
                                                       placeholder="<?= __('admin.enter_password') ?>"
                                                       <?= empty($user->id) ? 'required' : '' ?>>
                                                <button type="button" class="btn btn-outline-secondary" id="togglePassword" title="<?= __('admin.show_password') ?>">
                                                    <i class="bi bi-eye"></i>
                                                </button>
                                            </div>
                                            <?php if (!empty($user->id)): ?>
                                                <div class="form-text"><?= __('admin.leave_blank_keep_current') ?></div>
                                            <?php endif; ?>
                                        </div>
                                        <div class="col-md-6">
                                            <label class="form-label fw-bold">
                                                <i class="bi bi-lock-fill me-1"></i><?= __('admin.confirm_password') ?>
                                                <?= empty($user->id) ? '<span class="text-danger">*</span>' : '' ?>
                                            </label>
                                            <div class="input-group">
                                                <input type="password" name="cpassword" id="inputCpassword" class="form-control" 
                                                       placeholder="<?= __('admin.confirm_password') ?>"
                                                       <?= empty($user->id) ? 'required' : '' ?>>
                                                <button type="button" class="btn btn-outline-secondary" id="toggleCpassword" title="<?= __('admin.show_password') ?>">
                                                    <i class="bi bi-eye"></i>
                                                </button>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="mb-3">
                                        <label class="form-label fw-bold">
                                            <i class="bi bi-person-badge me-1"></i><?= __('admin.admin_role') ?>
                                        </label>
                                        <select name="admin_role_id" id="admin_role_id" class="form-select form-select-lg">
                                            <?php
                                            $role_select = '';
                                            if (!empty($user->admin_permissions)) {
                                                $role_select = 'custom';
                                            } elseif (isset($user->admin_role_id) && $user->admin_role_id) {
                                                $role_select = (int)$user->admin_role_id;
                                            }
                                            ?>
                                            <?php $full_access_disabled = !empty($is_demo_mode) || empty($can_assign_full_access); ?>
                                            <option value="" <?= $role_select === '' ? 'selected' : '' ?> <?= $full_access_disabled ? 'disabled' : '' ?>><?= __('admin.full_access_no_restrictions') ?><?= $full_access_disabled ? ' (' . __('admin.restricted_super_admin_only') . ')' : '' ?></option>
                                            <option value="custom" <?= $role_select === 'custom' ? 'selected' : '' ?>><?= __('admin.custom_permissions') ?></option>
                                            <?php if (!empty($admin_roles)): foreach ($admin_roles as $role): ?>
                                            <option value="<?= (int)$role->id ?>" <?= $role_select === (int)$role->id ? 'selected' : '' ?>>
                                                <?= htmlspecialchars($role->name) ?>
                                            </option>
                                            <?php endforeach; endif; ?>
                                        </select>
                                        <div class="form-text"><?= __('admin.admin_role_help') ?></div>
                                    </div>
                                    <div id="permissionsChecklist" class="mb-3 perm-selector-modern">
                                        <div class="perm-action-bar d-flex flex-wrap justify-content-between align-items-center gap-3 mb-4 p-3 rounded-3 bg-light border border-1">
                                            <div>
                                                <h5 class="fw-bold text-dark mb-0">
                                                    <i class="bi bi-shield-fill-check text-primary me-2"></i><?= __('admin.select_permissions') ?>
                                                </h5>
                                                <span class="text-muted small d-block"><?= __('admin.permissions_from_config') ?></span>
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
                                        $perm_groups = $perm_groups ?? $this->config->item('admin_permission_groups') ?? [];
                                        $perm_slugs = $this->config->item('admin_permission_slugs') ?: [];
                                        $user_perms = [];
                                        $is_full_access = empty($user->admin_role_id) && empty($user->admin_permissions);
                                        if (!empty($user->admin_permissions)) {
                                            $user_perms = is_string($user->admin_permissions) ? json_decode($user->admin_permissions, true) : (array)$user->admin_permissions;
                                        } elseif (isset($user->admin_role_id) && $user->admin_role_id && !empty($admin_roles)) {
                                            foreach ($admin_roles as $r) {
                                                if ($r->id == $user->admin_role_id) {
                                                    $user_perms = is_string($r->permissions) ? json_decode($r->permissions, true) : (array)($r->permissions ?? []);
                                                    break;
                                                }
                                            }
                                        } elseif ($is_full_access) {
                                            $user_perms = array_keys($perm_slugs);
                                        }
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
                                            $perm_group_keys = $perm_group_keys ?? []; $group_label = (isset($perm_group_keys[$group_name]) ? __('admin.' . $perm_group_keys[$group_name]) : $group_name);
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
                                                                        <?= in_array($slug, $user_perms, true) ? 'checked' : '' ?>>
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
                                        <p class="form-text mt-3 mb-0" id="permissionsHint"><?= __('admin.permissions_checklist_hint') ?></p>
                                    </div>
					</div>

                                <!-- Location Information -->
                                <div class="mb-4">
                                    <h6 class="fw-bold text-primary mb-3">
                                        <i class="bi bi-geo-alt me-2"></i><?= __('admin.location_information') ?>
                                    </h6>
                                    
                                    <div class="row g-3">
                                        <div class="col-md-6">
                                            <label class="form-label fw-bold">
                                                <i class="bi bi-flag me-1"></i><?= __('admin.country') ?>
                                            </label>
                                            <select name="Country" class="form-select">
                                                <option value=""><?= __('admin.select_country') ?></option>
							<?php foreach($country as $countries): ?>
                                                    <option value="<?= $countries->id ?>" 
                                                        <?= (!empty($user->Country) && $user->Country == $countries->id) ? 'selected' : '' ?>>
                                                        <?= htmlspecialchars($countries->name) ?>
                                                    </option>
							<?php endforeach; ?> 
						</select>
                                        </div>
                                        <div class="col-md-6">
                                            <label class="form-label fw-bold">
                                                <i class="bi bi-building me-1"></i><?= __('admin.city') ?>
                                            </label>
                                            <input type="text" name="City" class="form-control" 
                                                   placeholder="<?= __('admin.enter_your_city') ?>"
                                                   value="<?= htmlspecialchars($user->City ?? '') ?>">
                                        </div>
					</div>
					
                                    <div class="mt-3">
                                        <label class="form-label fw-bold">
                                            <i class="bi bi-mailbox me-1"></i><?= __('admin.postal_code') ?>
                                        </label>
                                        <input type="text" name="Zip" class="form-control" 
                                               placeholder="<?= __('admin.enter_your_pincode') ?>"
                                               value="<?= htmlspecialchars($user->Zip ?? '') ?>">
                                    </div>
					</div>

                                <!-- Profile Image -->
                                <div class="mb-4">
                                    <h6 class="fw-bold text-primary mb-3">
                                        <i class="bi bi-image me-2"></i><?= __('admin.profile_image') ?>
                                    </h6>
                                    
                                    <div class="row">
                                        <div class="col-md-6">
                                            <label class="form-label fw-bold">
                                                <?= __('admin.choose_image') ?>
                                            </label>
                                            <input type="file" name="avatar" class="form-control" 
                                                   id="uploadBtn" accept="image/*">
                                            <div class="form-text">
                                                <?= __('admin.image_upload_help') ?>
							</div>		
						</div>
                                        <div class="col-md-6">
                                            <label class="form-label fw-bold">
                                                <?= __('admin.current_image') ?>
                                            </label>
                                            <div class="text-center">
                                                <?php $avatar = $user->avatar != '' ? 'users/' . $user->avatar : 'no-user_image.jpg'; ?>
                                                <img src="<?= base_url('assets/images/' . $avatar) ?>" 
                                                     id="blah" 
                                                     class="img-thumbnail border-3 border-primary" 
                                                     width="150" height="150"
                                                     style="object-fit: cover;">
                                            </div>
							</div>
						</div>
					</div>

                                <!-- Submit Section -->
                                <div class="d-flex justify-content-between align-items-center pt-3 border-top">
                                    <div class="loading-submit d-none">
                                        <div class="d-flex align-items-center text-primary">
                                            <div class="spinner-border spinner-border-sm me-2" role="status">
                                                <span class="visually-hidden"><?= __('admin.loading') ?></span>
                                            </div>
                                            <span class="loading-text"><?= __('admin.saving') ?></span>
						</div>
					</div>

                                    <div class="ms-auto">
                                        <a href="<?= base_url('admincontrol/admin_user/') ?>" class="btn btn-outline-secondary me-2">
                                            <i class="bi bi-x-circle me-2"></i><?= __('admin.cancel') ?>
                                        </a>
                                        <button type="button" class="btn btn-outline-primary btn-lg btn-submit-stay me-2" data-stay="1">
                                            <i class="bi bi-check2-square me-2"></i><?= __('admin.save_and_stay') ?>
                                        </button>
                                        <button type="button" class="btn btn-primary btn-lg btn-submit">
                                            <i class="bi bi-check-circle me-2"></i>
                                            <?= !empty($user->id) ? __('admin.update_admin') : __('admin.create_admin') ?>
                                        </button>
                                    </div>
					</div>
				</form>
			</div>
                    </div>
                </div>
            </div>

		</div> 
	</div> 
</div>


<script type="text/javascript">
document.addEventListener('DOMContentLoaded', function() {
    const uploadBtn = document.getElementById('uploadBtn');
    const previewImg = document.getElementById('blah');
    const submitBtn = document.querySelector('.btn-submit');
    const submitStayBtn = document.querySelector('.btn-submit-stay');
    const loadingSubmit = document.querySelector('.loading-submit');
    const form = document.getElementById('admin-form');

    // Role & permissions sync
    var rolePermissionsData = <?= isset($role_permissions_json) ? $role_permissions_json : '{}' ?>;
    var roleSelect = document.getElementById('admin_role_id');
    var permCheckboxes = document.querySelectorAll('.perm-checkbox');
    var permSection = document.getElementById('permissionsChecklist');

    function updatePermCount() {
        var total = permCheckboxes.length;
        var checked = 0;
        permCheckboxes.forEach(function(cb) { if (cb.checked) checked++; });
        var badge = document.getElementById('permCountBadge');
        if (badge) badge.textContent = checked + ' / ' + total;
    }
    function updatePermissionsFromRole() {
        var val = roleSelect ? roleSelect.value : '';
        var groupBtns = document.querySelectorAll('.perm-group-select-all, .perm-group-deselect-all');
        if (val === '') {
            permSection.classList.add('opacity-50');
            permCheckboxes.forEach(function(cb) {
                cb.checked = true;
                cb.disabled = true;
            });
            groupBtns.forEach(function(b) { b.disabled = true; });
        } else {
            permSection.classList.remove('opacity-50');
            permCheckboxes.forEach(function(cb) { cb.disabled = false; });
            groupBtns.forEach(function(b) { b.disabled = false; });
            if (val === 'custom') {
                updatePermCount();
                return;
            }
            var key = parseInt(val, 10) || val;
            var slugs = rolePermissionsData[key];
            if (slugs && slugs.length) {
                permCheckboxes.forEach(function(cb) {
                    cb.checked = slugs.indexOf(cb.value) >= 0;
                });
            }
        }
        updatePermCount();
    }
    if (roleSelect) {
        roleSelect.addEventListener('change', updatePermissionsFromRole);
        updatePermissionsFromRole();
    }
    updatePermCount();
    permCheckboxes.forEach(function(cb) { cb.addEventListener('change', updatePermCount); });

    // Select all / Deselect all permissions
    var btnSelectAll = document.getElementById('btnSelectAllPerms');
    var btnDeselectAll = document.getElementById('btnDeselectAllPerms');
    if (btnSelectAll) {
        btnSelectAll.addEventListener('click', function() {
            permCheckboxes.forEach(function(cb) {
                if (!cb.disabled) cb.checked = true;
            });
            updatePermCount();
        });
    }
    if (btnDeselectAll) {
        btnDeselectAll.addEventListener('click', function() {
            permCheckboxes.forEach(function(cb) {
                if (!cb.disabled) cb.checked = false;
            });
            updatePermCount();
        });
    }

    document.querySelectorAll('.perm-group-select-all').forEach(function(btn) {
        btn.addEventListener('click', function() {
            var card = this.closest('.perm-group-card');
            if (card) {
                card.querySelectorAll('.perm-checkbox').forEach(function(cb) {
                    if (!cb.disabled) cb.checked = true;
                });
                updatePermCount();
            }
        });
    });
    document.querySelectorAll('.perm-group-deselect-all').forEach(function(btn) {
        btn.addEventListener('click', function() {
            var card = this.closest('.perm-group-card');
            if (card) {
                card.querySelectorAll('.perm-checkbox').forEach(function(cb) {
                    if (!cb.disabled) cb.checked = false;
                });
                updatePermCount();
            }
        });
    });

    // Fill test data (create mode only)
    var btnFillTest = document.getElementById('btnFillTestData');
    if (btnFillTest) {
        btnFillTest.addEventListener('click', function() {
            var countrySelect = form.querySelector('select[name="Country"]');
            var firstCountry = countrySelect ? Array.from(countrySelect.options).find(function(o) { return o.value && o.value !== ''; }) : null;
            form.querySelector('[name="firstname"]').value = 'Test';
            form.querySelector('[name="lastname"]').value = 'Subadmin';
            form.querySelector('[name="email"]').value = 'test.subadmin' + Date.now() + '@example.com';
            form.querySelector('[name="username"]').value = 'testadmin' + Math.floor(Math.random() * 999);
            form.querySelector('[name="PhoneNumber"]').value = '+1234567890';
            var testPwd = 'Test123!';
            form.querySelector('[name="password"]').value = testPwd;
            form.querySelector('[name="cpassword"]').value = testPwd;
            form.querySelector('[name="City"]').value = 'New York';
            form.querySelector('[name="Zip"]').value = '10001';
            if (firstCountry && countrySelect) countrySelect.value = firstCountry.value;
            roleSelect.value = 'custom';
            updatePermissionsFromRole();
            permCheckboxes.forEach(function(cb) { if (!cb.disabled) cb.checked = true; });
            showToast('<?= __('admin.success') ?>', '<?= __('admin.test_data_filled') ?> <?= __('admin.password_is') ?>: Test123!', 'success', 5000);
        });
    }

    // Password show/hide toggle
    var togglePwd = document.getElementById('togglePassword');
    var toggleCpwd = document.getElementById('toggleCpassword');
    var inputPwd = document.getElementById('inputPassword');
    var inputCpwd = document.getElementById('inputCpassword');
    function attachPwdToggle(btn, input) {
        if (!btn || !input) return;
        btn.addEventListener('click', function() {
            var isPwd = input.type === 'password';
            input.type = isPwd ? 'text' : 'password';
            btn.querySelector('i').className = isPwd ? 'bi bi-eye-slash' : 'bi bi-eye';
            btn.title = isPwd ? '<?= __('admin.hide_password') ?>' : '<?= __('admin.show_password') ?>';
        });
    }
    attachPwdToggle(togglePwd, inputPwd);
    attachPwdToggle(toggleCpwd, inputCpwd);

    // Image preview functionality
    uploadBtn.addEventListener('change', function(e) {
        if (this.files && this.files[0]) {
            const file = this.files[0];
            
            // Validate file size (5MB limit)
            if (file.size > 5 * 1024 * 1024) {
                showToast('<?= __('admin.error') ?>', '<?= __('admin.file_too_large') ?>', 'error', 4000);
                this.value = '';
                return;
            }

            // Validate file type
            if (!file.type.startsWith('image/')) {
                showToast('<?= __('admin.error') ?>', '<?= __('admin.invalid_file_type') ?>', 'error', 4000);
                this.value = '';
                return;
            }

            const reader = new FileReader();
        reader.onload = function(e) {
                previewImg.src = e.target.result;
                previewImg.style.transform = 'scale(1.05)';
                setTimeout(() => {
                    previewImg.style.transform = 'scale(1)';
                }, 200);
            };
            reader.readAsDataURL(file);
        }
    });

    function doSubmit(stayOnForm) {
        clearErrors();
        if (!validateForm()) return;

        const formData = new FormData(form);
        if (stayOnForm) formData.append('stay_on_form', '1');

        setLoadingState(true);
        fetch('<?= base_url('admincontrol/admin_user_form') ?>', {
            method: 'POST',
            body: formData
        })
        .then(response => response.json())
        .then(result => {
            setLoadingState(false);
            if (result.location) {
                showToast('<?= __('admin.success') ?>', '<?= __('admin.admin_saved_successfully') ?>', 'success', 2000);
                setTimeout(function() { window.location = result.location; }, 1000);
            } else if (result.success) {
                showToast('<?= __('admin.success') ?>', '<?= __('admin.admin_saved_successfully') ?>', 'success', 2500);
            } else if (result.errors) {
                displayErrors(result.errors);
            } else if (result.error) {
                showToast('<?= __('admin.error') ?>', result.error, 'error', 4000);
            }
        })
        .catch(function(error) {
            setLoadingState(false);
            showToast('<?= __('admin.error') ?>', '<?= __('admin.something_wrong_try_again') ?>', 'error', 4000);
        });
    }

    submitBtn.addEventListener('click', function(e) {
        e.preventDefault();
        doSubmit(false);
    });

    if (submitStayBtn) {
        submitStayBtn.addEventListener('click', function(e) {
            e.preventDefault();
            doSubmit(true);
        });
    }

    function validateForm() {
        let isValid = true;
        
        // Required fields validation
        const requiredFields = ['firstname', 'lastname', 'email', 'username'];
        const userId = form.querySelector('[name="user_id"]').value;
        
        // Add password fields as required for new users
        if (!userId || userId === '0') {
            requiredFields.push('password', 'cpassword');
        }
        
        requiredFields.forEach(fieldName => {
            const field = form.querySelector(`[name="${fieldName}"]`);
            if (field && !field.value.trim()) {
                showFieldError(field, '<?= __('admin.field_required') ?>'.replace('%s', fieldName));
                isValid = false;
            }
        });

        // Email validation
        const emailField = form.querySelector('[name="email"]');
        if (emailField && emailField.value) {
            const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
            if (!emailRegex.test(emailField.value)) {
                showFieldError(emailField, '<?= __('admin.invalid_email') ?>');
                isValid = false;
            }
        }

        // Password confirmation validation
        const passwordField = form.querySelector('[name="password"]');
        const confirmField = form.querySelector('[name="cpassword"]');
        
        if (passwordField && confirmField && passwordField.value) {
            if (passwordField.value !== confirmField.value) {
                showFieldError(confirmField, '<?= __('admin.passwords_not_match') ?>');
                isValid = false;
            }
            
            if (passwordField.value.length < 6) {
                showFieldError(passwordField, '<?= __('admin.password_min_length') ?>');
                isValid = false;
            }
        }

        return isValid;
    }

    function setLoadingState(loading) {
        if (loading) {
            submitBtn.disabled = true;
            if (submitStayBtn) submitStayBtn.disabled = true;
            submitBtn.innerHTML = '<i class="bi bi-hourglass-split me-2"></i><?= __('admin.saving') ?>...';
            loadingSubmit.classList.remove('d-none');
            loadingSubmit.querySelector('.loading-text').textContent = '<?= __('admin.saving') ?>';
        } else {
            submitBtn.disabled = false;
            if (submitStayBtn) submitStayBtn.disabled = false;
            submitBtn.innerHTML = '<i class="bi bi-check-circle me-2"></i><?= !empty($user->id) ? __('admin.update_admin') : __('admin.create_admin') ?>';
            loadingSubmit.classList.add('d-none');
        }
    }

    function clearErrors() {
        form.querySelectorAll('.is-invalid').forEach(el => {
            el.classList.remove('is-invalid');
        });
        form.querySelectorAll('.invalid-feedback').forEach(el => {
            el.remove();
        });
    }

    function showFieldError(field, message) {
        field.classList.add('is-invalid');
        
        const feedback = document.createElement('div');
        feedback.className = 'invalid-feedback';
        feedback.textContent = message;
        
        field.parentNode.appendChild(feedback);
    }

    function displayErrors(errors) {
        Object.entries(errors).forEach(([fieldName, message]) => {
            const field = form.querySelector(`[name="${fieldName}"]`);
            if (field) {
                // Clean HTML from message
                const tempDiv = document.createElement('div');
                tempDiv.innerHTML = message;
                const plainText = tempDiv.textContent || tempDiv.innerText;
                showFieldError(field, plainText);
            }
        });

        // Focus first error field
        const firstError = form.querySelector('.is-invalid');
        if (firstError) {
            firstError.focus();
            firstError.scrollIntoView({ behavior: 'smooth', block: 'center' });
        }
    }
});
</script>
