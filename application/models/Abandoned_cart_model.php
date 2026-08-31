<?php
class Abandoned_cart_model extends MY_Model {

    /**
     * Save or update an abandoned cart
     */
    public function saveCart($data) {
        // Check if cart already exists for this session/user
        if (!empty($data['user_id'])) {
            $existing = $this->db->get_where('abandoned_carts', [
                'user_id' => $data['user_id'],
                'status' => 'abandoned'
            ])->row();
        } elseif (!empty($data['session_id'])) {
            $existing = $this->db->get_where('abandoned_carts', [
                'session_id' => $data['session_id'],
                'status' => 'abandoned'
            ])->row();
        } else {
            return false;
        }

        if ($existing) {
            $this->db->where('id', $existing->id);
            $this->db->update('abandoned_carts', [
                'cart_data' => $data['cart_data'],
                'email' => $data['email'] ?? $existing->email,
                'created_at' => date('Y-m-d H:i:s')
            ]);
            return $existing->id;
        } else {
            $data['recovery_token'] = bin2hex(random_bytes(32));
            $data['created_at'] = date('Y-m-d H:i:s');
            $this->db->insert('abandoned_carts', $data);
            return $this->db->insert_id();
        }
    }

    /**
     * Mark cart as recovered
     */
    public function markRecovered($token) {
        $this->db->where('recovery_token', $token);
        $this->db->where('status', 'abandoned');
        $this->db->update('abandoned_carts', [
            'status' => 'recovered',
            'recovered_at' => date('Y-m-d H:i:s')
        ]);
        return $this->db->affected_rows() > 0;
    }

    /**
     * Get cart by recovery token
     */
    public function getByToken($token) {
        return $this->db->get_where('abandoned_carts', [
            'recovery_token' => $token,
            'status' => 'abandoned'
        ])->row();
    }

    /**
     * Get abandoned carts that need reminder emails
     */
    public function getCartsForReminder($delay_hours = 1, $limit = 50) {
        $cutoff = date('Y-m-d H:i:s', strtotime("-{$delay_hours} hours"));
        
        $this->db->where('status', 'abandoned');
        $this->db->where('reminder_count', 0);
        $this->db->where('created_at <=', $cutoff);
        $this->db->where('email IS NOT NULL', null, false);
        $this->db->where('email !=', '');
        $this->db->order_by('created_at', 'ASC');
        $this->db->limit($limit);
        
        return $this->db->get('abandoned_carts')->result();
    }

    /**
     * Mark reminder as sent
     */
    public function markReminderSent($cart_id) {
        $this->db->where('id', $cart_id);
        $this->db->update('abandoned_carts', [
            'reminder_count' => 1,
            'reminder_sent_at' => date('Y-m-d H:i:s')
        ]);
    }

    /**
     * Mark user carts as recovered when they complete an order
     */
    public function markUserCartsRecovered($user_id) {
        if (empty($user_id)) return;
        $this->db->where('user_id', $user_id);
        $this->db->where('status', 'abandoned');
        $this->db->update('abandoned_carts', [
            'status' => 'recovered',
            'recovered_at' => date('Y-m-d H:i:s')
        ]);
    }

    /**
     * Get abandoned cart stats for admin
     */
    public function getStats($days = 30) {
        $start = date('Y-m-d', strtotime("-{$days} days"));
        
        $total = $this->db->where('created_at >=', $start)->count_all_results('abandoned_carts');
        $recovered = $this->db->where('created_at >=', $start)->where('status', 'recovered')->count_all_results('abandoned_carts');
        $pending = $this->db->where('status', 'abandoned')->count_all_results('abandoned_carts');
        
        return [
            'total' => $total,
            'recovered' => $recovered,
            'pending' => $pending,
            'rate' => $total > 0 ? round(($recovered / $total) * 100, 1) : 0
        ];
    }

    /**
     * Cleanup expired carts (older than 30 days)
     */
    public function cleanup($days = 30) {
        $cutoff = date('Y-m-d H:i:s', strtotime("-{$days} days"));
        $this->db->where('created_at <', $cutoff);
        $this->db->where('status', 'abandoned');
        $this->db->update('abandoned_carts', ['status' => 'expired']);
        return $this->db->affected_rows();
    }
}
