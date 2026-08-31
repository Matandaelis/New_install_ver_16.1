<div class="container-fluid">
<div class="row">
    <div class="col-12">
        <div class="card mb-4 shadow">
            <div class="card-header bg-light">
                <h5 class="mb-0"><i class="fas fa-plug me-2 text-primary"></i><?= __('admin.integration_modules') ?></h5>
            </div>
            <div class="card-body">
                <div class="row row-cols-1 row-cols-sm-2 row-cols-md-3 row-cols-lg-4 row-cols-xl-6 g-4">
                    <?php foreach ($integration_modules as $key => $module) { ?>
                        <div class="col">
                            <div class="card h-100 border hover-shadow transition">
                                <a href="<?= base_url('usercontrol/instructions/'. $key) ?>" class="text-decoration-none">
                                    <div class="position-relative overflow-hidden">
                                        <img src="<?= $module['image'] ?>" class="card-img-top" alt="<?= htmlspecialchars($module['name']) ?>">
                                    </div>
                                    <div class="card-body text-center">
                                        <h6 class="card-title mb-0 text-primary fw-semibold"><?= $module['name'] ?></h6>
                                    </div>
                                </a>
                            </div>
                        </div>
                    <?php } ?>
                </div>
            </div>
        </div>
    </div>
</div>
</div>
