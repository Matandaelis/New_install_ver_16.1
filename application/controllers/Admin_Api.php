<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');

require(APPPATH.'/libraries/REST_Controller.php');
use Restserver\Libraries\REST_Controller;
require APPPATH . 'libraries/Format.php';

class Admin_Api extends REST_Controller {

    public function __construct() {
        parent::__construct();
        
        $this->load->model('Admin_model');
        $this->load->model('IntegrationModel');
        $this->load->model('Total_model');
        $this->load->model('Product_model');
        $this->load->helper('reusable');
        $this->load->helper('admin_permission');
        $this->config->load('admin_permissions');
    }

    /**
     * Require a permission slug (same slugs as admin_roles / admin_permission_map).
     *
     * @param array  $verify_data from verify_request() after 401 handling
     * @param string $slug        e.g. users, reports.wallet
     * @return bool false if HTTP response was already sent
     */
    protected function _admin_api_require_slug(array $verify_data, $slug) {
        $slug = (string)$slug;
        if ($slug === '') {
            return true;
        }
        $caller_id = isset($verify_data['userdata']['id']) ? (int)$verify_data['userdata']['id'] : 0;
        if (!function_exists('admin_api_has_permission') || !admin_api_has_permission($caller_id, $slug)) {
            $this->response(array(
                'status' => FALSE,
                'message' => 'Access Denied! Insufficient permissions.',
                'required_permission' => $slug,
            ), REST_Controller::HTTP_OK);
            return false;
        }
        return true;
    }

    /**
     * Apply admin_api_method_permissions for this controller method (profile_get is always allowed).
     *
     * @param array  $verify_data
     * @param string $method_name __FUNCTION__ from the caller
     * @return bool false if HTTP response was already sent
     */
    protected function _admin_api_require_for_method(array $verify_data, $method_name) {
        $method_name = (string)$method_name;
        if ($method_name === 'profile_get') {
            return true;
        }
        $map = $this->config->item('admin_api_method_permissions');
        if (!is_array($map) || !isset($map[$method_name])) {
            return true;
        }
        return $this->_admin_api_require_slug($verify_data, (string)$map[$method_name]);
    }

    public function dashboard_get() {
        $verify_data = verify_request();

        if (isset($verify_data['status']) && $verify_data['status'] == 401) {
            $response = array(
                'status' => FALSE,
                'message' => 'Unauthorized Access!',
            );
            $this->response($response, REST_Controller::HTTP_OK);
            return;
        }

        $user_id = isset($verify_data['userdata']['id']) ? $verify_data['userdata']['id'] : '';
        $user_type = isset($verify_data['userdata']['type']) ? $verify_data['userdata']['type'] : '';

        if ($user_type !== 'admin') {
            $response = array(
                'status' => FALSE,
                'message' => 'Access Denied! Admin only.',
            );
            $this->response($response, REST_Controller::HTTP_OK);
            return;
        }

        if (!$this->_admin_api_require_for_method($verify_data, __FUNCTION__)) {
            return;
        }

        $total_users = $this->Admin_model->get_total_users();
        $total_affiliates = $this->Admin_model->get_total_affiliates();
        $total_vendors = $this->Admin_model->get_total_vendors();
        $pending_users = $this->Admin_model->get_pending_users();

        $pending_withdrawals = $this->Admin_model->get_pending_withdrawals_count();
        $total_withdrawals_amount = $this->Admin_model->get_total_withdrawals_amount();

        $today_clicks = $this->Admin_model->get_today_clicks();
        $today_sales = $this->Admin_model->get_today_sales();

        $recent_users = $this->Admin_model->get_recent_users(5);

        $admin_totals = $this->Total_model->adminTotals();
        $admin_balance = isset($admin_totals['admin_balance']) ? (float)$admin_totals['admin_balance'] : 0;
        $admin_balance_growth = isset($admin_totals['admin_balance_growth']) ? (float)$admin_totals['admin_balance_growth'] : 0;

        $store_sales = number_format((float)($admin_totals['sale_localstore_total'] ?? 0), 2, '.', '');
        $store_commission = number_format((float)($admin_totals['sale_localstore_commission'] ?? 0), 2, '.', '');
        $store_orders = (int)($admin_totals['sale_localstore_count'] ?? 0);

        $vendor_sales = number_format((float)($admin_totals['sale_localstore_vendor_total'] ?? 0), 2, '.', '');
        $vendor_commission = number_format((float)($admin_totals['sale_localstore_vendor_commission'] ?? 0), 2, '.', '');
        $vendor_orders = (int)($admin_totals['sale_localstore_vendor_count'] ?? 0);

        $click_commission = number_format(
            (float)(($admin_totals['click_localstore_commission'] ?? 0) + ($admin_totals['click_integration_commission'] ?? 0) + ($admin_totals['click_form_commission'] ?? 0)),
            2, '.', ''
        );
        $total_clicks = (int)(($admin_totals['click_localstore_total'] ?? 0) + ($admin_totals['click_integration_total'] ?? 0) + ($admin_totals['click_form_total'] ?? 0));

        $data = array_merge(array(
            'total_users' => (int)$total_users,
            'total_affiliates' => (int)$total_affiliates,
            'total_vendors' => (int)$total_vendors,
            'pending_users' => (int)$pending_users,
            'pending_withdrawals' => (int)$pending_withdrawals,
            'total_withdrawals_amount' => number_format((float)$total_withdrawals_amount, 2, '.', ''),
            'admin_balance' => number_format($admin_balance, 2, '.', ''),
            'admin_balance_growth' => $admin_balance_growth,
            'store_sales' => $store_sales,
            'store_commission' => $store_commission,
            'store_orders' => $store_orders,
            'vendor_sales' => $vendor_sales,
            'vendor_commission' => $vendor_commission,
            'vendor_orders' => $vendor_orders,
            'click_commission' => $click_commission,
            'total_clicks' => $total_clicks,
            'today_clicks' => (int)$today_clicks,
            'today_sales' => (int)$today_sales,
            'recent_users' => $recent_users,
        ), get_currency_settings());

        $json = array(
            'status' => TRUE,
            'data' => $data,
        );

        $this->response($json, REST_Controller::HTTP_OK);
    }

    public function users_get() {
        $verify_data = verify_request();

        if (isset($verify_data['status']) && $verify_data['status'] == 401) {
            $response = array(
                'status' => FALSE,
                'message' => 'Unauthorized Access!',
            );
            $this->response($response, REST_Controller::HTTP_OK);
            return;
        }

        $user_type = isset($verify_data['userdata']['type']) ? $verify_data['userdata']['type'] : '';

        if ($user_type !== 'admin') {
            $response = array(
                'status' => FALSE,
                'message' => 'Access Denied! Admin only.',
            );
            $this->response($response, REST_Controller::HTTP_OK);
            return;
        }

        if (!$this->_admin_api_require_for_method($verify_data, __FUNCTION__)) {
            return;
        }

        $start_from = (int)($this->get('start_from') ?? 0);
        $limit = (int)($this->get('limit') ?? 20);
        if ($limit < 1) {
            $limit = 20;
        }
        if ($limit > 100) {
            $limit = 100;
        }
        $search = $this->get('search') ?? '';
        $status_filter = $this->get('status') ?? 'all';
        $account_filter = (string)($this->get('account') ?? 'all');
        if (!in_array($account_filter, array('all', 'affiliate', 'vendor'), true)) {
            $account_filter = 'all';
        }

        $where = "type='user'";
        
        if ($status_filter == 'active') {
            $where .= " AND status='1'";
        } elseif ($status_filter == 'pending') {
            $where .= " AND status='0' AND reg_approved='0'";
        } elseif ($status_filter == 'blocked') {
            $where .= " AND status='0'";
        }

        if ($account_filter === 'vendor') {
            $where .= " AND is_vendor='1'";
        } elseif ($account_filter === 'affiliate') {
            $where .= " AND (is_vendor IS NULL OR is_vendor='0' OR is_vendor='')";
        }

        if (!empty($search)) {
            $search_escaped = $this->db->escape_like_str($search);
            $where .= " AND (username LIKE '%{$search_escaped}%' OR email LIKE '%{$search_escaped}%' OR firstname LIKE '%{$search_escaped}%' OR lastname LIKE '%{$search_escaped}%')";
        }

        $total_count = $this->Admin_model->get_users_count($where);
        $users = $this->Admin_model->get_users($where, $start_from, $limit);

        foreach ($users as &$user) {
            $balance = $this->Total_model->getUserBalance($user['id']);
            $user['balance'] = number_format((float)$balance, 2, '.', '');
            
            $user['total_clicks'] = $this->Admin_model->get_user_clicks($user['id']);
            $user['total_sales'] = $this->Admin_model->get_user_sales($user['id']);
            $user['created_date'] = $user['created_at']; // Add for compatibility
            $user['account_type'] = (isset($user['is_vendor']) && (string)$user['is_vendor'] === '1') ? 'vendor' : 'affiliate';
        }
        unset($user);

        $data = array_merge(array(
            'users' => $users,
            'total_count' => (int)$total_count,
            'start_from' => (int)$start_from,
            'limit' => (int)$limit,
            'has_more' => ($start_from + $limit) < $total_count,
        ), get_currency_settings());

        $json = array(
            'status' => TRUE,
            'data' => $data,
        );

        $this->response($json, REST_Controller::HTTP_OK);
    }

    public function withdrawals_get() {
        $verify_data = verify_request();

        if (isset($verify_data['status']) && $verify_data['status'] == 401) {
            $response = array(
                'status' => FALSE,
                'message' => 'Unauthorized Access!',
            );
            $this->response($response, REST_Controller::HTTP_OK);
            return;
        }

        $user_type = isset($verify_data['userdata']['type']) ? $verify_data['userdata']['type'] : '';

        if ($user_type !== 'admin') {
            $response = array(
                'status' => FALSE,
                'message' => 'Access Denied! Admin only.',
            );
            $this->response($response, REST_Controller::HTTP_OK);
            return;
        }

        if (!$this->_admin_api_require_for_method($verify_data, __FUNCTION__)) {
            return;
        }

        $start_from = $this->get('start_from') ?? 0;
        $limit = $this->get('limit') ?? 20;
        $status_filter = $this->get('status') ?? 'unpaid';

        $where = "1=1";
        
        if ($status_filter == 'unpaid') {
            $where .= " AND wr.status='0'";
        } elseif ($status_filter == 'paid') {
            $where .= " AND wr.status='1'";
        } elseif ($status_filter == 'rejected') {
            $where .= " AND wr.status IN ('3','4')";
        }

        $total_count = $this->Admin_model->get_withdrawals_count($where);
        $requests = $this->Admin_model->get_withdrawals($where, $start_from, $limit);

        $status_labels = array(
            '0' => 'Received',
            '1' => 'Paid',
            '2' => 'Processing',
            '3' => 'Cancelled',
            '4' => 'Declined',
        );

        foreach ($requests as &$row) {
            $row['status_label'] = isset($status_labels[$row['status']]) ? $status_labels[$row['status']] : 'Unknown';

            if (!empty($row['settings'])) {
                $parsed = @json_decode($row['settings'], true);
                if (is_array($parsed)) {
                    $row['payment_details'] = $parsed;
                }
            }
            unset($row['settings']);
        }
        unset($row);

        $data = array_merge(array(
            'requests' => $requests,
            'total_count' => (int)$total_count,
            'start_from' => (int)$start_from,
            'limit' => (int)$limit,
            'has_more' => ($start_from + $limit) < $total_count,
        ), get_currency_settings());

        $json = array(
            'status' => TRUE,
            'data' => $data,
        );

        $this->response($json, REST_Controller::HTTP_OK);
    }

