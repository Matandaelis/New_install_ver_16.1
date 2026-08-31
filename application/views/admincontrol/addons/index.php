<div class="container-fluid addons-page">
  <div class="row">
    <div class="col-12">
      <!-- Module Management Section -->
      <div class="row mb-4">
        <div class="col-12">
          <div class="card border-0 shadow-sm">
            <div class="card-header bg-primary text-white py-3">
              <div class="d-flex align-items-center">
                <i class="bi bi-toggle-on me-2 fs-4"></i>
                <div>
                  <h4 class="mb-0 fw-bold"><?= __('admin.module_management') ?></h4>
                  <small class="opacity-75"><?= __('admin.enable_disable_modules') ?></small>
                </div>
              </div>
            </div>
            <div class="card-body p-3">
              <div class="row row-cols-1 row-cols-sm-2 row-cols-lg-3 row-cols-xl-4 g-3 addon-module-switcher">

                <div class="col">
                  <div class="card border h-100 shadow-sm position-relative <?= ((int)$mlm_admin_is_enable > 0) ? "border-success" : "border-secondary"; ?>">
                    <span class="badge <?= ((int)$mlm_admin_is_enable > 0) ? "bg-success" : "bg-secondary"; ?> position-absolute top-0 end-0 m-2">
                      <?= ((int)$mlm_admin_is_enable > 0) ? __('admin.active') : __('admin.inactive'); ?>
                    </span>
                    <div class="card-header border-0 bg-transparent py-3">
                      <h6 class="card-title mb-0 fw-semibold"><?= __('admin.mlm_admin') ?></h6>
                    </div>
                    <div class="card-body pt-0">
                      <div class="form-check form-switch">
                        <input class="form-check-input activity" type="checkbox" data-setting_type="referlevel" data-setting_key="status" data-sidebar="mlm" <?= ((int)$mlm_admin_is_enable > 0) ? "checked" : ""; ?>>
                      </div>
                    </div>
                  </div>
                </div>

                <div class="col">
                  <div class="card border h-100 shadow-sm position-relative <?= ((int)$mlm_vendor_is_enable > 0) ? "border-success" : "border-secondary"; ?>">
                    <span class="badge <?= ((int)$mlm_vendor_is_enable > 0) ? "bg-success" : "bg-secondary"; ?> position-absolute top-0 end-0 m-2">
                      <?= ((int)$mlm_vendor_is_enable > 0) ? __('admin.active') : __('admin.inactive'); ?>
                    </span>
                    <div class="card-header border-0 bg-transparent py-3">
                      <h6 class="card-title mb-0 fw-semibold"><?= __('admin.mlm_vendor') ?></h6>
                    </div>
                    <div class="card-body pt-0">
                      <div class="form-check form-switch">
                        <input class="form-check-input activity" type="checkbox" data-setting_type="market_vendor" data-setting_key="vendormlmmodule" data-sidebar="vendormlmmodule" <?= ((int)$mlm_vendor_is_enable > 0) ? "checked" : ""; ?>>
                      </div>
                    </div>
                  </div>
                </div>

                <!--SaaS module-->
                <div class="col">
                  <div class="card border h-100 shadow-sm position-relative <?= ((int)$saas_is_enable > 0) ? "border-success" : "border-secondary"; ?>">
                    <span class="badge <?= ((int)$saas_is_enable > 0) ? "bg-success" : "bg-secondary"; ?> position-absolute top-0 end-0 m-2">
                      <?= ((int)$saas_is_enable > 0) ? __('admin.active') : __('admin.inactive'); ?>
                    </span>
                    <div class="card-header border-0 bg-transparent py-3">
                      <h6 class="card-title mb-0 fw-semibold"><?= __('admin.saas_module') ?></h6>
                    </div>
                    <div class="card-body pt-0">
                      <div class="form-check form-switch">
                        <input class="form-check-input activity" type="checkbox" data-setting_type="market_vendor" data-setting_key="marketvendorstatus" data-sidebar="saas" <?= ((int)$saas_is_enable > 0) ? "checked" : ""; ?>>
                      </div>
                    </div>
                  </div>
                </div>

                <!--Store module-->
                <div class="col">
                  <div class="card border h-100 shadow-sm position-relative <?= ((int)$store_is_enable > 0) ? "border-success" : "border-secondary"; ?>">
                    <span class="badge <?= ((int)$store_is_enable > 0) ? "bg-success" : "bg-secondary"; ?> position-absolute top-0 end-0 m-2">
                      <?= ((int)$store_is_enable > 0) ? __('admin.active') : __('admin.inactive'); ?>
                    </span>
                    <div class="card-header border-0 bg-transparent py-3">
                      <h6 class="card-title mb-0 fw-semibold"><?= __('admin.store_module') ?></h6>
                    </div>
                    <div class="card-body pt-0">
                      <div class="form-check form-switch">
                        <input class="form-check-input activity" type="checkbox" data-setting_type="store" data-setting_key="status" data-sidebar="store" <?= ((int)$store_is_enable > 0) ? "checked" : ""; ?>>
                      </div>
                    </div>
                  </div>
                </div>

                <!--Membership module-->
                <div class="col">
                  <div class="card border h-100 shadow-sm position-relative <?= ((int)$membership_is_enable > 0) ? "border-success" : "border-secondary"; ?>">
                    <span class="badge <?= ((int)$membership_is_enable > 0) ? "bg-success" : "bg-secondary"; ?> position-absolute top-0 end-0 m-2">
                      <?= ((int)$membership_is_enable > 0) ? __('admin.active') : __('admin.inactive'); ?>
                    </span>
                    <div class="card-header border-0 bg-transparent py-3">
                      <h6 class="card-title mb-0 fw-semibold"><?= __('admin.membership_module') ?></h6>
                    </div>
                    <div class="card-body pt-0">
                      <div class="form-check form-switch">
                        <input class="form-check-input activity" type="checkbox" data-setting_type="membership" data-setting_key="status" data-sidebar="membership" <?= ((int)$membership_is_enable > 0) ? "checked" : ""; ?>>
                      </div>
                    </div>
                  </div>
                </div>

                <div class="col">
                  <div class="card border h-100 shadow-sm position-relative <?= ((int)$vendor_deposit_is_enable > 0) ? "border-success" : "border-secondary"; ?>">
                    <span class="badge <?= ((int)$vendor_deposit_is_enable > 0) ? "bg-success" : "bg-secondary"; ?> position-absolute top-0 end-0 m-2">
                      <?= ((int)$vendor_deposit_is_enable > 0) ? __('admin.active') : __('admin.inactive'); ?>
                    </span>
                    <div class="card-header border-0 bg-transparent py-3">
                      <h6 class="card-title mb-0 fw-semibold"><?= __('admin.vendor_deposit_module') ?></h6>
                    </div>
                    <div class="card-body pt-0">
                      <div class="form-check form-switch">
                        <input class="form-check-input activity" type="checkbox" data-setting_type="vendor" data-setting_key="depositstatus" data-sidebar="vendor" <?= ((int)$vendor_deposit_is_enable > 0) ? "checked" : ""; ?>>
                      </div>
                    </div>
                  </div>
                </div>

                <div class="col">
                  <div class="card border h-100 shadow-sm position-relative <?= ((int)$award_level_is_enable > 0) ? "border-success" : "border-secondary"; ?>">
                    <span class="badge <?= ((int)$award_level_is_enable > 0) ? "bg-success" : "bg-secondary"; ?> position-absolute top-0 end-0 m-2">
                      <?= ((int)$award_level_is_enable > 0) ? __('admin.active') : __('admin.inactive'); ?>
                    </span>
                    <div class="card-header border-0 bg-transparent py-3">
                      <h6 class="card-title mb-0 fw-semibold"><?= __('admin.award_level') ?></h6>
                    </div>
                    <div class="card-body pt-0">
                      <div class="form-check form-switch">
                        <input class="form-check-input activity" type="checkbox" data-setting_type="award_level" data-setting_key="status" data-sidebar="award_level" <?= ((int)$award_level_is_enable > 0) ? "checked" : ""; ?>>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>

      <!-- System Settings Section -->
      <div class="row mb-4">
        <div class="col-12">
          <div class="card border-0 shadow-sm">
            <div class="card-header bg-primary text-white py-3">
              <div class="d-flex align-items-center">
                <i class="bi bi-gear-fill me-2 fs-4"></i>
                <div>
                  <h4 class="mb-0 fw-bold"><?= __('admin.system_settings') ?></h4>
                  <small class="opacity-75"><?= __('admin.core_configuration_tools') ?></small>
                </div>
              </div>
            </div>
            <div class="card-body p-3">
              <div class="row row-cols-1 row-cols-sm-2 row-cols-md-3 row-cols-lg-4 row-cols-xl-6 g-3 addons-common">
                <div class="col">
                  <div class="card border-0 bg-light h-100 shadow-sm">
                    <div class="card-body text-center p-3">
                      <div class="mb-3">
                        <i class="bi bi-gear-fill fs-1 text-primary"></i>
                      </div>
                      <h6 class="card-title mb-2"><?= __( 'admin.menu_settings') ?></h6>
                      <p class="card-text text-muted small mb-3"><?= __('admin.configure_system_settings') ?></p>
                      <a href="<?= base_url('admincontrol/paymentsetting') ?>" target="_blank" role="button" class="btn btn-outline-primary btn-sm w-100"><?php echo __( 'admin.go_to_module') ?></a>
                    </div>
                  </div>
                </div>

                <div class="col">
                  <div class="card border-0 bg-light h-100 shadow-sm">
                    <div class="card-body text-center p-3">
                      <div class="mb-3">
                        <i class="bi bi-translate fs-1 text-primary"></i>
                      </div>
                      <h6 class="card-title mb-2"><?= __( 'admin.language') ?></h6>
                      <p class="card-text text-muted small mb-3"><?= __('admin.manage_languages') ?></p>
                      <a href="<?= base_url('admincontrol/language') ?>" target="_blank" role="button" class="btn btn-outline-primary btn-sm w-100"><?= __( 'admin.go_to_module') ?></a>
                    </div>
                  </div>
                </div>

                <div class="col">
                  <div class="card border-0 bg-light h-100 shadow-sm">
                    <div class="card-body text-center p-3">
                      <div class="mb-3">
                        <i class="bi bi-currency-dollar fs-1 text-primary"></i>
                      </div>
                      <h6 class="card-title mb-2"><?= __( 'admin.currency') ?></h6>
                      <p class="card-text text-muted small mb-3"><?= __('admin.currency_management') ?></p>
                      <a href="<?= base_url('admincontrol/currency_list') ?>" target="_blank" role="button" class="btn btn-outline-primary btn-sm w-100"><?php echo __( 'admin.go_to_module') ?></a>
                    </div>
                  </div>
                </div>

                <div class="col">
                  <div class="card border-0 bg-light h-100 shadow-sm">
                    <div class="card-body text-center p-3">
                      <div class="mb-3">
                        <i class="bi bi-envelope-fill fs-1 text-primary"></i>
                      </div>
                      <h6 class="card-title mb-2"><?= __('admin.mail_templates') ?></h6>
                      <p class="card-text text-muted small mb-3"><?= __('admin.email_template_management') ?></p>
                      <a href="<?= base_url('admincontrol/mails') ?>" target="_blank" role="button" class="btn btn-outline-primary btn-sm w-100"><?php echo __( 'admin.go_to_module') ?></a>
                    </div>
                  </div>
                </div>

                <div class="col">
                  <div class="card border-0 bg-light h-100 shadow-sm">
                    <div class="card-body text-center p-3">
                      <div class="mb-3">
                        <i class="bi bi-person-plus-fill fs-1 text-primary"></i>
                      </div>
                      <h6 class="card-title mb-2"><?= __( 'admin.registration_form') ?></h6>
                      <p class="card-text text-muted small mb-3"><?= __('admin.customize_registration_forms') ?></p>
                      <a href="<?= base_url('admincontrol/registration_builder') ?>" target="_blank" role="button" class="btn btn-outline-primary btn-sm w-100"><?php echo __( 'admin.go_to_module') ?></a>
                    </div>
                  </div>
                </div>

                <div class="col">
                  <div class="card border-0 bg-light h-100 shadow-sm">
                    <div class="card-body text-center p-3">
                      <div class="mb-3">
                        <i class="bi bi-hdd-fill fs-1 text-primary"></i>
                      </div>
                      <h6 class="card-title mb-2"><?= __( 'admin.backups') ?></h6>
                      <p class="card-text text-muted small mb-3"><?= __('admin.database_backup_management') ?></p>
                      <a href="<?= base_url('admincontrol/backup') ?>" target="_blank" role="button" class="btn btn-outline-primary btn-sm w-100"><?= __( 'admin.go_to_module') ?></a>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>

      <!-- Data Management Section -->
      <div class="row mb-4">
        <div class="col-12">
          <div class="card border-0 shadow-sm">
            <div class="card-header bg-success text-white py-3">
              <div class="d-flex align-items-center">
                <i class="bi bi-database-fill me-2 fs-4"></i>
                <div>
                  <h4 class="mb-0 fw-bold"><?= __('admin.data_management') ?></h4>
                  <small class="opacity-75"><?= __('admin.manage_system_data') ?></small>
                </div>
              </div>
            </div>
            <div class="card-body p-3">
              <div class="row row-cols-1 row-cols-sm-2 row-cols-md-3 row-cols-lg-4 row-cols-xl-6 g-3 addons-common">
                <div class="col">
                  <div class="card border-0 bg-light h-100 shadow-sm">
                    <div class="card-body text-center p-3">
                      <div class="mb-3">
                        <i class="bi bi-globe fs-1 text-success"></i>
                      </div>
                      <h6 class="card-title mb-2"><?= __( 'admin.countries_and_states') ?></h6>
                      <p class="card-text text-muted small mb-3"><?= __('admin.manage_locations') ?></p>
                      <a href="<?= base_url('admincontrol/countries_and_states') ?>" target="_blank" role="button" class="btn btn-outline-success btn-sm w-100"><?php echo __( 'admin.go_to_module') ?></a>
                    </div>
                  </div>
                </div>

                <div class="col">
                  <div class="card border-0 bg-light h-100 shadow-sm">
                    <div class="card-body text-center p-3">
                      <div class="mb-3">
                        <i class="bi bi-clock-history fs-1 text-success"></i>
                      </div>
                      <h6 class="card-title mb-2"><?= __( 'admin.cron_job') ?></h6>
                      <p class="card-text text-muted small mb-3"><?= __('admin.scheduled_tasks') ?></p>
                      <a href="<?= base_url('admincontrol/cron') ?>" target="_blank" role="button" class="btn btn-outline-success btn-sm w-100"><?php echo __( 'admin.go_to_module') ?></a>
                    </div>
                  </div>
                </div>

                <div class="col">
                  <div class="card border-0 bg-light h-100 shadow-sm">
                    <div class="card-body text-center p-3">
                      <div class="mb-3">
                        <i class="bi bi-list-task fs-1 text-success"></i>
                      </div>
                      <h6 class="card-title mb-2"><?= __( 'admin.menu_to_do_list') ?></h6>
                      <p class="card-text text-muted small mb-3"><?= __('admin.task_management') ?></p>
                      <a href="<?= base_url('admincontrol/todolist') ?>" target="_blank" role="button" class="btn btn-outline-success btn-sm w-100"><?php echo __( 'admin.go_to_module') ?></a>
                    </div>
                  </div>
                </div>

                <div class="col">
                  <div class="card border-0 bg-light h-100 shadow-sm">
                    <div class="card-body text-center p-3">
                      <div class="mb-3">
                        <i class="bi bi-ticket-perforated-fill fs-1 text-success"></i>
                      </div>
                      <h6 class="card-title mb-2"><?= __( 'admin.menu_tickets') ?></h6>
                      <p class="card-text text-muted small mb-3"><?= __('admin.support_tickets') ?></p>
                      <a href="<?= base_url('admincontrol/tickets') ?>" target="_blank" role="button" class="btn btn-outline-success btn-sm w-100"><?php echo __( 'admin.go_to_module') ?></a>
                    </div>
                  </div>
                </div>

                <div class="col">
                  <div class="card border-0 bg-light h-100 shadow-sm">
                    <div class="card-body text-center p-3">
                      <div class="mb-3">
                        <i class="bi bi-code-slash fs-1 text-success"></i>
                      </div>
                      <h6 class="card-title mb-2"><?= __('admin.user_api') ?></h6>
                      <p class="card-text text-muted small mb-3"><?= __('admin.api_documentation') ?></p>
                      <a href="<?= base_url('api-document') ?>" target="_blank" role="button" class="btn btn-outline-success btn-sm w-100"><?php echo __( 'admin.go_to_module') ?></a>
                    </div>
                  </div>
                </div>

                <div class="col">
                  <div class="card border-0 bg-light h-100 shadow-sm">
                    <div class="card-body text-center p-3">
                      <div class="mb-3">
                        <i class="bi bi-clock-history fs-1 text-success"></i>
                      </div>
                      <h6 class="card-title mb-2"><?= __('admin.system_update_logs') ?></h6>
                      <p class="card-text text-muted small mb-3"><?= __('admin.update_history') ?></p>
                      <a href="<?= base_url('debug/sysupdatereport') ?>" target="_blank" role="button" class="btn btn-outline-success btn-sm w-100"><?php echo __( 'admin.go_to_module') ?></a>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>

      <!-- Tools & Support Section -->
      <div class="row mb-4">
        <div class="col-12">
          <div class="card border-0 shadow-sm">
            <div class="card-header bg-info text-white py-3">
              <div class="d-flex align-items-center">
                <i class="bi bi-tools me-2 fs-4"></i>
                <div>
                  <h4 class="mb-0 fw-bold"><?= __('admin.tools_support') ?></h4>
                  <small class="opacity-75"><?= __('admin.help_resources_tools') ?></small>
                </div>
              </div>
            </div>
            <div class="card-body p-3">
              <div class="row row-cols-1 row-cols-sm-2 row-cols-md-3 row-cols-lg-4 row-cols-xl-6 g-3 addons-common">
                <div class="col">
                  <div class="card border-0 bg-light h-100 shadow-sm">
                    <div class="card-body text-center p-3">
                      <div class="mb-3">
                        <i class="bi bi-book fs-1 text-info"></i>
                      </div>
                      <h6 class="card-title mb-2"><?= __('admin.tutorial') ?></h6>
                      <p class="card-text text-muted small mb-3"><?= __('admin.learning_resources') ?></p>
                      <a href="<?= base_url('admincontrol/tutorial') ?>" target="_blank" role="button" class="btn btn-outline-info btn-sm w-100"><?php echo __( 'admin.go_to_module') ?></a>
                    </div>
                  </div>
                </div>

                <div class="col">
                  <div class="card border-0 bg-light h-100 shadow-sm">
                    <div class="card-body text-center p-3">
                      <div class="mb-3">
                        <i class="bi bi-tools fs-1 text-info"></i>
                      </div>
                      <h6 class="card-title mb-2"><?= __('admin.troubleshoot') ?></h6>
                      <p class="card-text text-muted small mb-3"><?= __('admin.system_troubleshooting') ?></p>
                      <a href="<?= base_url('admincontrol/troubleshoot') ?>" target="_blank" role="button" class="btn btn-outline-info btn-sm w-100"><?php echo __( 'admin.go_to_module') ?></a>
                    </div>
                  </div>
                </div>

                <div class="col">
                  <div class="card border-0 bg-light h-100 shadow-sm">
                    <div class="card-body text-center p-3">
                      <div class="mb-3">
                        <i class="bi bi-palette-fill fs-1 text-info"></i>
                      </div>
                      <h6 class="card-title mb-2"><?= __('admin.affiliate_theme') ?></h6>
                      <p class="card-text text-muted small mb-3"><?= __('admin.theme_customization') ?></p>
                      <a href="<?= base_url('admincontrol/affiliate_theme') ?>" target="_blank" role="button" class="btn btn-outline-info btn-sm w-100"><?php echo __( 'admin.go_to_module') ?></a>
                    </div>
                  </div>
                </div>

                <div class="col">
                  <div class="card border-0 bg-light h-100 shadow-sm">
                    <div class="card-body text-center p-3">
                      <div class="mb-3">
                        <i class="bi bi-shield-fill-check fs-1 text-info"></i>
                      </div>
                      <h6 class="card-title mb-2"><?= __('admin.security') ?></h6>
                      <p class="card-text text-muted small mb-3"><?= __('admin.security_settings') ?></p>
                      <a href="<?= base_url('admincontrol/paymentsetting#security') ?>" target="_blank" role="button" class="btn btn-outline-info btn-sm w-100"><?php echo __( 'admin.go_to_module') ?></a>
                    </div>
                  </div>
                </div>

                <div class="col">
                  <div class="card border-0 bg-light h-100 shadow-sm">
                    <div class="card-body text-center p-3">
                      <div class="mb-3">
                        <i class="bi bi-speedometer2 fs-1 text-info"></i>
                      </div>
                      <h6 class="card-title mb-2"><?= __('admin.user_dashboard') ?></h6>
                      <p class="card-text text-muted small mb-3"><?= __('admin.dashboard_settings') ?></p>
                      <a href="<?= base_url('admincontrol/paymentsetting#user-dashboard-setting') ?>" target="_blank" role="button" class="btn btn-outline-info btn-sm w-100"><?php echo __( 'admin.go_to_module') ?></a>
                    </div>
                  </div>
                </div>

                <div class="col">
                  <div class="card border-0 bg-light h-100 shadow-sm">
                    <div class="card-body text-center p-3">
                      <div class="mb-3">
                        <i class="bi bi-diagram-3-fill fs-1 text-info"></i>
                      </div>
                      <h6 class="card-title mb-2"><?= __('admin.cookies_tracking') ?></h6>
                      <p class="card-text text-muted small mb-3"><?= __('admin.tracking_settings') ?></p>
                      <a href="<?= base_url('admincontrol/paymentsetting#tracking') ?>" target="_blank" role="button" class="btn btn-outline-info btn-sm w-100"><?php echo __( 'admin.go_to_module') ?></a>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>


      <!-- System Management Section -->
      <div class="row mb-4">
        <div class="col-12">
          <div class="card border-0 shadow-sm">
            <div class="card-header bg-warning text-dark py-3">
              <div class="d-flex align-items-center">
                <i class="bi bi-hdd-rack me-2 fs-4"></i>
                <div>
                  <h4 class="mb-0 fw-bold"><?= __('admin.system_management') ?></h4>
                  <small class="opacity-75"><?= __('admin.advanced_system_tools') ?></small>
                </div>
              </div>
            </div>
            <div class="card-body p-3">
              <div class="row row-cols-1 row-cols-sm-2 row-cols-md-3 row-cols-lg-4 row-cols-xl-6 g-3 addons-common">
                <div class="col">
                  <div class="card border-0 bg-light h-100 shadow-sm">
                    <div class="card-body text-center p-3">
                      <div class="mb-3">
                        <i class="bi bi-trophy-fill fs-1 text-warning"></i>
                      </div>
                      <h6 class="card-title mb-2"><?= __('admin.award_level') ?></h6>
                      <p class="card-text text-muted small mb-3"><?= __('admin.achievement_system') ?></p>
                      <a href="<?= base_url('admincontrol/award_level') ?>" target="_blank" role="button" class="btn btn-outline-warning btn-sm w-100"><?php echo __( 'admin.go_to_module') ?></a>
                    </div>
                  </div>
                </div>

                <div class="col">
                  <div class="card border-0 bg-light h-100 shadow-sm">
                    <div class="card-body text-center p-3">
                      <div class="mb-3">
                        <i class="bi bi-heart-pulse-fill fs-1 text-warning"></i>
                      </div>
                      <h6 class="card-title mb-2"><?= __( 'admin.system_status') ?></h6>
                      <p class="card-text text-muted small mb-3"><?= __('admin.system_health_monitor') ?></p>
                      <a href="<?= base_url('admincontrol/system_status') ?>" target="_blank" role="button" class="btn btn-outline-warning btn-sm w-100"><?php echo __( 'admin.go_to_module') ?></a>
                    </div>
                  </div>
                </div>

                <div class="col">
                  <div class="card border-0 bg-light h-100 shadow-sm">
                    <div class="card-body text-center p-3">
                      <div class="mb-3">
                        <i class="bi bi-patch-check-fill fs-1 text-warning"></i>
                      </div>
                      <h6 class="card-title mb-2"><?= __( 'admin.system_license') ?></h6>
                      <p class="card-text text-muted small mb-3"><?= __('admin.license_information') ?></p>
                      <a href="<?= base_url('admincontrol/script_details') ?>" target="_blank" role="button" class="btn btn-outline-warning btn-sm w-100"><?php echo __( 'admin.go_to_module') ?></a>
                    </div>
                  </div>
                </div>

                <div class="col">
                  <div class="card border-0 bg-light h-100 shadow-sm">
                    <div class="card-body text-center p-3">
                      <div class="mb-3">
                        <i class="bi bi-brush-fill fs-1 text-warning"></i>
                      </div>
                      <h6 class="card-title mb-2"><?= __( 'admin.admin_user_theme') ?></h6>
                      <p class="card-text text-muted small mb-3"><?= __('admin.admin_theme_settings') ?></p>
                      <a href="<?= base_url('admincontrol/paymentsetting') ?>" target="_blank" role="button" class="btn btn-outline-warning btn-sm w-100"><?php echo __( 'admin.go_to_module') ?></a>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>

      <!-- Integration Plugins Section -->
      <div class="row mb-4">
        <div class="col-12">
          <div class="card border-0 shadow-sm">
            <div class="card-header bg-secondary text-white py-3">
              <div class="d-flex align-items-center">
                <i class="bi bi-plug me-2 fs-4"></i>
                <div>
                  <h4 class="mb-0 fw-bold"><?= __('admin.integration_modules') ?></h4>
                  <small class="opacity-75"><?= __('admin.third_party_integrations') ?></small>
                </div>
              </div>
            </div>
            <div class="card-body p-3">
              <?= $integration_modules_view; ?>
            </div>
          </div>
        </div>
      </div>

    </div> <!-- End col-12 -->
  </div> <!-- End row -->
