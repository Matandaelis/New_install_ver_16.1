<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Access Denied</title>
    <link rel="stylesheet" href="<?= base_url('assets/template/css/bootstrap.min.css') ?>">
    <link rel="stylesheet" href="<?= base_url('assets/template/css/404-css.css') ?>">
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
    <div class="c" style="max-width:480px">
        <div class="_404">Access Denied</div>
        <hr>
        <div class="_1">Proxy / VPN Detected</div>
        <div class="_2 mt-2">Use of proxies or VPNs violates our store policy and may result in account restriction.</div>
        <a class="back-btn" href="<?= base_url('store') ?>">&#8592; Return to Store</a>
    </div>
</body>
</html>
