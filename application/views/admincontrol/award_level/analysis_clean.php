<?php if (!defined('BASEPATH')) exit('No direct script access allowed'); ?>

<?php if (!$award_level_status): ?>
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="card border-0 shadow-sm">
                    <div class="card-body text-center py-5">
                        <i class="bi bi-exclamation-triangle text-warning" style="font-size: 4rem;"></i>
                        <h4 class="mt-3 text-warning"><?= __('admin.module_disabled') ?></h4>
                        <p class="text-muted"><?= __('admin.module_activation_message') ?></p>
                        <a href="<?= base_url('admincontrol/settings') ?>" class="btn btn-primary">
                            <i class="bi bi-gear me-1"></i>
                            <?= __('admin.activate_module') ?>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
<?php else: ?>

<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <!-- Page Header -->
            <div class="d-flex justify-content-between align-items-center mb-4">
                <div>
                    <h4 class="mb-1">
                        <i class="bi bi-graph-up text-primary me-2"></i>
                        <?= __('admin.level_analysis') ?>
                    </h4>
                    <p class="text-muted mb-0"><?= __('admin.level_analysis_desc') ?></p>
                </div>
                <div class="d-flex gap-2">
                    <a href="<?= base_url('admincontrol/award_level/create') ?>" class="btn btn-primary">
                        <i class="bi bi-plus-circle me-1"></i>
                        <?= __('admin.create_level_with_ai') ?>
                    </a>
                    <a href="<?= base_url('admincontrol/award_level') ?>" class="btn btn-outline-secondary">
                        <i class="bi bi-arrow-left me-1"></i>
                        <?= __('admin.back_to_levels') ?>
                    </a>
                </div>
            </div>

            <?php if (isset($analysis) && $analysis['status'] === 'success'): ?>
                
                <!-- Statistics Cards -->
                <div class="row mb-4">
                    <div class="col-xl-3 col-lg-6 col-md-6 mb-3">
                        <div class="card border-0 shadow-sm h-100">
                            <div class="card-body">
                                <div class="d-flex align-items-center">
                                    <div class="icon-shape bg-primary bg-opacity-10 text-primary rounded-3 me-3">
                                        <i class="bi bi-people-fill fs-4"></i>
                                    </div>
                                    <div>
                                        <h6 class="text-muted mb-1"><?= __('admin.active_users') ?></h6>
                                        <h4 class="mb-0"><?= number_format($analysis['total_users']) ?></h4>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-xl-3 col-lg-6 col-md-6 mb-3">
                        <div class="card border-0 shadow-sm h-100">
                            <div class="card-body">
                                <div class="d-flex align-items-center">
                                    <div class="icon-shape bg-success bg-opacity-10 text-success rounded-3 me-3">
                                        <i class="bi bi-currency-dollar fs-4"></i>
                                    </div>
                                    <div>
                                        <h6 class="text-muted mb-1"><?= __('admin.total_earnings') ?></h6>
                                        <h4 class="mb-0"><?= $CurrencySymbol ?><?= number_format($analysis['total_earnings'], 2) ?></h4>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-xl-3 col-lg-6 col-md-6 mb-3">
                        <div class="card border-0 shadow-sm h-100">
                            <div class="card-body">
                                <div class="d-flex align-items-center">
                                    <div class="icon-shape bg-info bg-opacity-10 text-info rounded-3 me-3">
                                        <i class="bi bi-bar-chart fs-4"></i>
                                    </div>
                                    <div>
                                        <h6 class="text-muted mb-1"><?= __('admin.average_earnings') ?></h6>
                                        <h4 class="mb-0"><?= $CurrencySymbol ?><?= number_format($analysis['average_earnings'], 2) ?></h4>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-xl-3 col-lg-6 col-md-6 mb-3">
                        <div class="card border-0 shadow-sm h-100">
                            <div class="card-body">
                                <div class="d-flex align-items-center">
                                    <div class="icon-shape bg-warning bg-opacity-10 text-warning rounded-3 me-3">
                                        <i class="bi bi-award fs-4"></i>
                                    </div>
                                    <div>
                                        <h6 class="text-muted mb-1"><?= __('admin.current_levels') ?></h6>
                                        <h4 class="mb-0"><?= number_format(count($analysis['current_levels'])) ?></h4>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Earnings Distribution -->
                <div class="row mb-4">
                    <div class="col-12">
                        <div class="card border-0 shadow-sm">
                            <div class="card-header bg-light border-0">
                                <h5 class="card-title mb-0">
                                    <i class="bi bi-bar-chart text-primary me-2"></i>
                                    <?= __('admin.earnings_distribution') ?>
                                </h5>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="table-responsive">
                                            <table class="table table-borderless">
                                                <tbody>
                                                    <tr>
                                                        <td class="fw-semibold text-muted"><?= __('admin.percentile_25') ?></td>
                                                        <td class="text-end fw-bold text-success"><?= $CurrencySymbol ?><?= number_format($analysis['percentiles']['25th'], 2) ?></td>
                                                    </tr>
                                                    <tr>
                                                        <td class="fw-semibold text-muted"><?= __('admin.percentile_50') ?></td>
                                                        <td class="text-end fw-bold text-info"><?= $CurrencySymbol ?><?= number_format($analysis['percentiles']['50th'], 2) ?></td>
                                                    </tr>
                                                    <tr>
                                                        <td class="fw-semibold text-muted"><?= __('admin.percentile_75') ?></td>
                                                        <td class="text-end fw-bold text-warning"><?= $CurrencySymbol ?><?= number_format($analysis['percentiles']['75th'], 2) ?></td>
                                                    </tr>
                                                    <tr>
                                                        <td class="fw-semibold text-muted"><?= __('admin.percentile_90') ?></td>
                                                        <td class="text-end fw-bold text-danger"><?= $CurrencySymbol ?><?= number_format($analysis['percentiles']['90th'], 2) ?></td>
                                                    </tr>
                                                    <tr>
                                                        <td class="fw-semibold text-muted"><?= __('admin.percentile_95') ?></td>
                                                        <td class="text-end fw-bold text-primary"><?= $CurrencySymbol ?><?= number_format($analysis['percentiles']['95th'], 2) ?></td>
                                                    </tr>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="alert alert-info">
                                            <h6 class="alert-heading"><i class="bi bi-lightbulb me-1"></i><?= __('admin.insights') ?></h6>
                                            <p class="mb-0">
                                                <?= __('admin.level_analysis_insights') ?>
                                                <br><small class="text-muted">
                                                    <?= __('admin.use_ai_smart_fill_suggestion') ?>
                                                </small>
                                            </p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Sample Users -->
                <?php if (!empty($analysis['sample_users'])): ?>
                <div class="row">
                    <div class="col-12">
                        <div class="card border-0 shadow-sm">
                            <div class="card-header bg-light border-0">
                                <h5 class="card-title mb-0">
                                    <i class="bi bi-people text-primary me-2"></i>
                                    <?= __('admin.sample_users') ?>
                                </h5>
                            </div>
                            <div class="card-body">
                                <div class="table-responsive">
                                    <table class="table table-hover align-middle">
                                        <thead class="table-light">
                                            <tr>
                                                <th class="fw-semibold"><?= __('admin.username') ?></th>
                                                <th class="fw-semibold text-end"><?= __('admin.balance') ?></th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php foreach ($analysis['sample_users'] as $user): ?>
                                            <tr>
                                                <td>
                                                    <div class="d-flex align-items-center">
                                                        <div class="avatar-sm bg-primary bg-opacity-10 rounded-circle d-flex align-items-center justify-content-center me-2">
                                                            <i class="bi bi-person text-primary"></i>
                                                        </div>
                                                        <span class="fw-semibold"><?= htmlspecialchars($user['username']) ?></span>
                                                    </div>
                                                </td>
                                                <td class="text-end">
                                                    <span class="fw-bold text-success">
                                                        <?= $CurrencySymbol ?><?= number_format($user['balance'], 2) ?>
                                                    </span>
                                                </td>
                                            </tr>
                                            <?php endforeach; ?>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <?php endif; ?>

            <?php elseif (isset($analysis) && $analysis['status'] === 'no_data'): ?>
                <div class="card border-0 shadow-sm">
                    <div class="card-body text-center py-5">
                        <i class="bi bi-inbox text-muted" style="font-size: 4rem;"></i>
                        <h4 class="mt-3 text-muted"><?= __('admin.no_data_found') ?></h4>
                        <p class="text-muted"><?= $analysis['message'] ?></p>
                    </div>
                </div>
            <?php endif; ?>

        </div>
    </div>
</div>

<?php endif; ?>
