<div class="container-fluid">
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
            <button type="button" class="intg-modal-close" data-bs-dismiss="modal" aria-label="Close"><i class="bi bi-x-lg"></i></button>
        </div>
        <div class="modal-body p-4">
            <div class="mlm-settings">
            </div>
            <div class="mlm-levels">
                <div class="table-responsive">
                <table class="table table-hover table-bordered align-middle mb-0" id="tbl_refer_level">
                    <thead class="table-light">
                        <tr>
                            <th class="text-center"><?= __('admin.level_mlm') ?></th>
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
        </div>
        <div class="intg-modal-footer">
            <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal"><i class="bi bi-x-lg me-1"></i><?= __('admin.close') ?></button>
        </div>
    </div>
</div>

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
            $(".refer-reg-symball").empty();
        else
            $(".refer-reg-symball").html(symbal);

        if(refer_reg_symball_select != "custom_percentage")
            $('.reg_comission_custom_amt').hide();
        else
            $('.reg_comission_custom_amt').show();
    }
    chnage_teigger2();
</script>

</div>