    public function update_withdrawal_status_post() {
        $verify_data = verify_request();

        if (isset($verify_data['status']) && $verify_data['status'] == 401) {
            $response = array(
                'status' => FALSE,
                'message' => 'Unauthorized Access!',
            );
            $this->response($response, REST_Controller::HTTP_OK);
            return;
        }

        $user_type = isset($verify_data['userdata']['type']) ? $verify_data['userdata']['type'] : '';

        if ($user_type !== 'admin') {
            $response = array(
                'status' => FALSE,
                'message' => 'Access Denied! Admin only.',
            );
            $this->response($response, REST_Controller::HTTP_OK);
            return;
        }

        if (!$this->_admin_api_require_for_method($verify_data, __FUNCTION__)) {
            return;
        }

        $request_id = $this->post('request_id');
        $status = $this->post('status'); // 'paid' or 'rejected'
        $admin_note = $this->post('admin_note') ?? '';

        if (empty($request_id) || empty($status)) {
            $response = array(
                'status' => FALSE,
                'message' => 'Request ID and status are required',
            );
            $this->response($response, REST_Controller::HTTP_OK);
            return;
        }

        $status_code = ($status === 'paid') ? 1 : 4;

        $existing = $this->Admin_model->get_withdrawal_by_id($request_id);
        if (!$existing) {
            $response = array(
                'status' => FALSE,
                'message' => 'Withdrawal request not found',
            );
            $this->response($response, REST_Controller::HTTP_OK);
            return;
        }

        $this->load->model('Withdrawal_payment_model');
        $this->Withdrawal_payment_model->apiAddWithdrwalRequestHistory((int)$request_id, [
            'status_id'      => $status_code,
            'comment'        => $admin_note,
            'transaction_id' => '',
        ]);

        $message = ($status === 'paid')
            ? 'Withdrawal request approved successfully'
            : 'Withdrawal request rejected successfully';

        $response = array(
            'status' => TRUE,
            'message' => $message,
        );
        $this->response($response, REST_Controller::HTTP_OK);
    }

    public function user_details_get() {
        $verify_data = verify_request();

        if (isset($verify_data['status']) && $verify_data['status'] == 401) {
            $response = array(
                'status' => FALSE,
                'message' => 'Unauthorized Access!',
            );
            $this->response($response, REST_Controller::HTTP_OK);
            return;
        }

        $user_type = isset($verify_data['userdata']['type']) ? $verify_data['userdata']['type'] : '';

        if ($user_type !== 'admin') {
            $response = array(
                'status' => FALSE,
                'message' => 'Access Denied! Admin only.',
            );
            $this->response($response, REST_Controller::HTTP_OK);
            return;
        }

        if (!$this->_admin_api_require_for_method($verify_data, __FUNCTION__)) {
            return;
        }

        $user_id = $this->get('user_id');

        if (empty($user_id)) {
            $response = array(
                'status' => FALSE,
                'message' => 'User ID is required',
            );
            $this->response($response, REST_Controller::HTTP_OK);
            return;
        }

        $user = $this->Admin_model->get_user_by_id($user_id);

        if (empty($user)) {
            $response = array(
                'status' => FALSE,
                'message' => 'User not found',
            );
            $this->response($response, REST_Controller::HTTP_OK);
            return;
        }

        $user['account_type'] = (isset($user['is_vendor']) && (string)$user['is_vendor'] === '1') ? 'vendor' : 'affiliate';

        $balance = $this->Total_model->getUserBalance($user_id);
        $user['balance'] = number_format((float)$balance, 2, '.', '');

        $total_clicks = $this->db->query("SELECT COUNT(*) as count FROM product_action WHERE user_id='{$user_id}'")->row()->count;
        $user['total_clicks'] = (int)($total_clicks ?? 0);

        $total_sales = $this->db->query("SELECT COUNT(*) as count FROM `order` WHERE user_id='{$user_id}'")->row()->count;
        $user['total_sales'] = (int)($total_sales ?? 0);

        $total_revenue = $this->db->query("SELECT SUM(total) as total FROM `order` WHERE user_id='{$user_id}'")->row()->total ?? 0;
        $user['total_revenue'] = number_format((float)$total_revenue, 2, '.', '');

        $referrals = $this->db->query("SELECT COUNT(*) as count FROM users WHERE refid='{$user_id}'")->row()->count;
        $user['total_referrals'] = (int)($referrals ?? 0);

        $recent_clicks = $this->db->query("SELECT action_id as id, product_id, created_at FROM product_action WHERE user_id='{$user_id}' ORDER BY created_at DESC LIMIT 10")->result_array();
        $user['recent_clicks'] = $recent_clicks;

        $recent_sales = $this->db->query("SELECT id, total, status, created_at FROM `order` WHERE user_id='{$user_id}' ORDER BY created_at DESC LIMIT 10")->result_array();
        $user['recent_sales'] = $recent_sales;

        unset($user['password']);

        if (isset($user['is_vendor']) && (string)$user['is_vendor'] === '1') {
            $vendor_extras = $this->Admin_model->get_vendor_profile_extras_for_api((int)$user_id);
            if (!empty($vendor_extras)) {
                $user['vendor_profile_extras'] = $vendor_extras;
            }
        }

        $user = array_merge($user, get_currency_settings());

        $json = array(
            'status' => TRUE,
            'data' => $user,
        );

        $this->response($json, REST_Controller::HTTP_OK);
    }

    /**
     * GET Admin_Api/admin_staff — read-only list of admin panel accounts (type = admin).
     * Requires permission slug admin.admins (same as web admin_user).
     */
    public function admin_staff_get() {
        $verify_data = verify_request();

        if (isset($verify_data['status']) && $verify_data['status'] == 401) {
            $this->response(array('status' => FALSE, 'message' => 'Unauthorized Access!'), REST_Controller::HTTP_OK);
            return;
        }

        $user_type = isset($verify_data['userdata']['type']) ? $verify_data['userdata']['type'] : '';
        if ($user_type !== 'admin') {
            $this->response(array('status' => FALSE, 'message' => 'Access Denied! Admin only.'), REST_Controller::HTTP_OK);
            return;
        }

        if (!$this->_admin_api_require_for_method($verify_data, __FUNCTION__)) {
            return;
        }

        $start_from = (int)($this->get('start_from') ?? 0);
        $limit = (int)($this->get('limit') ?? 20);
        if ($limit < 1) {
            $limit = 20;
        }
        if ($limit > 100) {
            $limit = 100;
        }
        $search = trim((string)($this->get('search') ?? ''));

        $total_count = $this->Admin_model->count_admin_staff_for_api($search);
        $staff = $this->Admin_model->get_admin_staff_for_api($start_from, $limit, $search);

        $data = array(
            'staff' => $staff,
            'total_count' => $total_count,
            'start_from' => $start_from,
            'limit' => $limit,
            'has_more' => ($start_from + $limit) < $total_count,
        );

        $this->response(array('status' => TRUE, 'data' => $data), REST_Controller::HTTP_OK);
    }

    /**
     * GET Admin_Api/admin_staff_details?user_id= — read-only single admin account.
     */
    public function admin_staff_details_get() {
        $verify_data = verify_request();

        if (isset($verify_data['status']) && $verify_data['status'] == 401) {
            $this->response(array('status' => FALSE, 'message' => 'Unauthorized Access!'), REST_Controller::HTTP_OK);
            return;
        }

        $user_type = isset($verify_data['userdata']['type']) ? $verify_data['userdata']['type'] : '';
        if ($user_type !== 'admin') {
            $this->response(array('status' => FALSE, 'message' => 'Access Denied! Admin only.'), REST_Controller::HTTP_OK);
            return;
        }

        if (!$this->_admin_api_require_for_method($verify_data, __FUNCTION__)) {
            return;
        }

        $user_id = $this->get('user_id');
        if (empty($user_id)) {
            $this->response(array('status' => FALSE, 'message' => 'user_id is required'), REST_Controller::HTTP_OK);
            return;
        }

        $row = $this->Admin_model->get_admin_staff_by_id_for_api((int)$user_id);
        if (empty($row)) {
            $this->response(array('status' => FALSE, 'message' => 'Admin user not found'), REST_Controller::HTTP_OK);
            return;
        }

        $sensitive = array('password', 'token', 'remember_token', 'activation_token', 'otp', 'forgot_password', 'forgot_token');
        foreach ($sensitive as $k) {
            if (array_key_exists($k, $row)) {
                unset($row[$k]);
            }
        }

        if ($this->db->table_exists('admin_roles') && function_exists('admin_user_with_permissions')) {
            $row = admin_user_with_permissions($row);
        }

        $row['is_super_admin'] = ((int)($row['id'] ?? 0) === 1);

        $this->response(array('status' => TRUE, 'data' => $row), REST_Controller::HTTP_OK);
    }

    /**
     * GET Admin_Api/admin_roles — read-only roles + permission slugs + admin user counts.
     */
    public function admin_roles_get() {
        $verify_data = verify_request();

        if (isset($verify_data['status']) && $verify_data['status'] == 401) {
            $this->response(array('status' => FALSE, 'message' => 'Unauthorized Access!'), REST_Controller::HTTP_OK);
            return;
        }

        $user_type = isset($verify_data['userdata']['type']) ? $verify_data['userdata']['type'] : '';
        if ($user_type !== 'admin') {
            $this->response(array('status' => FALSE, 'message' => 'Access Denied! Admin only.'), REST_Controller::HTTP_OK);
            return;
        }

        if (!$this->_admin_api_require_for_method($verify_data, __FUNCTION__)) {
            return;
        }

        $pack = $this->Admin_model->get_admin_roles_for_api();
        $data = array(
            'admin_roles_table_exists' => !empty($pack['table_exists']),
            'roles' => isset($pack['roles']) ? $pack['roles'] : array(),
        );

        $this->response(array('status' => TRUE, 'data' => $data), REST_Controller::HTTP_OK);
    }

