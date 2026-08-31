<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');

class Admin_model extends CI_Model {

    public function __construct() {
        parent::__construct();
    }

    // Dashboard Statistics
    public function get_total_users() {
        return $this->db->query("SELECT COUNT(*) as count FROM users WHERE type='user'")->row()->count;
    }

    public function get_total_affiliates() {
        return $this->db->query("SELECT COUNT(*) as count FROM users WHERE type='user' AND status='1' AND (is_vendor='0' OR is_vendor IS NULL)")->row()->count;
    }

    public function get_total_vendors() {
        return $this->db->query("SELECT COUNT(*) as count FROM users WHERE type='user' AND is_vendor='1'")->row()->count;
    }

    public function get_pending_users() {
        return $this->db->query("SELECT COUNT(*) as count FROM users WHERE type='user' AND status='0'")->row()->count;
    }

    public function get_pending_withdrawals_count() {
        return $this->db->query("SELECT COUNT(*) as count FROM wallet_requests WHERE status='0'")->row()->count;
    }

    public function get_total_withdrawals_amount() {
        $this->db->select_sum('total');
        $this->db->from('wallet_requests');
        $result = $this->db->get()->row()->total;
        return $result ?? 0;
    }

    public function get_today_clicks() {
        $result = $this->db->query("SELECT COUNT(*) as count FROM product_action WHERE DATE(created_at) = CURDATE()")->row()->count;
        return $result ?? 0;
    }

    public function get_today_sales() {
        $result = $this->db->query("SELECT COUNT(*) as count FROM `order` WHERE DATE(created_at) = CURDATE()")->row()->count;
        return $result ?? 0;
    }

    public function get_month_revenue() {
        $result = $this->db->query("SELECT SUM(amount) as total FROM wallet WHERE status='1' AND MONTH(created_at) = MONTH(CURDATE()) AND YEAR(created_at) = YEAR(CURDATE())")->row()->total;
        return $result ?? 0;
    }

    public function get_total_revenue() {
        $result = $this->db->query("SELECT SUM(amount) as total FROM wallet WHERE status='1'")->row()->total;
        return $result ?? 0;
    }

    public function get_recent_users($limit = 5) {
        return $this->db->query("SELECT id, firstname, lastname, username, email, status, created_at FROM users WHERE type='user' ORDER BY created_at DESC LIMIT {$limit}")->result_array();
    }

    // User Management

    /**
     * Returns all active users, or a single user by ID.
     * Used by the messaging interface (messages / chatmessage controllers).
     */
    public function getUsers($id = null) {
        $where = "type = 'user' AND status = 1";
        if ($id !== null) {
            $where .= " AND id = " . (int)$id;
        }
        return $this->db->query(
            "SELECT id, firstname, lastname, username, email, avatar
             FROM users WHERE {$where} ORDER BY firstname ASC"
        )->result_array();
    }

    public function get_users_count($where) {
        return $this->db->query("SELECT COUNT(*) as count FROM users WHERE {$where}")->row()->count;
    }

    public function get_users($where, $start_from, $limit) {
        return $this->db->query("SELECT id, firstname, lastname, username, email, status, reg_approved, is_vendor, created_at, country, phone FROM users WHERE {$where} ORDER BY created_at DESC LIMIT {$start_from}, {$limit}")->result_array();
    }

    public function get_user_clicks($user_id) {
        $result = $this->db->query("SELECT COUNT(*) as count FROM product_action WHERE user_id='{$user_id}'")->row()->count;
        return $result ?? 0;
    }

    public function get_user_sales($user_id) {
        $result = $this->db->query("SELECT COUNT(*) as count FROM `order` WHERE user_id='{$user_id}'")->row()->count;
        return $result ?? 0;
    }

    public function get_user_by_id($user_id) {
        return $this->db->query("SELECT * FROM users WHERE id='{$user_id}' AND type='user'")->row_array();
    }

    public function update_user_status($user_id, $status_data) {
        $this->db->where('id', $user_id);
        $this->db->where('type', 'user');
        $this->db->update('users', $status_data);
        return $this->db->affected_rows();
    }

    // Withdrawal Management
    public function get_withdrawals_count($where) {
        return $this->db->query("SELECT COUNT(*) as count FROM wallet_requests wr WHERE {$where}")->row()->count;
    }

