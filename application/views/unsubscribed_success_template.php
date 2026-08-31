<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= (!empty($title)) ? htmlspecialchars($title) : __('user.success') ?></title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    <style>
        body { background: linear-gradient(135deg, #f0f4f8 0%, #e8edf5 100%); }
        .unsub-card { max-width: 540px; border-radius: 1.5rem; }
        .unsub-icon-wrap { width: 100px; height: 100px; background: linear-gradient(135deg, #fff3cd, #fde68a); border-radius: 50%; display: flex; align-items: center; justify-content: center; font-size: 2.8rem; color: #d97706; box-shadow: 0 4px 20px rgba(217,119,6,0.2); }
        .resub-banner { background: linear-gradient(135deg, #f0fdf4, #dcfce7); border: 1px solid #86efac; border-radius: 0.75rem; }
    </style>
</head>
<body class="d-flex align-items-center justify-content-center min-vh-100 py-4">
    <div class="unsub-card card border-0 shadow-lg p-4 p-md-5 text-center mx-3">

        <div class="unsub-icon-wrap mx-auto mb-4">
            <i class="bi bi-envelope-x"></i>
        </div>

        <h3 class="fw-bold mb-2"><?= (!empty($title)) ? htmlspecialchars($title) : __('user.success') ?></h3>
        <p class="text-muted mb-4"><?= (!empty($message)) ? nl2br(htmlspecialchars($message)) : __('user.unsubscribed_success') ?></p>

        <?php if (!empty($resubscribe_done)): ?>
        <?php elseif (!empty($email_encoded)): ?>
        <div class="resub-banner p-3 mb-4 text-start">
            <div class="d-flex align-items-center gap-3 flex-wrap">
                <i class="bi bi-arrow-counterclockwise text-success fs-4 flex-shrink-0"></i>
                <div class="flex-grow-1">
                    <div class="fw-semibold text-success small"><?= __('user.changed_your_mind') ?></div>
                    <div class="text-muted small"><?= __('user.resubscribe_hint') ?></div>
                </div>
                <a href="<?= base_url('resubscribe/' . rawurlencode($email_encoded)) ?>" class="btn btn-success btn-sm flex-shrink-0">
                    <i class="bi bi-envelope-check me-1"></i><?= __('user.resubscribe') ?>
                </a>
            </div>
        </div>
        <?php endif; ?>

        <a href="<?= base_url() ?>" class="btn btn-outline-secondary px-5">
            <i class="bi bi-house me-1"></i><?= __('user.back_to_site') ?>
        </a>

    </div>
</body>
</html>
