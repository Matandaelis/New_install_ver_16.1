<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card shadow-sm border-0">
                <div class="card-header bg-light border-0">
                    <div class="d-flex align-items-center justify-content-between">
                        <div>
                            <h5 class="card-title mb-0"><?= __('admin.edit_countries_and_states') ?></h5>
                            <small class="text-muted"><?= __('admin.manage_locations') ?></small>
                        </div>
                        <div class="d-flex gap-2">
                            <span class="badge bg-primary" id="countries-count">0 <?= __('admin.countries') ?></span>
                            <span class="badge bg-info" id="states-count">0 <?= __('admin.states') ?></span>
                        </div>
                    </div>
        </div>
    <div class="card-body">
                                                            <ul class="nav nav-pills nav-fill mb-4" id="TabsNav" role="tablist">
                        <li class="nav-item" role="presentation">
                            <button class="nav-link active fw-bold py-3 px-4" id="countries-tab" data-bs-toggle="pill" data-bs-target="#countries" type="button" role="tab" aria-controls="countries" aria-selected="true">
                                <i class="bi bi-globe fs-5 me-2"></i><?= __('admin.countries') ?>
                                <span class="badge bg-light text-primary ms-2" id="countries-tab-count">0</span>
                            </button>
                    </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link fw-bold py-3 px-4" id="states-tab" data-bs-toggle="pill" data-bs-target="#states" type="button" role="tab" aria-controls="states" aria-selected="false">
                                <i class="bi bi-map fs-5 me-2"></i><?= __('admin.states') ?>
                                <span class="badge bg-light text-dark ms-2" id="states-tab-count">0</span>
                            </button>
                    </li>
                </ul>
                    <div class="tab-content" id="TabsNavContent">
                        <div class="tab-pane fade show active" id="countries" role="tabpanel" aria-labelledby="countries-tab">
                            <div class="d-flex justify-content-between align-items-center mb-3">
                                <h6 class="mb-0"><?= __('admin.countries') ?> <?= __('admin.management') ?></h6>
                                <button type="button" class="btn btn-primary createCountryFormModal">
                                    <i class="bi bi-plus-circle me-2"></i><?= __("admin.create_new_country"); ?>
                                </button>
                            </div>
                            
                                <?php if(empty($countries)) { ?>
                                <div class="alert alert-info d-flex align-items-center" role="alert">
                                    <i class="bi bi-info-circle me-2"></i>
                                    <?php echo __("admin.no_new_countries"); ?>
                                </div>
                                <?php } else { ?>
                                <div class="table-responsive">
                                    <table class="table table-hover align-middle" id="countries-table">
                                        <thead class="table-dark">
                                        <tr>
                                            <th><?= __('admin.th_iso_code'); ?></th>
                                            <th><?= __('admin.th_name'); ?></th>
                                            <th><?= __('admin.th_phone_code'); ?></th>
                                            <th><?= __('admin.th_latitude'); ?></th>
                                            <th><?= __('admin.th_longitude'); ?></th>
                                                <th class="text-center"><?= __('admin.action'); ?></th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach($countries as $country) { ?>
                                            <tr data-country-id="<?= $country['id']; ?>">
                                                <td class="sortname">
                                                    <span class="badge bg-secondary"><?= $country['sortname']; ?></span>
                                                </td>
                                                <td class="name fw-bold"><?= $country['name']; ?></td>
                                                <td class="phonecode">+<?= $country['phonecode']; ?></td>
                                                <td class="lat text-muted"><?= $country['lat']; ?></td>
                                                <td class="lng text-muted"><?= $country['lng']; ?></td>
                                                <td class="text-center">
                                                <?php if((int)$country['created_by'] >= 0) { ?>
                                                    <div class="btn-group" role="group">
                                                        <button data-id="<?= $country['id']; ?>" class="btn btn-sm btn-outline-primary updateCountryFormModal" title="<?= __('admin.edit'); ?>">
                                                            <i class="bi bi-pencil"></i>
                                                        </button>
                                                        <button data-id="<?= $country['id']; ?>" class="btn btn-sm btn-outline-danger deleteCountry" title="<?= __('admin.delete'); ?>">
                                                            <i class="bi bi-trash"></i>
                                                        </button>
                                                    </div>
                                            <?php } ?>
                                        </td>
                                    </tr>
                                    <?php } ?>
                                </tbody>
                            </table>
                                </div>
                            <?php } ?>
                        </div>
                        <div class="tab-pane fade" id="states" role="tabpanel" aria-labelledby="states-tab">
                            <div class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center mb-3 gap-2">
                                <h6 class="mb-0"><?= __('admin.states') ?> <?= __('admin.management') ?></h6>
                                <div class="d-flex flex-column flex-md-row gap-2">
                                    <select class="form-select filter-state-by-country" style="min-width: 200px;">
                                <option value=""><?= __("admin.show_all"); ?></option>
                                <?php foreach($countries as $country) { ?>
                                <option value="<?= $country['id']; ?>"><?= $country['name']; ?></option>
                                <?php } ?>
                            </select>
                                    <button type="button" class="btn btn-primary createStateFormModal">
                                        <i class="bi bi-plus-circle me-2"></i><?= __("admin.create_new_state"); ?>
                                    </button>
                                </div>
                            </div>
                            
                                                                                    <?php if(empty($states)) { ?>
                                <div class="alert alert-info d-flex align-items-center" role="alert">
                                    <i class="bi bi-info-circle me-2"></i>
                                    <?php echo __("admin.no_new_states"); ?>
                                </div>
                            <?php } else { ?>
                                <div class="table-responsive">
                                    <table class="table table-hover align-middle table-states" id="states-table">
                                        <thead class="table-dark">
                                            <tr>
                                                <th><?= __('admin.th_name'); ?></th>
                                                <th><?= __('admin.th_country_name'); ?></th>
                                                <th class="text-center"><?= __('admin.action'); ?></th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php foreach($states as $state) { ?>
                                            <tr data-country_id="<?= $state['country_id']; ?>" data-state-id="<?= $state['id']; ?>">
                                                <td class="name fw-bold"><?= $state['name']; ?></td>
                                                <td class="country_id" data-country_id="<?= $state['country_id']; ?>">
                                                    <span class="badge bg-info"><?= $state['country_name']; ?></span>
                                                </td>
                                                <td class="text-center">
                                                    <?php if((int)$state['created_by'] >= 0) { ?>
                                                    <div class="btn-group" role="group">
                                                        <button data-id="<?= $state['id']; ?>" class="btn btn-sm btn-outline-primary updateStateFormModal" title="<?= __('admin.edit'); ?>">
                                                            <i class="bi bi-pencil"></i>
                                                        </button>
                                                        <button data-id="<?= $state['id']; ?>" class="btn btn-sm btn-outline-danger deleteState" title="<?= __('admin.delete'); ?>">
                                                            <i class="bi bi-trash"></i>
                                                        </button>
                                                    </div>
                                                    <?php } ?>
                                                </td>
                                            </tr>
                                            <?php } ?>
                                        </tbody>
                                    </table>
                                </div>
                            <?php } ?>
                </div>
            </div>
        </div>
    </div>