    public function get_withdrawals($where, $start_from, $limit) {
        return $this->db->query("
            SELECT wr.id, wr.tran_ids, wr.total AS amount, wr.status, wr.user_id,
                   wr.prefer_method AS payment_method, wr.settings, wr.created_at,
                   u.username, u.email, u.firstname, u.lastname
            FROM wallet_requests wr
            LEFT JOIN users u ON u.id = wr.user_id
            WHERE {$where}
            ORDER BY wr.created_at DESC
            LIMIT {$start_from}, {$limit}
        ")->result_array();
    }

    public function get_withdrawal_by_id($request_id) {
        return $this->db->query("
            SELECT wr.id, wr.tran_ids, wr.total AS amount, wr.status, wr.user_id,
                   wr.prefer_method AS payment_method, wr.settings, wr.created_at,
                   u.username, u.email, u.firstname, u.lastname
            FROM wallet_requests wr
            LEFT JOIN users u ON u.id = wr.user_id
            WHERE wr.id='{$request_id}'
        ")->row_array();
    }

    public function update_withdrawal_status($request_id, $update_data) {
        $this->db->where('id', $request_id);
        $this->db->update('wallet_requests', $update_data);
        return $this->db->affected_rows();
    }

    // Reports
    public function get_total_products() {
        return $this->db->query("SELECT COUNT(*) as count FROM product")->row()->count;
    }

    public function get_total_orders() {
        return $this->db->query("SELECT COUNT(*) as count FROM `order`")->row()->count;
    }

    public function get_clicks_by_period($days = 30) {
        $date = date('Y-m-d H:i:s', strtotime("-{$days} days"));
        $result = $this->db->query("SELECT COUNT(*) as count FROM product_action WHERE created_at >= '{$date}'")->row()->count;
        return $result ?? 0;
    }

    public function get_new_users_by_period($days = 30) {
        $date = date('Y-m-d H:i:s', strtotime("-{$days} days"));
        return $this->db->query("SELECT COUNT(*) as count FROM users WHERE created_at >= '{$date}'")->row()->count;
    }

    public function get_revenue_by_period($days = 30) {
        $date = date('Y-m-d H:i:s', strtotime("-{$days} days"));
        $result = $this->db->query("SELECT SUM(amount) as total FROM wallet WHERE status='1' AND created_at >= '{$date}'")->row()->total;
        return $result ?? 0;
    }

    /**
     * Integration tools (integration_tools) — exposed as "campaigns" in Admin API (ads / tools under a program).
     */
    public function get_integration_campaigns_for_api($start_from, $limit, $search = '', $status = 'all') {
        $this->db->select('it.id, it.program_id, it.vendor_id, it.name, it.type, it.tool_type, it.status, it.created_at, ip.name AS program_name, u.username AS vendor_username', false);
        // Lightweight stats for admin mobile list (same semantics as integration admin grid).
        $this->db->select('(SELECT COUNT(*) FROM integration_orders io WHERE io.ads_id = it.id) AS stat_sales_count', false);
        $this->db->select("(SELECT COUNT(*) FROM integration_clicks_action ica WHERE ica.tools_id = it.id AND ica.action_code IN ('_af_product_click')) AS stat_clicks_count", false);
        $this->db->select('(SELECT COALESCE(SUM(io2.total), 0) FROM integration_orders io2 WHERE io2.ads_id = it.id) AS stat_orders_total', false);
        $this->db->from('integration_tools it');
        $this->db->join('integration_programs ip', 'ip.id = it.program_id', 'left');
        $this->db->join('users u', 'u.id = it.vendor_id', 'left');
        if ($status === 'active') {
            $this->db->where('it.status', 1);
        } elseif ($status === 'inactive') {
            $this->db->where('it.status', 0);
        }
        if ($search !== '') {
            $this->db->group_start();
            $this->db->like('it.name', $search);
            $this->db->or_like('ip.name', $search);
            $this->db->or_like('u.username', $search);
            $this->db->group_end();
        }
        $this->db->order_by('it.id', 'DESC');
        $this->db->limit((int)$limit, (int)$start_from);
        return $this->db->get()->result_array();
    }

    public function count_integration_campaigns_for_api($search = '', $status = 'all') {
        $this->db->select('COUNT(*) AS c', false);
        $this->db->from('integration_tools it');
        $this->db->join('integration_programs ip', 'ip.id = it.program_id', 'left');
        $this->db->join('users u', 'u.id = it.vendor_id', 'left');
        if ($status === 'active') {
            $this->db->where('it.status', 1);
        } elseif ($status === 'inactive') {
            $this->db->where('it.status', 0);
        }
        if ($search !== '') {
            $this->db->group_start();
            $this->db->like('it.name', $search);
            $this->db->or_like('ip.name', $search);
            $this->db->or_like('u.username', $search);
            $this->db->group_end();
        }
        $row = $this->db->get()->row_array();
        return isset($row['c']) ? (int)$row['c'] : 0;
    }

    /**
     * Integration marketing categories (integration_category) — Admin API list.
     */
    public function get_integration_categories_for_api($start_from, $limit, $search = '') {
        $this->db->select('c.id, c.name, c.parent_id, c.created_at, p.name AS parent_name', false);
        $this->db->from('integration_category c');
        $this->db->join('integration_category p', 'p.id = c.parent_id', 'left');
        if ($search !== '') {
            $this->db->group_start();
            $this->db->like('c.name', $search);
            $this->db->or_like('p.name', $search);
            $this->db->group_end();
        }
        $this->db->order_by('c.id', 'DESC');
        $this->db->limit((int)$limit, (int)$start_from);
        $rows = $this->db->get()->result_array();
        foreach ($rows as &$r) {
            $r['id'] = isset($r['id']) ? (int)$r['id'] : 0;
            $r['parent_id'] = isset($r['parent_id']) ? (int)$r['parent_id'] : 0;
        }
        return $rows;
    }

    public function count_integration_categories_for_api($search = '') {
        $this->db->select('COUNT(*) AS c', false);
        $this->db->from('integration_category c');
        $this->db->join('integration_category p', 'p.id = c.parent_id', 'left');
        if ($search !== '') {
            $this->db->group_start();
            $this->db->like('c.name', $search);
            $this->db->or_like('p.name', $search);
            $this->db->group_end();
        }
        $row = $this->db->get()->row_array();
        return isset($row['c']) ? (int)$row['c'] : 0;
    }

    public function get_integration_category_by_id_for_api($id) {
        $id = (int)$id;
        if ($id < 1) {
            return null;
        }
        $this->db->select('c.id, c.name, c.parent_id, c.created_at, p.name AS parent_name', false);
        $this->db->from('integration_category c');
        $this->db->join('integration_category p', 'p.id = c.parent_id', 'left');
        $this->db->where('c.id', $id);
        $row = $this->db->get()->row_array();
        if (empty($row)) {
            return null;
        }
        $row['id'] = (int)$row['id'];
        $row['parent_id'] = isset($row['parent_id']) ? (int)$row['parent_id'] : 0;
        return $row;
    }

    /**
     * Admin orders list: local store (`order`) + integration (`integration_orders`), lightweight rows.
     *
     * @param string $type_filter all|store|ex
     * @param string $status_filter all or numeric status code
     */
    public function count_admin_orders_for_api($search = '', $type_filter = 'all', $status_filter = 'all') {
        $type_filter = in_array($type_filter, array('all', 'store', 'ex'), true) ? $type_filter : 'all';
        $parts = array();
        $bind = array();
        if ($type_filter === 'all' || $type_filter === 'store') {
            list($sql, $b) = $this->_admin_orders_store_subquery_sql($search, $status_filter);
            $parts[] = $sql;
            $bind = array_merge($bind, $b);
        }
        if ($type_filter === 'all' || $type_filter === 'ex') {
            list($sql, $b) = $this->_admin_orders_ex_subquery_sql($search, $status_filter);
            $parts[] = $sql;
            $bind = array_merge($bind, $b);
        }
        if (empty($parts)) {
            return 0;
        }
        $inner = implode(' UNION ALL ', $parts);
        $sql = "SELECT COUNT(*) AS c FROM ({$inner}) AS admin_ord_count";
        $row = $this->db->query($sql, $bind)->row_array();
        return isset($row['c']) ? (int)$row['c'] : 0;
    }

    public function get_admin_orders_for_api($start_from, $limit, $search = '', $type_filter = 'all', $status_filter = 'all') {
        $type_filter = in_array($type_filter, array('all', 'store', 'ex'), true) ? $type_filter : 'all';
        $start_from = (int)$start_from;
        $limit = (int)$limit;
        $parts = array();
        $bind = array();
        if ($type_filter === 'all' || $type_filter === 'store') {
            list($sql, $b) = $this->_admin_orders_store_select_sql($search, $status_filter);
            $parts[] = '(' . $sql . ')';
            $bind = array_merge($bind, $b);
        }
        if ($type_filter === 'all' || $type_filter === 'ex') {
            list($sql, $b) = $this->_admin_orders_ex_select_sql($search, $status_filter);
            $parts[] = '(' . $sql . ')';
            $bind = array_merge($bind, $b);
        }
        if (empty($parts)) {
            return array();
        }
        $union_inner = implode(' UNION ALL ', $parts);
        $sql = "SELECT * FROM ({$union_inner}) AS admin_ord_list ORDER BY created_at DESC LIMIT ?, ?";
        $bind[] = $start_from;
        $bind[] = $limit;
        $rows = $this->db->query($sql, $bind)->result_array();
        foreach ($rows as &$r) {
            $r['id'] = isset($r['id']) ? (int)$r['id'] : 0;
            $r['status'] = isset($r['status']) ? (int)$r['status'] : 0;
            $r['user_id'] = isset($r['user_id']) ? (int)$r['user_id'] : 0;
            $r['total'] = isset($r['total']) ? number_format((float)$r['total'], 2, '.', '') : '0.00';
        }
        return $rows;
    }

    /**
     * @return array{0:string,1:array}
     */
    protected function _admin_orders_store_subquery_sql($search, $status_filter) {
        list($where, $bind) = $this->_admin_orders_store_where_bindings($search, $status_filter);
        $sql = "SELECT o.id FROM `order` o LEFT JOIN users u ON u.id = o.user_id WHERE {$where}";
        return array($sql, $bind);
    }

    /**
     * @return array{0:string,1:array}
     */
    protected function _admin_orders_store_select_sql($search, $status_filter) {
        list($where, $bind) = $this->_admin_orders_store_where_bindings($search, $status_filter);
        $sql = "SELECT 'store' AS order_type, o.id, o.status, o.user_id, o.total, o.currency_code AS currency_code, o.payment_method AS payment_method, o.txn_id AS external_reference, o.created_at, u.firstname, u.lastname, u.username, u.email FROM `order` o LEFT JOIN users u ON u.id = o.user_id WHERE {$where}";
        return array($sql, $bind);
    }

    /**
     * @return array{0:string,1:array}
     */
    protected function _admin_orders_ex_subquery_sql($search, $status_filter) {
        list($where, $bind) = $this->_admin_orders_ex_where_bindings($search, $status_filter);
        $sql = "SELECT io.id FROM integration_orders io LEFT JOIN users u2 ON u2.id = io.user_id WHERE {$where}";
        return array($sql, $bind);
    }

    /**
     * @return array{0:string,1:array}
     */
    protected function _admin_orders_ex_select_sql($search, $status_filter) {
        list($where, $bind) = $this->_admin_orders_ex_where_bindings($search, $status_filter);
        $sql = "SELECT 'ex' AS order_type, io.id, io.status, io.user_id, io.total, io.currency AS currency_code, '' AS payment_method, io.order_id AS external_reference, io.created_at, u2.firstname, u2.lastname, u2.username, u2.email FROM integration_orders io LEFT JOIN users u2 ON u2.id = io.user_id WHERE {$where}";
        return array($sql, $bind);
    }

    /**
     * @return array{0:string,1:array}
     */
    protected function _admin_orders_store_where_bindings($search, $status_filter) {
        $where = '1=1';
        $bind = array();
        if ($status_filter !== 'all' && $status_filter !== '' && is_numeric($status_filter)) {
            $where .= ' AND o.status = ?';
            $bind[] = (int)$status_filter;
        }
        if ($search !== '') {
            $like = '%' . $this->db->escape_like_str($search) . '%';
            $where .= " AND (o.txn_id LIKE ? OR u.username LIKE ? OR u.email LIKE ? OR u.firstname LIKE ? OR u.lastname LIKE ?";
            $bind[] = $like;
            $bind[] = $like;
            $bind[] = $like;
            $bind[] = $like;
            $bind[] = $like;
            if (ctype_digit((string)$search)) {
                $where .= ' OR o.id = ?';
                $bind[] = (int)$search;
            }
            $where .= ')';
        }
        return array($where, $bind);
    }

    /**
     * @return array{0:string,1:array}
     */
    protected function _admin_orders_ex_where_bindings($search, $status_filter) {
        $where = '1=1';
        $bind = array();
        if ($status_filter !== 'all' && $status_filter !== '' && is_numeric($status_filter)) {
            $where .= ' AND io.status = ?';
            $bind[] = (int)$status_filter;
        }
        if ($search !== '') {
            $like = '%' . $this->db->escape_like_str($search) . '%';
            $where .= " AND (io.order_id LIKE ? OR u2.username LIKE ? OR u2.email LIKE ? OR u2.firstname LIKE ? OR u2.lastname LIKE ? OR io.base_url LIKE ? OR io.script_name LIKE ?";
            $bind[] = $like;
            $bind[] = $like;
            $bind[] = $like;
            $bind[] = $like;
            $bind[] = $like;
            $bind[] = $like;
            $bind[] = $like;
            if (ctype_digit((string)$search)) {
                $where .= ' OR io.id = ?';
                $bind[] = (int)$search;
            }
            $where .= ')';
        }
        return array($where, $bind);
    }

    // --- Support tickets (admin API) ---

    /**
     * @param string $search
     * @param string $status_filter all|1|2|3
     * @param string $subject_id_filter all or numeric subject id
     * @return int
     */
    public function count_admin_tickets_for_api($search, $status_filter, $subject_id_filter) {
        list($where, $bind) = $this->_admin_tickets_where_bindings($search, $status_filter, $subject_id_filter);
        $sql = "SELECT COUNT(*) AS c FROM tickets t
            LEFT JOIN tickets_subject ts ON ts.id = t.subject_id
            LEFT JOIN users u ON u.id = t.user_id
            WHERE {$where}";
        $row = $this->db->query($sql, $bind)->row_array();
        return isset($row['c']) ? (int)$row['c'] : 0;
    }

    /**
     * @return array<int, array<string, mixed>>
     */
    public function get_admin_tickets_for_api($start_from, $limit, $search, $status_filter, $subject_id_filter) {
        list($where, $bind) = $this->_admin_tickets_where_bindings($search, $status_filter, $subject_id_filter);
        $sql = "SELECT t.ticket_id, t.subject_id, t.status, t.user_id, t.created_at, t.updated_at,
            ts.subject AS subject_name,
            TRIM(CONCAT(IFNULL(u.firstname,''),' ',IFNULL(u.lastname,''))) AS user_display_name,
            u.username, u.email
            FROM tickets t
            LEFT JOIN tickets_subject ts ON ts.id = t.subject_id
            LEFT JOIN users u ON u.id = t.user_id
            WHERE {$where}
            ORDER BY t.updated_at DESC, t.created_at DESC
            LIMIT ?, ?";
        $bind[] = (int)$start_from;
        $bind[] = (int)$limit;
        $rows = $this->db->query($sql, $bind)->result_array();
        foreach ($rows as &$r) {
            $r['subject_id'] = isset($r['subject_id']) ? (int)$r['subject_id'] : 0;
            $r['status'] = isset($r['status']) ? (int)$r['status'] : 0;
            $r['user_id'] = isset($r['user_id']) ? (int)$r['user_id'] : 0;
        }
        unset($r);
        return $rows;
    }

    /**
     * @return array{0:string,1:array}
     */
    protected function _admin_tickets_where_bindings($search, $status_filter, $subject_id_filter) {
        $where = '1=1';
        $bind = array();
        if ($status_filter !== 'all' && $status_filter !== '' && in_array((string)$status_filter, array('1', '2', '3'), true)) {
            $where .= ' AND t.status = ?';
            $bind[] = (int)$status_filter;
        }
        if ($subject_id_filter !== '' && $subject_id_filter !== 'all' && is_numeric($subject_id_filter)) {
            $where .= ' AND t.subject_id = ?';
            $bind[] = (int)$subject_id_filter;
        }
        if ($search !== '') {
            $like = '%' . $this->db->escape_like_str($search) . '%';
            $where .= " AND (t.ticket_id LIKE ? OR u.username LIKE ? OR u.email LIKE ? OR u.firstname LIKE ? OR u.lastname LIKE ? OR ts.subject LIKE ?)";
            $bind[] = $like;
            $bind[] = $like;
            $bind[] = $like;
            $bind[] = $like;
            $bind[] = $like;
            $bind[] = $like;
        }
        return array($where, $bind);
    }

    /**
     * @return array<int, array<string, mixed>>
     */
    public function get_ticket_subjects_for_api() {
        return $this->db->query("SELECT id, subject FROM tickets_subject ORDER BY subject ASC")->result_array();
    }

    // --- Admin notifications (notification table, viewfor = admin) ---

    /**
     * @param string $read_filter all|unread|read
     * @param string $type_filter all or exact notification_type
     * @return array{0:string,1:array}
     */
    protected function _admin_notifications_where_bindings($search, $read_filter, $type_filter) {
        $where = "notification_viewfor = 'admin'";
        $bind = array();
        if ($read_filter === 'unread') {
            $where .= " AND (notification_is_read = '0' OR notification_is_read = 0)";
        } elseif ($read_filter === 'read') {
            $where .= " AND notification_is_read = '1'";
        }
        if ($type_filter !== '' && $type_filter !== 'all') {
            $where .= ' AND notification_type = ?';
            $bind[] = $type_filter;
        }
        if ($search !== '') {
            $like = '%' . $this->db->escape_like_str($search) . '%';
            $where .= ' AND (notification_title LIKE ? OR notification_description LIKE ? OR notification_type LIKE ?)';
            $bind[] = $like;
            $bind[] = $like;
            $bind[] = $like;
        }
        return array($where, $bind);
    }

    public function count_admin_notifications_for_api($search, $read_filter, $type_filter) {
        list($where, $bind) = $this->_admin_notifications_where_bindings($search, $read_filter, $type_filter);
        $sql = "SELECT COUNT(*) AS c FROM notification WHERE {$where}";
        $row = $this->db->query($sql, $bind)->row_array();
        return isset($row['c']) ? (int)$row['c'] : 0;
    }

    /**
     * @return array<int, array<string, mixed>>
     */
    public function get_admin_notifications_for_api($start_from, $limit, $search, $read_filter, $type_filter) {
        list($where, $bind) = $this->_admin_notifications_where_bindings($search, $read_filter, $type_filter);
        // Table primary key is notification_id (not id) — alias as id for API clients.
        $sql = "SELECT notification_id AS id, notification_url, notification_type, notification_title, notification_description,
            notification_is_read, notification_created_date, notification_actionID
            FROM notification WHERE {$where}
            ORDER BY notification_created_date DESC
            LIMIT ?, ?";
        $bind[] = (int)$start_from;
        $bind[] = (int)$limit;
        return $this->db->query($sql, $bind)->result_array();
    }

    /**
     * @return array<string, mixed>|null
     */
    public function get_admin_notification_by_id_for_api($id) {
        $id = (int)$id;
        if ($id < 1) {
            return null;
        }
        $row = $this->db->query(
            "SELECT notification_id AS id, notification_url, notification_type, notification_title, notification_description,
            notification_is_read, notification_created_date, notification_actionID, notification_view_user_id
            FROM notification WHERE notification_id = ? AND notification_viewfor = 'admin' LIMIT 1",
            array($id)
        )->row_array();
        return !empty($row) ? $row : null;
    }

    public function mark_admin_notification_read_by_id($id) {
        $id = (int)$id;
        if ($id < 1) {
            return 0;
        }
        $this->db->where('notification_id', $id);
        $this->db->where('notification_viewfor', 'admin');
        $this->db->update('notification', array('notification_is_read' => '1'));
        return (int)$this->db->affected_rows();
    }

    public function mark_all_admin_notifications_read() {
        $this->db->where('notification_viewfor', 'admin');
        $this->db->where('notification_is_read !=', '1');
        $this->db->update('notification', array('notification_is_read' => '1'));
        return (int)$this->db->affected_rows();
    }

    /**
     * Read-only vendor context for Admin API user_details when users.is_vendor = 1.
     *
     * @return array<string, mixed>|null
     */
    public function get_vendor_profile_extras_for_api($user_id) {
        $user_id = (int)$user_id;
        if ($user_id < 1) {
            return null;
        }
        $row = $this->db->query(
            "SELECT is_vendor, store_name, store_slug, store_email, store_address, store_contact_number, store_contact_us_map, store_meta
             FROM users WHERE id = ? AND type = 'user' LIMIT 1",
            array($user_id)
        )->row_array();
        if (empty($row) || (string)($row['is_vendor'] ?? '') !== '1') {
            return null;
        }
        $out = array(
            'store' => array(
                'name' => isset($row['store_name']) ? trim((string)$row['store_name']) : '',
                'slug' => isset($row['store_slug']) ? trim((string)$row['store_slug']) : '',
                'email' => isset($row['store_email']) ? trim((string)$row['store_email']) : '',
                'address' => isset($row['store_address']) ? trim((string)$row['store_address']) : '',
                'contact_number' => isset($row['store_contact_number']) ? trim((string)$row['store_contact_number']) : '',
                'contact_map_url' => isset($row['store_contact_us_map']) ? trim((string)$row['store_contact_us_map']) : '',
                'meta' => isset($row['store_meta']) ? trim((string)$row['store_meta']) : '',
            ),
            'vendor_setting' => null,
            'counts' => array(
                'integration_programs' => 0,
                'integration_tools' => 0,
                'store_products' => 0,
            ),
        );
        $vs = $this->db->query(
            "SELECT vendor_status, affiliate_sale_commission_type, affiliate_commission_value, affiliate_click_count, affiliate_click_amount,
                form_affiliate_click_count, form_affiliate_click_amount, form_affiliate_sale_commission_type, form_affiliate_commission_value,
                vendor_shares_sales_status
             FROM vendor_setting WHERE user_id = ? LIMIT 1",
            array($user_id)
        )->row_array();
        if (!empty($vs)) {
            $out['vendor_setting'] = $vs;
        }
        $c = $this->db->query('SELECT COUNT(*) AS c FROM integration_programs WHERE vendor_id = ?', array($user_id))->row_array();
        $out['counts']['integration_programs'] = isset($c['c']) ? (int)$c['c'] : 0;
        $c2 = $this->db->query('SELECT COUNT(*) AS c FROM integration_tools WHERE vendor_id = ?', array($user_id))->row_array();
        $out['counts']['integration_tools'] = isset($c2['c']) ? (int)$c2['c'] : 0;
        $c3 = $this->db->query(
            "SELECT COUNT(*) AS c FROM product WHERE product_created_by = ? AND (is_campaign_product = 0 OR is_campaign_product IS NULL)",
            array($user_id)
        )->row_array();
        $out['counts']['store_products'] = isset($c3['c']) ? (int)$c3['c'] : 0;
        return $out;
    }

    /**
     * Admin panel staff (users.type = 'admin') — read-only API list count.
     */
    public function count_admin_staff_for_api($search) {
        $search = trim((string)$search);
        $this->db->from('users u');
        $this->db->where('u.type', 'admin');
        if ($search !== '') {
            $this->db->group_start();
            $this->db->like('u.firstname', $search);
            $this->db->or_like('u.lastname', $search);
            $this->db->or_like('u.username', $search);
            $this->db->or_like('u.email', $search);
            $this->db->group_end();
        }
        return (int)$this->db->count_all_results();
    }

    /**
     * @return array<int, array<string, mixed>>
     */
    public function get_admin_staff_for_api($start_from, $limit, $search) {
        $start_from = (int)$start_from;
        $limit = (int)$limit;
        if ($limit < 1) {
            $limit = 20;
        }
        $search = trim((string)$search);
        $join_roles = $this->db->table_exists('admin_roles');
        $this->db->select('u.id, u.firstname, u.lastname, u.username, u.email, u.status, u.PhoneNumber, u.admin_role_id, u.created_at', false);
        if ($this->db->field_exists('Country', 'users')) {
            $this->db->select('u.Country', false);
        }
        $this->db->select('c.sortname AS country_sortname', false);
        if ($join_roles) {
            $this->db->select('ar.name AS role_name, ar.slug AS role_slug', false);
        }
        $this->db->from('users u');
        $this->db->join('countries c', 'c.id = u.Country', 'left');
        if ($join_roles) {
            $this->db->join('admin_roles ar', 'ar.id = u.admin_role_id', 'left');
        }
        $this->db->where('u.type', 'admin');
        if ($search !== '') {
            $this->db->group_start();
            $this->db->like('u.firstname', $search);
            $this->db->or_like('u.lastname', $search);
            $this->db->or_like('u.username', $search);
            $this->db->or_like('u.email', $search);
            $this->db->group_end();
        }
        $this->db->order_by('(u.id = 1) DESC, u.firstname ASC, u.lastname ASC', '', false);
        $this->db->limit($limit, $start_from);
        $rows = $this->db->get()->result_array();
        return is_array($rows) ? $rows : array();
    }

    /**
     * Single admin staff row (type = admin) for read-only API detail.
     *
     * @return array<string, mixed>|null
     */
    public function get_admin_staff_by_id_for_api($user_id) {
        $user_id = (int)$user_id;
        if ($user_id < 1) {
            return null;
        }
        $join_roles = $this->db->table_exists('admin_roles');
        $this->db->select('u.*, c.sortname AS country_sortname', false);
        if ($join_roles) {
            $this->db->select('ar.name AS role_name, ar.slug AS role_slug', false);
        }
        $this->db->from('users u');
        $this->db->join('countries c', 'c.id = u.Country', 'left');
        if ($join_roles) {
            $this->db->join('admin_roles ar', 'ar.id = u.admin_role_id', 'left');
        }
        $this->db->where('u.id', $user_id);
        $this->db->where('u.type', 'admin');
        $this->db->limit(1);
        $row = $this->db->get()->row_array();
        return !empty($row) ? $row : null;
    }

    /**
     * Read-only roles list with decoded permission slugs and admin user counts.
     *
     * @return array{table_exists: bool, roles: array<int, array<string, mixed>>}
     */
    public function get_admin_roles_for_api() {
        if (!$this->db->table_exists('admin_roles')) {
            return array('table_exists' => false, 'roles' => array());
        }
        $this->db->order_by('name', 'ASC');
        $roles = $this->db->get('admin_roles')->result_array();
        if (!is_array($roles)) {
            $roles = array();
        }
        $counts = array();
        if ($this->db->field_exists('admin_role_id', 'users')) {
            $q = $this->db->select('admin_role_id, COUNT(*) AS cnt', false)
                ->from('users')
                ->where('type', 'admin')
                ->where('admin_role_id IS NOT NULL', null, false)
                ->group_by('admin_role_id')
                ->get()->result_array();
            foreach ($q as $r) {
                if (isset($r['admin_role_id'])) {
                    $counts[(int)$r['admin_role_id']] = isset($r['cnt']) ? (int)$r['cnt'] : 0;
                }
            }
        }
        $out = array();
        foreach ($roles as $r) {
            $id = isset($r['id']) ? (int)$r['id'] : 0;
            $perms_raw = isset($r['permissions']) ? $r['permissions'] : null;
            $perms = array();
            if (!empty($perms_raw)) {
                $decoded = is_string($perms_raw) ? json_decode($perms_raw, true) : $perms_raw;
                $perms = is_array($decoded) ? array_values($decoded) : array();
            }
            $out[] = array(
                'id' => (string)$id,
                'name' => isset($r['name']) ? (string)$r['name'] : '',
                'slug' => isset($r['slug']) ? (string)$r['slug'] : '',
                'permissions' => $perms,
                'admin_user_count' => isset($counts[$id]) ? $counts[$id] : 0,
                'created_at' => isset($r['created_at']) ? (string)$r['created_at'] : '',
            );
        }
        return array('table_exists' => true, 'roles' => $out);
    }

    // -----------------------------------------------------------------------
    // Membership — read-only API helpers
    // -----------------------------------------------------------------------

    /**
     * All membership plans, newest-first. No paging — plans list is small.
     */
    public function get_membership_plans_for_api() {
        $rows = $this->db->query(
            "SELECT mp.id, mp.name, mp.price, mp.special, mp.bonus,
                    mp.total_day, mp.billing_period, mp.custom_period,
                    mp.have_trail, mp.free_trail, mp.status,
                    al.level_number AS plan_level
             FROM membership_plans mp
             LEFT JOIN award_level al ON al.id = mp.level_id
             ORDER BY mp.id DESC"
        )->result_array();

        $out = array();
        foreach ($rows as $r) {
            $billing = (string)($r['billing_period'] ?? '');
            if ($billing === 'custom') {
                $cp = (int)($r['custom_period'] ?? 0);
                if ($cp === 7)       $billing_label = 'Per Week';
                elseif ($cp === 30)  $billing_label = 'Per Month';
                else                 $billing_label = 'Per ' . $cp . ' Days';
            } else {
                $billing_label = $billing === 'lifetime_free'
                    ? 'Lifetime'
                    : ucfirst(str_replace('_', ' ', $billing));
            }
            $out[] = array(
                'id'            => (int)$r['id'],
                'name'          => (string)$r['name'],
                'price'         => (string)$r['price'],
                'special_price' => isset($r['special']) && $r['special'] > 0 ? (string)$r['special'] : null,
                'bonus'         => (string)($r['bonus'] ?? '0'),
                'total_day'     => (int)($r['total_day'] ?? 0),
                'billing_period' => $billing,
                'billing_label'  => $billing_label,
                'is_lifetime'   => $billing === 'lifetime_free' ? 1 : 0,
                'has_trial'     => (int)($r['have_trail'] ?? 0),
                'trial_days'    => (int)($r['free_trail'] ?? 0),
                'plan_level'    => isset($r['plan_level']) ? (int)$r['plan_level'] : null,
                'status'        => isset($r['status']) ? (int)$r['status'] : 1,
            );
        }
        return $out;
    }

    public function count_membership_orders_for_api($filters = array()) {
        $this->db->from('membership_user mu');
        $this->db->join('users u', 'u.id = mu.user_id', 'left');
        $this->_membership_orders_where($filters);
        return (int)$this->db->count_all_results();
    }

    public function get_membership_orders_for_api($start_from, $limit, $filters = array()) {
        $this->db->select(
            'mu.id, mu.plan_id, mu.user_id, mu.total, mu.bonus_commission,
             mu.status_id, mu.is_active, mu.is_lifetime,
             mu.started_at, mu.expire_at, mu.created_at,
             mu.total_day, mu.payment_method,
             CONCAT(u.firstname," ",u.lastname) AS user_name,
             u.username AS user_username,
             mp.name AS plan_name, mp.billing_period AS plan_billing_period'
        );
        $this->db->from('membership_user mu');
        $this->db->join('users u', 'u.id = mu.user_id', 'left');
        $this->db->join('membership_plans mp', 'mp.id = mu.plan_id', 'left');
        $this->_membership_orders_where($filters);
        $this->db->order_by('mu.id', 'DESC');
        $this->db->limit((int)$limit, (int)$start_from);
        $rows = $this->db->get()->result_array();

        $status_list = array(
            0 => 'pending', 1 => 'active', 2 => 'total_not_match',
            3 => 'denied',  4 => 'expired', 5 => 'failed',
            7 => 'processed', 8 => 'refunded',
        );
        $out = array();
        foreach ($rows as $r) {
            $sid = (int)$r['status_id'];
            $out[] = array(
                'id'               => (int)$r['id'],
                'user_id'          => (int)$r['user_id'],
                'user_name'        => trim((string)($r['user_name'] ?? '')),
                'user_username'    => (string)($r['user_username'] ?? ''),
                'plan_id'          => (int)$r['plan_id'],
                'plan_name'        => (string)($r['plan_name'] ?? ''),
                'plan_billing'     => (string)($r['plan_billing_period'] ?? ''),
                'total'            => (string)$r['total'],
                'bonus_commission' => (string)($r['bonus_commission'] ?? '0'),
                'status_id'        => $sid,
                'status_label'     => isset($status_list[$sid]) ? $status_list[$sid] : 'unknown',
                'is_active'        => (int)($r['is_active'] ?? 0),
                'is_lifetime'      => (int)($r['is_lifetime'] ?? 0),
                'total_day'        => (int)($r['total_day'] ?? 0),
                'payment_method'   => (string)($r['payment_method'] ?? ''),
                'started_at'       => (string)($r['started_at'] ?? ''),
                'expire_at'        => (string)($r['expire_at'] ?? ''),
                'created_at'       => (string)($r['created_at'] ?? ''),
            );
        }
        return $out;
    }

    private function _membership_orders_where($filters) {
        if (!empty($filters['user_id'])) {
            $this->db->where('mu.user_id', (int)$filters['user_id']);
        }
        if (isset($filters['status']) && $filters['status'] !== '' && $filters['status'] !== 'all') {
            $this->db->where('mu.status_id', (int)$filters['status']);
        }
        if (!empty($filters['search'])) {
            $s = $this->db->escape_like_str($filters['search']);
            $this->db->group_start();
            $this->db->like('u.username', $s, 'both');
            $this->db->or_like('u.firstname', $s, 'both');
            $this->db->or_like('u.lastname', $s, 'both');
            $this->db->group_end();
        }
    }

    // -----------------------------------------------------------------------
    // Click / traffic logs — read-only API helpers (integration_clicks_logs)
    // -----------------------------------------------------------------------

    public function count_click_logs_for_api($filters = array()) {
        $this->db->from('integration_clicks_logs cl');
        $this->db->join('users u', 'u.id = cl.user_id', 'left');
        $this->_click_logs_where($filters);
        return (int)$this->db->count_all_results();
    }

    public function get_click_logs_for_api($start_from, $limit, $filters = array()) {
        $this->db->select(
            'cl.id, cl.click_type, cl.ip, cl.country_code,
             cl.browserName, cl.isMobile, cl.mobileName,
             cl.link, cl.page_open_time, cl.created_at,
             cl.user_id,
             CONCAT(u.firstname," ",u.lastname) AS user_name,
             u.username AS user_username'
        );
        $this->db->from('integration_clicks_logs cl');
        $this->db->join('users u', 'u.id = cl.user_id', 'left');
        $this->_click_logs_where($filters);
        $this->db->order_by('cl.id', 'DESC');
        $this->db->limit((int)$limit, (int)$start_from);
        $rows = $this->db->get()->result_array();

        $out = array();
        foreach ($rows as $r) {
            $out[] = array(
                'id'             => (int)$r['id'],
                'click_type'     => (string)($r['click_type'] ?? ''),
                'ip'             => (string)($r['ip'] ?? ''),
                'country_code'   => (string)($r['country_code'] ?? ''),
                'browser'        => (string)($r['browserName'] ?? ''),
                'is_mobile'      => (int)($r['isMobile'] ?? 0),
                'mobile_name'    => (string)($r['mobileName'] ?? ''),
                'link'           => (string)($r['link'] ?? ''),
                'page_open_time' => (string)($r['page_open_time'] ?? ''),
                'created_at'     => (string)($r['created_at'] ?? ''),
                'user_id'        => (int)($r['user_id'] ?? 0),
                'user_name'      => trim((string)($r['user_name'] ?? '')),
                'user_username'  => (string)($r['user_username'] ?? ''),
            );
        }
        return $out;
    }

    private function _click_logs_where($filters) {
        if (!empty($filters['user_id'])) {
            $this->db->where('cl.user_id', (int)$filters['user_id']);
        }
        if (!empty($filters['type']) && $filters['type'] !== 'all') {
            $this->db->where('cl.click_type', $filters['type']);
        }
        if (!empty($filters['search'])) {
            $s = $this->db->escape_like_str($filters['search']);
            $this->db->group_start();
            $this->db->like('u.username', $s, 'both');
            $this->db->or_like('u.firstname', $s, 'both');
            $this->db->or_like('u.lastname', $s, 'both');
            $this->db->or_like('cl.ip', $s, 'both');
            $this->db->or_like('cl.country_code', $s, 'both');
            $this->db->group_end();
        }
    }

}