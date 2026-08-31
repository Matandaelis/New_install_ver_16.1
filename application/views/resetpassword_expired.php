<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title><?= __('user.reset_link_expired') ?></title>
  <?php include(APPPATH.'views/includes/layout.php'); ?>
</head>
<body class="bg-light d-flex align-items-center vh-100">
  <div class="container">
    <div class="row justify-content-center">
      <div class="col-md-5">
        <div class="card shadow-sm border-0">
          <div class="card-body text-center p-5">
            <div class="mb-4">
              <i class="fas fa-clock fa-3x text-warning"></i>
            </div>
            <h5 class="fw-bold mb-2"><?= __('user.reset_link_expired') ?></h5>
            <p class="text-muted mb-4"><?= __('user.reset_token_expired') ?></p>
            <a href="<?= base_url('forget-password') ?>" class="btn btn-primary px-4">
              <i class="fas fa-redo me-2"></i><?= __('user.request_new_link') ?>
            </a>
          </div>
        </div>
      </div>
    </div>
  </div>
</body>
</html>