</div>


<!-- Country Form Modal -->
<div class="modal fade" id="countryFormModal" tabindex="-1" aria-labelledby="countryFormModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
    <div class="modal-content">
        <form id="countryForm">
                <div class="modal-header bg-light">
                    <h5 class="modal-title" id="countryFormModalLabel">
                        <i class="bi bi-globe me-2"></i><?= __("admin.create_new_country"); ?>
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label fw-bold"><?= __('admin.th_iso_code'); ?> <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" name="sortname" placeholder="<?= __('admin.th_iso_code'); ?>" required maxlength="3" />
                            <div class="form-text"><?= __('admin.iso_code_help'); ?></div>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-bold"><?= __('admin.th_name'); ?> <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" name="name" placeholder="<?= __('admin.th_name'); ?>" required />
                        </div>
                        <div class="col-md-4">
                            <label class="form-label fw-bold"><?= __('admin.th_phone_code'); ?> <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <span class="input-group-text">+</span>
                                <input type="number" class="form-control" name="phonecode" placeholder="1" required />
                            </div>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label fw-bold"><?= __('admin.th_latitude'); ?> <span class="text-danger">*</span></label>
                            <input type="number" step="any" class="form-control" name="lat" placeholder="0.000000" required />
                </div>
                        <div class="col-md-4">
                            <label class="form-label fw-bold"><?= __('admin.th_longitude'); ?> <span class="text-danger">*</span></label>
                            <input type="number" step="any" class="form-control" name="lng" placeholder="0.000000" required />
                </div>
                </div>
                </div>
                <div class="modal-footer bg-light">
                <input type="hidden" name="id" value=""/>
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                        <i class="bi bi-x-circle me-2"></i><?= __('admin.close'); ?>
                    </button>
                    <button type="button" class="btn btn-primary countryFormSubmit">
                        <i class="bi bi-check-circle me-2"></i><?= __('admin.save_changes'); ?>
                    </button>
            </div>
        </form>
    </div>
