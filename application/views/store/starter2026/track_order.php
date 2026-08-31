<?php
/**
 * Starter 2026 — Track Your Order Page
 *
 * @contract  Store API v1 — page: track_order
 *
 * GLOBALS  $store_setting, $home_link, $base_url, $googlerecaptcha
 *
 * PAGE VARIABLES
 *   $track_form_values  array  Pre-filled form values after search [{order_id, email, result, ...}]
 */
?>

<section class="s26-auth-page">
    <div class="container">

        <div class="text-center mb-5">
            <h1 class="s26-page-title"><?= __('store.track_your_order') ?? 'Track Your Order' ?></h1>
            <p class="s26-page-subtitle"><?= __('store.track_order_description') ?? 'Enter your order number and email to view your order details' ?></p>
        </div>

        <div class="row justify-content-center">
            <div class="col-lg-6 col-md-8">
                <div class="s26-auth-card">
                    <div class="s26-auth-card__body">

                        <?php if (!empty($error)): ?>
                            <div class="alert alert-danger" role="alert">
                                <?= htmlspecialchars($error) ?>
                            </div>
                        <?php endif; ?>

                        <form method="post" action="<?= base_url('store/track_order') ?>">
                            <div class="s26-form-group">
                                <label class="s26-form-label" for="order_number"><?= __('store.order_number') ?? 'Order Number' ?></label>
                                <div class="s26-input-wrap">
                                    <i class="fas fa-hashtag"></i>
                                    <input class="s26-form-input" type="text" id="order_number" name="order_number" placeholder="<?= __('store.enter_order_number') ?? 'e.g. 00006 or 6' ?>" value="<?= $track_form_values['order_number'] ?? '' ?>" required>
                                </div>
                            </div>
                            <div class="s26-form-group">
                                <label class="s26-form-label" for="email"><?= __('store.email_address') ?? 'Email Address' ?></label>
                                <div class="s26-input-wrap">
                                    <i class="fas fa-envelope"></i>
                                    <input class="s26-form-input" type="email" id="email" name="email" placeholder="<?= __('store.enter_email_address') ?? 'Email used for the order' ?>" value="<?= $track_form_values['email'] ?? '' ?>" required>
                                </div>
                            </div>
                            <button type="submit" class="s26-btn-primary w-100 justify-content-center">
                                <i class="fas fa-search"></i>
                                <?= __('store.view_order') ?? 'View Order' ?>
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>

    </div>
</section>
