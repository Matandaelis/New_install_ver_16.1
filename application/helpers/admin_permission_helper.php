<?php
defined('BASEPATH') OR exit('No direct script access allowed');

if (!function_exists('check_admin_permission')) {
    function check_admin_permission() {
        $CI =& get_instance();
        if (!$CI->session->userdata('administrator')) {
            return;
        }
        $controller = strtolower($CI->router->class);
        $method = $CI->router->method;
        $map = $CI->config->item('admin_permission_map');
        if (empty($map) || !isset($map[$controller])) {
            return;
        }
        $slug = isset($map[$controller][$method]) ? $map[$controller][$method] : null;
        if ($slug && function_exists('can_admin') && !can_admin($slug)) {
            $CI->session->set_flashdata('error', function_exists('__') ? __('admin.access_denied') : 'Access denied');
            redirect(base_url('admincontrol/dashboard'));
            exit;
        }
    }
}

if (!function_exists('admin_user_with_permissions')) {
    function admin_user_with_permissions($user) {
        if (empty($user) || !is_array($user)) {
            return $user;
        }
        if (isset($user['id']) && (int)$user['id'] === 1) {
            $user['admin_role_label'] = function_exists('__') ? __('admin.super_administrator') : 'Super Administrator';
            return $user;
        }
        if (!empty($user['admin_role_id'])) {
            $CI =& get_instance();
            $CI->load->database();
            $role = $CI->db->get_where('admin_roles', ['id' => (int)$user['admin_role_id']])->row_array();
            if (!empty($role)) {
                $user['admin_role_label'] = $role['name'];
                if (!empty($role['permissions'])) {
                    $perms = is_string($role['permissions']) ? json_decode($role['permissions'], true) : $role['permissions'];
                    $user['admin_permissions'] = is_array($perms) ? $perms : [];
                } else {
                    $user['admin_permissions'] = [];
                }
                return $user;
            }
            $user['admin_role_label'] = 'Role #' . $user['admin_role_id'];
            $user['admin_permissions'] = [];
            return $user;
        }
        $custom = isset($user['admin_permissions']) ? $user['admin_permissions'] : null;
        if (is_string($custom)) {
            $custom = json_decode($custom, true);
        }
        if (!empty($custom) && is_array($custom)) {
            $user['admin_permissions'] = $custom;
            $user['admin_role_label'] = function_exists('__') ? __('admin.role_custom') : 'Custom';
            return $user;
        }
        $user['admin_role_label'] = function_exists('__') ? __('admin.role_full_access') : 'Full access';
        return $user;
    }
}

if (!function_exists('can_admin')) {
    function can_admin($slug) {
        static $resolved_perms = null;
        static $is_full_access = null;

        $CI =& get_instance();
        if (!$CI->session->userdata('administrator')) {
            return false;
        }
        $admin = $CI->session->userdata('administrator');
        if (empty($admin) || !is_array($admin)) {
            return false;
        }
        if (isset($admin['id']) && (int)$admin['id'] === 1) {
            return true;
        }

        if ($resolved_perms === null && $is_full_access === null) {
            $user_row = $CI->db->select('admin_role_id, admin_permissions')
                ->where('id', (int)$admin['id'])
                ->get('users')
                ->row_array();

            $current_role_id = (!empty($user_row) && !empty($user_row['admin_role_id']))
                ? (int)$user_row['admin_role_id']
                : null;

            if ($current_role_id) {
                $role = $CI->db->get_where('admin_roles', ['id' => $current_role_id])->row_array();
                if (!empty($role) && !empty($role['permissions'])) {
                    $perms = is_string($role['permissions']) ? json_decode($role['permissions'], true) : $role['permissions'];
                    $resolved_perms = is_array($perms) ? $perms : [];
                } else {
                    $resolved_perms = [];
                }
            } else {
                $custom = null;
                if (!empty($user_row['admin_permissions'])) {
                    $custom = is_string($user_row['admin_permissions'])
                        ? json_decode($user_row['admin_permissions'], true)
                        : $user_row['admin_permissions'];
                }
                if (!empty($custom) && is_array($custom)) {
                    $resolved_perms = $custom;
                } else {
                    $is_full_access = true;
                }
            }
            if ($is_full_access === null && $resolved_perms === null) {
                $resolved_perms = [];
            }
        }

        if ($is_full_access === true) {
            return true;
        }
        return is_array($resolved_perms) && in_array($slug, $resolved_perms, true);
    }
}