    public function update_user_status_post() {
        $verify_data = verify_request();

        if (isset($verify_data['status']) && $verify_data['status'] == 401) {
            $response = array(
                'status' => FALSE,
                'message' => 'Unauthorized Access!',
            );
            $this->response($response, REST_Controller::HTTP_OK);
            return;
        }

        $user_type = isset($verify_data['userdata']['type']) ? $verify_data['userdata']['type'] : '';

        if ($user_type !== 'admin') {
            $response = array(
                'status' => FALSE,
                'message' => 'Access Denied! Admin only.',
            );
            $this->response($response, REST_Controller::HTTP_OK);
            return;
        }

        if (!$this->_admin_api_require_for_method($verify_data, __FUNCTION__)) {
            return;
        }

        $user_id = $this->post('user_id');

        if (empty($user_id)) {
            $response = array(
                'status' => FALSE,
                'message' => 'user_id is required',
            );
            $this->response($response, REST_Controller::HTTP_OK);
            return;
        }

        $user_id = (int)$user_id;
        $this->db->query("UPDATE users SET status = IF(status=1,0,1) WHERE id={$user_id} AND type='user'");
        $affected = $this->db->affected_rows();

        if ($affected > 0) {
            $new_status = $this->db->query("SELECT status FROM users WHERE id={$user_id}")->row()->status;
            $status_text = ($new_status == 1) ? 'enabled' : 'disabled';
            $json = array(
                'status' => TRUE,
                'message' => "User status {$status_text} successfully",
                'new_status' => (int)$new_status,
            );
        } else {
            $json = array(
                'status' => FALSE,
                'message' => 'User not found or status unchanged',
            );
        }

        $this->response($json, REST_Controller::HTTP_OK);
    }

