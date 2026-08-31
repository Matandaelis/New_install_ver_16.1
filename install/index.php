<?php
    error_reporting(1);
session_start();
    define('BASEPATH', __DIR__);
    include_once 'helper.php';
    include_once 'version.php';
    if (!defined('SCRIPT_VERSION')) define('SCRIPT_VERSION', '');
    $base_url = base_path();
    $SCRIPT_VERSION = SCRIPT_VERSION;
    $root_url = root_url();
    
    if(checkIsInstall() && ___construct()){
        header("location:../index.php");die;
    }
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>AffiliatePro — Installation Wizard</title>
    <link rel="icon" type="image/x-icon" href="<?= $base_url ?>/assets/favicon.ico">
    <link rel="stylesheet" href="<?= $base_url ?>/assets/bootstrap.min.css">
    <link rel="stylesheet" href="<?= $base_url ?>/assets/bootstrap-icons.css">
    <link rel="stylesheet" href="<?= $base_url ?>/assets/css.css">
</head>
<body class="ins-body">

<!-- Sticky header -->
<header class="ins-header">
    <div class="ins-header-brand">
        <i class="bi bi-layers-fill ins-header-icon"></i>
        AffiliatePro
        <span class="ins-ver-badge">v<?= $SCRIPT_VERSION ?></span>
    </div>
    <div class="ins-header-meta">
        <i class="bi bi-shield-lock me-1"></i>Secure Installation
    </div>
</header>

<!-- Main content — step views are AJAX-injected into #main -->
<div id="main" class="ins-main">
    <div class="ins-loading">
        <div class="ins-spinner"></div>
        <p>Initializing installation wizard...</p>
    </div>
</div>

<!-- Footer -->
<footer class="ins-footer">
    AffiliatePro &copy; <?= date('Y') ?> &nbsp;&bull;&nbsp; Installation Wizard
</footer>

<script src="<?= $base_url ?>/assets/jquery.min.js"></script>
<script src="<?= $base_url ?>/assets/popper.min.js"></script>
<script src="<?= $base_url ?>/assets/bootstrap.min.js"></script>

<script type="text/javascript">
    $.ajax({
        url:'proccess.php',
        type:'POST',
        dataType:'json',
        data:{page:'step1'},
        success:function(json){
            $("#main").html(json['html']);
        },
        error: function(xhr, status, error) {
            $("#main").html(
                '<div class="ins-card">' +
                '<div class="ins-card-header amber"><i class="bi bi-exclamation-triangle h-icon"></i>Error Loading Installation</div>' +
                '<div class="ins-card-body"><p style="color:#c9d1d9;margin-bottom:8px;">Unable to load the installation step. Please check your server configuration and refresh the page.</p>' +
                '<code style="color:#f85149;font-size:.78rem;">' + error + '</code></div></div>'
            );
            console.error('Installation loading error:', error);
        }
    });
</script>
</body>
</html>
