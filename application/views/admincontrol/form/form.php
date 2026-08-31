<link href="<?php echo base_url('assets/template/css/datepicker.css'); ?>" rel="stylesheet" type="text/css" />
<script src="<?php echo base_url('assets/template/js/bootstrap-datepicker.js'); ?>"></script>
      
<form id="form_form">
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="card shadow-sm">
                    <div class="card-header bg-primary text-white py-3">
                        <div class="d-flex align-items-center">
                            <i class="bi bi-wpforms me-2"></i>
                            <h5 class="mb-0 fw-semibold"><?= __('admin.form') ?></h5>
                        </div>
                    </div>
                    <div class="card-body p-4">
                    <input type="hidden" class="form-control" name="id" value="<?= (int)$form['form_id'] ?>">
                    <input type="hidden" class="form-control redirect" name="redirect" value="">
                        <div class="row g-3 mb-4">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label fw-medium"><?= __('admin.title'); ?></label>
                                    <input type="text" class="form-control" name="title" value="<?= $form['title'] ?>" placeholder="<?= __('admin.enter_form_title') ?>">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label fw-medium"><?= __('admin.seo_title'); ?></label>
                                    <input type="text" class="form-control" name="seo" value="<?= $form['seo'] ?>" placeholder="<?= __('admin.enter_seo_title') ?>">
                                    <span class="text-danger seo_error"></span>
                                </div>
                            </div>
                        </div>
                        <div class="mb-4">
                            <label class="form-label fw-medium"><?= __('admin.body_content'); ?></label>
                            <textarea data-height="300px" rows="3" placeholder="<?= __('admin.enter_form_content') ?>" class="form-control body_content summernote-img" name="description" type="text"><?= $form['description'] ?></textarea>
                        </div>

                        <div class="card border-0 bg-light mb-4">
                            <div class="card-header bg-transparent border-0 pb-0">
                                <h6 class="mb-0 fw-semibold text-dark">
                                    <i class="bi bi-arrow-repeat me-2"></i><?= __('admin.form_recursion') ?>
                                </h6>
                            </div>
                            <div class="card-body">
                                <div class="mb-3">
                                    <label class="form-label fw-medium"><?= __('admin.form_recursion') ?></label>
                                    <?php                                          
                                        $form_recursion_type = $form['form_recursion_type'];
                                        $form_recursion = $form['form_recursion'];
                                    ?>
                                    <select name="form_recursion_type" class="form-select">
                                        <option <?= '' == $form_recursion_type ? 'selected' : '' ?> value=""><?= __('admin.none') ?></option>
                                        <option <?= 'default' == $form_recursion_type ? 'selected' : '' ?> value="default"><?= __('admin.default') ?></option>
                                        <option <?= 'custom' == $form_recursion_type ? 'selected' : '' ?> value="custom"><?= __('admin.custom') ?></option>
                                    </select>                           
                                </div>
                            <div class="toggle-container mt-2">
                                <div class="d-none default-value">
                                    <small class="text-muted">
                                        <?php
                                            if($setting['form_recursion'] == 'custom_time'){
                                                if ($setting['recursion_endtime'] == NULL || $setting['recursion_endtime'] == '') {
                                                    echo __('admin.default_recursion')." : " . timetosting($setting['recursion_custom_time']). " | ".__('admin.endtime')." : ".__('admin.life_time');
                                                }else{
                                                    echo __('admin.default_recursion')." : " . timetosting($setting['recursion_custom_time']). " | ".__('admin.endtime')." : " . dateFormat($setting['recursion_endtime']);
                                                }
                                            }else{
                                                if ($setting['recursion_endtime'] == NULL || $setting['recursion_endtime'] == '') {
                                                    echo __('admin.default_recursion')." : " . __('admin.'.$setting['form_recursion']) . " | ".__('admin.endtime')." : ".__('admin.life_time');
                                                }else{
                                                    echo __('admin.default_recursion')." : " . __('admin.'.$setting['form_recursion']) . " | ".__('admin.endtime')." : " . dateFormat($setting['recursion_endtime']);
                                                }
                                            }
                                        ?>
                                    </small>
                                </div>
                                <div class="d-none custom-value">
                                    <div class="custom_recursion">
                                        <select name="form_recursion" class="form-select" id="recursion_type">
                                            <option value=""><?=  __('admin.select_recursion') ?></option>
                                            <option <?php if($form_recursion == 'every_day') { ?> selected <?php } ?> value="every_day"><?=  __('admin.every_day') ?></option>
                                            <option <?php if($form_recursion == 'every_week') { ?> selected <?php } ?>  value="every_week"><?=  __('admin.every_week') ?></option>
                                            <option <?php if($form_recursion == 'every_month') { ?> selected <?php } ?>  value="every_month"><?=  __('admin.every_month') ?></option>
                                            <option <?php if($form_recursion == 'every_year') { ?> selected <?php } ?>  value="every_year"><?=  __('admin.every_year') ?></option>
                                            <option <?php if($form_recursion == 'custom_time') { ?> selected <?php } ?>  value="custom_time"><?=  __('admin.custom_time') ?></option>
                                        </select>
                                        
                                        <div class="mt-3 custom_time">      
                                            <?php
                                                $minutes = $form['recursion_custom_time'];
                                                $day = floor ($minutes / 1440);
                                                $hour = floor (($minutes - $day * 1440) / 60);
                                                $minute = $minutes - ($day * 1440) - ($hour * 60);
                                            ?>
                                            <input type="hidden" name="recursion_custom_time" value="<?php echo $minutes; ?>">
                                            <div class="row g-3">
                                                <div class="col-md-4">
                                                    <label class="form-label fw-medium"><?=  __('admin.days') ?></label>
                                                    <input placeholder="<?= __('admin.days') ?>" type="number" class="form-control" value="<?= $day ? $day : '' ?>" id="recur_day" onkeydown="if(event.key==='.'){event.preventDefault();}"  oninput="event.target.value = event.target.value.replace(/[^0-9]*/g,'');">
                                                </div>                      
                                                <div class="col-md-4">
                                                    <label class="form-label fw-medium"><?=  __('admin.hours') ?></label>
                                                    <select class="form-select" id="recur_hour">
                                                        <?php 
                                                        for ($x = 0; $x <= 23; $x++) {
                                                            $selected = ($x == $hour ) ? 'selected="selected"' : '';
                                                            echo '<option value="'.$x.'" '.$selected.'>'.$x.'</option>';
                                                        }
                                                        ?>
                                                    </select>
                                                </div>                      
                                                <div class="col-md-4">
                                                    <label class="form-label fw-medium"><?=  __('admin.minutes') ?></label>
                                                    <select class="form-select" id="recur_minute">
                                                        <?php 
                                                        for ($x = 0; $x <= 59; $x++) {
                                                            $selected = ($x == $minute ) ? 'selected="selected"' : '';
                                                            echo '<option value="'.$x.'" '.$selected.'>'.$x.'</option>';
                                                        }
                                                        ?>
                                                    </select>
                                                </div>                      
                                            </div>                                  
                                        </div>
                                        <div class="mt-3">
                                            <div class="form-check">
                                                <input class="form-check-input" <?= $form['recursion_endtime'] ? 'checked' : '' ?> id='setCustomTime' name='recursion_endtime_status' type="checkbox">
                                                <label class="form-check-label fw-medium" for="setCustomTime">
                                                    <?= __('admin.choose_custom_endtime') ?>
                                                </label>
                                            </div>
                                            <div style="<?= !$form['recursion_endtime'] ? 'display:none' : '' ?>" class='custom_time_container mt-2'>
                                                <input type="text" class="form-control" value="<?= $form['recursion_endtime'] ? date("d-m-Y H:i",strtotime($form['recursion_endtime'])) : '' ?>" name="recursion_endtime" id="endtime" placeholder="<?= __('admin.choose_endtime') ?>">
                                            </div>
                                        </div>
                                    </div>   
                                </div>
                            </div>
                        </div>

                            <script type="text/javascript">
                                $("select[name=form_recursion_type]").on("change",function(){
                                    $con = $(this).closest(".card-body");
                                    $con.find(".toggle-container .custom-value, .toggle-container .default-value").addClass('d-none');

                                    if($(this).val() == 'default'){
                                        $con.find(".toggle-container .default-value").removeClass("d-none");
                                    }else if($(this).val() == 'custom'){
                                        $con.find(".toggle-container .custom-value").removeClass("d-none");
                                    }
                                })
                                $("select[name=form_recursion_type]").trigger("change");


                                $("select[name=form_recursion]").on("change",function(){
                                    $con = $(this).parents(".custom_recursion");
                                    $con.find(".custom_time").addClass('d-none');

                                    if($(this).val() == 'custom_time'){
                                        $con.find(".custom_time").removeClass("d-none");
                                    }
                                })
                                $("select[name=form_recursion]").trigger("change");
                            </script>
                        </div>
                    </fieldset>

                        <div class="row g-3 mb-4">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label fw-medium"><?= __('admin.form_feature_image') ?></label>
                                    <div class="d-flex align-items-center gap-3">
                                        <div class="fileUpload btn btn-outline-primary btn-sm">
                                            <i class="bi bi-upload me-1"></i><?= __('admin.choose_file') ?>
                                            <input id="form_fevi_icon" onchange="readURL(this,'#form_fevi_icon-img')" name="form_fevi_icon" class="upload" type="file">
                                        </div>
                                        <?php $form_fevi_icon = $form['fevi_icon'] != '' ? 'assets/images/form/favi/'.$form['fevi_icon'] : 'assets/images/no_image_available.png' ; ?>
                                        <img src="<?php echo base_url($form_fevi_icon); ?>" id="form_fevi_icon-img" class="img-thumbnail" style="width: 120px; height: 120px; object-fit: cover;">
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label fw-medium"><?= __('admin.allow_for_product'); ?></label>
                                    <select class="form-select" name="allow_for">
                                        <option value="A"><?= __('admin.all'); ?></option>
                                        <option value="S" <?= $form['allow_for'] == 'S' ? 'selected': '' ?>><?= __('admin.selected_only') ?></option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="row g-3 mb-4">
                            <div class="col-md-4">
                                <label class="form-label fw-medium"><?= __('admin.min_health_score_promote') ?></label>
                                <input type="number" step="0.01" min="0" max="100" class="form-control" name="min_health_score" value="<?= htmlspecialchars((string)($form['min_health_score'] ?? '0'), ENT_QUOTES, 'UTF-8') ?>">
                                <div class="form-text"><?= __('admin.min_health_score_promote_hint') ?></div>
                            </div>
                            <?php if (!empty($award_level_status) && !empty($award_levels_list)) { ?>
                            <div class="col-md-8">
                                <label class="form-label fw-medium d-inline-flex align-items-center flex-wrap gap-2 mb-1">
                                    <?= __('admin.min_award_level_promote') ?>
                                    <button type="button" class="btn btn-link btn-sm text-secondary p-0 lh-1 border-0 align-baseline" data-bs-toggle="tooltip" data-bs-placement="top" data-bs-container="body" title="<?= htmlspecialchars(__('admin.min_award_level_hierarchy_tooltip'), ENT_QUOTES, 'UTF-8') ?>" aria-label="<?= htmlspecialchars(__('admin.min_award_level_hierarchy_tooltip'), ENT_QUOTES, 'UTF-8') ?>">
                                        <i class="bi bi-question-circle" aria-hidden="true"></i>
                                    </button>
                                </label>
                                <select class="form-select" name="min_award_level_id">
                                    <option value=""><?= __('admin.min_award_level_promote_none') ?></option>
                                    <?php foreach ($award_levels_list as $alv) { ?>
                                        <option value="<?= (int)$alv['id'] ?>" <?= (isset($form['min_award_level_id']) && (int)$form['min_award_level_id'] === (int)$alv['id']) ? 'selected' : '' ?>><?= htmlspecialchars($alv['level_number'], ENT_QUOTES, 'UTF-8') ?></option>
                                    <?php } ?>
                                </select>
                                <div class="form-text"><?= __('admin.min_award_level_promote_hint') ?></div>
                            </div>
                            <?php } ?>
                        </div>

                        <div class="select-product mb-4">
                            <div class="card border-0 bg-light">
                                <div class="card-header bg-transparent border-0 pb-0">
                                    <h6 class="mb-0 fw-semibold text-dark">
                                        <i class="bi bi-box-seam me-2"></i><?= __('admin.select_products') ?>
                                    </h6>
                                </div>
                                <div class="card-body">
                                    <div class="table-responsive">
                                        <table class="table table-hover">
                                            <thead class="table-light">
                                                <tr>
                                                    <th class="fw-semibold"><?= __('admin.name') ?></th>
                                                    <th class="fw-semibold"><?= __('admin.price') ?></th>
                                                    <th class="fw-semibold"><?= __('admin.type') ?></th>
                                                    <th class="fw-semibold"><?= __('admin.allow_shipping') ?></th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php $ids =explode(",", $form['product']);
                                                foreach ($product as $key => $p) { ?>
                                                    <tr>
                                                        <td>
                                                            <div class="form-check">
                                                                <input class="form-check-input" type="checkbox" <?= in_array($p['product_id'], $ids) ? 'checked' : '' ?> name="product[]" value="<?= $p['product_id'] ?>" id="product_<?= $p['product_id'] ?>">
                                                                <label class="form-check-label" for="product_<?= $p['product_id'] ?>">
                                                                    <?= $p['product_name'] ?>
                                                                </label>
                                                            </div>
                                                        </td>
                                                        <td><?= c_format($p['product_price']) ?></td>
                                                        <td><?= product_type($p['product_type']) ?></td>
                                                        <td>
                                                            <span class="badge <?= $p['allow_shipping'] ? 'bg-success' : 'bg-secondary' ?>">
                                                                <?= $p['allow_shipping'] ? __('admin.yes') : __('admin.no') ?>
                                                            </span>
                                                        </td>
                                                    </tr>
                                                <?php } ?>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                  
                        <div class="row g-3 mb-4">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label fw-medium"><?= __('admin.coupon'); ?></label>
                                    <select class="form-select" name="coupon">
                                        <option value=""><?= __('admin.no_selected'); ?></option>
                                        <?php foreach ($coupons as $key => $value) { ?>
                                            <option value="<?= $value['form_coupon_id'] ?>" <?= $value['form_coupon_id'] == $form['coupon'] ? 'selected': '' ?>><?= $value['name'] ?></option>
                                        <?php } ?>                                            
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label fw-medium"><?= __('admin.footer_content'); ?></label>
                                    <input type="text" class="form-control" name="footer_title" value="<?= $form['footer_title'] ?>" placeholder="<?= __('admin.enter_footer_content') ?>">
                                </div>
                            </div>
                        </div>

                        <div class="mb-4">
                            <label class="form-label fw-medium"><?= __('admin.footer_google_analitics'); ?></label>
                            <textarea rows="4" class="form-control" name="google_analitics" placeholder="<?= __('admin.enter_google_analytics_code') ?>"><?= $form['google_analitics'] ?></textarea>                                        
                        </div>
                </div>
            </div>
        </div>
        <div class="col-12">
            <div class="card shadow-sm mb-3">
                <div class="card-header bg-primary text-white py-3">
                    <div class="d-flex align-items-center">
                        <i class="bi bi-percent me-2"></i>
                        <h5 class="mb-0 fw-semibold"><?= __('admin.commission_settings') ?></h5>
                    </div>
                </div>
                <div class="card-body p-4">
                    <div class="row g-4">
                        <div class="col-12">
                            <div class="card border-0 bg-light">
                                <div class="card-header bg-transparent border-bottom pb-3">
                                    <h6 class="mb-0 fw-semibold text-success">
                                        <i class="fas fa-dollar-sign me-2"></i><?= __('admin.form_sale_commission') ?>
                                    </h6>
                                </div>
                                <div class="card-body">
                                    <div class="mb-3">
                                        <label class="form-label fw-medium"><?= __('admin.commission_type') ?></label>
                                <?php
                                    $selected_commition_type = $form['sale_commision_type'];
                                    $selected_commision_value = $form['sale_commision_value'];
                                    $commission_type= array(
                                        'default'    => __('admin.default'),
                                        'percentage' => __('admin.percentage').' (%)',
                                        'fixed'      => __('admin.fixed'),
                                    );
                                ?>
                                <select name="form_commision_type" class="form-select showonchange">
                                    <?php foreach ($commission_type as $key => $value) { ?>
                                        <option <?= $key == $selected_commition_type ? 'selected' : '' ?> value="<?= $key ?>"><?= $value ?></option>
                                    <?php } ?>
                                </select>
                                    </div>

                                    <div class="toggle-container">
                                        <div class="default-value">
                                            <div class="alert alert-info mb-0">
                                                <small>
                                                    <?php
                                                        $commnent_line = "<b>".__('admin.default_commission').": </b>";
                                                        if($setting['product_commission_type'] == ''){
                                                            $commnent_line .= __('admin.default_commission_warning');
                                                        }
                                                        else if($setting['product_commission_type'] == 'percentage'){
                                                            $commnent_line .= __('admin.percentage')." : ". (float)$setting['product_commission'] .'%';
                                                        }
                                                        else if($setting['product_commission_type'] == 'Fixed'){
                                                            $commnent_line .= __('admin.fixed')." : ". c_format($setting['product_commission']);
                                                        }
                                                        echo $commnent_line;
                                                    ?>
                                                </small>
                                            </div>
                                        </div>
                                        <div class="fixed-value">
                                            <div class="mb-3">
                                                <label class="form-label fw-medium"><?= __('admin.commission_value') ?></label>
                                                <input placeholder="<?= __('admin.enter_form_sale_comission_value') ?>" name="form_commision_value" id="form_commision_value" class="form-control" value="<?php echo $selected_commision_value; ?>" type="text">
                                            </div>
                                        </div>
                                    </div>

                                    <script type="text/javascript">
                                        $("select[name=form_commision_type]").on("change",function(){
                                            $con = $(this).closest(".card-body");
                                            $con.find(".toggle-container .fixed-value, .toggle-container .default-value").addClass('d-none');

                                            if($(this).val() == 'default'){
                                                $con.find(".toggle-container .default-value").removeClass("d-none");
                                            }else{
                                                $con.find(".toggle-container .fixed-value").removeClass("d-none");
                                            }
                                        })
                                        $("select[name=form_commision_type]").trigger("change");
                                    </script>
                                </div>
                            </div>
                        </div>

                        <div class="col-12">
                            <div class="card border-0 bg-light">
                                <div class="card-header bg-transparent border-bottom pb-3">
                                    <h6 class="mb-0 fw-semibold text-primary">
                                        <i class="fas fa-mouse-pointer me-2"></i><?= __('admin.form_click_commission') ?>
                                    </h6>
                                </div>
                                <div class="card-body">
                                    <div class="mb-3">
                                        <label class="form-label fw-medium"><?= __('admin.commission_type') ?></label>
                                <?php
                                    $selected_commition_type = $form['click_commision_type'];
                                    $form_click_commision_ppc = $form['click_commision_ppc'];
                                    $form_click_commision_per = $form['click_commision_per'];
                                ?>
                                <select name="form_click_commision_type" class="form-select showonchange">
                                    <option <?= 'default' == $selected_commition_type ? 'selected' : '' ?> value="default"><?= __('admin.default') ?></option>
                                    <option <?= 'custom' == $selected_commition_type ? 'selected' : '' ?> value="custom"><?= __('admin.custom') ?></option>
                                </select>
                                    </div>

                                    <div class="toggle-container">                                            
                                        <div class="default-value">
                                            <div class="alert alert-info mb-0">
                                                <small>
                                                    <?php
                                                        $commnent_line = "<b>".__('admin.default_commission').": </b>";
                                                        if($setting['product_ppc'] && $setting['product_noofpercommission']){
                                                            $commnent_line .= c_format($setting['product_ppc']) .' '. __('admin.per') .' '. (int)$setting['product_noofpercommission'] .' '. __('admin.clicks');
                                                        } else{
                                                            $commnent_line .= __('admin.warning_default_commission_not_set');
                                                        }
                                                        echo $commnent_line;
                                                    ?>
                                                </small>
                                            </div>
                                        </div>
                                        <div class="fixed-value">
                                            <div class="row g-3">
                                                <div class="col-md-6">
                                                    <label class="form-label fw-medium"><?= __('admin.clicks') ?></label>
                                                    <div class="input-group">
                                                        <span class="input-group-text"><i class="fas fa-mouse-pointer"></i></span>
                                                        <input placeholder="<?= __('admin.clicks') ?>" name="form_click_commision_ppc" id="form_click_commision_ppc" class="form-control" value="<?php echo $form_click_commision_ppc; ?>" type="number">
                                                    </div>
                                                    <small class="text-muted"><?= __('admin.number_of_clicks') ?></small>
                                                </div>
                                                <div class="col-md-6">
                                                    <label class="form-label fw-medium"><?= __('admin.amount') ?></label>
                                                    <div class="input-group">
                                                        <span class="input-group-text">$</span>
                                                        <input placeholder="<?= __('admin.admin_amount') ?>" name="form_click_commision_per" id="form_click_commision_value" class="form-control" value="<?php echo $form_click_commision_per; ?>" type="number" step="0.01">
                                                    </div>
                                                    <small class="text-muted"><?= __('admin.commission_per_clicks') ?></small>
                                                </div>
                                            </div>
                                        </div>

                                        <script type="text/javascript">
                                            $("select[name=form_click_commision_type]").on("change",function(){
                                                $con = $(this).closest(".card-body");
                                                $con.find(".toggle-container .fixed-value, .toggle-container .default-value").addClass('d-none');

                                                if($(this).val() == 'default'){
                                                    $con.find(".toggle-container .default-value").removeClass("d-none");
                                                }else{
                                                    $con.find(".toggle-container .fixed-value").removeClass("d-none");
                                                }
                                            })
                                            $("select[name=form_click_commision_type]").trigger("change");
                                        </script>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <div class="row mt-4">
        <div class="col-12">
            <div class="d-flex justify-content-end gap-2">
                <span class="loading-submit"></span>
                <a href="<?= base_url('admincontrol/listproduct') ?>" class="btn btn-outline-secondary">
                    <i class="bi bi-arrow-left me-1"></i><?= __('admin.back') ?>
                </a>
                <button type="button" rtype='save_stay' class="btn btn-primary btn-submit" name="save">
                    <i class="bi bi-check-circle me-1"></i><?= __('admin.save') ?>
                </button>
                <button type="button" rtype='' class="btn btn-success btn-submit" name="save">
                    <i class="bi bi-check2-all me-1"></i><?= __('admin.save_close') ?>
                </button>
            </div>
        </div>
    </div>
