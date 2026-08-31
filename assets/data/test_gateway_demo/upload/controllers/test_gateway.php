<?php
class test_gateway {
    public $title = 'Test Gateway Demo';
    public $website = 'https://example.com';
    
    function __construct($api){ 
        $this->api = $api; 
    }
    
    public function onInstall() {
        // Called when the gateway is installed
        // Add any installation logic here if needed
    }
    
    public function onUnInstall() {
        // Called when the gateway is uninstalled
        // Add any cleanup logic here if needed
    }
    
    public function saveUserSubmit() {
        $data = $this->api->input->post(null, true);
        $json = [];
        
        // Basic validation
        if (empty($data['test_gateway_name'])) {
            $json['errors']['test_gateway_name'] = __('admin.test_gateway_name_required');
        }
        
        if (empty($data['test_gateway_email'])) {
            $json['errors']['test_gateway_email'] = __('admin.test_gateway_email_required');
        } elseif (!filter_var($data['test_gateway_email'], FILTER_VALIDATE_EMAIL)) {
            $json['errors']['test_gateway_email'] = __('admin.test_gateway_email_invalid');
        }
        
        if (!isset($json['errors'])) {
            // Prepare data for withdrawal request
            $payment_details = [];
            foreach ($data as $key => $value) {
                if ($key != 'code' && $key != 'ids') {
                    $payment_details[$key] = $value;
                }
            }
            
            // Wrap payment details in settings structure
            $saveSetting = [
                'payment_details' => $payment_details
            ];
            
            // Create withdrawal request
            $this->api->load->model('Withdrawal_payment_model');
            $status = $this->api->Withdrawal_payment_model->apiAddWithdrwalRequest($data['code'], $data['ids'], $saveSetting);
            
            if ((int)$status['status'] == 1) {
                $json['success'] = 1;
            } else {
                $json['errors']['test_gateway_details'] = $status['error_message'];
            }
        }
        
        return $json;
    }
    
    // Optional: Custom withdrawal processing logic
    public function processWithdrawal($amount, $user_id) {
        // Add your custom withdrawal processing logic here
        // This is just an example - customize for your actual payment gateway
        return [
            'success' => 1, 
            'transaction_id' => 'TXN' . time() . rand(1000, 9999),
            'message' => 'Withdrawal processed successfully'
        ];
    }
}
?>
