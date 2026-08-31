<?php
class Payment_details_model extends MY_Model {
    
    /**
     * Save user payment data for any gateway
     * @param int $user_id User ID
     * @param string $gateway_code Gateway code (e.g., 'new_test_gateway', 'stripe')
     * @param array $data Array of field_name => field_value pairs
     * @return bool Success status
     */
    public function saveUserPaymentData($user_id, $gateway_code, $data) {
        if (empty($user_id) || empty($gateway_code) || empty($data)) {
            return false;
        }
        
        foreach ($data as $field_name => $field_value) {
            // Use REPLACE to handle both INSERT and UPDATE
            $this->db->replace('user_payment_details', [
                'user_id' => $user_id,
                'gateway_code' => $gateway_code,
                'field_name' => $field_name,
                'field_value' => $field_value
            ]);
        }
        
        return true;
    }
    
    /**
     * Get user payment data for a specific gateway
     * @param int $user_id User ID
     * @param string $gateway_code Gateway code
     * @return array Associative array of field_name => field_value
     */
    public function getUserPaymentData($user_id, $gateway_code) {
        if (empty($user_id) || empty($gateway_code)) {
            return [];
        }
        
        $query = $this->db->get_where('user_payment_details', [
            'user_id' => $user_id,
            'gateway_code' => $gateway_code
        ]);
        
        $data = [];
        foreach ($query->result() as $row) {
            $data[$row->field_name] = $row->field_value;
        }
        
        return $data;
    }
    
    /**
     * Get all payment methods for a user
     * @param int $user_id User ID
     * @return array Array of gateway_code => [field_name => field_value]
     */
    public function getAllUserPaymentData($user_id) {
        if (empty($user_id)) {
            return [];
        }
        
        $query = $this->db->get_where('user_payment_details', [
            'user_id' => $user_id
        ]);
        
        $data = [];
        foreach ($query->result() as $row) {
            $data[$row->gateway_code][$row->field_name] = $row->field_value;
        }
        
        return $data;
    }
    
    /**
     * Delete all payment data for a specific user and gateway
     * @param int $user_id User ID
     * @param string $gateway_code Gateway code
     * @return bool Success status
     */
    public function deleteUserPaymentData($user_id, $gateway_code) {
        if (empty($user_id) || empty($gateway_code)) {
            return false;
        }
        
        return $this->db->delete('user_payment_details', [
            'user_id' => $user_id,
            'gateway_code' => $gateway_code
        ]);
    }
    
    /**
     * Check if user has payment data for a gateway
     * @param int $user_id User ID
     * @param string $gateway_code Gateway code
     * @return bool True if data exists
     */
    public function hasUserPaymentData($user_id, $gateway_code) {
        if (empty($user_id) || empty($gateway_code)) {
            return false;
        }
        
        $query = $this->db->get_where('user_payment_details', [
            'user_id' => $user_id,
            'gateway_code' => $gateway_code
        ]);
        
        return $query->num_rows() > 0;
    }
}
