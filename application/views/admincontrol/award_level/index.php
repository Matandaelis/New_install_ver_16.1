<?php if($award_level_status){ ?>

<div class="container-fluid px-4 pb-4">
  <div class="row">
    <div class="col-12">
      <?php $this->load->view('admincontrol/award_level/_award_nav'); ?>
      <div class="d-flex justify-content-between align-items-center mb-3">
        <h4 class="fw-bold mb-0"><?= __('admin.award_level') ?></h4>
        <div class="d-flex align-items-center gap-2">
          <a href="<?= base_url('admincontrol/create_award_level') ?>" class="btn btn-success btn-sm">
            <i class="fas fa-plus me-1"></i><?= __('admin.create_award_level') ?>
          </a>
          <button class="btn btn-outline-info btn-sm" data-bs-toggle="modal" data-bs-target="#check-award-level-modal">
            <i class="fas fa-sync me-1"></i><?= __('admin.check_award_level') ?>
          </button>
        </div>
      </div>
    </div>
  </div>
  
  <!-- Alert Message -->
  <div class="row">
    <div class="col-12">
      <div class="alert alert-info alert-dismissible fade show d-flex align-items-center mb-4" role="alert">
        <i class="bi bi-info-circle-fill me-2"></i>
        <div class="flex-grow-1">
          <?= __('admin.award_level_message') ?>
        </div>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
      </div>
    </div>
  </div>
</div>

<div class="container-fluid">
<div class="row">
    <div class="col-12">
            <!-- Main Card -->
            <div class="card shadow-sm border-0">
                <div class="card-header bg-light border-0">
                    <div class="row align-items-center">
                        <div class="col-md-6">
                            <h5 class="mb-0">
                                <i class="fas fa-list me-2"></i>
                                <?= __('admin.award_level') ?>
                            </h5>
                        </div>
                        <div class="col-md-6">
                            <div class="d-flex justify-content-end gap-2 flex-wrap">
                                <a href="<?= base_url('admincontrol/level_analysis') ?>" class="btn btn-outline-light btn-sm">
                                    <i class="bi bi-graph-up me-1"></i>
                                    <?= __('admin.level_analysis') ?>
                                </a>
                                <button class="btn btn-outline-light btn-sm" data-bs-toggle="modal" data-bs-target="#cron-job-info-modal">
                                    <i class="bi bi-gear me-1"></i>
                                    <?= __('admin.cron_job_setting') ?>
                                </button>
                                <button class="btn btn-outline-light btn-sm" data-bs-toggle="modal" data-bs-target="#check-award-level-modal">
                                    <i class="bi bi-check-circle me-1"></i>
                                    <?= __('admin.check_award_level') ?>
                                </button>
                                                                 <a href="<?= base_url('admincontrol/create_award_level') ?>" class="btn btn-light btn-sm">
                                     <i class="bi bi-plus-circle me-1"></i>
                                     <?= __("admin.add_new") ?>
                                 </a>


                            </div>
                        </div>
                    </div>
            </div>
                
            <div class="card-body p-0">
                <div class="table-responsive">
                        <table class="table table-hover mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th class="fw-semibold text-center">
                                        <i class="bi bi-hash me-1"></i>
                                        <?= __('admin.level_number') ?>
                                    </th>
                                    <th class="fw-semibold">
                                        <i class="bi bi-skip-end me-1"></i>
                                        <?= __('admin.jump_level') ?>
                                    </th>
                                    <th class="fw-semibold">
                                        <i class="bi bi-currency-dollar me-1"></i>
                                        <?= __('admin.minimum_earning') ?>
                                    </th>
                                    <th class="fw-semibold text-center">
                                        <i class="bi bi-percent me-1"></i>
                                        <?= __('admin.sale_comission_rate') ?>
                                    </th>
                                    <th class="fw-semibold">
                                        <i class="bi bi-gift me-1"></i>
                                        <?= __('admin.bonus') ?>
                                    </th>
                                    <th class="fw-semibold text-center">
                                        <i class="bi bi-star me-1"></i>
                                        <?= __('admin.default_registration_level') ?>
                                    </th>
                                    <th class="fw-semibold text-center">
                                        <i class="bi bi-calendar-plus me-1"></i>
                                        <?= __('admin.created_at') ?>
                                    </th>
                                    <th class="fw-semibold text-center">
                                        <i class="bi bi-gear me-1"></i>
                                        <?= __('admin.actions') ?>
                                    </th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach($award_level as $key => $value){ ?>
                                    <tr class="align-middle" data-level-id="<?= $value['id'] ?>">
                                        <td class="text-center">
                                            <span class="badge bg-primary rounded-pill fs-6 px-3 py-2">
                                                <?= $value['level_number'] ?>
                                            </span>
                                        </td>
                                    <td>
                                        <?php
                                            if($value['jump_level'] == '0')
                                                    echo '<span class="badge bg-secondary rounded-pill">' . __('admin.default') . '</span>';
                                            else
                                                    echo '<span class="badge bg-warning text-dark rounded-pill">' . $value['jump_level_name'] . '</span>';
                                        ?>
                                    </td>
                                        <td>
                                            <span class="fw-semibold text-success">
                                                <?= c_format($value['minimum_earning']); ?>
                                            </span>
                                        </td>
                                        <td class="text-center">
                                            <span class="badge bg-info rounded-pill">
                                                <?= $value['sale_comission_rate'].'%'; ?>
                                            </span>
                                        </td>
                                        <td>
                                            <span class="fw-semibold text-primary">
                                                <?= c_format($value['bonus']); ?>
                                            </span>
                                        </td>
                                        <td class="text-center">
                                            <?php if($value['default_registration_level']): ?>
                                                <span class="badge bg-success rounded-pill">
                                                    <i class="bi bi-star-fill me-1"></i>
                                                    <?= __('admin.default') ?>
                                                </span>
                                            <?php else: ?>
                                                <span class="text-muted">-</span>
                                            <?php endif; ?>
                                        </td>
                                        <td class="text-center">
                                            <?php if (!empty($value['created_at']) && $value['created_at'] !== '0000-00-00 00:00:00'): ?>
                                                <small class="text-muted">
                                                    <?= date('M j, Y', strtotime($value['created_at'])) ?>
                                                    <br>
                                                    <span class="text-secondary"><?= date('g:i A', strtotime($value['created_at'])) ?></span>
                                                </small>
                                            <?php else: ?>
                                                <span class="text-muted">-</span>
                                            <?php endif; ?>
                                        </td>
                                        <td class="text-center">
                                            <div class="btn-group" role="group">
                                                <a href="<?= base_url('admincontrol/update_award_level/'.$value['id']) ?>" 
                                                   class="btn btn-outline-primary btn-sm" 
                                                   data-bs-toggle="tooltip" 
                                                   title="<?= __('admin.edit') ?>">
                                                    <i class="bi bi-pencil-square"></i>
                                                </a>
                                                <a href="<?= base_url('admincontrol/delete_award_level/'.$value['id']) ?>" 
                                                   class="btn btn-outline-danger btn-sm btn-delete" 
                                                   data-bs-toggle="tooltip" 
                                                   title="<?= __('admin.delete') ?>"
                                                   data-level-id="<?= $value['id'] ?>"
                                                   data-level-name="<?= $value['level_number'] ?>">
                                                    <i class="bi bi-trash3"></i>
                                                </a>
                                            </div>
                                    </td>
                                </tr>
                            <?php } ?>
                        </tbody>
                    </table>
                </div>
                
                <?php if(isset($pagination) && !empty($pagination)): ?>
                <div class="card-footer bg-light border-0">
                    <div class="d-flex justify-content-center">
                        <?= $pagination ?>
                    </div>
                </div>
                <?php endif; ?>
            </div>
            </div>
        </div>
    </div>
</div>

<!-- Cron Job Information Modal -->
<div id="cron-job-info-modal" class="modal fade" tabindex="-1" role="dialog" data-bs-backdrop="static" data-bs-keyboard="false">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content border-0 shadow">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title d-flex align-items-center">
                    <i class="bi bi-gear-fill me-2"></i>
                    <?= __('admin.cron_job_setting') ?>
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-12">
                        <!-- What is Cron Job Section -->
                        <div class="card bg-light border-0 mb-4">
                            <div class="card-body">
                                <h5 class="text-primary d-flex align-items-center mb-3">
                                    <i class="bi bi-question-circle-fill me-2"></i>
                                    <?= __('admin.what_is_cron_job') ?>
                                </h5>
                                <p class="mb-0 text-muted"><?= __('admin.what_is_cron_job_answer') ?></p>
                            </div>
                        </div>

                        <!-- Setup Steps -->
                        <h6 class="text-dark mb-3 d-flex align-items-center">
                            <i class="bi bi-list-ol me-2"></i>
                            <?= __('admin.to_add_cron_job_steps') ?>:
                        </h6>

                        <div class="list-group list-group-flush">
                            <div class="list-group-item border-0 px-0">
                                <div class="d-flex align-items-start">
                                    <span class="badge bg-primary rounded-pill me-3 mt-1">1</span>
                                    <span><?= __('admin.to_add_cron_job_step1') ?></span>
                                </div>
                            </div>
                            <div class="list-group-item border-0 px-0">
                                <div class="d-flex align-items-start">
                                    <span class="badge bg-primary rounded-pill me-3 mt-1">2</span>
                                    <span><?= __('admin.to_add_cron_job_step2') ?></span>
                                </div>
                            </div>
                            <div class="list-group-item border-0 px-0">
                                <div class="d-flex align-items-start">
                                    <span class="badge bg-primary rounded-pill me-3 mt-1">3</span>
                                    <span><?= __('admin.to_add_cron_job_step3') ?></span>
                                </div>
                            </div>
                            <div class="list-group-item border-0 px-0">
                                <div class="d-flex align-items-start">
                                    <span class="badge bg-primary rounded-pill me-3 mt-1">4</span>
                                    <span>
                                        <?= __('admin.to_add_cron_job_step4') ?>
                                        <span class="badge bg-warning text-dark ms-2">
                                            <?= __('admin.once_per_minute') ?> (* * * * *)
                                        </span>
                                    </span>
                                </div>
                            </div>
                            <div class="list-group-item border-0 px-0">
                                <div class="d-flex align-items-start">
                                    <span class="badge bg-primary rounded-pill me-3 mt-1">5</span>
                                    <div class="flex-grow-1">
                                        <span><?= __('admin.to_add_cron_job_step5') ?></span>
                                        <div class="mt-2">
                                            <div class="input-group">
                                                <span class="input-group-text bg-dark text-white">
                                                    <i class="bi bi-terminal"></i>
                                                </span>
                                                <input type="text" class="form-control bg-dark text-white font-monospace" 
                                                       value="curl <?= base_url('/cronJob/check_award_level') ?>" 
                                                       readonly onclick="this.select()">
                                                <button class="btn btn-outline-secondary" type="button" 
                                                        onclick="navigator.clipboard.writeText(this.previousElementSibling.value)"
                                                        data-bs-toggle="tooltip" title="<?= __('admin.copy_to_clipboard') ?>">
                                                    <i class="bi bi-clipboard"></i>
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="list-group-item border-0 px-0">
                                <div class="d-flex align-items-start">
                                    <span class="badge bg-primary rounded-pill me-3 mt-1">6</span>
                                    <span><?= __('admin.to_add_cron_job_step6') ?></span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer bg-light border-0">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                    <i class="bi bi-x-circle me-1"></i>
                    <?= __('admin.close') ?>
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Check Award Level Modal -->
<div id="check-award-level-modal" class="modal fade" tabindex="-1" role="dialog" data-bs-backdrop="static" data-bs-keyboard="false">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content border-0 shadow">
            <div class="modal-header bg-info text-white">
                <h5 class="modal-title d-flex align-items-center">
                    <i class="bi bi-check-circle-fill me-2"></i>
                    <?= __('admin.check_award_level') ?>
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <!-- Step 1: Confirmation -->
                <div class="step-1">
                    <div class="text-center mb-4">
                        <div class="alert alert-warning d-flex align-items-center" role="alert">
                            <i class="bi bi-exclamation-triangle-fill me-2"></i>
                            <div>
                                <h5 class="mb-2"><?= __('admin.are_you_sure_checking_user_award_level') ?></h5>
                                <p class="mb-0"><?= __('admin.take_longer_depending_user_available') ?></p>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Step 2: Progress -->
                <div class="step-2" style="display:none;">
                    <div class="text-center mb-4">
                        <div class="spinner-border text-primary mb-3" role="status">
                            <span class="visually-hidden"><?= __('admin.loading') ?></span>
                        </div>
                        <h5 class="text-primary"><?= __('admin.wait_while_checking_user_award_level') ?></h5>
                    </div>
                    
                    <!-- Progress Bar -->
                    <div class="mb-4">
                        <div id="progressbar123" class="progress" style="height: 8px;">
                            <div class="progress-bar progress-bar-striped progress-bar-animated bg-primary" 
                                 role="progressbar" style="width: 0%"></div>
                        </div>
                    </div>
                    
                    <!-- Results -->
                    <div class="text-center">
                        <div class="alert alert-success d-flex align-items-center jumped" 
                             data-count="0" style="display:none;" role="alert">
                            <i class="bi bi-check-circle-fill me-2"></i>
                            <span>0 <?= __('admin.user_jumped_to_level') ?></span>
                        </div>
                        
                        <div class="alert alert-warning d-flex align-items-center warning" 
                             style="display:none;" role="alert">
                            <i class="bi bi-info-circle-fill me-2"></i>
                            <span><?= __('admin.no_jumped_user_available') ?></span>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer bg-light border-0">
                <button type="button" class="btn btn-primary allow_to_check_award_level">
                    <i class="bi bi-play-circle me-1"></i>
                    <?= __('admin.yes_continue') ?>
                </button>
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                    <i class="bi bi-x-circle me-1"></i>
                    <?= __('admin.close') ?>
                </button>
            </div>
        </div>
    </div>
</div>



<script type="text/javascript">
    // Initialize Bootstrap 5 tooltips
    document.addEventListener('DOMContentLoaded', function() {
        var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
        var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
            return new bootstrap.Tooltip(tooltipTriggerEl);
        });
    });

    var warning = true;
    var jumped_user = false;  // Add this line to track if any user jumped a level
    
    $(document).on('click','.allow_to_check_award_level', function(){
        $(this).hide();
        $('#check-award-level-modal .step-1').hide();
        $('#check-award-level-modal .step-2').show();
        $('#check-award-level-modal .modal-footer').hide();
        recursive_check_award_level();
    });

    function recursive_check_award_level(index = 1){
        $.ajax({
        type:"POST",
        url: '<?= base_url('admincontrol/check_award_level')?>',
        dataType:"json",
        data:{index:index},
        success: function(data){
            if(data.progress_percentage){
                $('#progressbar123').show();
                $('#progressbar123 .progress-bar').css('width',data.progress_percentage);
            }

            if(data.jumped){
                jumped_user = true;  // Update this flag when a user jumps a level
                warning = false;

                let existing_count = $('#check-award-level-modal .step-2 .jumped').data('count');
                $('#check-award-level-modal .step-2 .jumped').data('count',(existing_count+1));
                $('#check-award-level-modal .step-2 .jumped span').text((existing_count+1)+' '+data.message);
                $('#check-award-level-modal .step-2 .jumped').show();
            }
            
            if(data.index){
                recursive_check_award_level(data.index);
            } else {
                // Process completed - replace spinner with completion state
                $('#check-award-level-modal .step-2 .spinner-border').replaceWith('<div class="text-success mb-3"><i class="bi bi-check-circle-fill" style="font-size: 2rem;"></i></div>');
                $('#check-award-level-modal .step-2 h5').text('<?= __("admin.process_completed") ?>').removeClass('text-primary').addClass('text-success');
                
                // Remove progress bar animation and set to 100%
                $('#progressbar123 .progress-bar').removeClass('progress-bar-animated progress-bar-striped').css('width', '100%');
                
                // Display the appropriate message
                if(!jumped_user){
                    $('#check-award-level-modal .step-2 .warning').show();
                } else {
                    // If users jumped, hide the progress bar after a moment
                    setTimeout(function() {
                        $('#progressbar123').fadeOut();
                    }, 1000);
                }
                
                $('#check-award-level-modal .modal-footer').show();
                $('#check-award-level-modal .modal-footer').html('<button type="button" class="btn btn-secondary" onclick="window.location.reload()">'+'<?= __('admin.close') ?>'+'</button>');
            } 
            }
        });
    }

    $(".btn-delete").off('click').on('click', function(e) {
        e.preventDefault();
        var btn = $(this); // Store reference to the button
        var levelId = btn.data('level-id');
        var levelName = btn.data('level-name');
        var deleteUrl = btn.attr('href');
        
        // Show confirmation dialog first
        Swal.fire({
            title: '<?= __("admin.sure_delete") ?>',
            text: `Are you sure you want to delete "${levelName}"?`,
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#3085d6',
            confirmButtonText: '<?= __("admin.yes_delete") ?>',
            cancelButtonText: '<?= __("admin.cancel") ?>'
        }).then((result) => {
            if (result.isConfirmed || result.value === true) {
                // User confirmed, proceed with delete
            $.ajax({
                    url: deleteUrl,
                type: 'POST',
                dataType: 'json',
                success: function(result) {
                        if (result.status == 1) {
                            // Success - Use toast notification
                            showToast('<?= __("admin.success") ?>', result.message, 'success', 3000);
                            
                            // Remove the deleted row with animation
                            $(btn).closest('tr').fadeOut(500, function() {
                                $(this).remove();
                                
                                // Update row numbers
                                $('table tbody tr').each(function(index) {
                                    $(this).find('td:first .badge').text(index + 1);
                                });
                            });
                        } else if (result.message === '<?= __("admin.level_connected_to_user") ?>') {
                    // Show force delete confirmation
                    Swal.fire({
                        title: '<?= __("admin.force_delete_level") ?>',
                        html: `
                            <div class="text-start">
                                <p class="text-danger mb-3">
                                    <i class="bi bi-exclamation-triangle me-2"></i>
                                    <strong>Level "${levelName}" has connected users!</strong>
                                </p>
                                <p class="mb-3"><?= __("admin.force_delete_level_desc") ?></p>
                                <div class="alert alert-warning">
                                    <i class="bi bi-info-circle me-2"></i>
                                    <strong>Warning:</strong> This action will:
                                    <ul class="mb-0 mt-2">
                                        <li>Move all users to the default level</li>
                                        <li>Update membership plans</li>
                                        <li>Delete the level permanently</li>
                                    </ul>
                                </div>
                            </div>
                        `,
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonColor: '#d33',
                        cancelButtonColor: '#3085d6',
                        confirmButtonText: '<?= __("admin.force_delete_level") ?>',
                        cancelButtonText: '<?= __("admin.cancel") ?>',
                        reverseButtons: true
                    }).then((result) => {
                                                 if (result.isConfirmed || result.value === true) {
                             // Show loading state
                             Swal.fire({
                                 title: '<?= __("admin.force_delete_level") ?>',
                                 html: '<div class="text-center"><div class="spinner-border text-primary mb-3" role="status"></div><p>Processing...</p></div>',
                                 showConfirmButton: false,
                                 allowOutsideClick: false
                             });
                             
                             // Proceed with force delete
                             $.ajax({
                                 url: '<?= base_url("admincontrol/force_delete_award_level/") ?>' + levelId,
                                 type: 'POST',
                                 dataType: 'json',
                                                                             success: function(forceResult) {
                                                                                                if (forceResult.status == 1) {
                                                    // Success - Use toast notification
                                                    let toastMessage = forceResult.message;
                                                    if (forceResult.affected_users > 0) {
                                                        toastMessage += ` (${forceResult.affected_users} users moved)`;
                                                    }
                                                    if (forceResult.affected_plans > 0) {
                                                        toastMessage += ` (${forceResult.affected_plans} plans updated)`;
                                                    }
                                                    
                                                    showToast('<?= __("admin.success") ?>', toastMessage, 'success', 4000);
                                                    
                                                    // Remove the deleted row with animation
                                                    $(btn).closest('tr').fadeOut(500, function() {
                                                        $(this).remove();
                                                        
                                                        // Update row numbers
                                                        $('table tbody tr').each(function(index) {
                                                            $(this).find('td:first .badge').text(index + 1);
                                                        });
                                                    });
                                                } else {
                                         Swal.fire({
                                             icon: 'error',
                                             title: '<?= __("admin.error") ?>',
                                             text: forceResult.message || '<?= __("admin.something_went_wrong") ?>'
                                         });
                                     }
                                 },
                                 error: function(xhr, status, error) {
                                     Swal.fire({
                                         icon: 'error',
                                         title: '<?= __("admin.error") ?>',
                                         text: '<?= __("admin.network_error") ?>: ' + error
                                     });
                                 }
                             });
                         }
                    });
                    } else {
                        Swal.fire({
                            icon: 'error',
                            html: result.message,
                        });
                        showPrintMessage(result.message, 'error');
                    }
                },
                error: function(xhr, status, error) {
                    console.log('Delete error:', xhr.responseText);
                    Swal.fire({
                        icon: 'error',
                        title: '<?= __("admin.error") ?>',
                        text: '<?= __("admin.network_error") ?>: ' + error
                    });
                }
            });
        }
    });
     });




</script>


<?php } else { ?>
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card border-0 shadow-sm">
                <div class="card-body text-center py-5">
                    <div class="mb-4">
                        <i class="bi bi-exclamation-circle text-warning" style="font-size: 4rem;"></i>
                    </div>
                    <h3 class="text-muted mb-3"><?= __('admin.award_level_module_is_off') ?></h3>
                    <p class="text-muted mb-4"><?= __('admin.module_activation_message') ?></p>
                    <a href="<?= base_url('admincontrol/addons') ?>" class="btn btn-primary btn-lg">
                        <i class="bi bi-power me-2"></i>
                    <?= __('admin.admin_click_here_to_activate') ?>
                </a>
                </div>
            </div>
        </div>
    </div>
    </div> 
<?php } ?>