if (!function_exists('get_admin_role_label')) {
    function get_admin_role_label() {
        $CI =& get_instance();
        $admin = $CI->session->userdata('administrator');
        if (empty($admin) || !is_array($admin)) {
            return function_exists('__') ? __('admin.super_administrator') : 'Super Administrator';
        }
        if (isset($admin['id']) && (int)$admin['id'] === 1) {
            return function_exists('__') ? __('admin.super_administrator') : 'Super Administrator';
        }
        if (!empty($admin['id'])) {
            $CI->load->database();
            $row = $CI->db->select('u.admin_role_id, u.admin_permissions, r.name AS role_name')
                ->from('users u')
                ->join('admin_roles r', 'r.id = u.admin_role_id', 'left')
                ->where('u.id', (int)$admin['id'])
                ->get()->row_array();
            if ($row) {
                if (!empty($row['role_name'])) {
                    return $row['role_name'];
                }
                $perms = !empty($row['admin_permissions'])
                    ? (is_string($row['admin_permissions']) ? json_decode($row['admin_permissions'], true) : $row['admin_permissions'])
                    : [];
                if (!empty($perms)) {
                    return function_exists('__') ? __('admin.role_custom') : 'Custom';
                }
                return function_exists('__') ? __('admin.role_full_access') : 'Full access';
            }
        }
        if (!empty($admin['admin_role_id'])) {
            return 'Role #' . $admin['admin_role_id'];
        }
        if (!empty($admin['admin_permissions'])) {
            return function_exists('__') ? __('admin.role_custom') : 'Custom';
        }
        return function_exists('__') ? __('admin.role_full_access') : 'Full access';
    }
}

if (!function_exists('get_admin_role_badge_class')) {
    function get_admin_role_badge_class() {
        $label = get_admin_role_label();
        if ($label === (function_exists('__') ? __('admin.super_administrator') : 'Super Administrator')) {
            return 'bg-dark text-white';
        }
        if ($label === (function_exists('__') ? __('admin.role_full_access') : 'Full access')) {
            return 'bg-success text-white';
        }
        if ($label === (function_exists('__') ? __('admin.role_custom') : 'Custom')) {
            return 'bg-info text-white';
        }
        if (strpos($label, 'Finance') !== false) return 'bg-primary text-white';
        if (strpos($label, 'Support') !== false) return 'bg-warning text-dark';
        if (strpos($label, 'Marketing') !== false) return 'bg-danger text-white';
        return 'bg-secondary text-white';
    }
}

if (!function_exists('admin_api_has_permission')) {
    /**
     * Stateless permission check for Admin API (JWT user id), mirroring can_admin() without session.
     * User id 1 = full access. NULL role + no custom JSON perms = full access (same as web).
     *
     * @param int $admin_user_id users.id of the calling admin (type must be admin)
     * @param string $slug e.g. admin.admins
     */
    function admin_api_has_permission($admin_user_id, $slug) {
        $admin_user_id = (int)$admin_user_id;
        if ($admin_user_id === 1) {
            return true;
        }
        $slug = (string)$slug;
        if ($slug === '') {
            return false;
        }
        $CI =& get_instance();
        $CI->load->database();
        $user_row = $CI->db->select('admin_role_id, admin_permissions')
            ->where('id', $admin_user_id)
            ->where('type', 'admin')
            ->get('users')
            ->row_array();
        if (empty($user_row)) {
            return false;
        }
        $current_role_id = !empty($user_row['admin_role_id']) ? (int)$user_row['admin_role_id'] : null;
        if ($current_role_id) {
            $role = $CI->db->get_where('admin_roles', array('id' => $current_role_id))->row_array();
            if (!empty($role) && !empty($role['permissions'])) {
                $perms = is_string($role['permissions']) ? json_decode($role['permissions'], true) : $role['permissions'];
                $resolved_perms = is_array($perms) ? $perms : array();
            } else {
                $resolved_perms = array();
            }
            return in_array($slug, $resolved_perms, true);
        }
        $custom = null;
        if (!empty($user_row['admin_permissions'])) {
            $custom = is_string($user_row['admin_permissions'])
                ? json_decode($user_row['admin_permissions'], true)
                : $user_row['admin_permissions'];
        }
        if (!empty($custom) && is_array($custom)) {
            return in_array($slug, $custom, true);
        }
        return true;
    }
}