</div> <!-- End container-fluid -->

<script type="text/javascript">
  $("input[data-setting_key='depositstatus']").on('change',function(){
    if($(this).is(':checked')){
      Swal.fire({
        icon: 'info',
        text: "<?= __('admin.vendor_deposit_on_message')  ?>",
      })
    } else {
      Swal.fire({
        icon: 'warning',
        text: "<?= __('admin.vendor_deposit_off_message')  ?>",
      }) 
    }
  })

  $(document).on('change', '.activity', function(){
    let setting_type = $(this).data('setting_type');
    let setting_key = $(this).data('setting_key');
    let val = $(this).prop('checked') ? 1 : 0;
    
    let menu =  $(this).data('sidebar');
    let $card = $(this).closest('.card');
    let $badge = $card.find('.badge');

    if(val) {
      $('#sidebar_'+menu).show();
      // Update card appearance for active state
      $card.removeClass('border-secondary').addClass('border-success');
      $badge.removeClass('bg-secondary').addClass('bg-success').text("<?= __('admin.active') ?>");
    } else {
      $('#sidebar_'+menu).hide();
      // Update card appearance for inactive state
      $card.removeClass('border-success').addClass('border-secondary');
      $badge.removeClass('bg-success').addClass('bg-secondary').text("<?= __('admin.inactive') ?>");
    }

    $.ajax({
      type: "POST",
      data: {
        action: 'change_status', 
        setting_type: setting_type, 
        setting_key : setting_key, 
        val : val
      },
      success: function(res){
        if(typeof showToast === 'function') {
          let statusText = val ? '<?= __("admin.enabled") ?>' : '<?= __("admin.disabled") ?>';
          showToast('<?= __("admin.success") ?>', '<?= __("admin.module_status_updated") ?> ' + statusText, 'success', 2000);
        }
      },
      error: function(){
        if(typeof showToast === 'function') {
          showToast('<?= __("admin.error") ?>', '<?= __("admin.something_went_wrong") ?>', 'error', 3000);
        }
        $(this).prop('checked', !val);
      }
    });
  });

  // Dynamically set card appearance based on checkbox values
  $('.activity').each(function() {
    var $card = $(this).closest('.card');
    var $badge = $card.find('.badge');
    if ($(this).is(':checked')) {
      $card.removeClass('border-secondary').addClass('border-success');
      $badge.removeClass('bg-secondary').addClass('bg-success').text("<?= __('admin.active') ?>");
    } else {
      $card.removeClass('border-success').addClass('border-secondary');
      $badge.removeClass('bg-success').addClass('bg-secondary').text("<?= __('admin.inactive') ?>");
    }
  });
</script>
