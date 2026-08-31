<?php
/**
 * Stripe Integration Module - View
 * Redirects to Stripe Checkout
 */

$stripe_checkout_url = isset($gatewayData['target_link']) ? $gatewayData['target_link'] : '';

if (empty($stripe_checkout_url) || $stripe_checkout_url == '#') {
    echo '<div class="alert alert-danger">Stripe checkout link not available. Please contact administrator.</div>';
    exit;
}

header('Location: ' . $stripe_checkout_url);
exit;
