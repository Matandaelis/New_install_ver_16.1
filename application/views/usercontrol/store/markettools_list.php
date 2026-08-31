<?php
$db =& get_instance();
$userdetails=$db->userdetails();
$pro_setting = $this->Product_model->getSettings('productsetting');
$form_setting = $this->Product_model->getSettings('formsetting');
?>

<?php foreach($data_list as $index => $product){ ?>
<?php
$display_class = ($product['on_store'] == 0 && $product['product_created_by'] == 1) ? 'd-none' : '';
?>

<?php if(isset($product['is_form'])){ ?>
<?php
    $accentColor = '#7c3aed';
    $accentBg    = '#f5f3ff';
    $typeIcon    = 'fas fa-wpforms';
    $collapseId  = 'mktCollapse_'.$index;

    $views = $product['view_statistics'];
    $myViews = $product['my_view_statistics'] ?? 0;

    $ordercountratio = 0;
    if($product['view_statistics'] > 0)
        $ordercountratio = $product['all_count_commission']*100/$product['view_statistics'];
    $ordercountratio = is_float($ordercountratio) ? number_format((float)$ordercountratio,1,'.',''): $ordercountratio;

    $clickratio = 0;
    $comissionclickcount = (int)$product['all_commition_click_count'];
    if($product['view_statistics'] > 0)
        $clickratio = $comissionclickcount*100/$product['view_statistics'];
    $clickratio = is_float($clickratio) ? number_format((float)$clickratio,1,'.',''): $clickratio;

    $shareUrl = $product['slug'] ? base_url($product['slug']) : $product['public_page'];

    $mkt_promote_ok = $this->Product_model->user_can_promote_market_campaign($userdetails, $product);
    $mkt_min_h = (float)($product['min_health_score'] ?? 0);
    $mkt_cur_h = isset($userdetails['health_score']) && $userdetails['health_score'] !== '' && $userdetails['health_score'] !== null ? (float)$userdetails['health_score'] : 0.0;
    $mkt_lock_msg = sprintf(__('user.market_health_lock_detail'), number_format($mkt_min_h, 2, '.', ''), number_format($mkt_cur_h, 2, '.', ''));
?>
<div class="mkt-card card border-0 shadow-sm mb-3 <?= $display_class ?>" style="border-left: 4px solid <?= $accentColor ?> !important;">
    <div class="card-body p-3">

        <!-- Header -->
        <div class="d-flex align-items-start gap-3 mb-3">
            <div class="d-flex align-items-center justify-content-center mkt-thumb" style="background:<?= $accentBg ?>;">
                <img class="w-100 h-100 rounded" style="object-fit:cover;" src="<?= base_url($product['fevi_icon']) ?>" alt="" loading="lazy" onerror="this.onerror=null;this.src='<?= base_url('assets/images/no_product_image.png') ?>'">
            </div>
            <div class="flex-fill min-w-0">
                <h6 class="fw-bold mb-1 text-dark text-truncate"><?= $product['title'] ?></h6>
                <div class="d-flex align-items-center gap-2 flex-wrap">
                    <span class="mkt-type-badge" style="background:<?= $accentBg ?>;color:<?= $accentColor ?>;">
                        <i class="<?= $typeIcon ?> me-1"></i><?= __('user.campaign') ?>
                    </span>
                    <?php if($product['form_recursion_type']): ?>
                    <?php
                    if($product['form_recursion_type'] == 'custom'){
                        $fr = $product['form_recursion'] ?? '';
                        $recLabel = ($fr != 'custom_time') ? ($fr ? __('user.'.$fr) : __('user.life_time')) : timetosting($product['recursion_custom_time']);
                    } else {
                        $fsRec = $form_setting['form_recursion'] ?? '';
                        $recLabel = ($fsRec == 'custom_time') ? timetosting($form_setting['recursion_custom_time']) : ($fsRec ? __('user.'.$fsRec) : __('user.life_time'));
                    }
                    ?>
                    <span class="mkt-recurring badge bg-warning text-dark">
                        <i class="fas fa-sync-alt me-1"></i><?= __('user.recurring') ?>: <?= $recLabel ?>
                    </span>
                    <?php endif; ?>
                    <small class="text-muted ms-1" style="font-size:.7rem;"><i class="fas fa-eye me-1"></i><?= $myViews ?> <?= __('user.my_views') ?></small>
                </div>
            </div>
            <button class="btn mkt-details-btn flex-shrink-0" type="button" data-bs-toggle="collapse" data-bs-target="#<?= $collapseId ?>">
                <i class="fas fa-chart-bar me-1"></i><?= __('user.details') ?>
            </button>
        </div>

        <!-- Stats + Earnings -->
        <div class="d-flex align-items-stretch gap-2 mb-3 flex-wrap">
            <div class="mkt-stats-section mkt-stats flex-fill">
                <div class="mkt-stat">
                    <div class="mkt-stat-val text-primary"><?= $views ?></div>
                    <div class="mkt-stat-lbl"><i class="fas fa-eye me-1"></i><?= __('user.views') ?></div>
                </div>
                <div class="mkt-stat-divider"></div>
                <div class="mkt-stat">
                    <div class="mkt-stat-val text-success"><?= $ordercountratio ?>%</div>
                    <div class="mkt-stat-lbl"><i class="fas fa-shopping-cart me-1"></i><?= __('user.sales') ?></div>
                </div>
                <div class="mkt-stat-divider"></div>
                <div class="mkt-stat">
                    <div class="mkt-stat-val text-warning"><?= $clickratio ?>%</div>
                    <div class="mkt-stat-lbl"><i class="fas fa-mouse-pointer me-1"></i><?= __('user.clicks') ?></div>
                </div>
            </div>
            <div class="mkt-earnings d-flex flex-column justify-content-center gap-1">
                <div class="text-muted mb-1" style="font-size:.62rem;text-transform:uppercase;letter-spacing:.05em;"><i class="fas fa-coins text-warning me-1"></i><?= __('user.earnings') ?></div>
                <?php
                if($product['sale_commision_type'] == 'default'){
                    if($form_default_commission['product_commission_type'] == 'percentage'){
                        if($award_level_status == 1 && $userComission['status'] && $userComission['value'] && $userComission['value'] < $form_default_commission['product_commission'])
                            $product_commission = $userComission['value'];
                        else
                            $product_commission = $form_default_commission['product_commission'];
                        echo '<span class="badge bg-success">'.$product_commission.'% '.__('user.per_sale').'</span>';
                    } else if($form_default_commission['product_commission_type'] == 'Fixed'){
                        echo '<span class="badge bg-success">'.c_format($form_default_commission['product_commission']).' '.__('user.per_sale').'</span>';
                    }
                } else if($product['sale_commision_type'] == 'percentage'){
                    if($award_level_status == 1 && $userComission['status'] && $userComission['value'] && $userComission['value'] < $product['sale_commision_value'])
                        $sale_commision_value = $userComission['value'];
                    else
                        $sale_commision_value = $product['sale_commision_value'];
                    echo '<span class="badge bg-success">'.$sale_commision_value.'% '.__('user.per_sale').'</span>';
                } else if($product['sale_commision_type'] == 'fixed'){
                    echo '<span class="badge bg-success">'.c_format($product['sale_commision_value']).' '.__('user.per_sale').'</span>';
                }
                if($product['click_commision_type'] == 'default'){
                    if((int)$product['vendor_id']){
                        $vendor_setting = $this->db->query("SELECT * FROM vendor_setting WHERE user_id=".(int)$product['vendor_id']." ")->row();
                        echo '<span class="badge bg-info">'.c_format($vendor_setting->form_affiliate_click_amount).'/'. (int)$vendor_setting->form_affiliate_click_count.' '.__('user.clicks').'</span>';
                    } else {
                        if($form_default_commission['product_commission_type'] == 'percentage'){
                            echo '<span class="badge bg-info">'.c_format($form_default_commission['product_ppc']).'/'.$form_default_commission['product_noofpercommission'].' '.__('user.clicks').'</span>';
                        } else if($form_default_commission['product_commission_type'] == 'Fixed'){
                            echo '<span class="badge bg-info">'.c_format($form_default_commission['product_ppc']).'/'.$form_default_commission['product_noofpercommission'].' '.__('user.clicks').'</span>';
                        }
                    }
                } else if($product['click_commision_type'] == 'custom'){
                    echo '<span class="badge bg-info">'.c_format($product['click_commision_per']).'/'.$product['click_commision_ppc'].' '.__('user.clicks').'</span>';
                }
                ?>
            </div>
        </div>

        <!-- URL + Actions -->
        <div class="d-flex align-items-center gap-2 flex-wrap">
            <div class="input-group input-group-sm flex-fill">
                <input readonly value="<?= $mkt_promote_ok ? htmlspecialchars($shareUrl, ENT_QUOTES, 'UTF-8') : '' ?>" placeholder="<?= $mkt_promote_ok ? '' : htmlspecialchars(__('user.market_link_hidden_until_unlocked'), ENT_QUOTES, 'UTF-8') ?>" class="form-control mkt-url input-form-url-<?= $product['form_id'] ?>" <?= $mkt_promote_ok ? '' : 'disabled' ?> autocomplete="off">
                <button class="btn mkt-btn btn-copy" type="button" <?= $mkt_promote_ok ? 'copyToClipboard="'.htmlspecialchars($shareUrl, ENT_QUOTES, 'UTF-8').'"' : 'disabled' ?> data-bs-toggle="tooltip" title="<?= $mkt_promote_ok ? __('user.copied') : '' ?>">
                    <i class="far fa-copy"></i>
                </button>
            </div>
            <div class="d-flex gap-1 flex-shrink-0 align-items-center flex-wrap">
                <?php if ($mkt_promote_ok) { ?>
                <button class="btn mkt-btn btn-danger-soft" data-type="form" data-related-id="<?= $product['form_id'] ?>" data-input-class="input-form-url-<?= $product['form_id'] ?>" data-bs-toggle="tooltip" title="<?= __('user.slug_settings') ?>"><i class="fas fa-cog"></i></button>
                <a class="btn mkt-btn btn-primary-soft" target="_blank" href="<?= $product['public_page'] ?>" data-bs-toggle="tooltip" title="<?= __('user.preview') ?>"><i class="fas fa-external-link-alt"></i></a>
                <button class="btn mkt-btn btn-success-soft" onclick="generateCodeForm(<?= $product['form_id'] ?>,this);" data-bs-toggle="tooltip" title="<?= __('user.get_code') ?>"><i class="fas fa-code"></i></button>
                <button class="btn mkt-btn btn-info-soft get-downloads" onclick="downloadCode(this,<?= $product['form_id'] ?>,'form')" data-bs-toggle="tooltip" title="<?= __('user.download_cam_pack') ?>"><i class="fas fa-download"></i></button>
                <button class="btn mkt-btn btn-purple-soft qrcode" data-id="<?= $shareUrl ?>" data-bs-toggle="tooltip" title="<?= __('user.qrcode') ?>"><i class="fas fa-qrcode"></i></button>
                <button class="btn mkt-btn btn-warning-soft" data-social-share data-share-url="<?= $shareUrl ?>" data-share-title="<?= $product['title'] ?>" data-share-desc="<?= $product['description'] ?>" data-bs-toggle="tooltip" title="<?= __('user.share_campaign') ?>"><i class="fas fa-share-from-square"></i></button>
                <?php } else { ?>
                <a class="btn mkt-btn btn-primary-soft" target="_blank" href="<?= $product['public_page'] ?>" data-bs-toggle="tooltip" title="<?= __('user.preview') ?>"><i class="fas fa-external-link-alt"></i></a>
                <span class="btn mkt-btn btn-secondary-soft disabled text-secondary" data-bs-toggle="tooltip" data-bs-html="true" title="<?= htmlspecialchars($mkt_lock_msg, ENT_QUOTES, 'UTF-8') ?>"><i class="fas fa-lock me-1"></i><?= __('user.market_campaign_locked') ?></span>
                <?php } ?>
            </div>
        </div>
        <?php if (!$mkt_promote_ok) { ?>
        <div class="alert alert-warning border-0 py-2 px-3 mb-0 mt-2 small"><?= htmlspecialchars($mkt_lock_msg, ENT_QUOTES, 'UTF-8') ?></div>
        <?php } ?>

    </div>

    <!-- Details collapse -->
    <div class="collapse" id="<?= $collapseId ?>">
        <div class="border-top px-3 py-3 bg-light">
            <div class="row g-2">
                <div class="col-6 col-md-3">
                    <div class="mkt-detail-stat">
                        <div class="fw-bold text-success mb-0"><?= $product['all_count_commission'] ?></div>
                        <small class="text-muted"><?= __('user.sale_count') ?></small>
                    </div>
                </div>
                <div class="col-6 col-md-3">
                    <div class="mkt-detail-stat">
                        <div class="fw-bold text-success mb-0"><?= c_format($product['all_count_commission'] * ($product['sale_commision_value'] ?? 10)) ?></div>
                        <small class="text-muted"><?= __('user.sale_amount') ?></small>
                    </div>
                </div>
                <div class="col-6 col-md-3">
                    <div class="mkt-detail-stat">
                        <div class="fw-bold text-info mb-0"><?= $product['all_commition_click_count'] ?></div>
                        <small class="text-muted"><?= __('user.click_count') ?></small>
                    </div>
                </div>
                <div class="col-6 col-md-3">
                    <div class="mkt-detail-stat" style="background:#d1fae5;">
                        <div class="fw-bold text-success mb-0"><?= c_format($product['all_commition_click_count'] * ($product['click_commision_ppc'] ?? 1)) ?></div>
                        <small class="text-muted"><?= __('user.click_amount') ?></small>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php } else if(isset($product['is_product'])) { ?>
<?php
    if($product['is_campaign_product']) {
        $af_id = _encrypt_decrypt($userdetails['id']."-".$product['product_id']);
        $productLink = addParams($product['product_url'],"af_id",$af_id);
    } else {
        $productLink = base_url('store/'.base64_encode($userdetails['id']).'/product/'.$product['product_slug']);
    }
    $accentColor = '#0d6efd';
    $accentBg    = '#eff6ff';
    $typeIcon    = 'fas fa-box';
    $collapseId  = 'mktCollapse_'.$index;

    $views = $product['view_statistics'];
    $myViews = $product['my_view_statistics'] ?? 0;

    $ordercountratio = 0;
    if($product['view_statistics'] > 0)
        $ordercountratio = $product['all_order_count']*100/$product['view_statistics'];
    $ordercountratio = is_float($ordercountratio) ? number_format((float)$ordercountratio,1,'.',''): $ordercountratio;

    $clickratio = 0;
    $comissionclickcount = (int)$product['all_commition_click_count'];
    if($product['view_statistics'] > 0)
        $clickratio = $comissionclickcount*100/$product['view_statistics'];
    $clickratio = is_float($clickratio) ? number_format((float)$clickratio,1,'.',''): $clickratio;

    $shareUrl = $product['slug'] ? base_url($product['slug']) : $productLink;

    $mkt_promote_ok = $this->Product_model->user_can_promote_market_campaign($userdetails, $product);
    $mkt_min_h = (float)($product['min_health_score'] ?? 0);
    $mkt_cur_h = isset($userdetails['health_score']) && $userdetails['health_score'] !== '' && $userdetails['health_score'] !== null ? (float)$userdetails['health_score'] : 0.0;
    $mkt_lock_msg = sprintf(__('user.market_health_lock_detail'), number_format($mkt_min_h, 2, '.', ''), number_format($mkt_cur_h, 2, '.', ''));

    $imagePath = $product['product_featured_image'];
    $isExternal = preg_match('/^https?:\/\//', $imagePath);
    $imageSrc = $isExternal ? $imagePath : base_url('assets/images/product/upload/thumb/'.$imagePath);
    if(empty($imagePath)) $imageSrc = base_url('assets/images/no_product_image.png');
?>
<div class="mkt-card card border-0 shadow-sm mb-3 <?= $display_class ?>" style="border-left: 4px solid <?= $accentColor ?> !important;">
    <div class="card-body p-3">

        <!-- Header -->
        <div class="d-flex align-items-start gap-3 mb-3">
            <img class="mkt-thumb" src="<?= $imageSrc ?>" alt="" loading="lazy">
            <div class="flex-fill min-w-0">
                <h6 class="fw-bold mb-1 text-dark text-truncate"><?= $product['product_name'] ?></h6>
                <div class="d-flex align-items-center gap-2 flex-wrap">
                    <span class="mkt-type-badge" style="background:<?= $accentBg ?>;color:<?= $accentColor ?>;">
                        <i class="<?= $typeIcon ?> me-1"></i><?= __('user.campaign') ?>
                    </span>
                    <?php if($product['product_recursion_type']): ?>
                    <?php
                    if($product['product_recursion_type'] == 'custom'){
                        $pr = $product['product_recursion'] ?? '';
                        $recLabel = ($pr != 'custom_time') ? ($pr ? __('user.'.$pr) : __('user.life_time')) : timetosting($product['recursion_custom_time']);
                    } else {
                        $psRec = $pro_setting['product_recursion'] ?? '';
                        $recLabel = ($psRec == 'custom_time') ? timetosting($pro_setting['recursion_custom_time']) : ($psRec ? __('user.'.$psRec) : __('user.life_time'));
                    }
                    ?>
                    <span class="mkt-recurring badge bg-warning text-dark">
                        <i class="fas fa-sync-alt me-1"></i><?= __('user.recurring') ?>: <?= $recLabel ?>
                    </span>
                    <?php endif; ?>
                    <small class="text-muted ms-1" style="font-size:.7rem;"><i class="fas fa-eye me-1"></i><?= $myViews ?> <?= __('user.my_views') ?></small>
                </div>
            </div>
            <button class="btn mkt-details-btn flex-shrink-0" type="button" data-bs-toggle="collapse" data-bs-target="#<?= $collapseId ?>">
                <i class="fas fa-chart-bar me-1"></i><?= __('user.details') ?>
            </button>
        </div>

        <!-- Stats + Earnings -->
        <div class="d-flex align-items-stretch gap-2 mb-3 flex-wrap">
            <div class="mkt-stats-section mkt-stats flex-fill">
                <div class="mkt-stat">
                    <div class="mkt-stat-val text-primary"><?= $views ?></div>
                    <div class="mkt-stat-lbl"><i class="fas fa-eye me-1"></i><?= __('user.views') ?></div>
                </div>
                <div class="mkt-stat-divider"></div>
                <div class="mkt-stat">
                    <div class="mkt-stat-val text-success"><?= $ordercountratio ?>%</div>
                    <div class="mkt-stat-lbl"><i class="fas fa-shopping-cart me-1"></i><?= __('user.sales') ?></div>
                </div>
                <div class="mkt-stat-divider"></div>
                <div class="mkt-stat">
                    <div class="mkt-stat-val text-warning"><?= $clickratio ?>%</div>
                    <div class="mkt-stat-lbl"><i class="fas fa-mouse-pointer me-1"></i><?= __('user.clicks') ?></div>
                </div>
            </div>
            <div class="mkt-earnings d-flex flex-column justify-content-center gap-1">
                <div class="text-muted mb-1" style="font-size:.62rem;text-transform:uppercase;letter-spacing:.05em;"><i class="fas fa-coins text-warning me-1"></i><?= __('user.earnings') ?></div>
                <?php
                if($product['seller_id']){
                    $seller = $this->Product_model->getSellerFromProduct($product['product_id']);
                    $seller_setting = $this->Product_model->getSellerSetting($seller->user_id);
                    $commnent_line = "";
                    if($seller->affiliate_sale_commission_type == 'default'){
                        if($seller_setting->affiliate_sale_commission_type == ''){
                            $commnent_line .= __('user.warning_default_commission_not_set');
                        } else if($seller_setting->affiliate_sale_commission_type == 'percentage'){
                            if($award_level_status == 1 && $userComission['status'] && $userComission['value'] && $userComission['value'] < $seller_setting->affiliate_commission_value)
                                $affiliate_commission_value = $userComission['value'];
                            else
                                $affiliate_commission_value = (float)$seller_setting->affiliate_commission_value;
                            $commnent_line .= $affiliate_commission_value.'% '.__('user.per_sale');
                        } else if($seller_setting->affiliate_sale_commission_type == 'fixed'){
                            $commnent_line .= c_format($seller_setting->affiliate_commission_value).' '.__('user.per_sale');
                        }
                    } else if($seller->affiliate_sale_commission_type == 'percentage'){
                        if($award_level_status == 1 && $userComission['status'] && $userComission['value'] && $userComission['value'] < $seller->affiliate_commission_value)
                            $affiliate_commission_value = $userComission['value'];
                        else
                            $affiliate_commission_value = (float)$seller->affiliate_commission_value;
                        $commnent_line .= $affiliate_commission_value.'% '.__('user.per_sale');
                    } else if($seller->affiliate_sale_commission_type == 'fixed'){
                        $commnent_line .= c_format($seller->affiliate_commission_value).' '.__('user.per_sale');
                    }
                    echo '<span class="badge bg-success">'.$commnent_line.'</span>';
                    $commnent_line = "";
                    if($seller->affiliate_click_commission_type == 'default'){
                        $commnent_line .= c_format($seller_setting->affiliate_click_amount)."/". (int)$seller_setting->affiliate_click_count." ".__('user.clicks');
                    } else {
                        $commnent_line .= c_format($seller->affiliate_click_amount)."/". (int)$seller->affiliate_click_count." ".__('user.clicks');
                    }
                    echo '<span class="badge bg-info">'.$commnent_line.'</span>';
                } else {
                    if($product['product_commision_type'] == 'default'){
                        if($default_commition['product_commission_type'] == 'percentage'){
                            if($award_level_status == 1 && $userComission['status'] && $userComission['value'] && $userComission['value'] < $default_commition['product_commission'])
                                $product_commission = $userComission['value'];
                            else
                                $product_commission = $default_commition['product_commission'];
                            echo '<span class="badge bg-success">'.$product_commission.'% '.__('user.per_sale').'</span>';
                        } else {
                            echo '<span class="badge bg-success">'.c_format($default_commition['product_commission']).' '.__('user.per_sale').'</span>';
                        }
                    } else if($product['product_commision_type'] == 'percentage'){
                        if($award_level_status == 1 && $userComission['status'] && $userComission['value'] && $userComission['value'] < $product['product_commision_value'])
                            $product_commision_value = $userComission['value'];
                        else
                            $product_commision_value = $product['product_commision_value'];
                        echo '<span class="badge bg-success">'.$product_commision_value.'% '.__('user.per_sale').'</span>';
                    } else {
                        echo '<span class="badge bg-success">'.c_format($product['product_commision_value']).' '.__('user.per_sale').'</span>';
                    }
                    if($product['product_click_commision_type'] == 'default'){
                        echo '<span class="badge bg-info">'.c_format($default_commition['product_ppc']).'/'.$default_commition['product_noofpercommission'].' '.__('user.clicks').'</span>';
                    } else {
                        echo '<span class="badge bg-info">'.c_format($product['product_click_commision_ppc']).'/'.$product['product_click_commision_per'].' '.__('user.clicks').'</span>';
                    }
                }
                ?>
            </div>
        </div>

        <!-- URL + Actions -->
        <div class="d-flex align-items-center gap-2 flex-wrap">
            <div class="input-group input-group-sm flex-fill">
                <input readonly value="<?= $mkt_promote_ok ? htmlspecialchars($shareUrl, ENT_QUOTES, 'UTF-8') : '' ?>" placeholder="<?= $mkt_promote_ok ? '' : htmlspecialchars(__('user.market_link_hidden_until_unlocked'), ENT_QUOTES, 'UTF-8') ?>" class="form-control mkt-url input-product-url-<?= $product['product_id'] ?>" <?= $mkt_promote_ok ? '' : 'disabled' ?> autocomplete="off">
                <button class="btn mkt-btn btn-copy" type="button" <?= $mkt_promote_ok ? 'copyToClipboard="'.htmlspecialchars($shareUrl, ENT_QUOTES, 'UTF-8').'"' : 'disabled' ?> data-bs-toggle="tooltip" title="<?= $mkt_promote_ok ? __('user.copied') : '' ?>">
                    <i class="far fa-copy"></i>
                </button>
            </div>
            <div class="d-flex gap-1 flex-shrink-0 align-items-center flex-wrap">
                <?php if ($mkt_promote_ok) { ?>
                <a class="btn mkt-btn btn-danger-soft btn-model-slug" data-type="product" data-related-id="<?= $product['product_id'] ?>" data-input-class="input-product-url-<?= $product['product_id'] ?>" href="javascript:void(0);" data-bs-toggle="tooltip" title="<?= __('user.slug_settings') ?>"><i class="fas fa-cog"></i></a>
                <a class="btn mkt-btn btn-primary-soft" target="_blank" href="<?= $productLink ?>" data-bs-toggle="tooltip" title="<?= __('user.preview') ?>"><i class="fas fa-external-link-alt"></i></a>
                <a class="btn mkt-btn btn-success-soft" onclick="generateCode(<?= $product['product_id'] ?>,this);" href="javascript:void(0);" data-bs-toggle="tooltip" title="<?= __('user.get_code') ?>"><i class="fas fa-code"></i></a>
                <a class="btn mkt-btn btn-info-soft get-downloads" href="javascript:void(0)" onclick="downloadCode(this,<?= $product['product_id'] ?>,'product')" data-bs-toggle="tooltip" title="<?= __('user.download_cam_pack') ?>"><i class="fas fa-download"></i></a>
                <a class="btn mkt-btn btn-purple-soft qrcode" href="javascript:void(0)" data-id="<?= $shareUrl ?>" data-bs-toggle="tooltip" title="<?= __('user.qrcode') ?>"><i class="fas fa-qrcode"></i></a>
                <a class="btn mkt-btn btn-warning-soft" href="javascript:void(0);" data-social-share data-share-url="<?= $shareUrl ?>" data-share-title="<?= $product['product_name'] ?>" data-share-desc="<?= $product['product_short_description'] ?>" data-bs-toggle="tooltip" title="<?= __('user.share_campaign') ?>"><i class="fas fa-share-from-square"></i></a>
                <?php } else { ?>
                <a class="btn mkt-btn btn-primary-soft" target="_blank" href="<?= $productLink ?>" data-bs-toggle="tooltip" title="<?= __('user.preview') ?>"><i class="fas fa-external-link-alt"></i></a>
                <span class="btn mkt-btn btn-secondary-soft disabled text-secondary" data-bs-toggle="tooltip" data-bs-html="true" title="<?= htmlspecialchars($mkt_lock_msg, ENT_QUOTES, 'UTF-8') ?>"><i class="fas fa-lock me-1"></i><?= __('user.market_campaign_locked') ?></span>
                <?php } ?>
            </div>
        </div>
        <?php if (!$mkt_promote_ok) { ?>
        <div class="alert alert-warning border-0 py-2 px-3 mb-0 mt-2 small"><?= htmlspecialchars($mkt_lock_msg, ENT_QUOTES, 'UTF-8') ?></div>
        <?php } ?>

    </div>

    <!-- Details collapse -->
    <div class="collapse" id="<?= $collapseId ?>">
        <div class="border-top px-3 py-3 bg-light">
            <div class="row g-2">
                <div class="col-6 col-md-2">
                    <div class="mkt-detail-stat">
                        <div class="fw-bold text-primary mb-0"><?= c_format($product['product_price']) ?></div>
                        <small class="text-muted"><?= __('admin.price') ?></small>
                    </div>
                </div>
                <div class="col-6 col-md-2">
                    <div class="mkt-detail-stat">
                        <div class="fw-bold text-info mb-0"><?= $product['product_sku'] ?></div>
                        <small class="text-muted"><?= __('admin.sku') ?></small>
                    </div>
                </div>
                <div class="col-6 col-md-2">
                    <div class="mkt-detail-stat">
                        <div class="fw-bold text-success mb-0"><?= $product['order_count'] ?> / <?= c_format($product['commission']) ?></div>
                        <small class="text-muted"><?= __('admin.sales_/_commission') ?></small>
                    </div>
                </div>
                <div class="col-6 col-md-2">
                    <div class="mkt-detail-stat">
                        <div class="fw-bold text-warning mb-0"><?= (int)$product['commition_click_count'] ?> / <?= c_format($product['commition_click']) ?></div>
                        <small class="text-muted"><?= __('admin.clicks_/_commission') ?></small>
                    </div>
                </div>
                <div class="col-6 col-md-2">
                    <div class="mkt-detail-stat">
                        <div class="fw-bold mb-0 <?= $product['on_store'] == '1' ? 'text-success':'text-danger' ?>"><?= $product['on_store'] == '1' ? __('user.yes'):__('user.no') ?></div>
                        <small class="text-muted"><?= __('admin.display') ?></small>
                    </div>
                </div>
                <div class="col-6 col-md-2">
                    <div class="mkt-detail-stat" style="background:#d1fae5;">
                        <div class="fw-bold text-success mb-0"><?= c_format((float)$product['commition_click'] + (float)$product['commission']) ?></div>
                        <small class="text-muted"><?= __('admin.total') ?></small>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php } else { ?>
<?php
    $productLink = base_url('store/'.base64_encode($userdetails['id']).'/product/'.$product['product_slug']);
    $accentColor = '#0dcaf0';
    $accentBg    = '#ecfeff';
    $typeIcon    = 'fas fa-tools';
    $collapseId  = 'mktCollapse_'.$index;

    $views = $product['total_trigger_count'];
    $myViews = $product['my_view_statistics'] ?? 0;

    // Conversion ratio
    $conversionratio = 0;
    if($product['_tool_type'] == 'program' && $product['sale_status']){
        $totalratiocount = (int)$product['all_sale_count'];
        if($product['total_trigger_count'] > 0) $conversionratio = (int)($totalratiocount*100/$product['total_trigger_count']);
        $convLabel = __('user.sales'); $convColor = 'text-success';
    } else if($product['_tool_type'] == 'action' || $product['_tool_type'] == 'single_action'){
        $totalratiocount = (int)$product['all_action_click_count'];
        if($product['total_trigger_count'] > 0) $conversionratio = (int)($totalratiocount*100/$product['total_trigger_count']);
        $convLabel = __('user.actions'); $convColor = 'text-warning';
    } else if($product['_tool_type'] == 'general_click'){
        $totalratiocount = (int)$product['all_general_click_count'];
        if($product['total_trigger_count'] > 0) $conversionratio = (int)($totalratiocount*100/$product['total_trigger_count']);
        $convLabel = __('user.clicks'); $convColor = 'text-info';
    } else {
        $convLabel = __('user.conversion'); $convColor = 'text-secondary';
    }

    // Click ratio
    $clickratio = 0;
    if($product['_tool_type'] == 'program' && $product['click_status']){
        $totalratiocount2 = (int)$product['all_click_count'];
        if($product['total_trigger_count'] > 0) $clickratio = (int)($totalratiocount2*100/$product['total_trigger_count']);
    }

    $shareUrl = $product['slug'] ? base_url($product['slug']) : $product['redirectLocation'][0];

    $mkt_promote_ok = $this->Product_model->user_can_promote_market_campaign($userdetails, $product);
    $mkt_min_h = (float)($product['min_health_score'] ?? 0);
    $mkt_cur_h = isset($userdetails['health_score']) && $userdetails['health_score'] !== '' && $userdetails['health_score'] !== null ? (float)$userdetails['health_score'] : 0.0;
    $mkt_lock_msg = sprintf(__('user.market_health_lock_detail'), number_format($mkt_min_h, 2, '.', ''), number_format($mkt_cur_h, 2, '.', ''));

    $tool_img = base_url('assets/images/no_product_image.png');
    if(!empty($product['featured_image']) && file_exists(FCPATH.'assets/images/product/upload/thumb/'.$product['featured_image'])) {
        $tool_img = base_url('assets/images/product/upload/thumb/'.$product['featured_image']);
    } elseif(!empty($product['featured_image']) && preg_match('/^https?:\/\//', $product['featured_image'])) {
        $tool_img = $product['featured_image'];
    }
?>
<div class="mkt-card card border-0 shadow-sm mb-3 <?= $display_class ?>" style="border-left: 4px solid <?= $accentColor ?> !important;">
    <div class="card-body p-3">

        <!-- Header -->
        <div class="d-flex align-items-start gap-3 mb-3">
            <div class="mkt-thumb d-flex align-items-center justify-content-center" style="background:<?= $accentBg ?>; overflow:hidden;">
                <img class="w-100 h-100" style="object-fit:contain;" src="<?= $tool_img ?>" alt="" loading="lazy">
            </div>
            <div class="flex-fill min-w-0">
                <h6 class="fw-bold mb-1 text-dark text-truncate"><?= $product['name'] ?></h6>
                <div class="d-flex align-items-center gap-2 flex-wrap">
                    <span class="mkt-type-badge" style="background:<?= $accentBg ?>;color:#0891b2;">
                        <i class="<?= $typeIcon ?> me-1"></i><?= __('user.campaign') ?>
                    </span>
                    <?php if($product['recursion']): ?>
                    <?php 
                    $recVal = $product['recursion'] ?? '';
                    $recLabel2 = ($recVal != 'custom_time') ? ($recVal ? __('user.'.$recVal) : __('user.life_time')) : timetosting($product['recursion_custom_time'] ?? 0); 
                    ?>
                    <span class="mkt-recurring badge bg-secondary">
                        <i class="fas fa-sync-alt me-1"></i><?= __('user.recurring') ?>: <?= $recLabel2 ?>
                    </span>
                    <?php endif; ?>
                    <small class="text-muted ms-1" style="font-size:.7rem;"><i class="fas fa-eye me-1"></i><?= $myViews ?> <?= __('user.my_views') ?></small>
                </div>
            </div>
            <button class="btn mkt-details-btn flex-shrink-0" type="button" data-bs-toggle="collapse" data-bs-target="#<?= $collapseId ?>">
                <i class="fas fa-chart-bar me-1"></i><?= __('user.details') ?>
            </button>
        </div>

        <!-- Stats + Earnings -->
        <div class="d-flex align-items-stretch gap-2 mb-3 flex-wrap">
            <div class="mkt-stats-section mkt-stats flex-fill">
                <div class="mkt-stat">
                    <div class="mkt-stat-val text-primary"><?= $views ?></div>
                    <div class="mkt-stat-lbl"><i class="fas fa-chart-line me-1"></i><?= __('user.triggers') ?></div>
                </div>
                <div class="mkt-stat-divider"></div>
                <div class="mkt-stat">
                    <div class="mkt-stat-val <?= $convColor ?>"><?= $conversionratio ?>%</div>
                    <div class="mkt-stat-lbl"><?= $convLabel ?></div>
                </div>
                <?php if($product['_tool_type'] == 'program' && $product['click_status']): ?>
                <div class="mkt-stat-divider"></div>
                <div class="mkt-stat">
                    <div class="mkt-stat-val text-secondary"><?= $clickratio ?>%</div>
                    <div class="mkt-stat-lbl"><i class="fas fa-external-link-alt me-1"></i><?= __('user.p_clicks') ?></div>
                </div>
                <?php endif; ?>
            </div>
            <div class="mkt-earnings d-flex flex-column justify-content-center gap-1">
                <div class="text-muted mb-1" style="font-size:.62rem;text-transform:uppercase;letter-spacing:.05em;"><i class="fas fa-coins text-warning me-1"></i><?= __('user.earnings') ?></div>
                <?php
                if($product['_tool_type'] == 'program' && $product['sale_status']){
                    $comm = '';
                    if($product['commission_type'] == 'percentage'){
                        if($award_level_status == 1 && $userComission['status'] && $userComission['value'] && $userComission['value'] < $product['commission_sale'])
                            $commission_sale = $userComission['value'];
                        else
                            $commission_sale = $product['commission_sale'];
                        $comm = $commission_sale.'% '.__('user.per_sale');
                    } else if($product['commission_type'] == 'fixed'){
                        $comm = c_format($product['commission_sale']).' '.__('user.per_sale');
                    }
                    echo '<span class="badge bg-success">'.$comm.'</span>';
                }
                if($product['_tool_type'] == 'program' && $product['click_status']){
                    echo '<span class="badge bg-info">'.c_format($product["commission_click_commission"]).'/'.$product['commission_number_of_click'].' '.__('user.clicks').'</span>';
                }
                if($product['_tool_type'] == 'general_click'){
                    echo '<span class="badge bg-info">'.c_format($product["general_amount"]).'/'.$product['general_click'].' '.__('user.clicks').'</span>';
                }
                if($product['_tool_type'] == 'action' || $product['_tool_type'] == 'single_action'){
                    echo '<span class="badge bg-warning text-dark">'.c_format($product["action_amount"]).'/'.$product['action_click'].' '.__('user.actions').'</span>';
                }
                ?>
            </div>
        </div>

        <!-- URL + Actions -->
        <div class="d-flex align-items-center gap-2 flex-wrap">
            <div class="input-group input-group-sm flex-fill">
                <input readonly value="<?= $mkt_promote_ok ? htmlspecialchars($shareUrl, ENT_QUOTES, 'UTF-8') : '' ?>" placeholder="<?= $mkt_promote_ok ? '' : htmlspecialchars(__('user.market_link_hidden_until_unlocked'), ENT_QUOTES, 'UTF-8') ?>" class="form-control mkt-url input-<?= $product['_tool_type'] ?>-url-<?= $product['id'] ?>" <?= $mkt_promote_ok ? '' : 'disabled' ?> autocomplete="off">
                <button class="btn mkt-btn btn-copy" type="button" <?= $mkt_promote_ok ? 'copyToClipboard="'.htmlspecialchars($shareUrl, ENT_QUOTES, 'UTF-8').'"' : 'disabled' ?> data-bs-toggle="tooltip" title="<?= $mkt_promote_ok ? __('user.copied') : '' ?>">
                    <i class="far fa-copy"></i>
                </button>
            </div>
            <div class="d-flex gap-1 flex-shrink-0 align-items-center flex-wrap">
                <?php if ($mkt_promote_ok) { ?>
                <a class="btn mkt-btn btn-danger-soft btn-model-slug" data-type="<?= $product['_tool_type'] ?>" data-related-id="<?= $product['id'] ?>" data-input-class="input-<?= $product['_tool_type'] ?>-url-<?= $product['id'] ?>" href="javascript:void(0);" data-bs-toggle="tooltip" title="<?= __('user.slug_settings') ?>"><i class="fas fa-cog"></i></a>
                <a class="btn mkt-btn btn-primary-soft get-terms" data-id="<?= $product['id'] ?>" href="javascript:void(0);" data-bs-toggle="tooltip" title="<?= __('user.terms') ?>"><i class="fas fa-external-link-alt"></i></a>
                <a class="btn mkt-btn btn-success-soft get-code" data-id="<?= $product['id'] ?>" href="javascript:void(0);" data-bs-toggle="tooltip" title="<?= __('user.get_code') ?>"><i class="fas fa-code"></i></a>
                <a class="btn mkt-btn btn-info-soft get-downloads" href="javascript:void(0)" onclick="downloadCode(this,<?= $product['id'] ?>,'tool')" data-bs-toggle="tooltip" title="<?= __('user.download_cam_pack') ?>"><i class="fas fa-download"></i></a>
                <a class="btn mkt-btn btn-purple-soft qrcode" href="javascript:void(0)" data-id="<?= $shareUrl ?>" data-bs-toggle="tooltip" title="<?= __('user.qrcode') ?>"><i class="fas fa-qrcode"></i></a>
                <a class="btn mkt-btn btn-warning-soft" href="javascript:void(0);" data-social-share data-share-url="<?= $shareUrl ?>" data-share-title="<?= $product['name'] ?>" data-bs-toggle="tooltip" title="<?= __('user.share_campaign') ?>"><i class="fas fa-share-from-square"></i></a>
                <?php } else { ?>
                <a class="btn mkt-btn btn-primary-soft get-terms" data-id="<?= $product['id'] ?>" href="javascript:void(0);" data-bs-toggle="tooltip" title="<?= __('user.terms') ?>"><i class="fas fa-external-link-alt"></i></a>
                <span class="btn mkt-btn btn-secondary-soft disabled text-secondary" data-bs-toggle="tooltip" data-bs-html="true" title="<?= htmlspecialchars($mkt_lock_msg, ENT_QUOTES, 'UTF-8') ?>"><i class="fas fa-lock me-1"></i><?= __('user.market_campaign_locked') ?></span>
                <?php } ?>
            </div>
        </div>
        <?php if (!$mkt_promote_ok) { ?>
        <div class="alert alert-warning border-0 py-2 px-3 mb-0 mt-2 small"><?= htmlspecialchars($mkt_lock_msg, ENT_QUOTES, 'UTF-8') ?></div>
        <?php } ?>

    </div>

    <!-- Details collapse -->
    <div class="collapse" id="<?= $collapseId ?>">
        <div class="border-top px-3 py-3 bg-light">
            <div class="row g-2">
                <?php if($product['_tool_type'] == 'program' && $product['sale_status']): ?>
                <div class="col-6 col-md-3">
                    <div class="mkt-detail-stat">
                        <div class="fw-bold text-success mb-0"><?= (int)$product['total_sale_count'] ?></div>
                        <small class="text-muted"><?= __('user.sale_count') ?></small>
                    </div>
                </div>
                <div class="col-6 col-md-3">
                    <div class="mkt-detail-stat">
                        <div class="fw-bold text-success mb-0"><?= c_format((int)$product['total_sale_amount']) ?></div>
                        <small class="text-muted"><?= __('user.sale_amount') ?></small>
                    </div>
                </div>
                <?php endif; if($product['_tool_type'] == 'program' && $product['click_status']): ?>
                <div class="col-6 col-md-3">
                    <div class="mkt-detail-stat">
                        <div class="fw-bold text-info mb-0"><?= (int)$product['total_click_count'] ?></div>
                        <small class="text-muted"><?= __('user.click_count') ?></small>
                    </div>
                </div>
                <div class="col-6 col-md-3">
                    <div class="mkt-detail-stat" style="background:#d1fae5;">
                        <div class="fw-bold text-success mb-0"><?= (int)$product['total_click_amount'] ?></div>
                        <small class="text-muted"><?= __('user.click_amount') ?></small>
                    </div>
                </div>
                <?php endif; if($product['_tool_type'] == 'general_click'): ?>
                <div class="col-6">
                    <div class="mkt-detail-stat">
                        <div class="fw-bold text-info mb-0"><?= (int)$product['total_general_click_count'] ?></div>
                        <small class="text-muted"><?= __('user.general_count') ?></small>
                    </div>
                </div>
                <div class="col-6">
                    <div class="mkt-detail-stat" style="background:#d1fae5;">
                        <div class="fw-bold text-success mb-0"><?= $product['total_general_click_amount'] ?></div>
                        <small class="text-muted"><?= __('user.general_amount') ?></small>
                    </div>
                </div>
                <?php endif; if($product['_tool_type'] == 'action' || $product['_tool_type'] == 'single_action'): ?>
                <div class="col-6">
                    <div class="mkt-detail-stat">
                        <div class="fw-bold text-warning mb-0"><?= (int)$product['total_action_click_count'] ?></div>
                        <small class="text-muted"><?= __('user.action_count') ?></small>
                    </div>
                </div>
                <div class="col-6">
                    <div class="mkt-detail-stat" style="background:#d1fae5;">
                        <div class="fw-bold text-success mb-0"><?= $product['total_action_click_amount'] ?></div>
                        <small class="text-muted"><?= __('user.action_amount') ?></small>
                    </div>
                </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<?php } ?>
<?php } ?>

<?php if(!empty($pagination)): ?>
<div class="d-flex justify-content-center mt-4">
    <nav aria-label="Page navigation"><?= $pagination ?></nav>
</div>
<?php endif; ?>

<style>
.modal { z-index: 1050 !important; }
.modal-backdrop { z-index: 1040 !important; }
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
    tooltipTriggerList.map(function(el){ return new bootstrap.Tooltip(el); });
});
</script>