</div>
</div>
<!-- State Form Modal -->
<div class="modal fade" id="stateFormModal" tabindex="-1" aria-labelledby="stateFormModalLabel" aria-hidden="true">
    <div class="modal-dialog">
    <div class="modal-content">
        <form id="stateForm">
                <div class="modal-header bg-light">
                    <h5 class="modal-title" id="stateFormModalLabel">
                        <i class="bi bi-map me-2"></i><?= __("admin.create_new_state"); ?>
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                    <div class="row g-3">
                        <div class="col-12">
                            <label class="form-label fw-bold"><?= __('admin.country'); ?> <span class="text-danger">*</span></label>
                            <select class="form-select" name="country_id" required>
                                <option value="" disabled selected><?= __("admin.select_country"); ?></option>
                        <?php foreach($countries as $country) { ?>
                        <option value="<?= $country['id']; ?>"><?= $country['name']; ?></option>
                        <?php } ?>
                    </select>
                </div>
                        <div class="col-12">
                            <label class="form-label fw-bold"><?= __('admin.name'); ?> <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" name="name" placeholder="<?= __('admin.name'); ?>" required />
                        </div>
                    </div>
                </div>
                <div class="modal-footer bg-light">
                <input type="hidden" name="id" value=""/>
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                        <i class="bi bi-x-circle me-2"></i><?= __('admin.close'); ?>
                    </button>
                    <button type="button" class="btn btn-primary stateFormSubmit">
                        <i class="bi bi-check-circle me-2"></i><?= __('admin.save_changes'); ?>
                    </button>
            </div>
        </form>
    </div>
