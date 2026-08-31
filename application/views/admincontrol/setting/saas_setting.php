<?php if($saas_status){ ?>
<div class="container-fluid px-4 pb-4">
	<?php $this->load->view('admincontrol/setting/_saas_nav'); ?>
	<div class="row">
		<div class="col-12">
			<div class="d-flex justify-content-between align-items-center mb-3">
				<h4 class="fw-bold mb-0"><?= __('admin.saas_settings') ?></h4>
			</div>

			<div class="card shadow-sm border-0">
				<div class="card-body p-4">
					<form method="post" action="" enctype="multipart/form-data" id="setting-form">
						<div class="row">
							<div class="col-12">
								<ul class="nav nav-tabs nav-fill mb-4" id="TabsNav">
									<li class="nav-item flex-sm-fill text-sm-center">
										<a class="nav-link active" data-bs-toggle="tab" href="#market_vendor-setting" role="tab">
											<i class="fas fa-tools me-1"></i><?= __('admin.market_tools_admin_fee') ?>
										</a>
									</li>
									<li class="nav-item flex-sm-fill text-sm-center">
										<a class="nav-link" href="#vendor_setting" role="tab" data-bs-toggle="tab">
											<i class="fas fa-store me-1"></i><?= __('admin.store_admin_fee') ?>
										</a>
									</li>
									<li class="nav-item flex-sm-fill text-sm-center">
										<a class="nav-link" href="#vendor_deposite_setting" role="tab" data-bs-toggle="tab">
											<i class="fas fa-wallet me-1"></i><?= __('admin.vendor_deposit_settings') ?>
										</a>
									</li>
									<li class="nav-item flex-sm-fill text-sm-center">
										<a class="nav-link" href="#vendor_permission_setting" role="tab" data-bs-toggle="tab">
											<i class="fas fa-user-shield me-1"></i><?= __('admin.vendor_permission_setting') ?>
										</a>
									</li>
								</ul>
							</div>

							<div class="col-12">
								<div class="tab-content">
									<div class="tab-pane active show" id="market_vendor-setting" role="tabpanel">
										<div class="mb-4">
											<div class="card border-0 bg-light">
												<div class="card-header bg-transparent border-0 pb-0">
													<h6 class="mb-0 text-dark">
														<i class="fas fa-toggle-on me-2"></i><?= __('admin.vendor_status') ?>
													</h6>
												</div>
												<div class="card-body pt-2">
													<div class="form-check form-switch">
														<input class="form-check-input update_all_settings" type="checkbox" <?= $market_vendor['marketvendorstatus']==1 ? 'checked' : '' ?> data-toggle="toggle" data-size="normal" data-on="<?= __('admin.status_on'); ?>" data-off="<?= __('admin.status_off'); ?>" data-setting_key="marketvendorstatus" data-setting_type="market_vendor">
														<label class="form-check-label fw-semibold">
															<?= $market_vendor['marketvendorstatus']==1 ? __('admin.status_on') : __('admin.status_off') ?>
														</label>
													</div>
												</div>
											</div>
										</div>

										<div class="row g-4">
											<div class="col-lg-6">
												<div class="card h-100 border-0 shadow-sm">
													<div class="card-header bg-light border-0">
														<h6 class="mb-0 fw-semibold text-dark">
															<i class="fas fa-chart-line me-2 text-primary"></i><?= __('admin.admin_sale_settings_from_vendors') ?>
														</h6>
													</div>
													<div class="card-body">
														<div class="row g-3">
															<div class="col-md-6">
																<label class="form-label fw-semibold"><?= __('admin.commission_type') ?></label>
																<select name="market_vendor[commission_type]" class="form-select">
																	<option value=""><?= __('admin.select_product_commission_type') ?></option>
																	<option <?= ($market_vendor['commission_type'] == 'percentage') ? 'selected' : '' ?> value="percentage"><?= __('admin.percentage') ?></option>
																	<option <?= ($market_vendor['commission_type'] == 'fixed') ? 'selected' : '' ?> value="fixed"><?= __('admin.fixed') ?></option>
																</select>
															</div>
															<div class="col-md-6">
																<label class="form-label fw-semibold"><?= __('admin.commission_for_sale') ?></label>
																<div class="input-group">
																	<span class="input-group-text currency-symbol">
																		<?= ($market_vendor['commission_type'] == 'percentage') ? '%'  : $CurrencySymbol ?>
																	</span>
																	<input class="form-control" name="market_vendor[commission_sale]" type="number" value="<?= isset($market_vendor) ? $market_vendor['commission_sale'] : '' ?>">
																</div>
															</div>
														</div>

														<div class="mt-3">
															<label class="form-label fw-semibold"><?= __('admin.sale_status') ?></label>
															<div class="form-check form-switch">
																<input class="form-check-input update_all_settings" type="checkbox" <?= $market_vendor['sale_status']==1 ? 'checked' : '' ?> data-toggle="toggle" data-size="normal" data-on="<?= __('admin.status_on'); ?>" data-off="<?= __('admin.status_off'); ?>" data-setting_key="sale_status" data-setting_type="market_vendor">
																<label class="form-check-label fw-semibold">
																	<?= $market_vendor['sale_status']==1 ? __('admin.status_on') : __('admin.status_off') ?>
																</label>
															</div>
														</div>
													</div>
												</div>
											</div>

											<div class="col-lg-6">
												<div class="card h-100 border-0 shadow-sm">
													<div class="card-header bg-light border-0">
														<h6 class="mb-0 fw-semibold text-dark">
															<i class="fas fa-mouse-pointer me-2 text-primary"></i><?= __('admin.admin_click_settings_from_vendors') ?>
														</h6>
													</div>
													<div class="card-body">
														<div class="row g-3">
															<div class="col-12">
																<label class="form-label fw-semibold"><?= __('admin.click_allow') ?></label>
																<select name="market_vendor[click_allow]" class="form-select">
																	<option <?php if($market_vendor['click_allow'] == 'single') { ?> selected <?php } ?> value="single"><?= __('admin.allow_single_click') ?></option>
																	<option <?php if($market_vendor['click_allow'] == 'multiple') { ?> selected <?php } ?>  value="multiple"><?= __('admin.allow_multi_click') ?></option>
																</select>
															</div>
															<div class="col-md-6">
																<label class="form-label fw-semibold"><?= __('admin.number_of_click') ?></label>
																<input class="form-control" name="market_vendor[commission_number_of_click]" type="number" value="<?= isset($market_vendor) ? $market_vendor['commission_number_of_click'] : '' ?>">
															</div>
															<div class="col-md-6">
																<label class="form-label fw-semibold"><?= __('admin.amount_per_click') ?></label>
																<div class="input-group">
																	<span class="input-group-text"><?= $CurrencySymbol ?></span>
																	<input class="form-control" name="market_vendor[commission_click_commission]" type="number" value="<?= isset($market_vendor) ? $market_vendor['commission_click_commission'] : '' ?>">
																</div>
															</div>
														</div>

														<div class="mt-3">
															<label class="form-label fw-semibold"><?= __('admin.click_status') ?></label>
															<div class="form-check form-switch">
																<input class="form-check-input update_all_settings" type="checkbox" <?= $market_vendor['click_status']==1 ? 'checked' : '' ?> data-toggle="toggle" data-size="normal" data-on="<?= __('admin.status_on'); ?>" data-off="<?= __('admin.status_off'); ?>" data-setting_key="click_status" data-setting_type="market_vendor">
																<label class="form-check-label fw-semibold">
																	<?= $market_vendor['click_status']==1 ? __('admin.status_on') : __('admin.status_off') ?>
																</label>
															</div>
														</div>
													</div>
												</div>
											</div>
										</div>
					</div>

									<div class="tab-pane" id="vendor_setting" role="tabpanel">
										<div class="mb-4">
											<div class="card border-0 bg-light">
												<div class="card-header bg-transparent border-0 pb-0">
													<h6 class="mb-0 text-dark">
														<i class="fas fa-toggle-on me-2"></i><?= __('admin.vendor_status') ?>
													</h6>
												</div>
												<div class="card-body pt-2">
													<div class="form-check form-switch">
														<input class="form-check-input update_all_settings" type="checkbox" <?= $vendor['storestatus']==1 ? 'checked' : '' ?> data-toggle="toggle" data-size="normal" data-on="<?= __('admin.status_on'); ?>" data-off="<?= __('admin.status_off'); ?>" data-setting_key="storestatus" data-setting_type="vendor">
														<label class="form-check-label fw-semibold">
															<?= $vendor['storestatus']==1 ? __('admin.status_on') : __('admin.status_off') ?>
														</label>
													</div>
												</div>
											</div>
										</div>

										<div class="card border-0 shadow-sm">
											<div class="card-header bg-light border-0">
												<h6 class="mb-0 fw-semibold text-dark">
													<i class="fas fa-store me-2 text-primary"></i><?= __('admin.store_admin_fee_settings_from_vendors') ?>
												</h6>
											</div>
											<div class="card-body">
												<div class="row g-4">
													<div class="col-12">
														<div class="card border-0 bg-light">
															<div class="card-header bg-transparent border-0 pb-2">
																<h6 class="mb-0 text-dark">
																	<i class="fas fa-mouse-pointer me-2 text-primary"></i><?= __('admin.click_commission') ?>
																</h6>
															</div>
															<div class="card-body pt-2">
																<div class="row g-3">
																	<div class="col-md-4">
																		<label class="form-label fw-semibold"><?= __('admin.click') ?></label>
																		<div class="input-group">
																			<span class="input-group-text"><?= __('admin.click') ?></span>
																			<input name="vendor[admin_click_count]" class="form-control" value="<?php echo $vendor['admin_click_count']; ?>" type="text" placeholder='<?= __('admin.clicks'); ?>'>
																		</div>
																	</div>
																	<div class="col-md-4">
																		<label class="form-label fw-semibold"><?= __('admin.amount') ?></label>
																		<div class="input-group">
																			<span class="input-group-text"><?= $CurrencySymbol ?></span>
																			<input name="vendor[admin_click_amount]" class="form-control" value="<?php echo c_format($vendor['admin_click_amount'],false); ?>" type="number" placeholder='<?= __('admin.amount'); ?>'>
																		</div>
																	</div>
																	<div class="col-md-4">
																		<label class="form-label fw-semibold"><?= __('admin.status') ?></label>
																		<div class="form-check form-switch">
																			<input class="form-check-input update_all_settings" type="checkbox" <?= $vendor['admin_click_status']==1 ? 'checked' : '' ?> data-toggle="toggle" data-size="normal" data-on="<?= __('admin.status_on'); ?>" data-off="<?= __('admin.status_off'); ?>" data-setting_key="admin_click_status" data-setting_type="vendor">
																			<label class="form-check-label fw-semibold">
																				<?= $vendor['admin_click_status']==1 ? __('admin.status_on') : __('admin.status_off') ?>
																			</label>
																		</div>
																	</div>
																</div>
															</div>
														</div>
													</div>

													<div class="col-12">
														<div class="card border-0 bg-light">
															<div class="card-header bg-transparent border-0 pb-2">
																<h6 class="mb-0 text-dark">
																	<i class="fas fa-chart-line me-2 text-primary"></i><?= __('admin.sale_commission') ?>
																</h6>
															</div>
															<div class="card-body pt-2">
																<div class="row g-3">
																	<div class="col-md-4">
																		<label class="form-label fw-semibold"><?= __('admin.commission_type') ?></label>
																		<?php
																			$commission_type= array(
																				'percentage' => __('admin.percentage'),
																				'fixed'      => __('admin.fixed'),
																			);
																		?>
																		<select name="vendor[admin_sale_commission_type]" class="form-select admin_sale_commission_type">
																			<?php foreach ($commission_type as $key => $value) { ?>
																				<option <?= $vendor['admin_sale_commission_type'] == $key ? 'selected' : '' ?> value="<?= $key ?>"><?= $value ?></option>
																			<?php } ?>
																		</select>
																	</div>
																	<div class="col-md-4">
																		<label class="form-label fw-semibold"><?= __('admin.commission_value') ?></label>
																		<div class="input-group">
																			<span class="input-group-text currency-symbol"><?= $vendor['admin_sale_commission_type'] == 'percentage' ? '%' : $CurrencySymbol ?></span>
																			<input name="vendor[admin_commission_value]" id="admin_commission_value" class="form-control" value="<?php echo $vendor['admin_commission_value']; ?>" type="number" placeholder='<?= __('admin.sale_commission') ?>'>
																		</div>
																	</div>
																	<div class="col-md-4">
																		<label class="form-label fw-semibold"><?= __('admin.status') ?></label>
																		<div class="form-check form-switch">
																			<input class="form-check-input update_all_settings" type="checkbox" <?= $vendor['admin_sale_status']==1 ? 'checked' : '' ?> data-toggle="toggle" data-size="normal" data-on="<?= __('admin.status_on'); ?>" data-off="<?= __('admin.status_off'); ?>" data-setting_key="admin_sale_status" data-setting_type="vendor">
																			<label class="form-check-label fw-semibold">
																				<?= $vendor['admin_sale_status']==1 ? __('admin.status_on') : __('admin.status_off') ?>
																			</label>
																		</div>
																	</div>
																</div>
															</div>
														</div>
													</div>
												</div>
											</div>
										</div>
									</div>

									<div class="tab-pane" id="vendor_deposite_setting" role="tabpanel">
										<div class="mb-4">
											<div class="alert alert-info vendor-deposit-on-message <?= ($vendor['depositstatus']) ? '' : 'd-none' ?>">
												<div class="d-flex align-items-center">
													<i class="fas fa-info-circle me-3"></i>
													<div>
														<h6 class="mb-1 fw-bold"><?= __('admin.vendor_deposit_on_message') ?></h6>
														<small class="mb-0">Vendor must deposit minimum amount for campaign and product links to be visible to affiliates.</small>
													</div>
												</div>
											</div>

											<div class="alert alert-danger vendor-deposit-off-message <?= ($vendor['depositstatus']) ? 'd-none' : '' ?>">
												<div class="d-flex align-items-center">
													<i class="fas fa-exclamation-triangle me-3"></i>
													<div>
														<h6 class="mb-1 fw-bold"><?= __('admin.vendor_deposit_off_message') ?></h6>
														<small class="mb-0">Admin responsible for paying all vendor-generated commissions.</small>
													</div>
												</div>
											</div>
										</div>

										<div class="row g-4">
											<div class="col-lg-6">
												<div class="card border-0 shadow-sm">
													<div class="card-header bg-light border-0">
														<h6 class="mb-0 fw-semibold">
															<i class="fas fa-toggle-on me-2"></i><?= __('admin.vendor_status') ?>
														</h6>
													</div>
													<div class="card-body">
														<div class="form-check form-switch">
															<input class="form-check-input update_all_settings" type="checkbox" <?= $vendor['depositstatus']==1 ? 'checked' : '' ?> data-toggle="toggle" data-size="normal" data-on="<?= __('admin.status_on'); ?>" data-off="<?= __('admin.status_off'); ?>" data-setting_key="depositstatus" data-setting_type="vendor">
															<label class="form-check-label fw-semibold">
																<?= $vendor['depositstatus']==1 ? __('admin.status_on') : __('admin.status_off') ?>
															</label>
														</div>
													</div>
												</div>
											</div>

											<div class="col-lg-6">
												<div class="card border-0 shadow-sm">
													<div class="card-header bg-light border-0">
														<h6 class="mb-0 fw-semibold">
															<i class="fas fa-dollar-sign me-2"></i><?= __('admin.vendor_min_deposit') ?>
														</h6>
													</div>
													<div class="card-body">
														<div class="input-group">
															<span class="input-group-text"><?= $CurrencySymbol ?></span>
															<input name="site[vendor_min_deposit]" value="<?php echo empty($site['vendor_min_deposit']) ? 0 : $site['vendor_min_deposit']; ?>" class="form-control" type="number" min="0">
														</div>
														<div class="form-text"><?= __('admin.minimum_deposit_amount_help') ?></div>
													</div>
												</div>
											</div>
										</div>
									</div>

									<div class="tab-pane" id="vendor_permission_setting" role="tabpanel">
										<div class="alert alert-info mb-4">
											<div class="d-flex align-items-center">
												<i class="fas fa-info-circle me-3"></i>
												<div>
													<h6 class="mb-1 fw-bold"><?= __('admin.admin_approval_for_vendor') ?></h6>
													<small class="mb-0">Configure vendor permissions for different features and modules.</small>
												</div>
											</div>
										</div>

										<div class="row g-4">
											<div class="col-lg-6">
												<div class="card border-0 shadow-sm">
													<div class="card-header bg-light border-0">
														<h6 class="mb-0 fw-semibold">
															<i class="fas fa-tools me-2"></i><?= __('admin.vendor_market_tool') ?>
														</h6>
													</div>
													<div class="card-body">
														<div class="row g-3">
															<div class="col-12">
																<div class="d-flex justify-content-between align-items-center">
																	<div>
																		<label class="form-label fw-semibold mb-0"><?= __('admin.vendor_add_new_program') ?></label>
																	</div>
																	<div class="form-check form-switch">
																		<input class="form-check-input update_all_settings" type="checkbox" <?= $market_vendor['marketaddnewprogram']==1 ? 'checked' : '' ?> data-toggle="toggle" data-size="normal" data-on="<?= __('admin.status_on'); ?>" data-off="<?= __('admin.status_off'); ?>" data-setting_key="marketaddnewprogram" data-setting_type="market_vendor">
																	</div>
																</div>
															</div>
															<div class="col-12">
																<div class="d-flex justify-content-between align-items-center">
																	<div>
																		<label class="form-label fw-semibold mb-0"><?= __('admin.vendor_add_new_campaign') ?></label>
																	</div>
																	<div class="form-check form-switch">
																		<input class="form-check-input update_all_settings" type="checkbox" <?= $market_vendor['marketaddnewcampaign']==1 ? 'checked' : '' ?> data-toggle="toggle" data-size="normal" data-on="<?= __('admin.status_on'); ?>" data-off="<?= __('admin.status_off'); ?>" data-setting_key="marketaddnewcampaign" data-setting_type="market_vendor">
																	</div>
																</div>
															</div>
															<div class="col-12">
																<div class="d-flex justify-content-between align-items-center">
																	<div>
																		<label class="form-label fw-semibold mb-0"><?= __('admin.vendor_external_order_campaign') ?></label>
																	</div>
																	<div class="form-check form-switch">
																		<input class="form-check-input update_all_settings" type="checkbox" <?= $market_vendor['marketvendorexternalordercampaign']==1 ? 'checked' : '' ?> data-toggle="toggle" data-size="normal" data-on="<?= __('admin.status_on'); ?>" data-off="<?= __('admin.status_off'); ?>" data-setting_key="marketvendorexternalordercampaign" data-setting_type="market_vendor">
																	</div>
																</div>
															</div>
															<div class="col-12">
																<div class="d-flex justify-content-between align-items-center">
																	<div>
																		<label class="form-label fw-semibold mb-0"><?= __('admin.vendor_actions_campaign') ?></label>
																	</div>
																	<div class="form-check form-switch">
																		<input class="form-check-input update_all_settings" type="checkbox" <?= $market_vendor['marketvendoractionscampaign']==1 ? 'checked' : '' ?> data-toggle="toggle" data-size="normal" data-on="<?= __('admin.status_on'); ?>" data-off="<?= __('admin.status_off'); ?>" data-setting_key="marketvendoractionscampaign" data-setting_type="market_vendor">
																	</div>
																</div>
															</div>
															<div class="col-12">
																<div class="d-flex justify-content-between align-items-center">
																	<div>
																		<label class="form-label fw-semibold mb-0"><?= __('admin.vendor_click_campaign') ?></label>
																	</div>
																	<div class="form-check form-switch">
																		<input class="form-check-input update_all_settings" type="checkbox" <?= $market_vendor['marketvendorclickcampaign']==1 ? 'checked' : '' ?> data-toggle="toggle" data-size="normal" data-on="<?= __('admin.status_on'); ?>" data-off="<?= __('admin.status_off'); ?>" data-setting_key="marketvendorclickcampaign" data-setting_type="market_vendor">
																	</div>
																</div>
															</div>
														</div>
													</div>
												</div>
											</div>

											<div class="col-lg-6">
												<div class="card border-0 shadow-sm">
													<div class="card-header bg-light border-0">
														<h6 class="mb-0 fw-semibold">
															<i class="fas fa-store me-2"></i><?= __('admin.vendor_store_tool') ?>
														</h6>
													</div>
													<div class="card-body">
														<div class="row g-3">
															<div class="col-12">
																<div class="d-flex justify-content-between align-items-center">
																	<div>
																		<label class="form-label fw-semibold mb-0"><?= __('admin.vendor_add_new_store_product') ?></label>
																	</div>
																	<div class="form-check form-switch">
																		<input class="form-check-input update_all_settings" type="checkbox" <?= $market_vendor['marketaddnewstoreproduct']==1 ? 'checked' : '' ?> data-toggle="toggle" data-size="normal" data-on="<?= __('admin.status_on'); ?>" data-off="<?= __('admin.status_off'); ?>" data-setting_key="marketaddnewstoreproduct" data-setting_type="market_vendor">
																	</div>
																</div>
															</div>
															<div class="col-12">
																<div class="d-flex justify-content-between align-items-center">
																	<div>
																		<label class="form-label fw-semibold mb-0"><?= __('admin.allow_vendor_manage_review') ?></label>
																	</div>
																	<div class="form-check form-switch">
																		<input class="form-check-input update_all_settings" type="checkbox" <?= $market_vendor['vendormanagereview']==1 ? 'checked' : '' ?> data-toggle="toggle" data-size="normal" data-on="<?= __('admin.status_on'); ?>" data-off="<?= __('admin.status_off'); ?>" data-setting_key="vendormanagereview" data-setting_type="market_vendor">
																	</div>
																</div>
															</div>
															<div class="col-12">
																<div class="d-flex justify-content-between align-items-center">
																	<div>
																		<label class="form-label fw-semibold mb-0"><?= __('admin.allow_vendor_manage_review_image') ?></label>
																	</div>
																	<div class="form-check form-switch">
																		<input class="form-check-input update_all_settings" type="checkbox" <?= $market_vendor['vendormanagereviewimage']==1 ? 'checked' : '' ?> data-toggle="toggle" data-size="normal" data-on="<?= __('admin.status_on'); ?>" data-off="<?= __('admin.status_off'); ?>" data-setting_key="vendormanagereviewimage" data-setting_type="market_vendor">
																	</div>
																</div>
															</div>
															<div class="col-12">
																<div class="d-flex justify-content-between align-items-center">
																	<div>
																		<label class="form-label fw-semibold mb-0"><?= __('admin.allow_vendor_mlm_module') ?></label>
																	</div>
																	<div class="form-check form-switch">
																		<input class="form-check-input update_all_settings" type="checkbox" <?= $market_vendor['vendormlmmodule']==1 ? 'checked' : '' ?> data-toggle="toggle" data-size="normal" data-on="<?= __('admin.status_on'); ?>" data-off="<?= __('admin.status_off'); ?>" data-setting_key="vendormlmmodule" data-setting_type="market_vendor">
																	</div>
																</div>
															</div>
															<div class="col-12">
																<div class="d-flex justify-content-between align-items-center">
																	<div>
																		<label class="form-label fw-semibold mb-0"><?= __('admin.enable_disable_marketlinks_topaffs_section') ?></label>
																	</div>
																	<div class="form-check form-switch">
																		<input class="form-check-input update_all_settings" type="checkbox" <?= $market_vendor['marketvendorpanelmode']==1 ? 'checked' : '' ?> data-toggle="toggle" data-size="normal" data-on="<?= __('admin.status_on'); ?>" data-off="<?= __('admin.status_off'); ?>" data-setting_key="marketvendorpanelmode" data-setting_type="market_vendor">
																	</div>
																</div>
															</div>
														</div>
													</div>
												</div>
											</div>
										</div>

										<div class="row g-4 mt-1">
											<div class="col-lg-12">
												<div class="card h-100 border-0 shadow-sm">
													<div class="card-header bg-light border-0">
														<h6 class="mb-0 fw-semibold text-dark">
															<i class="fas fa-user-cog me-2"></i><?= __('admin.default_policies_for_new_vendors') ?>
														</h6>
													</div>
													<div class="card-body">
														<div class="row g-3">
															<div class="col-12">
																<div class="d-flex justify-content-between align-items-center">
																	<div>
																		<label class="form-label fw-semibold mb-0"><?= __('admin.default_vendor_shares_sales_status') ?></label>
																		<p class="text-muted small mb-0"><?= __('admin.default_vendor_shares_sales_status_desc') ?></p>
																	</div>
																	<div style="min-width: 250px;">
																		<select class="form-select" name="market_vendor[default_vendor_shares_sales_status]">
																			<option value="0" <?= (isset($market_vendor['default_vendor_shares_sales_status']) && $market_vendor['default_vendor_shares_sales_status'] == 0) ? 'selected' : '' ?>><?= __('admin.not_sell_anyone') ?></option>
																			<option value="1" <?= (!isset($market_vendor['default_vendor_shares_sales_status']) || $market_vendor['default_vendor_shares_sales_status'] == 1) ? 'selected' : '' ?>><?= __('admin.sell_all_affiliates') ?></option>
																			<option value="2" <?= (isset($market_vendor['default_vendor_shares_sales_status']) && $market_vendor['default_vendor_shares_sales_status'] == 2) ? 'selected' : '' ?>><?= __('admin.sell_my_affiliates') ?></option>
																		</select>
																	</div>
																</div>
															</div>
														</div>
													</div>
												</div>
											</div>
										</div>
									</div>
								</div>
							</div>

							<div class="col-12 mt-4">
								<div class="d-flex justify-content-end">
									<button type="submit" class="btn btn-primary btn-lg px-5 btn-submit">
										<i class="fas fa-save me-2"></i><?= __('admin.save_settings') ?>
									</button>
								</div>
							</div>
						</div>
					</form>
				</div>
			</div>
		</div>
	</div>
</div>

<script type="text/javascript">
	// Deep-link: open Deposit Settings tab when coming from Deposit page
	// Note: Do NOT remove ?tab=deposit from URL here - footer's remember-tab script needs it to apply correct tab
	(function(){
		var params = new URLSearchParams(window.location.search);
		if (params.get('tab') === 'deposit') {
			var tabEl = document.querySelector('#TabsNav a[href="#vendor_deposite_setting"]');
			if (tabEl && typeof bootstrap !== 'undefined' && bootstrap.Tab) {
				bootstrap.Tab.getOrCreateInstance(tabEl).show();
			}
		}
	})();
	$("select[name='market_vendor[commission_type]']").on('change',function(){
		if($(this).val() == 'percentage')
			$("input[name='market_vendor[commission_sale]']").siblings('.input-group-text').text('%');
		else
			$("input[name='market_vendor[commission_sale]']").siblings('.input-group-text').text('<?= $CurrencySymbol ?>');
	})

	$("select.admin_sale_commission_type").on("change",function(){
		if($(this).val() == 'percentage')
			$("input[name='vendor[admin_commission_value]']").siblings('.input-group-text').text('%');
		else
			$("input[name='vendor[admin_commission_value]']").siblings('.input-group-text').text('<?= $CurrencySymbol ?>');
	})

	$("select.admin_sale_commission_type").trigger("change");

	$("#setting-form").on('submit',function(e){
		e.preventDefault();
		$("#setting-form .alert-error").remove();
		var affiliate_cookie = parseInt($(".input-affiliate_cookie").val());
		if(affiliate_cookie <= 0 || affiliate_cookie > 365){
			$(".input-affiliate_cookie").after("<div class='alert alert-danger alert-error'><?= __('admin.days_between_1_and_365'); ?></div>");
		}
		if($("#setting-form .alert-error").length == 0) {
			submitForm();
		}
		return false;
	})

	function submitForm(){
		var formData = new FormData($("#setting-form")[0]);
		$(".btn-submit").prop('disabled', true).html('<span class="spinner-border spinner-border-sm me-2"></span><?= __('admin.saving') ?>...');
		formData = formDataFilter(formData);
		$this = $("#setting-form");
	   
		$.ajax({
			type:'POST',
			dataType:'json',
			cache:false,
			contentType: false,
			processData: false,
			data:formData,
			success:function(result){
				$(".btn-submit").prop('disabled', false).html('<i class="fas fa-save me-2"></i><?= __('admin.save_settings') ?>');
				$(".alert-dismissable").remove();

				$this.find(".is-invalid").removeClass("is-invalid");
				$this.find("span.invalid-feedback").remove();
				
				if(result['location']){
					window.location = result['location'];
				}

				if(result['success']){
					showToast('<?= __('admin.success') ?>', result['success'], 'success', 4000);
					var body = $("html, body");
					body.stop().animate({scrollTop:0}, 500, 'swing', function() { });
				}

				if(result['errors']){
					$.each(result['errors'], function(i,j){
						$ele = $this.find('[name="'+ i +'"]');
						if($ele){
							$ele.addClass("is-invalid");
							$ele.after("<span class='invalid-feedback'>"+ j +"</span>");
						}
					});
				}
			},
			error: function() {
				$(".btn-submit").prop('disabled', false).html('<i class="fas fa-save me-2"></i><?= __('admin.save_settings') ?>');
				showToast('<?= __('admin.error') ?>', '<?= __('admin.something_went_wrong') ?>', 'error', 4000);
			}
		})
	}

	$('.update_all_settings').on('change', function(){
		var checked = $(this).prop('checked');
		var setting_key = $(this).data('setting_key');
		var setting_type = $(this).data('setting_type');

		if (setting_key == 'depositstatus') {
			$('.vendor-deposit-on-message, .vendor-deposit-off-message').addClass('d-none');

			if(checked == true){
				$('.vendor-deposit-on-message').removeClass('d-none');
			}else{
				$('.vendor-deposit-off-message').removeClass('d-none');
			}
		}

		var status = checked ? 1 : 0;

		$.ajax({
			url:'<?= base_url("admincontrol/update_all_settings") ?>',
			type:'POST',
			dataType:'json',
			data:{'action':'update_all_settings', status:status, setting_key:setting_key, setting_type:setting_type},
			success:function(json){
				if(json.success) {
					showToast('<?= __('admin.success') ?>', json.success, 'success', 3000);
				}
			},
			error: function() {
				showToast('<?= __('admin.error') ?>', '<?= __('admin.something_went_wrong') ?>', 'error', 4000);
			}
		})
	});
</script>
<?php } else { ?>
<div class="container-fluid">
	<div class="row">
		<div class="col-12">
			<div class="alert alert-info">
				<div class="d-flex align-items-center">
					<i class="fas fa-info-circle me-3" style="font-size: 1.5rem;"></i>
					<div>
						<h5 class="mb-1"><?= __('admin.saas_module_is_off') ?></h5>
						<p class="mb-0"><?= __('admin.admin_click_here_to_activate') ?> <a href="<?= base_url('admincontrol/addons') ?>" class="alert-link"><?= __('admin.click_here') ?></a></p>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
<?php } ?>
