<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card shadow-sm">
                <div class="card-header bg-primary text-white">
                    <div class="d-flex justify-content-between align-items-center">
                        <h4 class="mb-0">
                            <i class="fas fa-chart-pie me-2"></i><?= __('admin.statistics') ?>
                        </h4>
                        <div class="text-end">
                            <small class="text-light">
                                <i class="fas fa-info-circle me-1"></i>
                                <?= __('admin.data_updates_automatically') ?>
                            </small>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <!-- First row -->
                    <div class="row">
                        <?php
                        $first_row_categories = ['clicks', 'action_clicks'];
                        foreach ($first_row_categories as $category) {
                        ?>
                        <div class="col-lg-6 mb-4">
                            <div class="card h-100 shadow-sm" id="<?= $category ?>-small-card">
                                <div class="card-header bg-primary text-white">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <h5 class="mb-0">
                                            <i class="fas fa-mouse-pointer me-2"></i>
                                            <?= __('admin.' . $category . '_by_country') ?>
                                        </h5>
                                        <span class="badge bg-light text-dark fs-6"><?= (int)$statistics[$category . '_count'] ?></span>
                                    </div>
                                </div>
                                <div class="card-body d-flex flex-column">
                                    <?php if ((int)$statistics[$category . '_count'] > 0) { ?>
                                        <div id="<?= $category ?>-chart-small" class="w-100 flex-grow-1" style="height:300px;"></div>
                                        <div class="mt-3 text-center">
                                            <button type="button" class="btn btn-outline-primary" data-bs-toggle="modal" data-bs-target="#<?= $category ?>-large-modal">
                                                <i class="fas fa-expand me-1"></i><?= __('admin.view_larger') ?>
                                            </button>
                                        </div>
                                    <?php } else { ?>
                                        <div class="text-center flex-grow-1 d-flex flex-column justify-content-center">
                                            <i class="fas fa-chart-pie fa-5x text-muted mb-3"></i>
                                            <h5 class="text-muted"><?= __('admin.no_data_found') ?></h5>
                                            <p class="text-muted small"><?= __('admin.no_data_available') ?></p>
                                        </div>
                                    <?php } ?>
                                </div>
                            </div>

                            <!-- Large modal -->
                            <div class="modal fade" id="<?= $category ?>-large-modal" tabindex="-1">
                                <div class="modal-dialog modal-xl">
                                    <div class="modal-content">
                                        <div class="modal-header bg-primary text-white">
                                            <h5 class="modal-title">
                                                <i class="fas fa-chart-pie me-2"></i>
                                                <?= __('admin.' . $category . '_by_country') ?>
                                            </h5>
                                            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                                        </div>
                                        <div class="modal-body p-4">
                                            <div id="<?= $category ?>-chart-large" style="height:500px;"></div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <?php } ?>
                    </div>

                    <!-- Second row -->
                    <div class="row">
                        <?php
                        $second_row_categories = ['sale', 'affiliate_user', 'client_user'];
                        foreach ($second_row_categories as $category) {
                        ?>
                        <div class="col-lg-4 mb-4">
                            <div class="card h-100 shadow-sm" id="<?= $category ?>-small-card">
                                <div class="card-header bg-primary text-white">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <h5 class="mb-0">
                                            <i class="fas fa-<?= $category === 'sale' ? 'shopping-cart' : ($category === 'affiliate_user' ? 'users' : 'user-tie') ?> me-2"></i>
                                            <?= __('admin.' . $category . '_by_country') ?>
                                        </h5>
                                        <span class="badge bg-light text-dark fs-6"><?= (int)$statistics[$category . '_count'] ?></span>
                                    </div>
                                </div>
                                <div class="card-body d-flex flex-column">
                                    <?php if ((int)$statistics[$category . '_count'] > 0) { ?>
                                        <div id="<?= $category ?>-chart-small" class="w-100 flex-grow-1" style="height:300px;"></div>
                                        <div class="mt-3 text-center">
                                            <button type="button" class="btn btn-outline-primary" data-bs-toggle="modal" data-bs-target="#<?= $category ?>-large-modal">
                                                <i class="fas fa-expand me-1"></i><?= __('admin.view_larger') ?>
                                            </button>
                                        </div>
                                    <?php } else { ?>
                                        <div class="text-center flex-grow-1 d-flex flex-column justify-content-center">
                                            <i class="fas fa-chart-pie fa-5x text-muted mb-3"></i>
                                            <h5 class="text-muted"><?= __('admin.no_data_found') ?></h5>
                                            <p class="text-muted small"><?= __('admin.no_data_available') ?></p>
                                        </div>
                                    <?php } ?>
                                </div>
                            </div>

                            <!-- Large modal -->
                            <div class="modal fade" id="<?= $category ?>-large-modal" tabindex="-1">
                                <div class="modal-dialog modal-xl">
                                    <div class="modal-content">
                                        <div class="modal-header bg-primary text-white">
                                            <h5 class="modal-title">
                                                <i class="fas fa-<?= $category === 'sale' ? 'shopping-cart' : ($category === 'affiliate_user' ? 'users' : 'user-tie') ?> me-2"></i>
                                                <?= __('admin.' . $category . '_by_country') ?>
                                            </h5>
                                            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                                        </div>
                                        <div class="modal-body p-4">
                                            <div id="<?= $category ?>-chart-large" style="height:500px;"></div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <?php } ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
	var statistics = <?php echo json_encode($statistics); ?>;