</form>

<script type="text/javascript">
    $('#endtime').datetimepicker({
        format:'d-m-Y H:i',
        inline:true,
    });

    $('#setCustomTime').on('change', function(){
        $(".custom_time_container").hide();
        if($(this).prop("checked")){
            $(".custom_time_container").show();
        }
    });

    var btn;
    $(".datepicker").datepicker({ 
        autoclose: true, 
        todayHighlight: true,
        format:"dd-mm-yyyy"
    })

    $('.btn-submit').click(function(){
        $('.redirect').val($(this).attr('rtype'));
        $("#form_form").submit();
      
    })

    $('[name="allow_for"]').change(function(){
        $(".select-product").hide();
        if($(this).val() == 'S') $(".select-product").show();
    });

    $(".datepicker").each(function(){
        var d= $(this).val().split("-");
        if(d[0]){
            var date = d[1]  + "-" + d[2] + "-" + d[0];
            $(this).datepicker('update', new Date(date))
        }
        else{ $(this).val(''); }
    })

    $("#form_form").submit(function(evt){
        evt.preventDefault();

        var regex = new RegExp("^[a-zA-Z0-9 ]+$");
        var key = $('input[name="seo"').val();
        if (!regex.test(key)) {
            $('.seo_error').text('<?= __('admin.seo_validation_message') ?>');
            return false;
        } else {
            $('.seo_error').text('');
            var formData = new FormData(this);
        
            formData = formDataFilter(formData);
            $this = $(this);
            $btn = $('.btn-submit');

            $btn.prop('disabled', true).html('<span class="spinner-border spinner-border-sm me-2"></span>Loading...');
            $.ajax({
                url:'<?= base_url('admincontrol/save_form') ?>',
                type:'POST',
                dataType:'json',
                cache:false,
                contentType: false,
                processData: false,
                data:formData,
                xhr: function (){
                    var jqXHR = null;

                    if ( window.ActiveXObject ){
                        jqXHR = new window.ActiveXObject( "Microsoft.XMLHTTP" );
                    }else {
                        jqXHR = new window.XMLHttpRequest();
                    }
                    
                    jqXHR.upload.addEventListener( "progress", function ( evt ){
                        if ( evt.lengthComputable ){
                            var percentComplete = Math.round( (evt.loaded * 100) / evt.total );
                            $('.loading-submit').text(percentComplete + "% "+'<?= __('admin.loading') ?>');
                        }
                    }, false );

                    jqXHR.addEventListener( "progress", function ( evt ){
                        if ( evt.lengthComputable ){
                            var percentComplete = Math.round( (evt.loaded * 100) / evt.total );
                            $('.loading-submit').text('<?= __('admin.save') ?>');
                        }
                    }, false );
                    return jqXHR;
                },
                error:function(){ $btn.prop('disabled', false).html($(this).data('original-text') || 'Submit'); },
                success:function(result){
                    $('.loading-submit').hide();
                    $btn.prop('disabled', false).html($(this).data('original-text') || 'Submit');
                    $this.find(".has-error").removeClass("has-error");
                    $this.find("span.text-danger").remove();
                    
                    if(result['location']){
                        window.location = result['location'];
                    }
                    if(result['errors'] && Object.keys(result['errors']).length > 0){
                        var errorCount = Object.keys(result['errors']).length;
                        var firstErrorField = null;
                        var errorFields = [];
                        var addedFields = {};

                        var fieldNameMap = {
                            'title': 'Form Title',
                            'seo': 'SEO Title',
                            'description': 'Description',
                            'allow_for': 'Allow For Product',
                            'footer_title': 'Footer Content',
                            'form_recursion': 'Form Recursion',
                            'recursion_custom_time': 'Custom Time',
                            'form_commision_type': 'Sale Commission Type',
                            'form_commision_value': 'Sale Commission Value',
                            'form_click_commision_type': 'Click Commission Type',
                            'form_click_commision_ppc': 'Clicks Count',
                            'form_click_commision_per': 'Commission Amount'
                        };

                        $.each(result['errors'], function(i,j){
                            $ele = $this.find('[name="'+ i +'"]');
                            if($ele.length > 0){
                                $ele.closest(".mb-3, .form-group").addClass("has-error");
                                $ele.after("<span class='text-danger'>"+ j +"</span>");

                                if(!firstErrorField) {
                                    firstErrorField = $ele;
                                }
                            }
                            
                            var fieldLabel = fieldNameMap[i] || ($ele.length > 0 ? $ele.closest('.mb-3, .form-group').find('label').first().text().replace('*', '').trim() : fieldNameMap[i]) || i;
                            fieldLabel = fieldLabel.replace(/\s+/g, ' ').trim();

                            if(fieldLabel && !addedFields[fieldLabel.toLowerCase()]) {
                                errorFields.push(fieldLabel);
                                addedFields[fieldLabel.toLowerCase()] = true;
                            }
                        });

                        var actualErrorCount = errorFields.length > 0 ? errorFields.length : errorCount;
                        var errorMessage = (errorFields.length > 0 ? errorFields.join(', ') : actualErrorCount + ' <?= __('admin.fields') ?>');
                        var title = 'Form: ' + actualErrorCount + ' <?= __('admin.required_fields_missing') ?>';
                        showToast(title, errorMessage, 'error');

                        if(firstErrorField) {
                            $('html, body').animate({
                                scrollTop: firstErrorField.offset().top - 100
                            }, 500);
                            setTimeout(function() {
                                firstErrorField.focus();
                            }, 600);
                        }
                    } else if(result['success']){
                        showToast('<?= __('admin.success') ?>', '<?= __('admin.form_saved_successfully') ?>', 'success');
                    }
                },
            })
            return false;
        }
    });

    $(document).on('change', '#recursion_type', function(){
        var recursion_type = $(this).val();     
        if( recursion_type == 'custom_time' ){
            $('.custom_time').show();
        }else{
            $('.custom_time').hide();
        }
    });

    $(document).on('change', '#recur_day, #recur_hour, #recur_minute', function(){
        var days = $('#recur_day').val();
        var hours = $('#recur_hour').val();
        var minutes = $('#recur_minute').val();
        var total_minutes;      
        
        total_hours = parseInt(days*24) + parseInt(hours);
        total_minutes = parseInt(total_hours*60) + parseInt(minutes);
        $('.custom_time').find('input[name="recursion_custom_time"]').val(total_minutes);

    });

    $(document).ready(function() {
        $('[name="allow_for"]').trigger("change");
    });
</script>