    public function reports_get() {
        $verify_data = verify_request();

        if (isset($verify_data['status']) && $verify_data['status'] == 401) {
            $response = array(
                'status' => FALSE,
                'message' => 'Unauthorized Access!',
            );
            $this->response($response, REST_Controller::HTTP_OK);
            return;
        }

        $user_type = isset($verify_data['userdata']['type']) ? $verify_data['userdata']['type'] : '';

        if ($user_type !== 'admin') {
            $response = array(
                'status' => FALSE,
                'message' => 'Access Denied! Admin only.',
            );
            $this->response($response, REST_Controller::HTTP_OK);
            return;
        }

        if (!$this->_admin_api_require_for_method($verify_data, __FUNCTION__)) {
            return;
        }

        $date_from = $this->get('date_from') ?? date('Y-m-01');
        $date_to = $this->get('date_to') ?? date('Y-m-d');

        $clicks_by_day = $this->db->query("
            SELECT DATE(created_at) as date, COUNT(*) as count 
            FROM product_action 
            WHERE DATE(created_at) BETWEEN ? AND ?
            GROUP BY DATE(created_at) 
            ORDER BY date ASC
        ", array($date_from, $date_to))->result_array();

        $sales_by_day = $this->db->query("
            SELECT DATE(created_at) as date, COUNT(*) as count, SUM(total) as total 
            FROM `order` 
            WHERE DATE(created_at) BETWEEN ? AND ?
            GROUP BY DATE(created_at) 
            ORDER BY date ASC
        ", array($date_from, $date_to))->result_array();

        $top_affiliates = $this->db->query("
            SELECT users.id, users.firstname, users.lastname, users.username, 
                   COUNT(`order`.id) as total_sales, 
                   SUM(`order`.total) as total_revenue 
            FROM users 
            LEFT JOIN `order` ON users.id = `order`.user_id 
            WHERE users.type='user' AND `order`.created_at BETWEEN ? AND ?
            GROUP BY users.id 
            ORDER BY total_revenue DESC 
            LIMIT 10
        ", array($date_from, $date_to))->result_array();

        $data = array_merge(array(
            'date_from' => $date_from,
            'date_to' => $date_to,
            'clicks_by_day' => $clicks_by_day,
            'sales_by_day' => $sales_by_day,
            'top_affiliates' => $top_affiliates,
        ), get_currency_settings());

        $json = array(
            'status' => TRUE,
            'data' => $data,
        );

        $this->response($json, REST_Controller::HTTP_OK);
    }

    public function wallet_get() {
        $verify_data = verify_request();

        if (isset($verify_data['status']) && $verify_data['status'] == 401) {
            $response = array(
                'status' => FALSE,
                'message' => 'Unauthorized Access!',
            );
            $this->response($response, REST_Controller::HTTP_OK);
            return;
        }

        $user_type = isset($verify_data['userdata']['type']) ? $verify_data['userdata']['type'] : '';

        if ($user_type !== 'admin') {
            $response = array(
                'status' => FALSE,
                'message' => 'Access Denied! Admin only.',
            );
            $this->response($response, REST_Controller::HTTP_OK);
            return;
        }

        if (!$this->_admin_api_require_for_method($verify_data, __FUNCTION__)) {
            return;
        }

        $admin_totals = $this->Total_model->adminTotals();
        
        $this->load->model('Wallet_model');
        $filter = array(
            'status_gt' => 0,
            'parent_id' => 0,
            'not_negative_balence' => true,
            'page_num' => 1,
            'per_page' => 50,
            'offset' => 0,
        );
        
        $transactions = $this->Wallet_model->getTransaction($filter, false, 'ONLY_PARENTS');

        $data = array_merge(array(
            'admin_balance' => number_format((float)$admin_totals['admin_balance'], 2, '.', ''),
            'sale_localstore_total' => number_format((float)$admin_totals['sale_localstore_total'], 2, '.', ''),
            'sale_localstore_vendor_total' => number_format((float)$admin_totals['sale_localstore_vendor_total'], 2, '.', ''),
            'order_external_total' => number_format((float)$admin_totals['order_external_total'], 2, '.', ''),
            'click_action_total' => (int)$admin_totals['click_action_total'],
            'click_action_commission' => number_format((float)$admin_totals['click_action_commission'], 2, '.', ''),
            'click_localstore_total' => (int)$admin_totals['click_localstore_total'],
            'click_integration_total' => (int)$admin_totals['click_integration_total'],
            'click_form_total' => (int)$admin_totals['click_form_total'],
            'click_localstore_commission' => number_format((float)$admin_totals['click_localstore_commission'], 2, '.', ''),
            'click_integration_commission' => number_format((float)$admin_totals['click_integration_commission'], 2, '.', ''),
            'click_form_commission' => number_format((float)$admin_totals['click_form_commission'], 2, '.', ''),
            'transactions' => $transactions,
        ), get_currency_settings());

        $json = array(
            'status' => TRUE,
            'data' => $data,
        );

        $this->response($json, REST_Controller::HTTP_OK);
    }

    public function profile_get() {
        $verify_data = verify_request();

        if (isset($verify_data['status']) && $verify_data['status'] == 401) {
            $this->response(array('status' => FALSE, 'message' => 'Unauthorized Access!'), REST_Controller::HTTP_OK);
            return;
        }

        $user_id = isset($verify_data['userdata']['id']) ? $verify_data['userdata']['id'] : '';
        $user_type = isset($verify_data['userdata']['type']) ? $verify_data['userdata']['type'] : '';

        if ($user_type !== 'admin') {
            $this->response(array('status' => FALSE, 'message' => 'Access Denied! Admin only.'), REST_Controller::HTTP_OK);
            return;
        }

        $this->load->model('user_model', 'user');
        $user = $this->user->get_user_by_id($user_id);

        if (empty($user)) {
            $this->response(array('status' => FALSE, 'message' => 'Admin not found'), REST_Controller::HTTP_OK);
            return;
        }

        $phone_val = !empty($user['PhoneNumber']) ? $user['PhoneNumber'] : (!empty($user['phone']) ? $user['phone'] : '');
        $city_val = !empty($user['City']) ? $user['City'] : (!empty($user['ucity']) ? $user['ucity'] : '');
        $zip_val = !empty($user['Zip']) ? $user['Zip'] : (!empty($user['uzip']) ? $user['uzip'] : '');
        $country_id = !empty($user['Country']) ? $user['Country'] : (!empty($user['ucountry']) ? $user['ucountry'] : '');

        $country_code = '';
        $country_name = '';
        $country_flag = '';
        if (!empty($country_id)) {
            $country_data = $this->db->query("SELECT sortname, name FROM countries WHERE id = ?", array($country_id))->row();
            if ($country_data) {
                $country_code = $country_data->sortname;
                $country_name = $country_data->name;
                $country_flag = base_url('assets/template/images/flags/' . strtolower($country_code) . '.png');
            }
        }

        $this->load->model('Product_model');
        $countries_raw = $this->Product_model->getcountry();
        $countries_list = array();
        if (!empty($countries_raw)) {
            foreach ($countries_raw as $c) {
                $countries_list[] = array('id' => $c->id, 'name' => $c->name);
            }
        }

        $data = array(
            'firstname' => $user['firstname'],
            'lastname' => $user['lastname'],
            'email' => $user['email'],
            'username' => $user['username'],
            'PhoneNumber' => $phone_val,
            'city' => $city_val,
            'pincode' => $zip_val,
            'country_id' => $country_id,
            'country_code' => $country_code,
            'country_name' => $country_name,
            'country_flag' => $country_flag,
            'profile_avatar' => (!empty($user['avatar'])) ? base_url('assets/images/users/' . $user['avatar']) : base_url('assets/template/images/no-image.jpg'),
            'role' => $user['type'],
            'countries' => $countries_list,
        );
        if (function_exists('admin_api_permission_payload_for_user')) {
            $data = array_merge($data, admin_api_permission_payload_for_user((int)$user_id));
        }

        $response = array(
            'status' => TRUE,
            'message' => 'Admin profile loaded successfully',
            'data' => $data,
        );

        $this->response($response, REST_Controller::HTTP_OK);
    }

    public function update_profile_post() {
        $verify_data = verify_request();

        if (isset($verify_data['status']) && $verify_data['status'] == 401) {
            $this->response(array('status' => FALSE, 'message' => 'Unauthorized Access!'), REST_Controller::HTTP_OK);
            return;
        }

        $user_id = isset($verify_data['userdata']['id']) ? $verify_data['userdata']['id'] : '';
        $user_type = isset($verify_data['userdata']['type']) ? $verify_data['userdata']['type'] : '';

        if ($user_type !== 'admin') {
            $this->response(array('status' => FALSE, 'message' => 'Access Denied! Admin only.'), REST_Controller::HTTP_OK);
            return;
        }

        if (!$this->_admin_api_require_for_method($verify_data, __FUNCTION__)) {
            return;
        }

        $this->load->model('user_model', 'user');

        $updateArray = array(
            'firstname' => $this->input->post('firstname', true),
            'lastname'  => $this->input->post('lastname', true),
            'email'     => $this->input->post('email', true),
        );

        $phone = $this->input->post('phone', true);
        if ($phone !== null) {
            $updateArray['PhoneNumber'] = $phone;
            $updateArray['phone'] = $phone;
        }

        $country_id = $this->input->post('country_id', true);
        if ($country_id !== null) {
            $updateArray['Country'] = $country_id;
            $updateArray['ucountry'] = $country_id;
        }

        $city = $this->input->post('city', true);
        if ($city !== null) {
            $updateArray['City'] = $city;
            $updateArray['ucity'] = $city;
        }

        $pincode = $this->input->post('pincode', true);
        if ($pincode !== null) {
            $updateArray['Zip'] = $pincode;
            $updateArray['uzip'] = $pincode;
        }

        $password = $this->input->post('password', true);
        if (!empty($password)) {
            $updateArray['password'] = sha1($password);
        }

        if (!empty($_FILES['avatar']['name'])) {
            $config['upload_path'] = 'assets/images/users';
            $config['allowed_types'] = 'png|gif|jpeg|jpg';
            $config['max_size'] = 2048;
            $this->load->library('upload', $config);
            if ($this->upload->do_upload('avatar')) {
                $upload_data = $this->upload->data();
                $updateArray['avatar'] = $upload_data['file_name'];
            }
        }

        $this->user->update_user($user_id, $updateArray);

        $updated_user = $this->user->get_user_by_id($user_id);

        $phone_val = !empty($updated_user['PhoneNumber']) ? $updated_user['PhoneNumber'] : (!empty($updated_user['phone']) ? $updated_user['phone'] : '');
        $city_val = !empty($updated_user['City']) ? $updated_user['City'] : (!empty($updated_user['ucity']) ? $updated_user['ucity'] : '');
        $zip_val = !empty($updated_user['Zip']) ? $updated_user['Zip'] : (!empty($updated_user['uzip']) ? $updated_user['uzip'] : '');
        $c_id = !empty($updated_user['Country']) ? $updated_user['Country'] : (!empty($updated_user['ucountry']) ? $updated_user['ucountry'] : '');

        $country_code = '';
        $country_name = '';
        $country_flag = '';
        if (!empty($c_id)) {
            $country_data = $this->db->query("SELECT sortname, name FROM countries WHERE id = ?", array($c_id))->row();
            if ($country_data) {
                $country_code = $country_data->sortname;
                $country_name = $country_data->name;
                $country_flag = base_url('assets/template/images/flags/' . strtolower($country_code) . '.png');
            }
        }

        $response = array(
            'status' => TRUE,
            'message' => 'Profile updated successfully',
            'data' => array(
                'firstname' => $updated_user['firstname'],
                'lastname' => $updated_user['lastname'],
                'email' => $updated_user['email'],
                'username' => $updated_user['username'],
                'PhoneNumber' => $phone_val,
                'city' => $city_val,
                'pincode' => $zip_val,
                'country_id' => $c_id,
                'country_code' => $country_code,
                'country_name' => $country_name,
                'country_flag' => $country_flag,
                'profile_avatar' => (!empty($updated_user['avatar'])) ? base_url('assets/images/users/' . $updated_user['avatar']) : base_url('assets/template/images/no-image.jpg'),
                'role' => $updated_user['type'],
            )
        );

        $this->response($response, REST_Controller::HTTP_OK);
    }

    /**
     * GET Admin_Api/programs — integration_programs (vendor / integration affiliate programs).
     */
    public function programs_get() {
        $verify_data = verify_request();

        if (isset($verify_data['status']) && $verify_data['status'] == 401) {
            $this->response(array('status' => FALSE, 'message' => 'Unauthorized Access!'), REST_Controller::HTTP_OK);
            return;
        }

        $user_type = isset($verify_data['userdata']['type']) ? $verify_data['userdata']['type'] : '';
        if ($user_type !== 'admin') {
            $this->response(array('status' => FALSE, 'message' => 'Access Denied! Admin only.'), REST_Controller::HTTP_OK);
            return;
        }

        if (!$this->_admin_api_require_for_method($verify_data, __FUNCTION__)) {
            return;
        }

        $start_from = (int)($this->get('start_from') ?? 0);
        $limit = (int)($this->get('limit') ?? 20);
        if ($limit < 1) {
            $limit = 20;
        }
        if ($limit > 100) {
            $limit = 100;
        }

        $search = trim((string)($this->get('search') ?? ''));
        $status_filter = (string)($this->get('status') ?? 'all');
        if (!in_array($status_filter, array('all', 'active', 'inactive'), true)) {
            $status_filter = 'all';
        }

        $filter = array();
        if ($search !== '') {
            $filter['name'] = $search;
        }
        if ($status_filter === 'active') {
            $filter['status'] = 1;
        } elseif ($status_filter === 'inactive') {
            $filter['status'] = 0;
        }

        $total_count = (int)$this->IntegrationModel->getProgramsCount($filter);
        $filter['limit']  = $limit;
        $filter['offset'] = $start_from;
        $programs = $this->IntegrationModel->getPrograms($filter);

        $data = array_merge(array(
            'programs' => $programs,
            'total_count' => $total_count,
            'start_from' => $start_from,
            'limit' => $limit,
            'has_more' => ($start_from + $limit) < $total_count,
        ), get_currency_settings());

        $this->response(array('status' => TRUE, 'data' => $data), REST_Controller::HTTP_OK);
    }

    /**
     * GET Admin_Api/program_details?program_id=
     */
    public function program_details_get() {
        $verify_data = verify_request();

        if (isset($verify_data['status']) && $verify_data['status'] == 401) {
            $this->response(array('status' => FALSE, 'message' => 'Unauthorized Access!'), REST_Controller::HTTP_OK);
            return;
        }

        $user_type = isset($verify_data['userdata']['type']) ? $verify_data['userdata']['type'] : '';
        if ($user_type !== 'admin') {
            $this->response(array('status' => FALSE, 'message' => 'Access Denied! Admin only.'), REST_Controller::HTTP_OK);
            return;
        }

        if (!$this->_admin_api_require_for_method($verify_data, __FUNCTION__)) {
            return;
        }

        $program_id = $this->get('program_id');
        if (empty($program_id)) {
            $this->response(array('status' => FALSE, 'message' => 'program_id is required'), REST_Controller::HTTP_OK);
            return;
        }

        $row = $this->IntegrationModel->getProgramByID((int)$program_id);
        if (empty($row)) {
            $this->response(array('status' => FALSE, 'message' => 'Program not found'), REST_Controller::HTTP_OK);
            return;
        }

        // Add tools_count (not included in getProgramByID)
        $tc = $this->db->query(
            "SELECT COUNT(*) as c FROM integration_tools WHERE program_id = ?",
            array((int)$program_id)
        )->row_array();
        $row['tools_count'] = (int)($tc['c'] ?? 0);

        // Normalize vendor_name into separate fields for API consistency
        if (empty($row['firstname']) && !empty($row['vendor_name'])) {
            $parts = explode(' ', $row['vendor_name'], 2);
            $row['firstname'] = $parts[0] ?? '';
            $row['lastname']  = $parts[1] ?? '';
        }

        $this->response(array(
            'status' => TRUE,
            'data' => array_merge($row, get_currency_settings()),
        ), REST_Controller::HTTP_OK);
    }

    /**
     * GET Admin_Api/campaigns — integration_tools (marketing / integration campaigns under programs).
     */
    public function campaigns_get() {
        $verify_data = verify_request();

        if (isset($verify_data['status']) && $verify_data['status'] == 401) {
            $this->response(array('status' => FALSE, 'message' => 'Unauthorized Access!'), REST_Controller::HTTP_OK);
            return;
        }

        $user_type = isset($verify_data['userdata']['type']) ? $verify_data['userdata']['type'] : '';
        if ($user_type !== 'admin') {
            $this->response(array('status' => FALSE, 'message' => 'Access Denied! Admin only.'), REST_Controller::HTTP_OK);
            return;
        }

        if (!$this->_admin_api_require_for_method($verify_data, __FUNCTION__)) {
            return;
        }

        $start_from = (int)($this->get('start_from') ?? 0);
        $limit = (int)($this->get('limit') ?? 20);
        if ($limit < 1) {
            $limit = 20;
        }
        if ($limit > 100) {
            $limit = 100;
        }

        $search = trim((string)($this->get('search') ?? ''));
        $status_filter = (string)($this->get('status') ?? 'all');
        if (!in_array($status_filter, array('all', 'active', 'inactive'), true)) {
            $status_filter = 'all';
        }

        $total_count = $this->Admin_model->count_integration_campaigns_for_api($search, $status_filter);
        $campaigns = $this->Admin_model->get_integration_campaigns_for_api($start_from, $limit, $search, $status_filter);

        $data = array_merge(array(
            'campaigns' => $campaigns,
            'total_count' => $total_count,
            'start_from' => $start_from,
            'limit' => $limit,
            'has_more' => ($start_from + $limit) < $total_count,
        ), get_currency_settings());

        $this->response(array('status' => TRUE, 'data' => $data), REST_Controller::HTTP_OK);
    }

    /**
     * GET Admin_Api/campaign_details?campaign_id=  (integration_tools.id)
     */
    public function campaign_details_get() {
        $verify_data = verify_request();

        if (isset($verify_data['status']) && $verify_data['status'] == 401) {
            $this->response(array('status' => FALSE, 'message' => 'Unauthorized Access!'), REST_Controller::HTTP_OK);
            return;
        }

        $user_type = isset($verify_data['userdata']['type']) ? $verify_data['userdata']['type'] : '';
        if ($user_type !== 'admin') {
            $this->response(array('status' => FALSE, 'message' => 'Access Denied! Admin only.'), REST_Controller::HTTP_OK);
            return;
        }

        if (!$this->_admin_api_require_for_method($verify_data, __FUNCTION__)) {
            return;
        }

        $campaign_id = $this->get('campaign_id');
        if (empty($campaign_id)) {
            $this->response(array('status' => FALSE, 'message' => 'campaign_id is required'), REST_Controller::HTTP_OK);
            return;
        }

        $detail = $this->IntegrationModel->getProgramToolsByID((int)$campaign_id, false);

        if (empty($detail) || !is_array($detail)) {
            $this->response(array('status' => FALSE, 'message' => 'Campaign not found'), REST_Controller::HTTP_OK);
            return;
        }

        if (empty($detail['program_name']) && !empty($detail['program_id'])) {
            $pn = $this->db->query(
                "SELECT name FROM integration_programs WHERE id = ?",
                array((int)$detail['program_id'])
            )->row_array();
            if (!empty($pn['name'])) {
                $detail['program_name'] = $pn['name'];
            }
        }

        $this->response(array(
            'status' => TRUE,
            'data' => array_merge($detail, get_currency_settings()),
        ), REST_Controller::HTTP_OK);
    }

    /**
     * GET Admin_Api/categories — integration_category (marketing categories for integration programs).
     */
    public function categories_get() {
        $verify_data = verify_request();

        if (isset($verify_data['status']) && $verify_data['status'] == 401) {
            $this->response(array('status' => FALSE, 'message' => 'Unauthorized Access!'), REST_Controller::HTTP_OK);
            return;
        }

        $user_type = isset($verify_data['userdata']['type']) ? $verify_data['userdata']['type'] : '';
        if ($user_type !== 'admin') {
            $this->response(array('status' => FALSE, 'message' => 'Access Denied! Admin only.'), REST_Controller::HTTP_OK);
            return;
        }

        if (!$this->_admin_api_require_for_method($verify_data, __FUNCTION__)) {
            return;
        }

        $start_from = (int)($this->get('start_from') ?? 0);
        $limit = (int)($this->get('limit') ?? 20);
        if ($limit < 1) {
            $limit = 20;
        }
        if ($limit > 100) {
            $limit = 100;
        }

        $search = trim((string)($this->get('search') ?? ''));

        $total_count = $this->Admin_model->count_integration_categories_for_api($search);
        $categories = $this->Admin_model->get_integration_categories_for_api($start_from, $limit, $search);

        $data = array_merge(array(
            'categories' => $categories,
            'total_count' => $total_count,
            'start_from' => $start_from,
            'limit' => $limit,
            'has_more' => ($start_from + $limit) < $total_count,
        ), get_currency_settings());

        $this->response(array('status' => TRUE, 'data' => $data), REST_Controller::HTTP_OK);
    }

    /**
     * GET Admin_Api/category_details?category_id=
     */
    public function category_details_get() {
        $verify_data = verify_request();

        if (isset($verify_data['status']) && $verify_data['status'] == 401) {
            $this->response(array('status' => FALSE, 'message' => 'Unauthorized Access!'), REST_Controller::HTTP_OK);
            return;
        }

        $user_type = isset($verify_data['userdata']['type']) ? $verify_data['userdata']['type'] : '';
        if ($user_type !== 'admin') {
            $this->response(array('status' => FALSE, 'message' => 'Access Denied! Admin only.'), REST_Controller::HTTP_OK);
            return;
        }

        if (!$this->_admin_api_require_for_method($verify_data, __FUNCTION__)) {
            return;
        }

        $category_id = $this->get('category_id');
        if (empty($category_id)) {
            $this->response(array('status' => FALSE, 'message' => 'category_id is required'), REST_Controller::HTTP_OK);
            return;
        }

        $row = $this->Admin_model->get_integration_category_by_id_for_api((int)$category_id);
        if (empty($row)) {
            $this->response(array('status' => FALSE, 'message' => 'Category not found'), REST_Controller::HTTP_OK);
            return;
        }

        $cc = $this->db->query(
            'SELECT COUNT(*) AS c FROM integration_category WHERE parent_id = ?',
            array((int)$row['id'])
        )->row_array();
        $row['children_count'] = isset($cc['c']) ? (int)$cc['c'] : 0;

        $this->response(array(
            'status' => TRUE,
            'data' => array_merge($row, get_currency_settings()),
        ), REST_Controller::HTTP_OK);
    }

    /**
     * GET Admin_Api/orders — store orders + integration_orders (paginated, read-only).
     */
    public function orders_get() {
        $verify_data = verify_request();

        if (isset($verify_data['status']) && $verify_data['status'] == 401) {
            $this->response(array('status' => FALSE, 'message' => 'Unauthorized Access!'), REST_Controller::HTTP_OK);
            return;
        }

        $user_type = isset($verify_data['userdata']['type']) ? $verify_data['userdata']['type'] : '';
        if ($user_type !== 'admin') {
            $this->response(array('status' => FALSE, 'message' => 'Access Denied! Admin only.'), REST_Controller::HTTP_OK);
            return;
        }

        if (!$this->_admin_api_require_for_method($verify_data, __FUNCTION__)) {
            return;
        }

        $start_from = (int)($this->get('start_from') ?? 0);
        $limit = (int)($this->get('limit') ?? 20);
        if ($limit < 1) {
            $limit = 20;
        }
        if ($limit > 100) {
            $limit = 100;
        }

        $search = trim((string)($this->get('search') ?? ''));
        $type_filter = (string)($this->get('type') ?? 'all');
        if (!in_array($type_filter, array('all', 'store', 'ex'), true)) {
            $type_filter = 'all';
        }

        $status_filter = (string)($this->get('status') ?? 'all');
        if ($status_filter !== 'all' && $status_filter !== '' && !is_numeric($status_filter)) {
            $status_filter = 'all';
        }

        $total_count = $this->Admin_model->count_admin_orders_for_api($search, $type_filter, $status_filter);
        $orders = $this->Admin_model->get_admin_orders_for_api($start_from, $limit, $search, $type_filter, $status_filter);

        $data = array_merge(array(
            'orders' => $orders,
            'total_count' => $total_count,
            'start_from' => $start_from,
            'limit' => $limit,
            'has_more' => ($start_from + $limit) < $total_count,
        ), get_currency_settings());

        $this->response(array('status' => TRUE, 'data' => $data), REST_Controller::HTTP_OK);
    }

    /**
     * GET Admin_Api/order_details?order_type=store|ex&id=
     */
    public function order_details_get() {
        $verify_data = verify_request();

        if (isset($verify_data['status']) && $verify_data['status'] == 401) {
            $this->response(array('status' => FALSE, 'message' => 'Unauthorized Access!'), REST_Controller::HTTP_OK);
            return;
        }

        $user_type = isset($verify_data['userdata']['type']) ? $verify_data['userdata']['type'] : '';
        if ($user_type !== 'admin') {
            $this->response(array('status' => FALSE, 'message' => 'Access Denied! Admin only.'), REST_Controller::HTTP_OK);
            return;
        }

        if (!$this->_admin_api_require_for_method($verify_data, __FUNCTION__)) {
            return;
        }

        $order_type = (string)($this->get('order_type') ?? '');
        $order_id = $this->get('id');
        if ($order_type === '' || $order_id === null || $order_id === '') {
            $this->response(array('status' => FALSE, 'message' => 'order_type and id are required'), REST_Controller::HTTP_OK);
            return;
        }

        if (!in_array($order_type, array('store', 'ex'), true)) {
            $this->response(array('status' => FALSE, 'message' => 'order_type must be store or ex'), REST_Controller::HTTP_OK);
            return;
        }

        $oid = (int)$order_id;
        if ($oid < 1) {
            $this->response(array('status' => FALSE, 'message' => 'Invalid id'), REST_Controller::HTTP_OK);
            return;
        }

        if ($order_type === 'store') {
            $this->load->model('Order_model');
            $order = $this->db->query("
                SELECT o.*, u.firstname, u.lastname, u.email, u.username,
                c.name AS country_name, s.name AS state_name
                FROM `order` o
                LEFT JOIN users u ON u.id = o.user_id
                LEFT JOIN countries c ON c.id = o.country_id
                LEFT JOIN states s ON s.id = o.state_id
                WHERE o.id = ?
            ", array($oid))->row_array();

            if (empty($order)) {
                $this->response(array('status' => FALSE, 'message' => 'Order not found'), REST_Controller::HTTP_OK);
                return;
            }

            $order['order_type'] = 'store';
            $order['id'] = (int)$order['id'];
            $order['user_id'] = isset($order['user_id']) ? (int)$order['user_id'] : 0;
            $order['status'] = isset($order['status']) ? (int)$order['status'] : 0;

            if (!empty($order['files']) && is_string($order['files'])) {
                $decoded = json_decode($order['files'], true);
                $order['files'] = is_array($decoded) ? $decoded : array();
            } elseif (!is_array($order['files'])) {
                $order['files'] = array();
            }

            if (!empty($order['comment']) && is_string($order['comment'])) {
                $cdec = json_decode($order['comment'], true);
                $order['comment'] = is_array($cdec) ? $cdec : $order['comment'];
            }

            $order['total'] = isset($order['total']) ? number_format((float)$order['total'], 2, '.', '') : '0.00';
            $order['shipping_cost'] = isset($order['shipping_cost']) ? number_format((float)$order['shipping_cost'], 2, '.', '') : '0.00';
            $order['tax_cost'] = isset($order['tax_cost']) ? number_format((float)$order['tax_cost'], 2, '.', '') : '0.00';
            $order['coupon_discount'] = isset($order['coupon_discount']) ? number_format((float)$order['coupon_discount'], 2, '.', '') : '0.00';

            $order['products'] = $this->Order_model->getProducts($oid);
            $order['payment_history'] = $this->_sanitize_orders_history_for_mobile_api(
                $this->Order_model->getHistory($oid, 'payment')
            );
            $order['order_history'] = $this->_sanitize_orders_history_for_mobile_api(
                $this->Order_model->getHistory($oid, 'order')
            );

            $this->response(array(
                'status' => TRUE,
                'data' => array_merge($order, get_currency_settings()),
            ), REST_Controller::HTTP_OK);
            return;
        }

        $row = $this->db->query("
            SELECT io.*, u.firstname, u.lastname, u.email, u.username,
            it.name AS campaign_name, ip.name AS program_name
            FROM integration_orders io
            LEFT JOIN users u ON u.id = io.user_id
            LEFT JOIN integration_tools it ON it.id = io.ads_id
            LEFT JOIN integration_programs ip ON ip.id = it.program_id
            WHERE io.id = ?
        ", array($oid))->row_array();

        if (empty($row)) {
            $this->response(array('status' => FALSE, 'message' => 'Order not found'), REST_Controller::HTTP_OK);
            return;
        }

        $row['order_type'] = 'ex';
        $row['id'] = (int)$row['id'];
        $row['user_id'] = isset($row['user_id']) ? (int)$row['user_id'] : 0;
        $row['status'] = isset($row['status']) ? (int)$row['status'] : 0;
        $row['vendor_id'] = isset($row['vendor_id']) ? (int)$row['vendor_id'] : 0;
        $row['ads_id'] = isset($row['ads_id']) ? (int)$row['ads_id'] : 0;
        $row['total'] = isset($row['total']) ? number_format((float)$row['total'], 2, '.', '') : '0.00';
        $row['commission'] = isset($row['commission']) ? number_format((float)$row['commission'], 2, '.', '') : '0.00';

        if (!empty($row['custom_data']) && is_string($row['custom_data'])) {
            $cd = json_decode($row['custom_data'], true);
            $row['custom_data'] = is_array($cd) ? $cd : $row['custom_data'];
        }

        $this->response(array(
            'status' => TRUE,
            'data' => array_merge($row, get_currency_settings()),
        ), REST_Controller::HTTP_OK);
    }

    /**
     * GET Admin_Api/settings_summary — read-only, non-secret site / store / module overview for mobile admin.
     * Same access pattern as other Admin_Api GETs: valid admin JWT and user type admin.
     */
    public function settings_summary_get() {
        $verify_data = verify_request();

        if (isset($verify_data['status']) && $verify_data['status'] == 401) {
            $this->response(array('status' => FALSE, 'message' => 'Unauthorized Access!'), REST_Controller::HTTP_OK);
            return;
        }

        $user_type = isset($verify_data['userdata']['type']) ? $verify_data['userdata']['type'] : '';
        if ($user_type !== 'admin') {
            $this->response(array('status' => FALSE, 'message' => 'Access Denied! Admin only.'), REST_Controller::HTTP_OK);
            return;
        }

        if (!$this->_admin_api_require_for_method($verify_data, __FUNCTION__)) {
            return;
        }

        $site = $this->Product_model->getSettings('site');
        $store = $this->Product_model->getSettings('store');
        $market_tools = $this->Product_model->getSettings('market_tools');
        $market_vendor = $this->Product_model->getSettings('market_vendor');
        $referlevel = $this->Product_model->getSettings('referlevel');
        $membership = $this->Product_model->getSettings('membership');
        $award_level = $this->Product_model->getSettings('award_level');
        $vendor = $this->Product_model->getSettings('vendor');

        $mv_status = isset($market_vendor['marketvendorstatus']) ? $market_vendor['marketvendorstatus'] : '0';
        $v_store = isset($vendor['storestatus']) ? $vendor['storestatus'] : '0';
        $saas_enable = ((string)$mv_status === '1' || (string)$v_store === '1') ? '1' : '0';

        $lang_row = $this->db->where('status', 1)->where('is_default', 1)->get('language')->row_array();
        $language = array(
            'id' => isset($lang_row['id']) ? (string)$lang_row['id'] : '',
            'name' => isset($lang_row['name']) ? $lang_row['name'] : '',
        );
        if (isset($lang_row['code'])) {
            $language['code'] = $lang_row['code'];
        }

        $currency = get_currency_settings();

        $summary = array(
            'site' => array(
                'name' => isset($site['name']) ? $site['name'] : '',
                'time_zone' => isset($site['time_zone']) ? $site['time_zone'] : '',
                'session_timeout' => isset($site['session_timeout']) ? $site['session_timeout'] : '',
                'hide_currency_from' => isset($site['hide_currency_from']) ? $site['hide_currency_from'] : '',
                'block_click_across_browser' => isset($site['block_click_across_browser']) ? $site['block_click_across_browser'] : '',
                'cookies_consent' => isset($site['cookies_consent']) ? $site['cookies_consent'] : '',
                'notify_email' => isset($site['notify_email']) ? $site['notify_email'] : '',
                'telegram_enable' => isset($site['telegram_enable']) ? $site['telegram_enable'] : '0',
            ),
            'store' => array(
                'status' => isset($store['status']) ? $store['status'] : '',
                'theme' => isset($store['theme']) ? $store['theme'] : '',
                'store_mode' => isset($store['store_mode']) ? $store['store_mode'] : '',
                'affiliate_cookie' => isset($store['affiliate_cookie']) ? $store['affiliate_cookie'] : '',
                'registration_status' => isset($store['registration_status']) ? $store['registration_status'] : '',
                'registration_approval' => isset($store['registration_approval']) ? $store['registration_approval'] : '',
                'mail_verifiy' => isset($store['mail_verifiy']) ? $store['mail_verifiy'] : '',
            ),
            'modules' => array(
                'market_tools' => isset($market_tools['status']) ? $market_tools['status'] : '',
                'referlevel' => isset($referlevel['status']) ? $referlevel['status'] : '',
                'membership' => isset($membership['status']) ? $membership['status'] : '',
                'award_level' => isset($award_level['status']) ? $award_level['status'] : '',
                'vendor_store' => isset($vendor['storestatus']) ? $vendor['storestatus'] : '',
                'vendor_deposit' => isset($vendor['depositstatus']) ? $vendor['depositstatus'] : '',
                'market_vendor_status' => $mv_status,
                'saas_enable' => $saas_enable,
            ),
            'language' => $language,
        );

        if (!empty($site['favicon'])) {
            $summary['site']['favicon_url'] = base_url('assets/images/site/' . $site['favicon']);
        }

        $data = array_merge(array('summary' => $summary), $currency);

        $this->response(array('status' => TRUE, 'data' => $data), REST_Controller::HTTP_OK);
    }

    /**
     * GET Admin_Api/notifications — admin inbox (notification_viewfor = admin).
     */
    public function notifications_get() {
        $verify_data = verify_request();

        if (isset($verify_data['status']) && $verify_data['status'] == 401) {
            $this->response(array('status' => FALSE, 'message' => 'Unauthorized Access!'), REST_Controller::HTTP_OK);
            return;
        }

        $user_type = isset($verify_data['userdata']['type']) ? $verify_data['userdata']['type'] : '';
        if ($user_type !== 'admin') {
            $this->response(array('status' => FALSE, 'message' => 'Access Denied! Admin only.'), REST_Controller::HTTP_OK);
            return;
        }

        if (!$this->_admin_api_require_for_method($verify_data, __FUNCTION__)) {
            return;
        }

        $start_from = (int)($this->get('start_from') ?? 0);
        $limit = (int)($this->get('limit') ?? 20);
        if ($limit < 1) {
            $limit = 20;
        }
        if ($limit > 100) {
            $limit = 100;
        }

        $search = trim((string)($this->get('search') ?? ''));
        $read_filter = (string)($this->get('read') ?? 'all');
        if (!in_array($read_filter, array('all', 'unread', 'read'), true)) {
            $read_filter = 'all';
        }
        $type_filter = trim((string)($this->get('type') ?? 'all'));

        $total_count = $this->Admin_model->count_admin_notifications_for_api($search, $read_filter, $type_filter);
        $rows = $this->Admin_model->get_admin_notifications_for_api($start_from, $limit, $search, $read_filter, $type_filter);

        $notifications = array();
        foreach ($rows as $r) {
            $notifications[] = $this->_admin_notification_row_for_api($r, true);
        }

        $payload = array(
            'notifications' => $notifications,
            'total_count' => $total_count,
            'start_from' => $start_from,
            'limit' => $limit,
            'has_more' => ($start_from + $limit) < $total_count,
        );

        // Per-tab totals (same search + type); only on first page to avoid extra COUNTs when paginating.
        if ($start_from === 0) {
            $payload['counts'] = array(
                'all' => $this->Admin_model->count_admin_notifications_for_api($search, 'all', $type_filter),
                'unread' => $this->Admin_model->count_admin_notifications_for_api($search, 'unread', $type_filter),
                'read' => $this->Admin_model->count_admin_notifications_for_api($search, 'read', $type_filter),
            );
        }

        $this->response(array(
            'status' => TRUE,
            'data' => array_merge($payload, get_currency_settings()),
        ), REST_Controller::HTTP_OK);
    }

    /**
     * GET Admin_Api/notification_details?notification_id=
     */
    public function notification_details_get() {
        $verify_data = verify_request();

        if (isset($verify_data['status']) && $verify_data['status'] == 401) {
            $this->response(array('status' => FALSE, 'message' => 'Unauthorized Access!'), REST_Controller::HTTP_OK);
            return;
        }

        $user_type = isset($verify_data['userdata']['type']) ? $verify_data['userdata']['type'] : '';
        if ($user_type !== 'admin') {
            $this->response(array('status' => FALSE, 'message' => 'Access Denied! Admin only.'), REST_Controller::HTTP_OK);
            return;
        }

        if (!$this->_admin_api_require_for_method($verify_data, __FUNCTION__)) {
            return;
        }

        $nid = (int)($this->get('notification_id') ?? 0);
        if ($nid < 1) {
            $this->response(array('status' => FALSE, 'message' => 'notification_id is required'), REST_Controller::HTTP_OK);
            return;
        }

        $row = $this->Admin_model->get_admin_notification_by_id_for_api($nid);
        if (empty($row)) {
            $this->response(array('status' => FALSE, 'message' => 'Notification not found'), REST_Controller::HTTP_OK);
            return;
        }

        $this->response(array(
            'status' => TRUE,
            'data' => array_merge(array(
                'notification' => $this->_admin_notification_row_for_api($row, false),
            ), get_currency_settings()),
        ), REST_Controller::HTTP_OK);
    }

    /**
     * POST Admin_Api/notification_read — mark one admin notification as read.
     */
    public function notification_read_post() {
        $verify_data = verify_request();

        if (isset($verify_data['status']) && $verify_data['status'] == 401) {
            $this->response(array('status' => FALSE, 'message' => 'Unauthorized Access!'), REST_Controller::HTTP_OK);
            return;
        }

        $user_type = isset($verify_data['userdata']['type']) ? $verify_data['userdata']['type'] : '';
        if ($user_type !== 'admin') {
            $this->response(array('status' => FALSE, 'message' => 'Access Denied! Admin only.'), REST_Controller::HTTP_OK);
            return;
        }

        if (!$this->_admin_api_require_for_method($verify_data, __FUNCTION__)) {
            return;
        }

        $nid = (int)$this->post('notification_id');
        if ($nid < 1) {
            $this->response(array('status' => FALSE, 'message' => 'notification_id is required'), REST_Controller::HTTP_OK);
            return;
        }

        $exists = $this->Admin_model->get_admin_notification_by_id_for_api($nid);
        if (empty($exists)) {
            $this->response(array('status' => FALSE, 'message' => 'Notification not found'), REST_Controller::HTTP_OK);
            return;
        }

        $this->Admin_model->mark_admin_notification_read_by_id($nid);

        $this->response(array(
            'status' => TRUE,
            'message' => 'Marked as read',
        ), REST_Controller::HTTP_OK);
    }

    /**
     * POST Admin_Api/notifications_mark_all_read — mark every admin notification as read.
     */
    public function notifications_mark_all_read_post() {
        $verify_data = verify_request();

        if (isset($verify_data['status']) && $verify_data['status'] == 401) {
            $this->response(array('status' => FALSE, 'message' => 'Unauthorized Access!'), REST_Controller::HTTP_OK);
            return;
        }

        $user_type = isset($verify_data['userdata']['type']) ? $verify_data['userdata']['type'] : '';
        if ($user_type !== 'admin') {
            $this->response(array('status' => FALSE, 'message' => 'Access Denied! Admin only.'), REST_Controller::HTTP_OK);
            return;
        }

        if (!$this->_admin_api_require_for_method($verify_data, __FUNCTION__)) {
            return;
        }

        $updated = $this->Admin_model->mark_all_admin_notifications_read();

        $this->response(array(
            'status' => TRUE,
            'message' => 'All marked as read',
            'updated_count' => $updated,
        ), REST_Controller::HTTP_OK);
    }

    /**
     * @param array $row DB row
     * @param bool  $list_mode shorten description
     * @return array<string, mixed>
     */
    protected function _admin_notification_row_for_api($row, $list_mode) {
        $id = isset($row['id']) ? (int)$row['id'] : 0;
        $path = isset($row['notification_url']) ? trim((string)$row['notification_url']) : '';
        $title = $this->_sanitize_api_history_text(isset($row['notification_title']) ? $row['notification_title'] : '', false);
        $body = $this->_sanitize_api_history_text(isset($row['notification_description']) ? $row['notification_description'] : '', true);
        if ($list_mode && strlen($body) > 280) {
            $body = substr($body, 0, 277) . '...';
        }
        $is_read_raw = isset($row['notification_is_read']) ? $row['notification_is_read'] : '0';
        $is_read = ((string)$is_read_raw === '1');
        $web = $this->_admin_notification_web_url($path);

        return array(
            'id' => $id,
            'type' => isset($row['notification_type']) ? (string)$row['notification_type'] : '',
            'title' => $title,
            'description' => $body,
            'link_path' => $path,
            'admin_web_url' => $web,
            'is_read' => $is_read,
            'created_at' => isset($row['notification_created_date']) ? $row['notification_created_date'] : '',
            'action_id' => isset($row['notification_actionID']) ? (string)$row['notification_actionID'] : '',
        );
    }

    /**
     * Build absolute admin panel URL for a stored relative path.
     *
     * @param string $notification_url
     * @return string
     */
    protected function _admin_notification_web_url($notification_url) {
        $u = trim((string)$notification_url);
        if ($u === '') {
            return '';
        }
        if (preg_match('#^https?://#i', $u)) {
            return $u;
        }
        return rtrim(base_url(), '/') . '/admincontrol/' . ltrim($u, '/');
    }

    /**
     * GET Admin_Api/ticket_subjects — ticket category list for filters (read-only).
     */
    public function ticket_subjects_get() {
        $verify_data = verify_request();

        if (isset($verify_data['status']) && $verify_data['status'] == 401) {
            $this->response(array('status' => FALSE, 'message' => 'Unauthorized Access!'), REST_Controller::HTTP_OK);
            return;
        }

        $user_type = isset($verify_data['userdata']['type']) ? $verify_data['userdata']['type'] : '';
        if ($user_type !== 'admin') {
            $this->response(array('status' => FALSE, 'message' => 'Access Denied! Admin only.'), REST_Controller::HTTP_OK);
            return;
        }

        if (!$this->_admin_api_require_for_method($verify_data, __FUNCTION__)) {
            return;
        }

        $subjects = $this->Admin_model->get_ticket_subjects_for_api();
        foreach ($subjects as &$s) {
            $s['id'] = isset($s['id']) ? (int)$s['id'] : 0;
        }
        unset($s);

        $this->response(array(
            'status' => TRUE,
            'data' => array_merge(array('subjects' => $subjects), get_currency_settings()),
        ), REST_Controller::HTTP_OK);
    }

    /**
     * GET Admin_Api/tickets — support tickets list (paginated).
     */
    public function tickets_get() {
        $verify_data = verify_request();

        if (isset($verify_data['status']) && $verify_data['status'] == 401) {
            $this->response(array('status' => FALSE, 'message' => 'Unauthorized Access!'), REST_Controller::HTTP_OK);
            return;
        }

        $user_type = isset($verify_data['userdata']['type']) ? $verify_data['userdata']['type'] : '';
        if ($user_type !== 'admin') {
            $this->response(array('status' => FALSE, 'message' => 'Access Denied! Admin only.'), REST_Controller::HTTP_OK);
            return;
        }

        if (!$this->_admin_api_require_for_method($verify_data, __FUNCTION__)) {
            return;
        }

        $start_from = (int)($this->get('start_from') ?? 0);
        $limit = (int)($this->get('limit') ?? 20);
        if ($limit < 1) {
            $limit = 20;
        }
        if ($limit > 100) {
            $limit = 100;
        }

        $search = trim((string)($this->get('search') ?? ''));
        $status_filter = (string)($this->get('status') ?? 'all');
        if ($status_filter !== 'all' && !in_array($status_filter, array('1', '2', '3'), true)) {
            $status_filter = 'all';
        }
        $subject_id_filter = (string)($this->get('subject_id') ?? 'all');
        if ($subject_id_filter !== 'all' && $subject_id_filter !== '' && !ctype_digit($subject_id_filter)) {
            $subject_id_filter = 'all';
        }

        $total_count = $this->Admin_model->count_admin_tickets_for_api($search, $status_filter, $subject_id_filter);
        $tickets = $this->Admin_model->get_admin_tickets_for_api($start_from, $limit, $search, $status_filter, $subject_id_filter);

        $this->response(array(
            'status' => TRUE,
            'data' => array_merge(array(
                'tickets' => $tickets,
                'total_count' => $total_count,
                'start_from' => $start_from,
                'limit' => $limit,
                'has_more' => ($start_from + $limit) < $total_count,
            ), get_currency_settings()),
        ), REST_Controller::HTTP_OK);
    }

    /**
     * GET Admin_Api/ticket_details?ticket_id=
     */
    public function ticket_details_get() {
        $verify_data = verify_request();

        if (isset($verify_data['status']) && $verify_data['status'] == 401) {
            $this->response(array('status' => FALSE, 'message' => 'Unauthorized Access!'), REST_Controller::HTTP_OK);
            return;
        }

        $user_type = isset($verify_data['userdata']['type']) ? $verify_data['userdata']['type'] : '';
        if ($user_type !== 'admin') {
            $this->response(array('status' => FALSE, 'message' => 'Access Denied! Admin only.'), REST_Controller::HTTP_OK);
            return;
        }

        if (!$this->_admin_api_require_for_method($verify_data, __FUNCTION__)) {
            return;
        }

        $ticket_id = trim((string)($this->get('ticket_id') ?? ''));
        if ($ticket_id === '' || strlen($ticket_id) > 64) {
            $this->response(array('status' => FALSE, 'message' => 'ticket_id is required'), REST_Controller::HTTP_OK);
            return;
        }

        $this->load->model('Tickets_model');
        $detail = $this->Tickets_model->getTicketDetails($ticket_id, null);
        if (empty($detail) || !is_array($detail)) {
            $this->response(array('status' => FALSE, 'message' => 'Ticket not found'), REST_Controller::HTTP_OK);
            return;
        }

        $detail['subject_id'] = isset($detail['subject_id']) ? (int)$detail['subject_id'] : 0;
        $detail['status'] = isset($detail['status']) ? (int)$detail['status'] : 0;
        $detail['user_id'] = isset($detail['user_id']) ? (int)$detail['user_id'] : 0;
        unset($detail['action']);

        $replies = $this->db->query(
            "SELECT id, ticket_id, user_id, message, attachment, message_type, user_type, created_at
             FROM tickets_reply WHERE ticket_id = ? ORDER BY id ASC",
            array($ticket_id)
        )->result_array();

        $out_replies = array();
        foreach ($replies as $r) {
            $msg = $this->_sanitize_ticket_message_for_api(isset($r['message']) ? $r['message'] : '');
            $attachments = array();
            if (!empty($r['attachment']) && is_string($r['attachment'])) {
                $dec = json_decode($r['attachment'], true);
                if (is_array($dec)) {
                    foreach ($dec as $fn) {
                        if (!is_string($fn) || $fn === '') {
                            continue;
                        }
                        $safe = basename($fn);
                        if ($safe === '' || $safe === '.' || $safe === '..') {
                            continue;
                        }
                        $attachments[] = array(
                            'filename' => $safe,
                            'url' => base_url('assets/user_upload/' . $safe),
                        );
                    }
                }
            }
            $out_replies[] = array(
                'id' => isset($r['id']) ? (int)$r['id'] : 0,
                'ticket_id' => isset($r['ticket_id']) ? $r['ticket_id'] : $ticket_id,
                'user_id' => isset($r['user_id']) ? (int)$r['user_id'] : 0,
                'message' => $msg,
                'message_type' => isset($r['message_type']) ? (int)$r['message_type'] : 0,
                'user_type' => isset($r['user_type']) ? (int)$r['user_type'] : 0,
                'created_at' => isset($r['created_at']) ? $r['created_at'] : '',
                'attachments' => $attachments,
            );
        }

        $this->response(array(
            'status' => TRUE,
            'data' => array_merge(array(
                'ticket' => $detail,
                'replies' => $out_replies,
            ), get_currency_settings()),
        ), REST_Controller::HTTP_OK);
    }

    /**
     * POST Admin_Api/ticket_reply — admin text reply (no file upload in this endpoint).
     */
    public function ticket_reply_post() {
        $verify_data = verify_request();

        if (isset($verify_data['status']) && $verify_data['status'] == 401) {
            $this->response(array('status' => FALSE, 'message' => 'Unauthorized Access!'), REST_Controller::HTTP_OK);
            return;
        }

        $user_type = isset($verify_data['userdata']['type']) ? $verify_data['userdata']['type'] : '';
        if ($user_type !== 'admin') {
            $this->response(array('status' => FALSE, 'message' => 'Access Denied! Admin only.'), REST_Controller::HTTP_OK);
            return;
        }

        if (!$this->_admin_api_require_for_method($verify_data, __FUNCTION__)) {
            return;
        }

        $admin_id = isset($verify_data['userdata']['id']) ? (int)$verify_data['userdata']['id'] : 0;
        if ($admin_id < 1) {
            $this->response(array('status' => FALSE, 'message' => 'Invalid admin user'), REST_Controller::HTTP_OK);
            return;
        }

        $ticket_id = trim((string)$this->post('ticket_id'));
        $raw_message = $this->post('message');
        if ($ticket_id === '' || strlen($ticket_id) > 64) {
            $this->response(array('status' => FALSE, 'message' => 'ticket_id is required'), REST_Controller::HTTP_OK);
            return;
        }
        if ($raw_message === null || $raw_message === '') {
            $this->response(array('status' => FALSE, 'message' => 'message is required'), REST_Controller::HTTP_OK);
            return;
        }

        $message = $this->_sanitize_ticket_message_for_api($raw_message);
        if ($message === '') {
            $this->response(array('status' => FALSE, 'message' => 'message is empty after sanitization'), REST_Controller::HTTP_OK);
            return;
        }

        $this->load->model('Tickets_model');
        $ticket = $this->Tickets_model->getTicketDetails($ticket_id, null);
        if (empty($ticket)) {
            $this->response(array('status' => FALSE, 'message' => 'Ticket not found'), REST_Controller::HTTP_OK);
            return;
        }

        $data_to_add = array(
            'ticket_id' => $ticket_id,
            'user_id' => $admin_id,
            'message' => $message,
            'attachment' => '[]',
            'message_type' => 1,
            'created_at' => date('Y-m-d H:i:s'),
            'user_type' => 1,
        );

        $this->db->insert('tickets_reply', $data_to_add);
        $reply_id = (int)$this->db->insert_id();

        $old_status = isset($ticket['status']) ? (int)$ticket['status'] : 0;
        if ($old_status === 3) {
            $update_data = array('updated_at' => date('Y-m-d H:i:s'), 'status' => 1);
        } else {
            $update_data = array('updated_at' => date('Y-m-d H:i:s'));
        }
        $this->db->where('ticket_id', $ticket_id);
        $this->db->update('tickets', $update_data);

        if ($reply_id > 0) {
            $this->load->model('Mail_model');
            $this->Mail_model->send_ticket_mail($ticket_id, 'ticket_reply_email');
            $this->Product_model->sendTicketNotification(array(
                'id' => $ticket_id,
                'type' => 'ticket_created',
                'title' => __('user.new_replay_on_ticket') . ' #' . $ticket_id,
                'desc' => __('admin.support_team') . ', ' . __('user.has_replyed_on_ticket'),
                'user_notification' => $this->Tickets_model->ticket_owner($ticket_id),
            ));
        }

        $this->response(array(
            'status' => TRUE,
            'message' => 'Reply sent',
            'reply_id' => $reply_id,
        ), REST_Controller::HTTP_OK);
    }

    /**
     * POST Admin_Api/ticket_status — set ticket status (1 open, 2 pending, 3 closed).
     */
    public function ticket_status_post() {
        $verify_data = verify_request();

        if (isset($verify_data['status']) && $verify_data['status'] == 401) {
            $this->response(array('status' => FALSE, 'message' => 'Unauthorized Access!'), REST_Controller::HTTP_OK);
            return;
        }

        $user_type = isset($verify_data['userdata']['type']) ? $verify_data['userdata']['type'] : '';
        if ($user_type !== 'admin') {
            $this->response(array('status' => FALSE, 'message' => 'Access Denied! Admin only.'), REST_Controller::HTTP_OK);
            return;
        }

        if (!$this->_admin_api_require_for_method($verify_data, __FUNCTION__)) {
            return;
        }

        $ticket_id = trim((string)$this->post('ticket_id'));
        $status = $this->post('status');
        if ($ticket_id === '' || strlen($ticket_id) > 64) {
            $this->response(array('status' => FALSE, 'message' => 'ticket_id is required'), REST_Controller::HTTP_OK);
            return;
        }
        if ($status === null || $status === '' || !in_array((string)$status, array('1', '2', '3'), true)) {
            $this->response(array('status' => FALSE, 'message' => 'status must be 1, 2, or 3'), REST_Controller::HTTP_OK);
            return;
        }
        $status = (int)$status;

        $this->load->model('Tickets_model');
        $ticket = $this->Tickets_model->getTicketDetails($ticket_id, null);
        if (empty($ticket)) {
            $this->response(array('status' => FALSE, 'message' => 'Ticket not found'), REST_Controller::HTTP_OK);
            return;
        }

        $this->db->where('ticket_id', $ticket_id);
        $ok = $this->db->update('tickets', array(
            'updated_at' => date('Y-m-d H:i:s'),
            'status' => $status,
        ));

        if (!$ok) {
            $this->response(array('status' => FALSE, 'message' => 'Update failed'), REST_Controller::HTTP_OK);
            return;
        }

        $this->load->model('Mail_model');
        $this->Mail_model->send_ticket_mail($ticket_id, 'ticket_status_email');

        if ($status === 3) {
            $title = '#' . $ticket_id . ' ' . __('admin.ticket_has_closed');
            $desc = __('admin.support_team') . ', ' . __('admin.has_closed_ticket_on') . ' ' . date('d M Y');
        } else {
            $title = '#' . $ticket_id . ' ' . __('admin.ticket_status_has_changed');
            $desc = __('admin.support_team') . ', ' . __('admin.has_changed_ticket_status') . ' ' . date('d M Y');
        }

        $this->Product_model->sendTicketNotification(array(
            'id' => $ticket_id,
            'type' => 'ticket_status_updated',
            'title' => $title,
            'desc' => $desc,
            'admin_notification' => 1,
            'user_notification' => $this->Tickets_model->ticket_owner($ticket_id),
        ));

        $this->response(array(
            'status' => TRUE,
            'message' => 'Status updated',
            'status' => $status,
        ), REST_Controller::HTTP_OK);
    }

    /**
     * Strip ticket / reply bodies for API (matches order history sanitizer behaviour).
     *
     * @param mixed $text
     * @return string
     */
    protected function _sanitize_ticket_message_for_api($text) {
        $text = $this->_sanitize_api_history_text((string)$text, true);
        if (strlen($text) > 20000) {
            $text = substr($text, 0, 20000);
        }
        return $text;
    }

    /**
     * Strip HTML / controls / PUA from orders_history rows so mobile Text widgets
     * never show replacement "tofu" boxes (common in wallet comments with <br> or icon codepoints).
     *
     * @param array $rows result_array() rows from orders_history
     * @return array
     */
    protected function _sanitize_orders_history_for_mobile_api($rows) {
        if (!is_array($rows)) {
            return array();
        }
        foreach ($rows as &$row) {
            if (!is_array($row)) {
                continue;
            }
            if (array_key_exists('comment', $row)) {
                $row['comment'] = $this->_normalize_orders_history_comment_for_api($row['comment']);
            }
            if (isset($row['paypal_status']) && is_string($row['paypal_status'])) {
                $row['paypal_status'] = $this->_sanitize_api_history_text($row['paypal_status'], false);
            }
            if (isset($row['created_at']) && is_string($row['created_at'])) {
                $row['created_at'] = $this->_sanitize_api_history_text($row['created_at'], false);
            }
        }
        unset($row);
        return $rows;
    }

    /**
     * @param bool $allow_newline keep single newlines in comments after <br> → \n
     */
    // -----------------------------------------------------------------------
    // Membership
    // -----------------------------------------------------------------------

    public function membership_plans_get() {
        $verify_data = verify_request();
        if (isset($verify_data['status']) && $verify_data['status'] == 401) {
            $this->response(array('status' => FALSE, 'message' => 'Unauthorized Access!'), REST_Controller::HTTP_OK);
            return;
        }
        if ((isset($verify_data['userdata']['type']) ? $verify_data['userdata']['type'] : '') !== 'admin') {
            $this->response(array('status' => FALSE, 'message' => 'Access Denied! Admin only.'), REST_Controller::HTTP_OK);
            return;
        }
        if (!$this->_admin_api_require_for_method($verify_data, __FUNCTION__)) { return; }

        $plans = $this->Admin_model->get_membership_plans_for_api();

        $this->response(array(
            'status'  => TRUE,
            'data'    => array('plans' => $plans, 'total' => count($plans)),
        ), REST_Controller::HTTP_OK);
    }

    public function membership_orders_get() {
        $verify_data = verify_request();
        if (isset($verify_data['status']) && $verify_data['status'] == 401) {
            $this->response(array('status' => FALSE, 'message' => 'Unauthorized Access!'), REST_Controller::HTTP_OK);
            return;
        }
        if ((isset($verify_data['userdata']['type']) ? $verify_data['userdata']['type'] : '') !== 'admin') {
            $this->response(array('status' => FALSE, 'message' => 'Access Denied! Admin only.'), REST_Controller::HTTP_OK);
            return;
        }
        if (!$this->_admin_api_require_for_method($verify_data, __FUNCTION__)) { return; }

        $start_from = max(0, (int)$this->get('start_from'));
        $limit      = min(100, max(1, (int)($this->get('limit') ?: 20)));
        $search     = trim((string)($this->get('search') ?: ''));
        $status     = trim((string)($this->get('status') ?: 'all'));
        $user_id    = (int)($this->get('user_id') ?: 0);

        $filters = array('search' => $search, 'status' => $status);
        if ($user_id > 0) $filters['user_id'] = $user_id;

        $total   = $this->Admin_model->count_membership_orders_for_api($filters);
        $orders  = $this->Admin_model->get_membership_orders_for_api($start_from, $limit, $filters);
        $has_more = ($start_from + count($orders)) < $total;

        $this->response(array(
            'status' => TRUE,
            'data'   => array(
                'orders'    => $orders,
                'total'     => $total,
                'has_more'  => $has_more,
            ),
        ), REST_Controller::HTTP_OK);
    }

    // -----------------------------------------------------------------------
    // Click / traffic logs
    // -----------------------------------------------------------------------

    public function click_logs_get() {
        $verify_data = verify_request();
        if (isset($verify_data['status']) && $verify_data['status'] == 401) {
            $this->response(array('status' => FALSE, 'message' => 'Unauthorized Access!'), REST_Controller::HTTP_OK);
            return;
        }
        if ((isset($verify_data['userdata']['type']) ? $verify_data['userdata']['type'] : '') !== 'admin') {
            $this->response(array('status' => FALSE, 'message' => 'Access Denied! Admin only.'), REST_Controller::HTTP_OK);
            return;
        }
        if (!$this->_admin_api_require_for_method($verify_data, __FUNCTION__)) { return; }

        $start_from = max(0, (int)$this->get('start_from'));
        $limit      = min(100, max(1, (int)($this->get('limit') ?: 20)));
        $search     = trim((string)($this->get('search') ?: ''));
        $type       = trim((string)($this->get('type') ?: 'all'));
        $user_id    = (int)($this->get('user_id') ?: 0);

        $filters = array('search' => $search, 'type' => $type);
        if ($user_id > 0) $filters['user_id'] = $user_id;

        $total    = $this->Admin_model->count_click_logs_for_api($filters);
        $logs     = $this->Admin_model->get_click_logs_for_api($start_from, $limit, $filters);
        $has_more = ($start_from + count($logs)) < $total;

        $this->response(array(
            'status' => TRUE,
            'data'   => array(
                'logs'     => $logs,
                'total'    => $total,
                'has_more' => $has_more,
            ),
        ), REST_Controller::HTTP_OK);
    }

    protected function _sanitize_api_history_text($text, $allow_newline) {
        if ($text === '' || $text === null) {
            return '';
        }
        $text = (string)$text;
        $text = html_entity_decode($text, ENT_QUOTES | ENT_HTML5, 'UTF-8');
        $text = preg_replace('/<br\s*\/?>/i', "\n", $text);
        $text = strip_tags($text);
        // C0 controls except TAB / LF / CR when allowing newlines
        if ($allow_newline) {
            $text = preg_replace('/[\x00-\x08\x0B\x0C\x0E-\x1F\x7F]/u', '', $text);
            $text = str_replace(array("\r\n", "\r"), "\n", $text);
        } else {
            $text = preg_replace('/[\x00-\x1F\x7F]/u', '', $text);
        }
        // Zero-width, bidi, BOM, object / replacement, tag characters (PUA / specials)
        $text = preg_replace('/[\x{200B}-\x{200F}\x{2028}\x{2029}\x{202A}-\x{202E}\x{2060}-\x{2064}\x{FEFF}\x{FFF9}-\x{FFFD}]/u', '', $text);
        // Private-use planes (often icon-font codepoints → tofu on mobile)
        $text = preg_replace('/[\x{E000}-\x{F8FF}]/u', '', $text);
        $text = preg_replace('/[\x{F0000}-\x{FFFFD}\x{100000}-\x{10FFFD}]/u', '', $text);
        if (!$allow_newline) {
            $text = preg_replace('/\s+/u', ' ', $text);
        } else {
            $text = preg_replace("/\n{3,}/u", "\n\n", $text);
        }
        return trim($text);
    }

    /**
     * DB sometimes stores JSON "[]" / "{}" for empty metadata — mobile must not render that as text.
     *
     * @param mixed $comment
     * @return string
     */
    protected function _normalize_orders_history_comment_for_api($comment) {
        if ($comment === null) {
            return '';
        }
        if (is_array($comment)) {
            return empty($comment) ? '' : $this->_sanitize_api_history_text(json_encode($comment), true);
        }
        if (!is_string($comment)) {
            $comment = (string)$comment;
        }
        $t = trim($comment);
        if ($t === '' || $t === '[]' || $t === '{}' || strcasecmp($t, 'null') === 0) {
            return '';
        }
        if (preg_match('/^\[\s*\]$/', $t) || preg_match('/^\{\s*\}$/', $t)) {
            return '';
        }
        $decoded = json_decode($t, true);
        if (is_array($decoded) && empty($decoded)) {
            return '';
        }
        return $this->_sanitize_api_history_text($comment, true);
    }

}
