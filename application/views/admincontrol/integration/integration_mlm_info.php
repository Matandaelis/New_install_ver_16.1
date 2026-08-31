<div class="modal-dialog modal-xl modal-dialog-centered modal-dialog-scrollable intg-modal">
    <div class="modal-content">
        <div class="intg-modal-header">
            <div class="intg-modal-header-left">
                <div class="intg-modal-icon intg-modal-icon--info">
                    <i class="bi bi-diagram-3"></i>
                </div>
                <div>
                    <h5 class="intg-modal-title"><?= __('admin.integration_mlm_info') ?></h5>
                    <p class="intg-modal-subtitle"><?= htmlspecialchars($tool['name'] ?? '') ?></p>
                </div>
            </div>
            <button type="button" class="intg-modal-close" aria-label="Close" data-bs-dismiss="modal"><i class="bi bi-x-lg"></i></button>
        </div>
        <div class="modal-body p-4">
            <div class="mlm-settings">
                <!-- Campaign MLM Context Badges -->
                <div class="d-flex flex-wrap gap-2 mb-3">
                    <?php if($tool['commission_type'] == 'custom'): ?>
                        <span class="badge bg-warning text-dark"><i class="bi bi-pencil-square me-1"></i><?= __('admin.custom') ?> MLM</span>
                    <?php else: ?>
                        <span class="badge bg-info"><i class="bi bi-gear me-1"></i><?= __('admin.default') ?> MLM</span>
                    <?php endif ?>
                    <?php if($tool['vendor_id']): ?>
                        <span class="badge bg-success"><i class="bi bi-person me-1"></i><?= __('admin.vendor') ?>: <?= htmlspecialchars($tool['vendor_name'] ?? '') ?></span>
                    <?php else: ?>
                        <span class="badge bg-primary"><i class="bi bi-shield-lock me-1"></i><?= __('admin.admin') ?></span>
                    <?php endif ?>

                    <?php if(!$mlm_enabled): ?>
                        <span class="badge bg-danger"><i class="bi bi-x-circle me-1"></i>MLM <?= __('admin.disabled') ?></span>
                    <?php else: ?>
                        <span class="badge bg-success"><i class="bi bi-check-circle me-1"></i>MLM <?= __('admin.active') ?></span>
                    <?php endif ?>
                </div>
            </div>

            <?php if(!$mlm_enabled): ?>
                <!-- MLM Disabled State -->
                <div class="intg-modal-disabled-state text-center py-4">
                    <div class="intg-modal-empty-icon mb-3">
                        <i class="bi bi-slash-circle"></i>
                    </div>
                    <h6 class="fw-bold text-muted mb-2"><?= __('admin.mlm_currently_disabled') ?></h6>
                    <p class="text-muted small mb-3"><?= __('admin.mlm_disabled_explanation') ?></p>
                    <?php if($tool['vendor_id'] && !$vendor_module_enabled): ?>
                        <span class="badge bg-danger bg-opacity-10 text-danger border border-danger px-3 py-2">
                            <i class="bi bi-shield-x me-1"></i><?= __('admin.vendor_mlm_module_disabled') ?>
                        </span>
                    <?php endif ?>
                    <?php if($tool['vendor_id'] && !$vendor_mlm_enabled): ?>
                        <span class="badge bg-danger bg-opacity-10 text-danger border border-danger px-3 py-2">
                            <i class="bi bi-person-x me-1"></i><?= __('admin.vendor_disabled_mlm') ?>
                        </span>
                    <?php endif ?>
                    <?php if(!$tool['vendor_id'] && !$admin_mlm_enabled): ?>
                        <span class="badge bg-danger bg-opacity-10 text-danger border border-danger px-3 py-2">
                            <i class="bi bi-shield-x me-1"></i><?= __('admin.admin_disabled_mlm') ?>
                        </span>
                    <?php endif ?>
                </div>
            <?php else: ?>
                <!-- MLM Levels Table -->
                <div class="mlm-levels">
                    <div class="table-responsive">
                    <table class="table table-hover table-bordered align-middle mb-0" id="tbl_refer_level">
                        <thead class="table-light">
                            <tr>
                                <th class="text-center"><?= __('admin.level_mlm') ?></th>
                                <?php if(!$tool['vendor_id']): ?>
                                    <th class="text-center">
                                        <?= __('admin.cpr_cost') ?><br>
                                        <?php if ($tool['referlevel']['reg_comission_type'] == 'disabled'): ?>
                                            <span class="form-control form-control-sm"><?= __('admin.select_registration_commission_plan') ?></span>
                                        <?php endif ?>
                                        <?php if ($tool['referlevel']['reg_comission_type'] == 'percentage'): ?>
                                            <span class="form-control form-control-sm refer-reg-symball-select" symbal='%'><?= __('admin.membership_registration_commission_perce') ?></span>
                                        <?php endif ?>
                                        <?php if ($tool['referlevel']['reg_comission_type'] == 'custom_percentage'): ?>
                                            <span class="form-control form-control-sm refer-reg-symball-select" symbal='%'><?= __('admin.registration_custom_commission_amount_perce') ?></span>
                                        <?php endif ?>
                                        <?php if ($tool['referlevel']['reg_comission_type'] == 'fixed'): ?>
                                            <span class="form-control form-control-sm refer-reg-symball-select" symbal='<?= $CurrencySymbol ?>'><?= __('admin.registration_fixed_amount') ?></span>
                                        <?php endif ?>

                                        <?php if ($tool['commission_type'] == 'default'): ?>
                                            <span class="form-control form-control-sm reg_comission_custom_amt"><?php echo isset($tool['referlevel']['reg_comission_custom_amt']) ? $tool['referlevel']['reg_comission_custom_amt'] : 0;?>
                                             </span>
                                        <?php endif ?>
                                    </th>
                                <?php endif ?>
                                <th class="text-center">
                                    <?= __('admin.cps_cost') ?><br>
                                    <?php if ($tool['referlevel']['sale_type'] == 'percentage'): ?>
                                        <span class="form-control form-control-sm refer-symball-select" symbal='%'><?= __('admin.percentage') ?></span>
                                    <?php endif ?>
                                    <?php if ($tool['referlevel']['sale_type'] == 'fixed'): ?>
                                        <span class="form-control form-control-sm refer-symball-select" symbal='<?= $CurrencySymbol ?>'><?= __('admin.fixed') ?></span>
                                    <?php endif ?>
                                </th>
                                <th class="text-center" colspan="2"><?= __('admin.clicks_count') ?> &amp; <?= __('admin.cpc_cost') ?></th>
                                <th class="text-center"><?= __('admin.cpa_cost') ?></th>
                            </tr>
                        </thead>
                        <tbody>
                        <?php $tool_levels = isset($tool['referlevel']['levels']) ? (int)$tool['referlevel']['levels'] : 3;
                            for ($level =1; $level <= $tool_levels; $level++) { ?>
                                <tr>
                                    <td class="text-center fw-semibold"><?= $level ?></td>
                                    <?php if(!$tool['vendor_id']): ?>
                                        <td>
                                            <div class="input-group input-group-sm">
                                                <span class="form-control"><?php echo $tool['referlevel_'. $level]['reg_commission'] ?></span>
                                                <span class="input-group-text refer-reg-symball"></span>
                                            </div>
                                        </td>
                                    <?php endif ?>
                                    <td>
                                        <div class="input-group input-group-sm">
                                            <span class="form-control"><?php echo $tool['referlevel_'. $level]['sale_commition'] ?></span>
                                            <span class="input-group-text refer-symball"></span>
                                        </div>
                                    </td>
                                    <td><span class="form-control form-control-sm"><?php echo $tool['referlevel_'. $level]['commition'] ?></span></td>
                                    <td>
                                        <div class="input-group input-group-sm">
                                            <span class="form-control"><?php echo $tool['referlevel_'. $level]['ex_commition'] ?></span>
                                            <span class="input-group-text"><?= $CurrencySymbol ?></span>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="input-group input-group-sm">
                                            <span class="form-control"><?php echo $tool['referlevel_'. $level]['ex_action_commition'] ?></span>
                                            <span class="input-group-text"><?= $CurrencySymbol ?></span>
                                        </div>
                                    </td>
                                </tr>
                            <?php } ?>
                        </tbody>
                    </table>
                    </div>
                </div>
            <?php endif ?>
        </div>
        <div class="intg-modal-footer">
            <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal"><i class="bi bi-x-lg me-1"></i><?= __('admin.close') ?></button>
        </div>
    </div>
</div>

<?php if($mlm_enabled): ?>
<script type="text/javascript">
    function chnage_teigger() {
        var symbal = $(".refer-symball-select").attr("symbal");
        $(".refer-symball").html(symbal);
    }
    chnage_teigger();

    var refer_reg_symball_select = '<?= $tool['referlevel']['reg_comission_type']; ?>';
    function chnage_teigger2() {
        var symbal = $(".refer-reg-symball-select").attr("symbal");

        if(refer_reg_symball_select == "disabled")
            $(".refer-reg-symball").html('#');
        else
            $(".refer-reg-symball").html(symbal);

        if(refer_reg_symball_select != "custom_percentage")
            $('.reg_comission_custom_amt').hide();
        else
            $('.reg_comission_custom_amt').show();
    }
    chnage_teigger2();
</script>
<?php endif ?>