</script>

<script>
$(document).ready(function() {
    var colors = ['#007bff', '#6c757d', '#28a745', '#17a2b8', '#ffc107', '#dc3545', '#fd7e14', '#20c997', '#6f42c1', '#e83e8c'];

    function createMorrisDonut(elementId, data) {
        try {
            // Clear any existing content
            $('#' + elementId).empty();
            
            Morris.Donut({
                element: elementId,
                data: data,
                resize: true,
                colors: colors,
                formatter: function(value) {
                    return value.toLocaleString();
                }
            });
        } catch (error) {
            console.error('Error creating Morris chart for ' + elementId + ':', error);
            $('#' + elementId).html('<div class="text-center text-danger"><i class="fas fa-exclamation-triangle fa-3x mb-2"></i><p>Chart Error</p></div>');
        }
    }

    const categories = ['clicks', 'action_clicks', 'sale', 'affiliate_user', 'client_user'];
    
    categories.forEach(category => {
        // Check if statistics[category] exists and has data
        if (statistics[category] && Object.keys(statistics[category]).length > 0) {
            var data = Object.keys(statistics[category])
                .map(function(country) {
                    return { 
                        label: country, 
                        value: parseInt(statistics[category][country]) || 0 
                    };
                })
                .sort(function(a, b) { return b.value - a.value; }); // Sort by value descending

            // Create small chart
            if ($("#" + category + "-chart-small").length) {
                createMorrisDonut(category + "-chart-small", data);
            }

            // Modal large charts
            $('#' + category + '-large-modal').on('shown.bs.modal', function() {
                // Small delay to ensure modal is fully rendered
                setTimeout(function() {
                    createMorrisDonut(category + "-chart-large", data);
                }, 100);
            });
        } else {
            // If no data, display "No Data" message in the chart area
            $("#" + category + "-chart-small").html(`
                <div class="text-center flex-grow-1 d-flex flex-column justify-content-center">
                    <i class="fas fa-chart-pie fa-5x text-muted mb-3"></i>
                    <h5 class="text-muted"><?= __('admin.no_data_found') ?></h5>
                    <p class="text-muted small"><?= __('admin.no_data_available') ?></p>
                </div>
            `);
        }
    });

    // Add loading states for modals
    $('.modal').on('show.bs.modal', function() {
        var modalId = $(this).attr('id');
        var category = modalId.replace('-large-modal', '');
        $('#' + category + '-chart-large').html('<div class="d-flex justify-content-center align-items-center h-100"><div class="spinner-border text-primary" role="status"><span class="visually-hidden">Loading...</span></div></div>');
    });
});
</script>