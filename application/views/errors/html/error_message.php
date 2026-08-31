<?php
defined('BASEPATH') OR exit('No direct script access allowed');
$base_url = config_item('base_url');
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Permission Error</title>
    <link rel="stylesheet" href="<?= $base_url ?>assets/template/css/bootstrap.min.css">
    <link rel="stylesheet" href="<?= $base_url ?>assets/template/css/404-css.css">
</head>
<body>
    <div id="clouds">
        <div class="cloud x1"></div>
        <div class="cloud x1_5"></div>
        <div class="cloud x2"></div>
        <div class="cloud x3"></div>
        <div class="cloud x4"></div>
        <div class="cloud x5"></div>
    </div>
    <div class="c">
        <div class="_404">Permission Error</div>
        <hr>
        <div class="_1"><?= htmlspecialchars($heading ?? '') ?></div>
        <div class="_2 mt-2"><?= htmlspecialchars($message ?? '') ?></div>
        <a href="<?= $base_url ?>" class="back-btn">&#8592; <?= __('store.go_to_home') ?></a>
    </div>
</body>
</html>
