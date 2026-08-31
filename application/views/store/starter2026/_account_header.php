<?php
/**
 * Starter 2026 — Account Page Hero Banner (partial)
 *
 * @contract  Store API v1 — shared partial: _account_header
 * @note      Include this partial at the top of any account page. Set variables before including.
 *
 * REQUIRED (set by parent view before include)
 *   $s26hdr_icon    string  Font Awesome icon class, e.g. "fas fa-user"
 *   $s26hdr_eyebrow string  Small breadcrumb/eyebrow text line
 *   $s26hdr_title   string  Main page title (already translated)
 *   $s26hdr_sub     string  Subtitle / short description
 *
 * OPTIONAL
 *   $s26hdr_stats   array   Stat boxes: [['val'=>'12', 'lbl'=>'Orders', 'color'=>'#fff'], ...]
 */
$s26hdr_icon    = isset($s26hdr_icon)    ? $s26hdr_icon    : 'fas fa-circle';
$s26hdr_eyebrow = isset($s26hdr_eyebrow) ? $s26hdr_eyebrow : '';
$s26hdr_title   = isset($s26hdr_title)   ? $s26hdr_title   : '';
$s26hdr_sub     = isset($s26hdr_sub)     ? $s26hdr_sub     : '';
$s26hdr_stats   = isset($s26hdr_stats)   ? $s26hdr_stats   : [];
?>
<div class="s26-acc-hero">
    <div class="s26-acc-hero__blob s26-acc-hero__blob--1"></div>
    <div class="s26-acc-hero__blob s26-acc-hero__blob--2"></div>
    <div class="container">
        <div class="s26-acc-hero__inner">
            <div>
                <?php if ($s26hdr_eyebrow): ?>
                <p class="s26-acc-hero__eyebrow"><?= $s26hdr_eyebrow ?></p>
                <?php endif; ?>
                <h1 class="s26-acc-hero__title">
                    <i class="<?= $s26hdr_icon ?>"></i> <?= $s26hdr_title ?>
                </h1>
                <?php if ($s26hdr_sub): ?>
                <p class="s26-acc-hero__sub"><?= $s26hdr_sub ?></p>
                <?php endif; ?>
            </div>
            <?php if (!empty($s26hdr_stats)): ?>
            <div class="s26-acc-stats">
                <?php foreach ($s26hdr_stats as $stat): ?>
                <div class="s26-acc-stat">
                    <div class="s26-acc-stat__val" style="color:<?= $stat['color'] ?? '#fff' ?>">
                        <?= $stat['val'] ?>
                    </div>
                    <div class="s26-acc-stat__lbl"><?= $stat['lbl'] ?></div>
                </div>
                <?php endforeach; ?>
            </div>
            <?php endif; ?>
        </div>
    </div>
</div>
