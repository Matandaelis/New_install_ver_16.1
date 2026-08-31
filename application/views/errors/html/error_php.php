<?php
defined('BASEPATH') OR exit('No direct script access allowed');
$base_url = config_item('base_url');
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>PHP Error</title>
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
    <div class="c" style="max-width:640px;text-align:left">
        <div class="_404" style="text-align:center">PHP Error</div>
        <hr>
        <table class="table table-sm table-borderless mb-0" style="font-size:.85rem">
            <tr><th style="width:120px;color:#6b7280">Severity</th><td><?= htmlspecialchars($severity ?? '', ENT_QUOTES, 'UTF-8') ?></td></tr>
            <tr><th style="color:#6b7280">Message</th><td><?= htmlspecialchars($message ?? '', ENT_QUOTES, 'UTF-8') ?></td></tr>
            <tr><th style="color:#6b7280">File</th><td style="word-break:break-all"><?= htmlspecialchars($filepath ?? '', ENT_QUOTES, 'UTF-8') ?></td></tr>
            <tr><th style="color:#6b7280">Line</th><td><?= htmlspecialchars((string)($line ?? ''), ENT_QUOTES, 'UTF-8') ?></td></tr>
        </table>
        <?php if (defined('SHOW_DEBUG_BACKTRACE') && SHOW_DEBUG_BACKTRACE === TRUE): ?>
        <hr>
        <p class="fw-bold mb-2" style="font-size:.85rem">Backtrace</p>
        <?php foreach (debug_backtrace() as $error): ?>
            <?php if (isset($error['file']) && strpos($error['file'], realpath(BASEPATH)) !== 0): ?>
            <div style="font-size:.78rem;background:#f8f9fa;border-radius:6px;padding:.5rem .75rem;margin-bottom:.4rem;word-break:break-all">
                <span style="color:#6b7280">File:</span> <?= htmlspecialchars($error['file'], ENT_QUOTES, 'UTF-8') ?><br>
                <span style="color:#6b7280">Line:</span> <?= htmlspecialchars((string)$error['line'], ENT_QUOTES, 'UTF-8') ?><br>
                <span style="color:#6b7280">Function:</span> <?= htmlspecialchars($error['function'], ENT_QUOTES, 'UTF-8') ?>
            </div>
            <?php endif ?>
        <?php endforeach ?>
        <?php endif ?>
    </div>
</body>
</html>
