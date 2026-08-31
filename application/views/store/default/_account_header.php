<?php
/**
 * Default theme — Shared account page header banner partial
 *
 * @contract  Store API v1 — fragment: _account_header (included by account pages before content)
 * @see       Storeapp::view() — shared partial, no direct API endpoint
 *
 * VARIABLES (set before include by parent account page)
 *   $hdr_icon   string  Font Awesome class for the icon (e.g. 'fa fa-user')
 *   $hdr_title  string  Page title text (already translated)
 *   $hdr_sub    string  Subtitle / breadcrumb text
 *   $hdr_pills  array   Optional stat pills [['num'=>X, 'lbl'=>'Label', 'color'=>'#fff'], ...]
 *
 * GLOBALS (inherited from parent page scope)
 *   $store_setting  array   All store settings key-value map
 *   $client         array   Logged-in customer data
 */
$hdr_icon  = isset($hdr_icon)  ? $hdr_icon  : 'fa fa-circle';
$hdr_title = isset($hdr_title) ? $hdr_title : '';
$hdr_sub   = isset($hdr_sub)   ? $hdr_sub   : '';
$hdr_pills = isset($hdr_pills) ? $hdr_pills : [];
?>
<div class="acc-page-header">
    <div class="container">
        <div class="acc-page-header__inner">
            <div>
                <h1 class="acc-page-header__title">
                    <i class="<?= $hdr_icon ?>"></i> <?= $hdr_title ?>
                </h1>
                <p class="acc-page-header__sub"><?= $hdr_sub ?></p>
            </div>
            <?php if (!empty($hdr_pills)): ?>
            <div class="d-flex" style="gap:10px;flex-wrap:wrap">
                <?php foreach ($hdr_pills as $pill): ?>
                <div class="acc-stat-pill">
                    <span class="acc-stat-pill__num" style="color:<?= $pill['color'] ?? '#fff' ?>">
                        <?= $pill['num'] ?>
                    </span>
                    <span class="acc-stat-pill__lbl"><?= $pill['lbl'] ?></span>
                </div>
                <?php endforeach; ?>
            </div>
            <?php endif; ?>
        </div>
    </div>
</div>
