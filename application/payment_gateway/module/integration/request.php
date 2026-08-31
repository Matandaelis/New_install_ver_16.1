<?php
/**
 * Stripe Integration Module - Request
 * Creates Stripe Payment Link when campaign is created
 */

// This file is called when admin creates a new integration campaign with Stripe
// $settingData contains Stripe configuration
// $gatewayData contains campaign information (name, price, currency, etc.)

$stripe_settings = $settingData;
$campaign_data = $gatewayData;

// Get the appropriate API keys based on environment
$environment = isset($stripe_settings['environment']) ? (int)$stripe_settings['environment'] : 0;
$secret_key = $environment === 1 ? $stripe_settings['live_secret_key'] : $stripe_settings['test_secret_key'];

if (empty($secret_key)) {
    return [
        'success' => false,
        'error' => 'Stripe API key not configured. Please configure Stripe in Payment Gateways settings.'
    ];
}

try {
    // Initialize Stripe
    require_once APPPATH . 'payment_gateway/library/stripe/stripe.php';
    \Stripe\Stripe::setApiKey($secret_key);
    
    // Get campaign details
    $product_name = $campaign_data['name'] ?? 'Product';
    $price = isset($campaign_data['price']) ? (float)$campaign_data['price'] : 0;
    $currency = $campaign_data['currency'] ?? 'usd';
    $campaign_id = $campaign_data['campaign_id'] ?? 0;
    
    if ($price <= 0) {
        return [
            'success' => false,
            'error' => 'Invalid price. Price must be greater than 0.'
        ];
    }
    
    // Create Stripe Price
    $stripe_price = \Stripe\Price::create([
        'unit_amount' => (int)($price * 100), // Convert to cents
        'currency' => strtolower($currency),
        'product_data' => [
            'name' => $product_name,
        ],
    ]);
    
    // Create Stripe Payment Link
    $payment_link = \Stripe\PaymentLink::create([
        'line_items' => [[
            'price' => $stripe_price->id,
            'quantity' => 1,
        ]],
        'after_completion' => [
            'type' => 'redirect',
            'redirect' => [
                'url' => base_url('stripe_integration/success?campaign_id=' . $campaign_id),
            ],
        ],
        'metadata' => [
            'campaign_id' => $campaign_id,
            'integration_type' => 'affiliate_campaign',
        ],
    ]);
    
    // Return success with payment link URL and IDs
    return [
        'success' => true,
        'payment_link_url' => $payment_link->url,
        'stripe_price_id' => $stripe_price->id,
        'stripe_payment_link_id' => $payment_link->id,
    ];
    
} catch (\Stripe\Exception\ApiErrorException $e) {
    return [
        'success' => false,
        'error' => 'Stripe API Error: ' . $e->getMessage()
    ];
} catch (\Exception $e) {
    return [
        'success' => false,
        'error' => 'Error: ' . $e->getMessage()
    ];
}
