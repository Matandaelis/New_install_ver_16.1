<div class="modal-dialog modal-lg modal-dialog-centered modal-dialog-scrollable intg-modal">
    <div class="modal-content">
        <div class="intg-modal-header">
            <div class="intg-modal-header-left">
                <div class="intg-modal-icon intg-modal-icon--secondary">
                    <i class="bi bi-file-earmark-text"></i>
                </div>
                <div>
                    <h5 class="intg-modal-title"><?= __('admin.terms') ?></h5>
                    <p class="intg-modal-subtitle"><?= htmlspecialchars($terms_data['name'] ?? '') ?></p>
                </div>
            </div>
            <button type="button" class="intg-modal-close" data-bs-dismiss="modal" aria-label="Close"><i class="bi bi-x-lg"></i></button>
        </div>

        <link rel="stylesheet" type="text/css" href="<?= base_url('assets/integration/prism/css.css') ?>?v=<?= av() ?>">
        <script type="text/javascript" src="<?= base_url('assets/integration/prism/js.js') ?>"></script>
        <script type="text/javascript" src="<?= base_url('assets/integration/prism/clipboard.min.js') ?>"></script>

        <div class="modal-body">
            <?php if(!empty($terms_data['terms'])) { ?>
                <div class="intg-modal-card">
                    <?= $terms_data['terms'] ?>
                </div>
            <?php } else { ?>
                <div class="intg-modal-card text-center py-4">
                    <i class="bi bi-file-earmark-x d-block mb-2 intg-modal-empty-icon"></i>
                    <span class="text-muted"><?= __('admin.there_is_not_terms_available') ?></span>
                </div>
            <?php } ?>
        </div>
        <div class="intg-modal-footer">
            <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal"><i class="bi bi-x-lg me-1"></i><?= __('admin.close') ?></button>
        </div>
    </div>
</div>
