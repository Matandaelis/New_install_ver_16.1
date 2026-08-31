<?php
/**
 * Stripe Integration Module - Callback
 * Handles Stripe Webhook for Integration Tools
 */

// This file is called by Stripe webhook when payment is completed
// $settingData contains Stripe configuration
// $gatewayData contains webhook event data

$stripe_settings = $settingData;
$webhook_data = $gatewayData;

// Get webhook secret
$webhook_secret = $stripe_settings['webhook_secret'] ?? '';

if (empty($webhook_secret)) {
    http_response_code(400);
    echo json_encode(['error' => 'Webhook secret not configured']);
    exit;
}

try {
    // Get the raw POST body
    $payload = @file_get_contents('php://input');
    $sig_header = $_SERVER['HTTP_STRIPE_SIGNATURE'] ?? '';
    
    if (empty($sig_header)) {
        throw new Exception('No signature header');
    }
    
    // Initialize Stripe
    require_once APPPATH . 'payment_gateway/library/stripe/stripe.php';
    
    // Verify webhook signature
    $event = \Stripe\Webhook::constructEvent(
        $payload,
        $sig_header,
        $webhook_secret
    );
    
    // Handle the event
    if ($event->type === 'checkout.session.completed') {
        $session = $event->data->object;
        
        // Get campaign ID from metadata
        $campaign_id = $session->metadata->campaign_id ?? 0;
        $amount = $session->amount_total / 100; // Convert from cents
        $currency = strtoupper($session->currency);
        $customer_email = $session->customer_details->email ?? '';
        
        // Get affiliate ID from client_reference_id (if passed)
        $af_id = $session->client_reference_id ?? '';
        
        // Record the order in integration system
        // This will be handled by the IntegrationModel
        return [
            'success' => true,
            'campaign_id' => $campaign_id,
            'order_id' => $session->id,
            'amount' => $amount,
            'currency' => $currency,
            'customer_email' => $customer_email,
            'af_id' => $af_id,
            'payment_status' => $session->payment_status,
        ];
    }
    
    // Return success for other event types
    return ['success' => true, 'message' => 'Event received'];
    
} catch (\Stripe\Exception\SignatureVerificationException $e) {
    http_response_code(400);
    return ['success' => false, 'error' => 'Invalid signature'];
} catch (\Exception $e) {
    http_response_code(400);
    return ['success' => false, 'error' => $e->getMessage()];
}
