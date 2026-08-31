<?php if(!isset($modals_html)) $modals_html = ''; ?>
<?php foreach ($tools as $key => $tool) { ?>
	<tr>
		<td>
			<img class="intg-thumb" width="50px" height="50px" src="<?= $tool['featured_image'] ?>" alt="<?= htmlspecialchars($tool['name']) ?>">
		</td>
		<td>
			<?php
			$printableToolName = ucwords(strtolower($tool['name']));
			$printableToolName = strlen($printableToolName) > 50 ? substr($printableToolName,0,50)."..." : $printableToolName;
			echo $printableToolName;
			?>
		</td>

		<td>
			<?= isset($integration_plugins[$tool['tool_integration_plugin']]) ? $integration_plugins[$tool['tool_integration_plugin']]['name'] : "<span class='text-muted'>".__('admin.not_available_dashed')."</span>" ?>
		</td>

		<td class="text-center">
			<?= $tool['total_trigger_count'] ?>
		</td>
		<td>
			<?php 
	        	if($tool['_tool_type'] == 'action' || $tool['_tool_type'] == 'single_action')
	        	{
	        		$conversionratio=0;
	        		$totalratiocount=(int)$tool['total_action_click_count'];
	        		if($tool['total_trigger_count']>0)
	        			$conversionratio=(int)($totalratiocount*100/$tool['total_trigger_count']);
	        		$conversionratio = is_float($conversionratio) ==1 ? number_format((float)$conversionratio, 2, '.', '') : $conversionratio;
	        		echo __('user.action'). ' : '. $conversionratio . '%<br/>';
	        		
	        	}
	        	if($tool['_tool_type'] == 'general_click')
	        	{
	        		$conversionratio=0;
	        		$totalratiocount=(int)$tool['total_general_click_count'];
	        		if($tool['total_trigger_count']>0)
	        			$conversionratio=(int)($totalratiocount*100/$tool['total_trigger_count']);
	        		$conversionratio = is_float($conversionratio) ==1 ? number_format((float)$conversionratio, 2, '.', '') : $conversionratio;
	        		echo __('user.click'). ' : '. $conversionratio . '%<br/>';
	        	}
	        	if($tool['_tool_type'] == 'program' && $tool['click_status']) 
	        	{
	        		$conversionratio=0;
	        		$totalratiocount=(int)$tool['total_click_count'];
	        		if($tool['total_trigger_count']>0)
	        			$conversionratio=(int)($totalratiocount*100/$tool['total_trigger_count']);
	        		$conversionratio = is_float($conversionratio) ==1 ? number_format((float)$conversionratio, 2, '.', '') : $conversionratio;
	        		echo __('user.product_click'). ' : '. $conversionratio . '%<br/>';
	        	}
	        	if($tool['_tool_type'] == 'program' && $tool['sale_status'])  
	        	{
	        		$conversionratio=0;
	        		$totalratiocount=(int)$tool['total_sale_count'];
	        		if($tool['total_trigger_count']>0)
	        		$conversionratio=(int)($totalratiocount*100/$tool['total_trigger_count']);
	        		$conversionratio = is_float($conversionratio) ==1 ? number_format((float)$conversionratio, 2, '.', '') : $conversionratio;
	        		echo __('user.sale'). ' : '. $conversionratio . '%<br/>';
	        	}
 
	        	 ?>
		</td>

		<!-- Integration Status (combined: security status + method badge) -->
		<td class="text-center security-status">
			<div class="d-flex flex-column align-items-center gap-1">
				<?php $im = isset($tool['integration_method']) ? $tool['integration_method'] : 'js_pixel'; ?>
				<?= ads_security_status($tool['security_status'], null, $im); ?>

				<?php if ($im === 's2s_direct'): ?>
					<span class="badge bg-info text-dark"><i class="bi bi-phone me-1"></i><?= __('user.integration_type_s2s_mobile') ?></span>
					<button class="btn btn-sm btn-outline-info btn-show-setup" data-id="<?= $tool['id'] ?>"
						data-bs-toggle="tooltip" title="<?= __('user.s2s_mobile_view_setup') ?>">
						<i class="bi bi-phone"></i>
					</button>
				<?php elseif ($im === 's2s'): ?>
					<span class="badge bg-warning text-dark"><i class="bi bi-hdd-rack me-1"></i><?= __('user.integration_type_s2s') ?></span>
					<button class="btn btn-sm btn-outline-warning btn-show-setup" data-id="<?= $tool['id'] ?>"
						data-bs-toggle="tooltip" title="<?= __('user.s2s_view_setup') ?>">
						<i class="bi bi-hdd-rack"></i>
					</button>
				<?php elseif ($im === 'postback'): ?>
					<span class="badge bg-secondary"><i class="bi bi-arrow-left-right me-1"></i><?= __('user.integration_type_postback') ?></span>
					<button class="btn btn-sm btn-outline-secondary btn-show-setup" data-id="<?= $tool['id'] ?>"
						data-bs-toggle="tooltip" title="<?= __('user.integration_type_postback') ?>">
						<i class="bi bi-arrow-left-right"></i>
					</button>
				<?php elseif ($im === 'conversion_api'): ?>
					<span class="badge bg-dark"><i class="bi bi-braces me-1"></i><?= __('user.integration_method_conv_api') ?></span>
					<button class="btn btn-sm btn-outline-dark btn-show-setup" data-id="<?= $tool['id'] ?>"
						data-bs-toggle="tooltip" title="<?= __('user.integration_method_conv_api') ?>">
						<i class="bi bi-braces"></i>
					</button>
				<?php else: ?>
					<span class="badge bg-primary"><i class="bi bi-code-slash me-1"></i><?= __('user.integration_type_js_pixel') ?></span>
					<button data-bs-toggle="tooltip" title="<?= __('user.integration_code') ?>"
						class="btn-show-code btn btn-sm btn-outline-primary" data-id='<?= $tool['id'] ?>'>
						<i class="bi bi-code-slash"></i>
					</button>
				<?php endif; ?>
			</div>
		</td>


		<!-- Campaign Status -->
		<td class="text-center">
			<div class="d-flex flex-column align-items-center gap-1">
				<?= ads_status($tool['status']) ?>
				<?php if($tool['status'] == 1):
					$isRunning = 1;
					$isRunningTooltip = __('user.lifetime');

					if (!empty($tool['start_date']) && $tool['start_date'] != null) {
						$startDateAvailable = date('M d, Y H:i', strtotime($tool['start_date']));
						if (time() < strtotime($tool['start_date'])) {
							$isRunning = 0;
						}
					} else {
						$startDateAvailable = date('M d, Y H:i', strtotime($tool['created_at']));
					}

					if (!empty($tool['end_date']) && $tool['end_date'] != null) {
						$endDateAvailable = date('M d, Y H:i', strtotime($tool['end_date']));
						if (time() > strtotime($tool['end_date'])) {
							$isRunning = 2;
						}
					} else {
						$endDateAvailable = __('user.lifetime');
					} ?>

					<button type="button" class="btn btn-sm btn-outline-secondary"
						data-bs-toggle="tooltip"
						title="<?= __('admin.schedule') ?>: <?= $startDateAvailable ?> - <?= $endDateAvailable ?>">
						<i class="bi bi-clock text-<?= ads_running_status($isRunning) == 'success' ? 'success' : ($isRunning == 0 ? 'warning' : 'danger') ?>"></i>
					</button>
				<?php endif ?>
			</div>
		</td>


		<!-- Actions -->
		<td class="text-center">
			<div class="d-flex align-items-center justify-content-center gap-1">
				<a data-bs-toggle="tooltip" title="<?= __('admin.edit') ?>"
				   class="btn btn-sm btn-outline-primary"
				   onclick="return confirm('<?= __('user.are_you_sure_to_edit') ?>');"
				   href="<?= base_url('usercontrol/integration_tools_form/'. $tool['_type'] .'/' . $tool['id']) ?>">
					<i class="bi bi-pencil-square"></i>
				</a>
				<div class="dropdown intg-action-dropdown">
					<button class="btn btn-sm btn-outline-secondary dropdown-toggle" type="button" data-bs-toggle="dropdown" data-bs-auto-close="true" aria-expanded="false">
						<i class="bi bi-three-dots-vertical"></i>
					</button>
					<ul class="dropdown-menu dropdown-menu-end">
						<?php if($tool['main_commission_type'] != 'disabled'): ?>
						<li>
							<a class="dropdown-item btn-show-integration-mlm-info" href="javascript:void(0)" data-id="<?= $tool['id'] ?>">
								<i class="bi bi-diagram-3 me-2 text-info"></i><?= __('user.integration_mlm_info') ?>
							</a>
						</li>
						<?php endif ?>
						<li>
							<a class="dropdown-item" href="javascript:void(0)" data-bs-toggle="modal" data-bs-target="#campaign-details-<?= $tool['id']; ?>">
								<i class="bi bi-info-circle me-2 text-primary"></i><?= __('admin.campaign_info') ?>
							</a>
						</li>
						<li>
							<a class="dropdown-item btn-show-terms" href="javascript:void(0)" data-id="<?= $tool['id'] ?>">
								<i class="bi bi-file-earmark-text me-2 text-secondary"></i><?= __('admin.terms') ?>
							</a>
						</li>
						<li>
							<a class="dropdown-item check-campaign-with-id" href="javascript:void(0)" data-id="<?= $tool['id'] ?>">
								<i class="bi bi-shield-check me-2 text-warning"></i><?= __('admin.validate_campaign') ?>
							</a>
						</li>
						<li><hr class="dropdown-divider"></li>
						<li>
							<a class="dropdown-item" href="<?= base_url('usercontrol/integration_tools_duplicate/'. $tool['id']) ?>">
								<i class="bi bi-copy me-2 text-info"></i><?= __('admin.duplicate') ?>
							</a>
						</li>
						<li>
							<a class="dropdown-item text-danger tool-remove-link" href="<?= base_url('usercontrol/integration_tools_delete/'. $tool['id']) ?>">
								<i class="bi bi-trash me-2"></i><?= __('admin.delete') ?>
							</a>
						</li>
					</ul>
				</div>
			</div>
		</td>
	</tr>

	<?php
	ob_start();
	?>
	<div class="modal fade" id="campaign-details-<?= $tool['id']; ?>" tabindex="-1" aria-labelledby="vendorCampaignModal<?= $tool['id'] ?>" aria-hidden="true">
		<div class="modal-dialog modal-lg modal-dialog-centered modal-dialog-scrollable intg-modal">
			<div class="modal-content">
				<div class="intg-modal-header">
					<div class="intg-modal-header-left">
						<div class="intg-modal-icon intg-modal-icon--primary">
							<i class="bi bi-info-circle"></i>
						</div>
						<div>
							<h5 class="intg-modal-title" id="vendorCampaignModal<?= $tool['id'] ?>"><?= ucwords(strtolower($tool['name'])) ?></h5>
							<p class="intg-modal-subtitle"><?= __('admin.campaign_info') ?></p>
						</div>
					</div>
					<button type="button" class="intg-modal-close" data-bs-dismiss="modal" aria-label="Close"><i class="bi bi-x-lg"></i></button>
				</div>
				<div class="modal-body">
					<!-- Quick Info -->
					<div class="row g-2 mb-3">
						<div class="col-md-6">
							<div class="intg-modal-card">
								<div class="intg-modal-card-title"><i class="bi bi-tag"></i> <?= __('admin.tool_type') ?></div>
								<div class="intg-modal-card-value"><?= parseIntegrationType($tool['_tool_type']); ?></div>
							</div>
						</div>
						<div class="col-md-6">
							<div class="intg-modal-card">
								<div class="intg-modal-card-title"><i class="bi bi-calendar3"></i> <?= __('user.created_at') ?></div>
								<div class="intg-modal-card-value"><?= $tool['created_at'] ?></div>
							</div>
						</div>
						<?php if($tool['_tool_type'] == 'program'){ ?>
						<div class="col-md-6">
							<div class="intg-modal-card">
								<div class="intg-modal-card-title"><i class="bi bi-plug"></i> <?= __('admin.integration_plugin_name') ?></div>
								<div class="intg-modal-card-value"><?= isset($integration_plugins[$tool['tool_integration_plugin']]) ? $integration_plugins[$tool['tool_integration_plugin']]['name'] : "<span class='text-muted'>".__('admin.not_available_dashed')."</span>" ?></div>
							</div>
						</div>
						<?php } ?>
						<div class="col-md-6">
							<div class="intg-modal-card">
								<div class="intg-modal-card-title"><i class="bi bi-eye"></i> <?= __('user.view') ?></div>
								<div class="intg-modal-card-value"><?= number_format((int)$tool['total_trigger_count']) ?></div>
							</div>
						</div>
					</div>

					<!-- Commission Structure -->
					<div class="mb-3">
						<div class="intg-modal-section-title"><i class="bi bi-percent"></i> <?= __('admin.commission_structure') ?></div>

						<?php if ($tool['_tool_type'] == 'action' || $tool['_tool_type'] == 'single_action') { ?>
							<div class="intg-modal-card mb-2">
								<div class="intg-modal-card-title"><i class="bi bi-lightning"></i> <?= __('admin.action_click') ?></div>
								<div class="row g-2">
									<div class="col-md-6">
										<div class="intg-modal-stat">
											<div class="intg-modal-stat-icon intg-modal-stat-icon--green"><i class="bi bi-person"></i></div>
											<div>
												<div class="intg-modal-stat-label"><?= $tool['vendor_id'] ? __('admin.affiliate_will_get') : __('admin.you_will_get') ?></div>
												<div class="intg-modal-stat-value"><?= c_format($tool["action_amount"]). " ".__('admin.per')." ". $tool['action_click'] ." ".__('admin.actions') ?></div>
											</div>
										</div>
									</div>
									<?php if($tool['vendor_id']){ ?>
									<div class="col-md-6">
										<div class="intg-modal-stat">
											<div class="intg-modal-stat-icon intg-modal-stat-icon--blue"><i class="bi bi-shield-lock"></i></div>
											<div>
												<div class="intg-modal-stat-label"><?= __('admin.admin_will_get') ?></div>
												<div class="intg-modal-stat-value"><?= c_format($tool["admin_action_amount"]). " ".__('admin.per')." ". $tool['admin_action_click'] ." ".__('admin.actions') ?></div>
											</div>
										</div>
									</div>
									<?php } ?>
								</div>
							</div>
						<?php } ?>

						<?php if($tool['_tool_type'] == 'general_click') { ?>
							<div class="intg-modal-card mb-2">
								<div class="intg-modal-card-title"><i class="bi bi-cursor"></i> <?= __('admin.general_click') ?></div>
								<div class="row g-2">
									<div class="col-md-6">
										<div class="intg-modal-stat">
											<div class="intg-modal-stat-icon intg-modal-stat-icon--green"><i class="bi bi-person"></i></div>
											<div>
												<div class="intg-modal-stat-label"><?= $tool['vendor_id'] ? __('admin.affiliate_will_get') : __('admin.you_will_get') ?></div>
												<div class="intg-modal-stat-value"><?= c_format($tool["general_amount"]). " ".__('admin.per')." ". $tool['general_click'] ." ".__('admin.clicks') ?></div>
											</div>
										</div>
									</div>
									<?php if($tool['vendor_id']){ ?>
									<div class="col-md-6">
										<div class="intg-modal-stat">
											<div class="intg-modal-stat-icon intg-modal-stat-icon--blue"><i class="bi bi-shield-lock"></i></div>
											<div>
												<div class="intg-modal-stat-label"><?= __('admin.admin_will_get') ?></div>
												<div class="intg-modal-stat-value"><?= c_format($tool["admin_general_amount"]). " ".__('admin.per')." ". $tool['admin_general_click'] ." ".__('admin.clicks') ?></div>
											</div>
										</div>
									</div>
									<?php } ?>
								</div>
							</div>
						<?php } ?>

						<?php if($tool['_tool_type'] == 'program' && $tool['click_status']) { ?>
							<div class="intg-modal-card mb-2">
								<div class="intg-modal-card-title"><i class="bi bi-cart"></i> <?= __('admin.product_click') ?></div>
								<div class="row g-2">
									<div class="col-md-6">
										<div class="intg-modal-stat">
											<div class="intg-modal-stat-icon intg-modal-stat-icon--green"><i class="bi bi-person"></i></div>
											<div>
												<div class="intg-modal-stat-label"><?= $tool['vendor_id'] ? __('admin.affiliate_will_get') : __('admin.you_will_get') ?></div>
												<div class="intg-modal-stat-value"><?= c_format($tool["commission_click_commission"]). " ".__('admin.per')." ". $tool['commission_number_of_click'] ." ".__('admin.clicks') ?></div>
											</div>
										</div>
									</div>
									<?php if($tool['vendor_id']){ ?>
									<div class="col-md-6">
										<div class="intg-modal-stat">
											<div class="intg-modal-stat-icon intg-modal-stat-icon--blue"><i class="bi bi-shield-lock"></i></div>
											<div>
												<div class="intg-modal-stat-label"><?= __('admin.admin_will_get') ?></div>
												<div class="intg-modal-stat-value"><?= c_format($tool["admin_commission_click_commission"]). " ".__('admin.per')." ". $tool['admin_commission_number_of_click'] ." ".__('admin.clicks') ?></div>
											</div>
										</div>
									</div>
									<?php } ?>
								</div>
							</div>
						<?php } ?>

						<?php if($tool['_tool_type'] == 'program' && $tool['sale_status']) { ?>
							<div class="intg-modal-card mb-2">
								<div class="intg-modal-card-title"><i class="bi bi-currency-dollar"></i> <?= __('admin.sale_commisssion') ?></div>
								<div class="row g-2">
									<div class="col-md-6">
										<div class="intg-modal-stat">
											<div class="intg-modal-stat-icon intg-modal-stat-icon--green"><i class="bi bi-person"></i></div>
											<div>
												<div class="intg-modal-stat-label"><?= $tool['vendor_id'] ? __('admin.affiliate_will_get') : __('admin.you_will_get') ?></div>
												<div class="intg-modal-stat-value">
													<?php
													if($tool['commission_type'] == 'percentage'){ echo $tool['commission_sale'].'%'; }
													else if($tool['commission_type'] == 'fixed'){ echo c_format($tool['commission_sale']); }
													?>
												</div>
											</div>
										</div>
									</div>
									<?php if($tool['vendor_id']){ ?>
									<div class="col-md-6">
										<div class="intg-modal-stat">
											<div class="intg-modal-stat-icon intg-modal-stat-icon--blue"><i class="bi bi-shield-lock"></i></div>
											<div>
												<div class="intg-modal-stat-label"><?= __('admin.admin_will_get') ?></div>
												<div class="intg-modal-stat-value">
													<?php
													if($tool['admin_commission_type'] == 'percentage'){ echo $tool['admin_commission_sale'].'%'; }
													else if($tool['admin_commission_type'] == 'fixed'){ echo c_format($tool['admin_commission_sale']); }
													?>
												</div>
											</div>
										</div>
									</div>
									<?php } ?>
								</div>
							</div>
						<?php } ?>
					</div>

					<!-- Transaction Statistics -->
					<div class="mb-3">
						<div class="intg-modal-section-title"><i class="bi bi-graph-up"></i> <?= __('admin.transactions_details') ?></div>
						<div class="row g-2">
							<?php if($tool['_tool_type'] == 'action' || $tool['_tool_type'] == 'single_action') { ?>
								<div class="col-md-6">
									<div class="intg-modal-card">
										<div class="intg-modal-card-title"><i class="bi bi-lightning"></i> <?= __('admin.action_click') ?></div>
										<div class="d-flex justify-content-between mb-1"><span class="intg-modal-stat-label"><?= __('admin.admin_count') ?></span><span class="intg-modal-stat-value"><?= number_format((int)$tool['total_action_click_count']) ?></span></div>
										<div class="d-flex justify-content-between"><span class="intg-modal-stat-label"><?= __('admin.admin_amount') ?></span><span class="intg-modal-stat-value"><?= $tool['total_action_click_amount'] ?></span></div>
									</div>
								</div>
							<?php } ?>

							<?php if($tool['_tool_type'] == 'general_click') { ?>
								<div class="col-md-6">
									<div class="intg-modal-card">
										<div class="intg-modal-card-title"><i class="bi bi-cursor"></i> <?= __('admin.general_click') ?></div>
										<div class="d-flex justify-content-between mb-1"><span class="intg-modal-stat-label"><?= __('admin.admin_count') ?></span><span class="intg-modal-stat-value"><?= number_format((int)$tool['total_general_click_count']) ?></span></div>
										<div class="d-flex justify-content-between"><span class="intg-modal-stat-label"><?= __('admin.admin_amount') ?></span><span class="intg-modal-stat-value"><?= $tool['total_general_click_amount'] ?></span></div>
									</div>
								</div>
							<?php } ?>

							<?php if($tool['_tool_type'] == 'program' && $tool['click_status']) { ?>
								<div class="col-md-6">
									<div class="intg-modal-card">
										<div class="intg-modal-card-title"><i class="bi bi-cart"></i> <?= __('admin.product_click') ?></div>
										<div class="d-flex justify-content-between mb-1"><span class="intg-modal-stat-label"><?= __('admin.admin_count') ?></span><span class="intg-modal-stat-value"><?= number_format((int)$tool['total_click_count']) ?></span></div>
										<div class="d-flex justify-content-between"><span class="intg-modal-stat-label"><?= __('admin.admin_amount') ?></span><span class="intg-modal-stat-value"><?= $tool['total_click_amount'] ?></span></div>
									</div>
								</div>
							<?php } ?>

							<?php if($tool['_tool_type'] == 'program' && $tool['sale_status']) { ?>
								<div class="col-md-6">
									<div class="intg-modal-card">
										<div class="intg-modal-card-title"><i class="bi bi-currency-dollar"></i> <?= __('admin.sale_commisssion') ?></div>
										<div class="d-flex justify-content-between mb-1"><span class="intg-modal-stat-label"><?= __('admin.admin_count') ?></span><span class="intg-modal-stat-value"><?= number_format((int)$tool['total_sale_count']) ?></span></div>
										<div class="d-flex justify-content-between"><span class="intg-modal-stat-label"><?= __('admin.admin_amount') ?></span><span class="intg-modal-stat-value"><?= $tool['total_sale_amount'] ?></span></div>
									</div>
								</div>
							<?php } ?>
						</div>
					</div>

					<!-- Groups -->
					<div class="mb-2">
						<div class="intg-modal-section-title"><i class="bi bi-people"></i> <?= __('admin.group') ?></div>
						<div class="intg-modal-card">
							<?php
							if(empty($tool['groups'])) {
								echo '<span class="text-muted"><i class="bi bi-dash-circle me-1"></i>' . __('user.groups_not_assigned') . '</span>';
							} else {
								$groups = explode(',', $tool['groups']);
								foreach($groups as $g) {
									echo '<span class="badge bg-primary me-1">'.$g.'</span>';
								}
							}
							?>
						</div>
					</div>
				</div>
				<div class="intg-modal-footer">
					<button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">
						<i class="bi bi-x-lg me-1"></i><?= __('user.close') ?>
					</button>
				</div>
			</div>
		</div>
	</div>
	<?php
	$modals_html .= ob_get_clean();
	?>
<?php } ?>

<script>
(function() {
	var container = document.getElementById('vendor-campaign-modals-container');
	if(container) container.innerHTML = '';
	var modalsHtml = <?= json_encode($modals_html) ?>;
	if(container) container.insertAdjacentHTML('beforeend', modalsHtml);
})();
</script>
