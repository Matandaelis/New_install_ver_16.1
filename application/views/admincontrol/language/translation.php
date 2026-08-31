<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <!-- Page Header -->
            <div class="d-flex justify-content-between align-items-center mb-4">
                <div>
                    <h2 class="h4 mb-1">
                        <i class="fas fa-language text-primary me-2"></i>
                        <?= __("admin.translation") ?> - <?= $language['name'] ?>
                    </h2>
                    <p class="text-muted mb-0"><?= __("admin.manage_translations_for") ?> <?= $language['name'] ?></p>
                </div>
                <div>
                    <a href="<?= base_url("admincontrol/language") ?>" class="btn btn-outline-secondary">
                        <i class="fas fa-arrow-left me-2"></i><?= __("admin.backtohome") ?>
                    </a>
                </div>
            </div>

            <div class="card shadow-sm border-0">
                <div class="card-header bg-light border-bottom">
                    <div class="row align-items-center">
                        <div class="col-md-6">
                            <h5 class="card-title mb-0">
                                <i class="fas fa-language text-primary me-2"></i>
                                <?= __("admin.translation_manager") ?>
                            </h5>
                        </div>
                        <div class="col-md-6">
                            <div class="d-flex justify-content-end align-items-center gap-3">
                                <div class="badge bg-warning text-dark">
                                    <i class="fas fa-exclamation-triangle me-1"></i>
                                    <span id="missing-count"><?= __("admin.missing_translation") ?>: <?= $language['count']['missing'] ?> / <?= $language['count']['all'] ?></span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Translation Controls -->
                <div class="card-body border-bottom">
                    <div class="row align-items-center">
                        <div class="col-md-4">
                            <label class="form-label fw-semibold mb-2"><?= __("admin.select_section") ?></label>
                            <select id="translation_file" class="form-select">
                                <option value=""><?= __("admin.select_translation") ?></option>
                                <option value="admin"><?= __("admin.admin_side") ?></option>
                                <option value="user"><?= __("admin.affiliate_side") ?></option>
                                <option value="client"><?= __("admin.client_side") ?></option>
                                <option value="store"><?= __("admin.store_side") ?></option>
                                <option value="template_simple"><?= __("admin.default_template") ?></option>
                                <option value="front"><?= __("admin.landing_template") ?></option>
                            </select>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label fw-semibold mb-2"><?= __("admin.search") ?></label>
                            <div class="input-group">
                                <span class="input-group-text bg-white border-end-0">
                                    <i class="fas fa-search text-muted"></i>
                                </span>
                                <input type="text" id="myInput" onkeyup="searchFunction()" 
                                       placeholder="<?= __('admin.search_translations') ?>" 
                                       class="form-control border-start-0">
                            </div>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label fw-semibold mb-2"><?= __("admin.actions") ?></label>
                            <div class="d-flex gap-2">
                                <button class="btn btn-primary save-translation d-none">
                                    <i class="fas fa-save me-2"></i><?= __("admin.save_changes") ?>
                                </button>
                                <div class="text-muted small mt-2">
                                    <span id="instruction-text"><?= __('admin.select_section_to_begin') ?></span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Translation Table -->
                <div class="card-body p-0">
                    <div class="lang_translation_empty_state text-center" id="empty-state">
                        <div class="lang_translation_empty_icon mb-3">
                            <i class="fas fa-language text-muted"></i>
                        </div>
                        <h5 class="text-muted"><?= __("admin.no_section_selected") ?></h5>
                        <p class="text-muted"><?= __("admin.select_section_to_start_translating") ?></p>
                    </div>
                    
                    <div class="table-responsive translation-table d-none">
                        <table id="myTable" class="table table-hover mb-0">
                            <thead class="table-dark sticky-top">
                                <tr>
                                    <th class="border-0 ps-4 text-nowrap w-auto">
                                        <i class="fas fa-key me-2"></i><?= __("admin.key") ?>
                                    </th>
                                    <th class="border-0 text-nowrap w-auto">
                                        <i class="fas fa-flag me-2"></i><?= __("admin.default_english") ?>
                                    </th>
                                    <th class="border-0 text-nowrap">
                                        <i class="fas fa-language me-2"></i><?= $language['name'] ?> <?= __("admin.translation") ?>
                                    </th>
                                </tr>
                            </thead>
                            <tbody id="translation"></tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script type="text/javascript">
    $("#translation_file").on('change',function(){
        $this = $(this);
        var html = '';
        
        // Hide save button and clear search
        $(".save-translation").addClass('d-none');
        $('#myInput').val('');
        location.hash = $this.val();
        
        if($this.val() != ''){
            // Show loading state
            $("#empty-state").addClass('d-none');
            $(".translation-table").removeClass('d-none');
            $("#translation").html('<tr><td colspan="3" class="text-center py-5"><i class="fas fa-spinner fa-spin me-2"></i><?= __("admin.loading_translations") ?>...</td></tr>');
            $("#instruction-text").text('<?= __("admin.loading_please_wait") ?>');
            
            $.ajax({
                url:'<?= base_url("admincontrol/get_translation") ?>',
                type:'POST',
                dataType:'json',
                data:{id:$this.val(),'translation_id': <?= $language['id'] ?>},
                beforeSend:function(){
                    $this.prop("disabled",true);
                },
                complete:function(){
                    $this.prop("disabled",false);
                },
                success:function(json){
                    let totalCount = Object.keys(json).length;
                    
                    $.each(json,function(key,data){
                        html += '<tr class="translation-row">';
                        html += '    <td class="ps-4">';
                        html += '        <code class="lang_translation_key_code text-primary">'+ key +'</code>';
                        html += '    </td>';
                        html += '    <td>';
                        html += '        <div class="text-dark small">'+ data['text'] +'</div>';
                        html += '    </td>';
                        html += '    <td>';
                        html += '        <input type="text" name="translation['+ key +']" value="'+ data['value'] +'" class="form-control" placeholder="<?= __('admin.enter_translation') ?>" data-key="'+ key +'">';
                        html += '    </td>';
                        html += '</tr>';
                    });
                    
                    $("#translation").html(html);
                    $(".save-translation").removeClass('d-none');
                    $("#instruction-text").text('<?= __("admin.edit_translations_below") ?>');
                    
                    checkMissing();
                    
                    // Add input event listeners
                    $('input[name^="translation"]').on('input', function() {
                        checkMissing();
                    });
                    
                    // Show translation count
                    showToast('<?= __("admin.success") ?>', '<?= __("admin.translations_loaded") ?>: ' + totalCount, 'success', 3000);
                },
                error: function() {
                    $("#translation").html('<tr><td colspan="3" class="text-center py-5 text-danger"><i class="fas fa-exclamation-triangle me-2"></i><?= __("admin.error_loading_translations") ?></td></tr>');
                    showToast('<?= __("admin.error") ?>', '<?= __("admin.failed_to_load_translations") ?>', 'error', 5000);
                }
            })
        }
        else
        {
            $("#translation").html('');
            $(".translation-table").addClass('d-none');
            $("#empty-state").removeClass('d-none');
            $("#instruction-text").text('<?= __("admin.select_section_to_begin") ?>');
        }
    })
    
    if(location.hash.replace("#","") != ''){
        $("#translation_file").val(location.hash.replace("#","")).trigger("change");
    }
    
    $(".save-translation").on('click',function(){
        $this = $(this);
        let data = {};
        $('input[name^="translation"]').each(function(oneTag){
            let name = $(this).attr('name');
            name = name.replace('translation[', '');
            name = name.slice(0, -1);
            data[name] = $(this).val();
        });

        data = JSON.stringify(data);
        
        if($("#translation_file").val() != ''){
            $this.prop('disabled', true).html('<i class="fas fa-spinner fa-spin me-2"></i><?= __('admin.saving') ?>');
            
            $.ajax({
                url:'<?= base_url("admincontrol/save_translation") ?>?id=' + $("#translation_file").val() + '&translation_id=<?= $language['id'] ?>',
                type:'POST',
                dataType:'json',
                data:{data:data},
                success: function(json){
                    if(json['success']){
                        showToast('<?= __('admin.success') ?>', json['success'], 'success', 4000);
                        checkMissing(); // Refresh missing count
                    } else {
                        showToast('<?= __('admin.error') ?>', json['message'] || '<?= __('admin.save_failed') ?>', 'error', 5000);
                    }
                },
                error: function() {
                    showToast('<?= __('admin.error') ?>', '<?= __('admin.save_failed') ?>', 'error', 5000);
                },
                complete: function(){
                    $this.prop('disabled', false).html('<i class="fas fa-save me-2"></i><?= __("admin.save_changes") ?>');
                }
            })
        }
    })
    
    function checkMissing(){
        let missingCount = 0;
        let totalCount = 0;
        
        $('[name^="translation"]').each(function(){
            totalCount++;
            var val = $.trim($(this).val());
            if(val == ''){
                $(this).addClass("is-invalid");
                $(this).closest('tr').addClass('table-warning');
                missingCount++;
            }
            else{
                $(this).removeClass("is-invalid");
                $(this).closest('tr').removeClass('table-warning');
            }
        });
        
        // Update missing count in header
        $('#missing-count').html('<?= __("admin.missing_translation") ?>: ' + missingCount + '/' + totalCount);
    }
    
    function searchFunction() {
        var input, filter, table, tr, td, i;
        input = document.getElementById("myInput");
        filter = input.value.toUpperCase();
        table = document.getElementById("myTable");
        tr = table.getElementsByTagName("tr");
        
        for (i = 0; i < tr.length; i++) {
            td = tr[i].getElementsByTagName("td")[1]; // Search in default text column
            if (td) {
                if (td.innerHTML.toUpperCase().indexOf(filter) > -1) {
                    tr[i].style.display = "";
                } else {
                    tr[i].style.display = "none";
                }
            }       
        }
    }
</script>


