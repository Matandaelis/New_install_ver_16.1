<!-- Tutorial Page Content -->
<div class="container-fluid py-4">
    <div class="row">
        <div class="col-12">
            <?php 
            if(!isset($tutorial) || !is_array($tutorial) || count($tutorial)==0 )
            {
                ?>
                <!-- No Tutorial Found -->
                <div class="card border-0 shadow-sm">
                    <div class="card-body text-center py-5">
                        <div class="mb-4">
                            <i class="fas fa-exclamation-circle display-1 text-muted opacity-50"></i>
                        </div>
                        <h3 class="text-muted mb-0"><?= __('user.no_data_found') ?></h3>
                    </div>
                </div>
                <?php
            }
            else 
            {
                ?>
                <!-- Tutorial Content -->
                <div class="card border-0 shadow-sm">
                    <div class="card-header bg-primary text-white py-3">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <h4 class="mb-1 fw-bold">
                                    <i class="fas fa-file-alt me-2"></i><?= htmlspecialchars($tutorial['title']); ?>
                                </h4>
                                <p class="mb-0 opacity-75">
                                    <i class="fas fa-folder me-1"></i><?= htmlspecialchars($tutorial['name']); ?>
                                </p>
                            </div>
                        </div>
                    </div>
                    <div class="card-body p-4">
                        <div class="tutorial-content">
                            <?= isset($tutorial) ? $tutorial['content'] : '' ?>
                        </div>
                    </div>
                </div>
                <?php
            }
            ?>
        </div>
    </div>
</div>