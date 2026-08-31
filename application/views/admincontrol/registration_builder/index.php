<div class="container-fluid registration-builder-page">
    <div class="row">
        <div class="col-12">

            <!-- Header Card -->
            <div class="card shadow-sm border-0 mb-4">
                <div class="card-header bg-primary text-white py-3">
                    <div class="d-flex justify-content-between align-items-center flex-wrap gap-2">
                        <div class="d-flex align-items-center">
                            <i class="bi bi-layout-text-window me-2 fs-4"></i>
                            <div>
                                <h4 class="mb-0 fw-bold"><?= __("admin.registration_builder") ?></h4>
                                <small class="opacity-75"><?= __("admin.customize_registration_form") ?></small>
                            </div>
                        </div>
                        <button class="btn btn-outline-light btn-sm save-form">
                            <i class="bi bi-save me-1"></i><?= __('admin.save') ?>
                        </button>
                    </div>
                </div>
            </div>

            <!-- Builder Card -->
            <div class="card shadow-sm border-0">
                <div class="card-body p-4">
                    <!-- Instructions -->
                    <div class="alert alert-info mb-4">
                        <div class="d-flex">
                            <i class="bi bi-info-circle fs-4 me-3"></i>
                            <div>
                                <h6 class="fw-bold mb-2"><?= __('admin.how_to_use') ?>:</h6>
                                <ul class="mb-0">
                                    <li><?= __('admin.drag_fields_instruction') ?></li>
                                    <li><?= __('admin.required_fields_note') ?></li>
                                    <li><?= __('admin.save_changes_reminder') ?></li>
                                </ul>
                            </div>
                        </div>
                    </div>

                    <!-- Form Builder Container -->
                    <div id="build-wrap" class="border rounded p-3 bg-light"></div>
                    
                    <div id="form-data" style='display:none'><?= htmlspecialchars($builder['registration_builder']) ?></div>

                    <!-- Save Button Bottom -->
                    <div class="mt-4 text-end">
                        <button class="btn btn-primary btn-lg save-form">
                            <i class="bi bi-save me-2"></i><?= __('admin.save_changes') ?>
                        </button>
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>

<script type="text/javascript" src="<?= base_url('assets/plugins/ui/jquery-ui.min.js') ?>"></script>
<script type="text/javascript" src="<?= base_url('assets/plugins/registration_builder/js/form-builder.js') ?>"></script>

<script type="text/javascript">
$(document).ready(function() {
    let controls = ['autocomplete', 'button', 'checkbox-group', 'date', 'file', 'header', 'hidden', 'number', 'paragraph', 'radio-group', 'select', 'starRating', 'text', 'textarea'];
    let typeUserAttrs = {
        text: {
            mobile_validation: {
                label: '<?= __('admin.mobile_validation') ?>',
                value: false,
                type: 'checkbox',
            }
        }
    };
    
    for (var i = controls.length - 1; i >= 0; i--) {
        let xyz = {
            hide_on_registration: {
                label: '<?= __('admin.hide_on_registration') ?>',
                value: false,
                type: 'checkbox',
            }
        };

        if(typeof typeUserAttrs[controls[i]] != 'undefined') {
            typeUserAttrs[controls[i]].hide_on_registration = {
                label: '<?= __('admin.hide_on_registration') ?>',
                value: false,
                type: 'checkbox',
            }
        } else {
            typeUserAttrs[controls[i]] = xyz
        }
    }

    const fbTemplate = document.getElementById('build-wrap');
    var fields = [
        {
            label: '<?= __('admin.static_field') ?>',
            type: 'header',
            subtype: 'header',
            icon: '',
        },
        {
            label: '<?= __('admin.mobile_number') ?>',
            type: 'text',
            icon:"<i style=\"font-size:24px\" class=\"fa\">&#xf095;</i>",
        }
    ];

    var formBuilder = $(fbTemplate).formBuilder({
        fields:fields,
        typeUserAttrs: typeUserAttrs,
        disabledFieldButtons: {
            header: ['remove','edit','copy']
        },
        disableFields:['hidden'],
        disabledActionButtons:['clear','save','save'],
        disabledAttrs:['access','description','inline','other','rows','step','style','subtype','toggle'],
        formData:$("#form-data").html(),
        dataType: 'json'
    }).promise.then(formBuilder => {
        let formData = JSON.parse($("#form-data").html());
        for (var i = formData.length - 1; i >= 0; i--) {
            let elementsParent = $('input[value="'+formData[i].name+'"]').closest('.form-elements');
            if(elementsParent.length > 0) {
                $(elementsParent).find('input[type="checkbox"]').each(function(index) {
                    if(formData[i][$(this).prop('name')] == "true"){
                        $(this).prop('checked', true);
                    }
                });
            }
        }

        $(".save-form").on('click',function(){
            $this = $(this);
            
            $.ajax({
                url:'',
                type:'POST',
                dataType:'json',
                data:{
                    registration_builder:formBuilder.actions.getData(),
                },
                beforeSend:function(){ 
                    $this.prop('disabled', true).html('<span class="spinner-border spinner-border-sm me-2"></span><?= __('admin.saving') ?>...'); 
                },
                complete:function(){ 
                    $this.prop('disabled', false).html('<i class="bi bi-save me-1"></i><?= __('admin.save') ?>'); 
                },
                success:function(json){
                    if(typeof showToast === 'function') {
                        showToast('<?= __('admin.success') ?>', '<?= __('admin.registration_form_saved') ?>', 'success', 3000);
                    }
                },
                error:function(){
                    if(typeof showToast === 'function') {
                        showToast('<?= __('admin.error') ?>', '<?= __('admin.save_failed') ?>', 'error', 3000);
                    }
                }
            });
        });
    });
});
</script>