</div>
</div>
<script type="text/javascript">
$(document).ready(function() {
    updateCounts();
    
    function updateCounts() {
        const countriesCount = $('#countries-table tbody tr').length || 0;
        const statesCount = $('#states-table tbody tr').length || 0;
        $('#countries-count').text(countriesCount + ' <?= __('admin.countries') ?>');
        $('#states-count').text(statesCount + ' <?= __('admin.states') ?>');
        $('#countries-tab-count').text(countriesCount + ' <?= __('admin.items') ?>');
        $('#states-tab-count').text(statesCount + ' <?= __('admin.items') ?>');
    }

    $('.filter-state-by-country').on('change', function() {
        const selectedCountry = $(this).val();
        if(selectedCountry === "") {
            $('.table-states tbody tr').show();
        } else {
            $('.table-states tbody tr').hide();
            $('.table-states tbody tr[data-country_id="'+selectedCountry+'"]').show();
        }
    });

    $(document).on('click', '.deleteCountry', function(e){
        e.preventDefault();
        const countryId = $(this).data('id');
        const row = $(this).closest('tr');
        
        if (confirm('<?= __('admin.are_you_sure'); ?>\n<?= __('admin.delete_country_warning'); ?>')) {
            $.ajax({
                url: '<?= base_url('admincontrol/deleteCountryAjax') ?>',
                type: 'POST',
                dataType: 'json',
                data: { id: countryId },
                beforeSend: function() {
                    $(this).prop('disabled', true).html('<i class="bi bi-spinner-border spinner-border-sm"></i>');
                },
                success: function(response) {
                    if (response.status) {
                        row.fadeOut(400, function() {
                            row.remove();
                            updateCounts();
                            if (typeof showToast === 'function') {
                                showToast('<?= __('admin.success') ?>', '<?= __('admin.country_deleted_successfully') ?>', 'success', 3000);
                            }
                        });
                    } else {
                        if (typeof showToast === 'function') {
                            showToast('<?= __('admin.error') ?>', response.message || '<?= __('admin.failed_to_delete_country') ?>', 'error', 3000);
                        }
                    }
                },
                error: function() {
                    if (typeof showToast === 'function') {
                        showToast('<?= __('admin.error') ?>', '<?= __('admin.failed_to_delete_country') ?>', 'error', 3000);
                    }
                }
            });
        }
    });

            $(document).on('click', '.deleteState', function(e){
        e.preventDefault();
        const stateId = $(this).data('id');
        const row = $(this).closest('tr');
        
        if (confirm('<?= __('admin.are_you_sure'); ?>\n<?= __('admin.delete_state_warning'); ?>')) {
            $.ajax({
                url: '<?= base_url('admincontrol/deleteStateAjax') ?>',
                type: 'POST',
                dataType: 'json',
                data: { id: stateId },
                beforeSend: function() {
                    $(this).prop('disabled', true).html('<i class="bi bi-spinner-border spinner-border-sm"></i>');
                },
                success: function(response) {
                    if (response.status) {
                        row.fadeOut(400, function() {
                            row.remove();
                            updateCounts();
                            if (typeof showToast === 'function') {
                                showToast('<?= __('admin.success') ?>', '<?= __('admin.state_deleted_successfully') ?>', 'success', 3000);
                            }
                        });
                    } else {
                        if (typeof showToast === 'function') {
                            showToast('<?= __('admin.error') ?>', response.message || '<?= __('admin.failed_to_delete_state') ?>', 'error', 3000);
                        }
                    }
                },
                error: function() {
                    if (typeof showToast === 'function') {
                        showToast('<?= __('admin.error') ?>', '<?= __('admin.failed_to_delete_state') ?>', 'error', 3000);
                    }
                }
            });
        }
    });

        $('.createCountryFormModal').on('click', function(e){
        $('#countryFormModalLabel').html('<i class="bi bi-globe me-2"></i><?= __("admin.create_new_country"); ?>');
        $('#countryForm')[0].reset();
        clearErrors('countryFormModal');
            $('#countryFormModal').modal('show');
        });

        $('.updateCountryFormModal').on('click', function(e){
        $('#countryFormModalLabel').html('<i class="bi bi-pencil me-2"></i><?= __("admin.update_country"); ?>');
        
        const row = $(this).closest('tr');
        const sortname = row.find('td.sortname .badge').text();
        const name = row.find('td.name').text();
        const phonecode = row.find('td.phonecode').text().replace('+', '');
        const lat = row.find('td.lat').text();
        const lng = row.find('td.lng').text();
        
        $('#countryFormModal input[name="sortname"]').val(sortname);
        $('#countryFormModal input[name="name"]').val(name);
        $('#countryFormModal input[name="phonecode"]').val(phonecode);
        $('#countryFormModal input[name="lat"]').val(lat);
        $('#countryFormModal input[name="lng"]').val(lng);
            $('#countryFormModal input[name="id"]').val($(this).data('id'));
        
        clearErrors('countryFormModal');
            $('#countryFormModal').modal('show');
        });

        $('.createStateFormModal').on('click', function(e){
        $('#stateFormModalLabel').html('<i class="bi bi-map me-2"></i><?= __("admin.create_new_state"); ?>');
        $('#stateForm')[0].reset();
        clearErrors('stateFormModal');
            $('#stateFormModal').modal('show');
        });

        $('.updateStateFormModal').on('click', function(e){
        $('#stateFormModalLabel').html('<i class="bi bi-pencil me-2"></i><?= __("admin.update_state"); ?>');

        const row = $(this).closest('tr');
        const name = row.find('td.name').text();
        const countryId = row.find('td.country_id').data('country_id');

        $('#stateFormModal input[name="name"]').val(name);
        $('#stateFormModal select[name="country_id"]').val(countryId);
            $('#stateFormModal input[name="id"]').val($(this).data('id'));
        
        clearErrors('stateFormModal');
            $('#stateFormModal').modal('show');
        });

        $('.countryFormSubmit').on('click', function(e){
        e.preventDefault();
        const submitBtn = $(this);
        const originalText = submitBtn.html();
        
        clearErrors('countryFormModal');
        submitBtn.prop('disabled', true).html('<i class="bi bi-spinner-border spinner-border-sm me-2"></i><?= __('admin.saving') ?>...');
        
            $.ajax({
                type: "POST",
                url: "<?= base_url('admincontrol/createUpdateCountry') ?>",
                data: $('#countryForm').serialize(),
                dataType: 'JSON',
                success: function(response) {
                    if(response.errors) {
                      showErrors('countryFormModal', response.errors);
                    } else if(response.reload){
                    $('#countryFormModal').modal('hide');
                    if (typeof showToast === 'function') {
                        const isUpdate = $('#countryFormModal input[name="id"]').val();
                        const message = isUpdate ? '<?= __('admin.country_updated_successfully') ?>' : '<?= __('admin.country_created_successfully') ?>';
                        showToast('<?= __('admin.success') ?>', message, 'success', 3000);
                    }
                    setTimeout(() => window.location.reload(), 1000);
                }
            },
            error: function() {
                if (typeof showToast === 'function') {
                    showToast('<?= __('admin.error') ?>', '<?= __('admin.something_wrong_try_again') ?>', 'error', 3000);
                }
            },
            complete: function() {
                submitBtn.prop('disabled', false).html(originalText);
                }
            });
        });

        $('.stateFormSubmit').on('click', function(e){
        e.preventDefault();
        const submitBtn = $(this);
        const originalText = submitBtn.html();
        
        clearErrors('stateFormModal');
        submitBtn.prop('disabled', true).html('<i class="bi bi-spinner-border spinner-border-sm me-2"></i><?= __('admin.saving') ?>...');
        
            $.ajax({
                type: "POST",
                url: "<?= base_url('admincontrol/createUpdateState') ?>",
                data: $('#stateForm').serialize(),
                dataType: 'JSON',
                success: function(response) {
                    if(response.errors) {
                      showErrors('stateFormModal', response.errors);
                    } else if(response.reload){
                    $('#stateFormModal').modal('hide');
                    if (typeof showToast === 'function') {
                        const isUpdate = $('#stateFormModal input[name="id"]').val();
                        const message = isUpdate ? '<?= __('admin.state_updated_successfully') ?>' : '<?= __('admin.state_created_successfully') ?>';
                        showToast('<?= __('admin.success') ?>', message, 'success', 3000);
                    }
                    setTimeout(() => window.location.reload(), 1000);
                }
            },
            error: function() {
                if (typeof showToast === 'function') {
                    showToast('<?= __('admin.error') ?>', '<?= __('admin.something_wrong_try_again') ?>', 'error', 3000);
                }
            },
            complete: function() {
                submitBtn.prop('disabled', false).html(originalText);
            }
        });
    });

    function clearErrors(modalId) {
        $('#' + modalId + ' .text-danger').remove();
        $('#' + modalId + ' .is-invalid').removeClass('is-invalid');
        $('#' + modalId + ' input, #' + modalId + ' select').removeClass('border-danger');
    }

    function showErrors(modalId, errors) {
            for(const key in errors) {
            const field = $('#' + modalId + ' [name="' + key + '"]');
            if(field.length) {
                field.addClass('is-invalid border-danger');
                field.after('<div class="invalid-feedback text-danger d-block">' + errors[key] + '</div>');
                }
            }
        }
    });
</script>