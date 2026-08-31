<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Stripe Integration Controller
 * Handles webhooks and callbacks for Stripe Direct Checkout in Integration Tools
 */

class Stripe_integration extends MY_Controller {
    
    function __construct() {
        parent::__construct();
        $this->load->model('Product_model');
        $this->load->model('IntegrationModel');
        ___construct(1);
    }
    
    /**
     * Stripe Webhook Handler
     * Called by Stripe when payment is completed
     */
    public function webhook() {
        // Get Stripe settings
        $stripe_settings = $this->Product_model->getSettings('payment_gateway_stripe');
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
            
            // Initialize Stripe
            require_once APPPATH . 'payment_gateway/library/stripe/stripe.php';
            
            // Get the appropriate API key based on environment
            $environment = isset($stripe_settings['environment']) ? (int)$stripe_settings['environment'] : 0;
            $secret_key = $environment === 1 ? $stripe_settings['live_secret_key'] : $stripe_settings['test_secret_key'];
            \Stripe\Stripe::setApiKey($secret_key);
            
            // Verify webhook signature
            $event = \Stripe\Webhook::constructEvent(
                $payload,
                $sig_header,
                $webhook_secret
            );
            
            // Handle the event
            if ($event->type === 'checkout.session.completed') {
                $session = $event->data->object;
                
                $campaign_id = $session->metadata->campaign_id ?? $session->client_reference_id ?? 0;
                $amount = $session->amount_total / 100;
                $currency = strtoupper($session->currency);
                $session_id = $session->id;
                
                $stripe_session = $this->db->get_where('stripe_sessions', ['session_id' => $session_id])->row();
                
                if($stripe_session && !empty($stripe_session->affiliate_id)){
                    $campaign = $this->IntegrationModel->getProgramToolsByID($campaign_id);
                    
                    if($campaign){
                        $short_order_id = 'STR-' . strtoupper(substr(md5($session_id), 0, 12));
                        
                        $order_data = [
                            'af_id' => $stripe_session->affiliate_id,
                            'order_id' => $short_order_id,
                            'order_total' => $amount,
                            'order_currency' => $currency,
                            'product_ids' => $campaign_id,
                            'script_name' => 'stripe',
                            'base_url' => base64_encode(base_url()),
                            'current_page_url' => base64_encode($session->success_url ?? base_url()),
                            'restricted_vendors' => [],'customFields' => json_encode([
                                'stripe_session_id' => $session_id,
                                'payment_intent' => $session->payment_intent ?? '',
                                'customer_email' => $session->customer_details->email ?? '',
                                'payment_status' => $session->payment_status ?? 'paid'
                            ])
                        ];
                        
                        ob_start();
                        $this->IntegrationModel->addOrder($order_data);
                        ob_end_clean();
                        
                        $this->db->where('order_id', $short_order_id);
                        $this->db->where('script_name', 'stripe');
                        $this->db->update('integration_orders', ['status' => 1]);
                        
                        $order_record = $this->db->get_where('integration_orders', [
                            'order_id' => $short_order_id,
                            'script_name' => 'stripe'
                        ])->row();
                        
                        if($order_record){
                            $this->db->where('reference_id_2', $order_record->id);
                            $this->db->where('type', 'sale_commission');
                            $this->db->where('comm_from', 'ex');
                            $this->db->update('wallet', ['status' => 1]);
                        }
                        
                        $this->db->delete('stripe_sessions', ['session_id' => $session_id]);
                    }
                }
            }
            
            // Return success
            http_response_code(200);
            echo json_encode(['success' => true]);
            
        } catch (\Stripe\Exception\SignatureVerificationException $e) {
            http_response_code(400);
            log_message('error', 'Stripe Integration Webhook: Invalid signature - ' . $e->getMessage());
            echo json_encode(['error' => 'Invalid signature']);
        } catch (\Exception $e) {
            http_response_code(400);
            log_message('error', 'Stripe Integration Webhook: Error - ' . $e->getMessage());
            echo json_encode(['error' => $e->getMessage()]);
        }
    }
    
    /**
     * Create Fresh Stripe Checkout Session
     * Called when user clicks affiliate link - creates new session every time
     */
    public function create_session($campaign_id) {
        $campaign_id = (int)$campaign_id;
        
        if(empty($campaign_id)){
            $this->show_error_page('Invalid Campaign', 'The campaign you are trying to access is invalid or does not exist.');
            return;
        }
        
        $campaign = $this->IntegrationModel->getProgramToolsByID($campaign_id);
        
        if(!$campaign || $campaign['tool_integration_plugin'] != 'stripe'){
            $this->show_error_page('Campaign Not Found', 'The campaign you are trying to access is not available or is not configured for Stripe payments.');
            return;
        }
        
        $commission_data = is_array($campaign['commission']) ? $campaign['commission'] : json_decode($campaign['commission'], true);
        $stripe_price = $commission_data['stripe_price'] ?? 0;
        $stripe_currency = $commission_data['stripe_currency'] ?? 'USD';
        
        if(empty($stripe_price) || $stripe_price <= 0){
            $this->show_error_page('Configuration Error', 'This campaign is not properly configured. Please contact the administrator.');
            return;
        }
        
        require_once(APPPATH . 'payment_gateway/controllers/stripe.php');
        $stripe_gateway = new stripe(null);
        
        $stripe_result = $stripe_gateway->create_integration_payment_link($campaign_id, $stripe_price, $stripe_currency, $campaign['name']);
        
        if($stripe_result['success']){
            $af_id = isset($_SESSION['stripe_affiliate_id']) ? $_SESSION['stripe_affiliate_id'] : '';
            
            if(!empty($af_id)){
                $this->db->insert('stripe_sessions', [
                    'session_id' => $stripe_result['session_id'],
                    'campaign_id' => $campaign_id,
                    'affiliate_id' => $af_id,
                    'created_at' => date('Y-m-d H:i:s')
                ]);
            }
            
            redirect($stripe_result['payment_link']);
        } else {
            $this->show_error_page('Payment System Unavailable', $stripe_result['message']);
        }
    }
    
    /**
     * Success Page
     * Customer is redirected here after successful payment
     */
    public function success() {
        $campaign_id = $this->input->get('campaign_id', true);
        $session_id = $this->input->get('session_id', true);
        
        if(empty($campaign_id) && isset($_SESSION['stripe_campaign_id'])){
            $campaign_id = $_SESSION['stripe_campaign_id'];
        }
        
        $data['campaign_id'] = $campaign_id;
        $data['session_id'] = $session_id;
        $data['message'] = 'Payment successful! Thank you for your purchase.';
        
        $this->load->view('integration/stripe_success', $data);
    }
    
    /**
     * Cancel Page
     * Customer is redirected here if they cancel payment
     */
    public function cancel() {
        $campaign_id = $this->input->get('campaign_id', true);
        
        $data['campaign_id'] = $campaign_id;
        $data['message'] = 'Payment was cancelled.';
        
        $this->load->view('integration/stripe_cancel', $data);
    }
    
    /**
     * Process Test Commission (Localhost Testing Only)
     * Simulates webhook for local testing
     */
    public function process_test_commission() {
        $session_id = $this->input->get('session_id', true);
        $campaign_id = (int)$this->input->get('campaign_id', true);
        
        if(empty($session_id) || empty($campaign_id)){
            show_error('Missing session_id or campaign_id');
            return;
        }
        
        $stripe_settings = $this->Product_model->getSettings('payment_gateway_stripe');
        
        require_once APPPATH . 'payment_gateway/library/stripe/stripe.php';
        
        $environment = isset($stripe_settings['environment']) ? (int)$stripe_settings['environment'] : 0;
        $secret_key = $environment === 1 ? $stripe_settings['live_secret_key'] : $stripe_settings['test_secret_key'];
        \Stripe\Stripe::setApiKey($secret_key);
        
        try {
            $requestor = new \Stripe\ApiRequestor();
            $response = $requestor->request('get', '/v1/checkout/sessions/' . $session_id, []);
            
            $session = $response[0]->json;
            
            $amount = $session['amount_total'] / 100;
            $currency = strtoupper($session['currency']);
            
            $af_id = isset($_SESSION['stripe_affiliate_id']) ? $_SESSION['stripe_affiliate_id'] : '';
            
            $campaign = $this->IntegrationModel->getProgramToolsByID($campaign_id);
            
            if ($campaign) {
                if(empty($af_id)){
                    echo '<div style="font-family: Arial; padding: 20px; max-width: 600px; margin: 50px auto; border: 2px solid #ffc107; border-radius: 10px; background: #f8f9fa;">';
                    echo '<h2 style="color: #ffc107;">⚠️ No Affiliate ID Found</h2>';
                    echo '<p>The affiliate link was not used. Please click the affiliate link first before making a payment.</p>';
                    echo '</div>';
                    return;
                }
                
                $order_data = [
                    'af_id' => $af_id,
                    'order_id' => $session_id,
                    'order_total' => $amount,
                    'order_currency' => $currency,
                    'product_ids' => $campaign_id,
                    'script_name' => 'stripe',
                    'base_url' => base64_encode(base_url()),
                    'restricted_vendors' => []
                ];
                
                $result = $this->IntegrationModel->addOrder($order_data);
                
                unset($_SESSION['stripe_affiliate_id']);
                unset($_SESSION['stripe_user_id']);
                unset($_SESSION['stripe_campaign_id']);
                
                echo '<div style="font-family: Arial; padding: 20px; max-width: 600px; margin: 50px auto; border: 2px solid #28a745; border-radius: 10px; background: #f8f9fa;">';
                echo '<h2 style="color: #28a745;">✅ Commission Processed!</h2>';
                echo '<p><strong>Session ID:</strong> ' . $session_id . '</p>';
                echo '<p><strong>Campaign ID:</strong> ' . $campaign_id . '</p>';
                echo '<p><strong>Affiliate ID:</strong> ' . $af_id . '</p>';
                echo '<p><strong>Amount:</strong> ' . $amount . ' ' . $currency . '</p>';
                echo '<hr><p><a href="' . base_url('usercontrol/wallet') . '">View Wallet Transactions</a></p>';
                echo '</div>';
            } else {
                echo '<div style="font-family: Arial; padding: 20px; max-width: 600px; margin: 50px auto; border: 2px solid #dc3545; border-radius: 10px; background: #f8f9fa;">';
                echo '<h2 style="color: #dc3545;">❌ Campaign Not Found</h2>';
                echo '<p>Campaign ID: ' . $campaign_id . '</p>';
                echo '</div>';
            }
            
        } catch (Exception $e) {
            echo '<div style="font-family: Arial; padding: 20px; max-width: 600px; margin: 50px auto; border: 2px solid #dc3545; border-radius: 10px; background: #f8f9fa;">';
            echo '<h2 style="color: #dc3545;">❌ Error</h2>';
            echo '<p>' . $e->getMessage() . '</p>';
            echo '</div>';
        }
    }
    
    /**
     * Show user-friendly error page
     */
    private function show_error_page($title, $message) {
        $data = [
            'title' => $title,
            'message' => $message
        ];
        $this->load->view('integration/stripe_error', $data);
    }
}
