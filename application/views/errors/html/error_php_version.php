<?php
if (!defined('BASEPATH')) define('BASEPATH', 'Direct access not allowed');

if (!function_exists('config_item')) {
    function config_item($item) {
        if ($item == 'base_url') {
            $protocol = isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off' ? 'https' : 'http';
            $host = $_SERVER['HTTP_HOST'];
            $baseDir = rtrim(dirname($_SERVER['SCRIPT_NAME']), '/\\') . '/';
            return $protocol . '://' . $host . $baseDir;
        }
        return null;
    }
}

$base_url = config_item('base_url');
$currentPHPVersion    = phpversion();
$requiredMinVersion   = '7.4';
$supportedMaxVersion  = '8.2';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>PHP Version Error</title>
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
    <div class="c" style="max-width:520px">
        <div class="_404">PHP Version Error</div>
        <hr>
        <div class="_1">Unsupported PHP Version</div>
        <div class="_2 mt-2">
            Your server is running PHP <strong><?= htmlspecialchars($currentPHPVersion, ENT_QUOTES, 'UTF-8') ?></strong>.
            This application requires PHP <strong><?= $requiredMinVersion ?>+</strong>
            (up to <strong><?= $supportedMaxVersion ?></strong>).
            Please upgrade or contact your hosting provider.
        </div>
    </div>
</body>
</html>
