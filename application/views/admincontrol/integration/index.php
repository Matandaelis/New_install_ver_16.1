<div class="row row-cols-1 row-cols-sm-2 row-cols-md-3 row-cols-lg-4 row-cols-xl-6 g-3">
    <?php foreach ($integration_modules as $key => $module): ?>
        <div class="col">
            <div class="card border-0 bg-light h-100 shadow-sm">
                <div class="card-body text-center p-3">
                    <div class="mb-3">
                        <img class="img-fluid" src="<?= $module['image'] ?>" alt="<?= $module['name'] ?>" style="max-height: 40px; max-width: 60px;">
                    </div>
                    <h6 class="card-title mb-2 text-truncate"><?= $module['name'] ?></h6>
                    <?php if ($key === 'postback'): ?>
                        <a href="<?= base_url('admincontrol/market_tools_setting') ?>" class="btn btn-outline-secondary btn-sm w-100"><?= __('admin.configure') ?></a>
                    <?php else: ?>
                        <a href="<?= base_url("integration/instructions/{$key}") ?>" class="btn btn-outline-secondary btn-sm w-100"><?= __('admin.view_instructions') ?></a>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    <?php endforeach; ?>
</div>