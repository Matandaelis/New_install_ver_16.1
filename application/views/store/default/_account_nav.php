<?php
/**
 * Default theme — Shared account sub-navigation partial
 *
 * @contract  Store API v1 — fragment: _account_nav (included by all account pages)
 * @see       Storeapp::view() — shared partial, no direct API endpoint
 *
 * VARIABLES (set before include via PHP variable injection)
 *   $acc_active  string  Active nav item slug: 'profile' | 'orders' | 'my_courses' | 'shipping' | 'wishlist'
 *
 * GLOBALS (inherited from parent page scope)
 *   $store_setting  array   All store settings key-value map
 *   $client         array   Logged-in customer data
 *   $home_link      string  Absolute URL to store homepage
 */
$acc_active = isset($acc_active) ? $acc_active : '';
?>
<div class="acc-subnav">
    <div class="container">
        <nav class="navbar navbar-expand-lg navbar-light">
            <button class="navbar-toggler my-2" type="button" data-bs-toggle="collapse"
                    data-bs-target="#accSubnav" aria-controls="accSubnav" aria-expanded="false">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="accSubnav">
                <ul class="nav navbar-nav">
                    <li class="nav-item">
                        <a class="nav-link <?= $acc_active === 'profile'     ? 'active' : '' ?>"
                           href="<?= $base_url ?>profile">
                            <i class="fa fa-user"></i> <?= __('store.profile') ?>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link <?= $acc_active === 'orders'      ? 'active' : '' ?>"
                           href="<?= $base_url ?>order">
                            <i class="fa fa-gift"></i> <?= __('store.orders') ?>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link <?= $acc_active === 'my_courses'  ? 'active' : '' ?>"
                           href="<?= $base_url ?>my_courses">
                            <i class="fa fa-graduation-cap"></i> <?= __('store.my_courses') ?>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link <?= $acc_active === 'shipping'    ? 'active' : '' ?>"
                           href="<?= $base_url ?>shipping">
                            <i class="fa fa-truck"></i> <?= __('store.shipping') ?>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link <?= $acc_active === 'wishlist'    ? 'active' : '' ?>"
                           href="<?= $base_url ?>wishlist">
                            <i class="fa fa-heart"></i> <?= __('store.wishlist') ?>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link acc-logout" href="<?= $base_url ?>logout">
                            <i class="fa fa-power-off"></i> <?= __('store.logout') ?>
                        </a>
                    </li>
                </ul>
            </div>
        </nav>
    </div>
</div>
