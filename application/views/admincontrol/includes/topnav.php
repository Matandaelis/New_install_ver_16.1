<?php
$db        =& get_instance();
$products  = $db->Product_model;
$page_id   = $products->page_id();
$serverReq = checkReq();
require APPPATH."config/breadcrumb.php";
$pageKey = $db->Product_model->page_id();
$_uri    = $db->uri->uri_string();

// No breadcrumb suppression — all pages show breadcrumbs when a breadcrumb entry exists.

$_hasBreadcrumb = isset($pageSetting[$pageKey]['breadcrumb'])
                  && count($pageSetting[$pageKey]['breadcrumb']) > 0;
if (!$_hasBreadcrumb) { return; }

$_crumbs      = $pageSetting[$pageKey]['breadcrumb'];
$_crumbCount  = count($_crumbs) - 1;
?>
<div style="padding:4px 20px 0;margin-bottom:14px;">
    <nav style="display:flex;align-items:center;gap:5px;font-size:.76rem;flex-wrap:wrap;">
        <?php foreach ($_crumbs as $k => $v): ?>
            <?php if ($k > 0): ?>
                <i class="fas fa-chevron-right" style="color:#d1d5db;font-size:.55rem;"></i>
            <?php endif; ?>
            <?php if ($_crumbCount === $k): ?>
                <span style="color:#374151;font-weight:600;">
                    <?php if ($k === 0): ?><i class="fas fa-home" style="font-size:.7rem;margin-right:3px;"></i><?php endif; ?><?= $v['title'] ?>
                </span>
            <?php else: ?>
                <a href="<?= $v['link'] ?>" style="color:#9ca3af;text-decoration:none;" onmouseover="this.style.color='#374151'" onmouseout="this.style.color='#9ca3af'">
                    <?php if ($k === 0): ?><i class="fas fa-home" style="font-size:.7rem;margin-right:3px;"></i><?php endif; ?><?= $v['title'] ?>
                </a>
            <?php endif; ?>
        <?php endforeach; ?>
    </nav>
</div>
