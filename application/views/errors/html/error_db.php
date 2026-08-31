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

if (!isset($heading)) {
    $heading = 'Database Error';
}
if (!isset($message)) {
    $message = 'A database error occurred. Please check the database configuration file at application/config/database.php';
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Database Error</title>
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
    <div class="c" style="max-width:560px">
        <div class="_404">Database Error</div>
        <hr>
        <div class="_1"><?= htmlspecialchars($heading, ENT_QUOTES, 'UTF-8') ?></div>
        <div class="_2 mt-2"><?= htmlspecialchars($message, ENT_QUOTES, 'UTF-8') ?></div>
    </div>
</body>
</html>
