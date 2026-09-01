<?php
/**
 * Starter2026 theme — Shared account sub-navigation partial
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
<div class="s26-acc-subnav">
    <div class="container">
        <nav class="s26-acc-subnav__inner">
            <button class="s26-acc-subnav__toggle" type="button"
                    data-bs-toggle="collapse" data-bs-target="#s26AccSubnav"
                    aria-controls="s26AccSubnav" aria-expanded="false" aria-label="<?= __('store.user_menu') ?>">
                <i class="fas fa-bars" aria-hidden="true"></i>
                <span><?= __('store.menu') ?: 'Menu' ?></span>
            </button>
            <div class="collapse s26-acc-subnav__collapse" id="s26AccSubnav">
                <ul class="s26-acc-subnav__list">
                    <li class="s26-acc-subnav__item">
                        <a class="s26-acc-subnav__link <?= $acc_active === 'profile' ? 's26-acc-subnav__link--active' : '' ?>"
                           href="<?= $base_url ?>profile">
                            <i class="fa fa-user" aria-hidden="true"></i> <?= __('store.profile') ?>
                        </a>
                    </li>
                    <li class="s26-acc-subnav__item">
                        <a class="s26-acc-subnav__link <?= $acc_active === 'orders' ? 's26-acc-subnav__link--active' : '' ?>"
                           href="<?= $base_url ?>order">
                            <i class="fa fa-gift" aria-hidden="true"></i> <?= __('store.orders') ?>
                        </a>
                    </li>
                    <li class="s26-acc-subnav__item">
                        <a class="s26-acc-subnav__link <?= $acc_active === 'my_courses' ? 's26-acc-subnav__link--active' : '' ?>"
                           href="<?= $base_url ?>my_courses">
                            <i class="fa fa-graduation-cap" aria-hidden="true"></i> <?= __('store.my_courses') ?>
                        </a>
                    </li>
                    <li class="s26-acc-subnav__item">
                        <a class="s26-acc-subnav__link <?= $acc_active === 'shipping' ? 's26-acc-subnav__link--active' : '' ?>"
                           href="<?= $base_url ?>shipping">
                            <i class="fa fa-truck" aria-hidden="true"></i> <?= __('store.shipping') ?>
                        </a>
                    </li>
                    <li class="s26-acc-subnav__item">
                        <a class="s26-acc-subnav__link <?= $acc_active === 'wishlist' ? 's26-acc-subnav__link--active' : '' ?>"
                           href="<?= $base_url ?>wishlist">
                            <i class="fa fa-heart" aria-hidden="true"></i> <?= __('store.wishlist') ?>
                        </a>
                    </li>
                    <li class="s26-acc-subnav__item">
                        <a class="s26-acc-subnav__link s26-acc-subnav__link--logout" href="<?= $base_url ?>logout">
                            <i class="fa fa-power-off" aria-hidden="true"></i> <?= __('store.logout') ?>
                        </a>
                    </li>
                </ul>
            </div>
        </nav>
    </div>
</div>