if (!function_exists('admin_api_all_defined_slugs')) {
    /**
     * All permission slugs defined in admin_permissions (keys of admin_permission_slugs) plus admin.admins.
     *
     * @return array<int, string>
     */
    function admin_api_all_defined_slugs() {
        $CI =& get_instance();
        $CI->config->load('admin_permissions', true);
        $slugs = $CI->config->item('admin_permission_slugs', 'admin_permissions');
        $keys = array();
        if (is_array($slugs)) {
            foreach (array_keys($slugs) as $k) {
                $keys[] = (string)$k;
            }
        }
        if (!in_array('admin.admins', $keys, true)) {
            $keys[] = 'admin.admins';
        }
        $keys = array_values(array_unique($keys));
        sort($keys);
        return $keys;
    }
}

if (!function_exists('admin_api_permission_payload_for_user')) {
    /**
     * Payload for Admin API profile / mobile: mirrors can_admin resolution (full access vs explicit slugs).
     *
     * @return array{admin_full_access: bool, admin_permission_slugs: array<int, string>, admin_role_name: ?string}
     */
    function admin_api_permission_payload_for_user($admin_user_id) {
        $admin_user_id = (int)$admin_user_id;
        $all = admin_api_all_defined_slugs();
        if ($admin_user_id === 1) {
            return array(
                'admin_full_access' => true,
                'admin_permission_slugs' => $all,
                'admin_role_name' => null,
            );
        }
        $CI =& get_instance();
        $CI->load->database();
        $user_row = $CI->db->select('admin_role_id, admin_permissions')
            ->where('id', $admin_user_id)
            ->where('type', 'admin')
            ->get('users')
            ->row_array();
        if (empty($user_row)) {
            return array(
                'admin_full_access' => false,
                'admin_permission_slugs' => array(),
                'admin_role_name' => null,
            );
        }
        $role_name = null;
        $current_role_id = !empty($user_row['admin_role_id']) ? (int)$user_row['admin_role_id'] : null;
        if ($current_role_id) {
            $role = $CI->db->get_where('admin_roles', array('id' => $current_role_id))->row_array();
            if (!empty($role['name'])) {
                $role_name = (string)$role['name'];
            }
            if (!empty($role) && !empty($role['permissions'])) {
                $perms = is_string($role['permissions']) ? json_decode($role['permissions'], true) : $role['permissions'];
                $resolved = is_array($perms) ? $perms : array();
                $out = array();
                foreach ($resolved as $p) {
                    $out[] = (string)$p;
                }
                $out = array_values(array_unique($out));
                sort($out);
                return array(
                    'admin_full_access' => false,
                    'admin_permission_slugs' => $out,
                    'admin_role_name' => $role_name,
                );
            }
            return array(
                'admin_full_access' => false,
                'admin_permission_slugs' => array(),
                'admin_role_name' => $role_name,
            );
        }
        $custom = null;
        if (!empty($user_row['admin_permissions'])) {
            $custom = is_string($user_row['admin_permissions'])
                ? json_decode($user_row['admin_permissions'], true)
                : $user_row['admin_permissions'];
        }
        if (!empty($custom) && is_array($custom)) {
            $out = array();
            foreach ($custom as $p) {
                $out[] = (string)$p;
            }
            $out = array_values(array_unique($out));
            sort($out);
            return array(
                'admin_full_access' => false,
                'admin_permission_slugs' => $out,
                'admin_role_name' => null,
            );
        }
        return array(
            'admin_full_access' => true,
            'admin_permission_slugs' => $all,
            'admin_role_name' => null,
        );
    }
}
