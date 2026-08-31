<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Public login-page statistics (no HTML). Used by affiliate login themes when enabled in admin.
 */
class Login_stats_model extends CI_Model {

	public function __construct() {
		parent::__construct();
	}

	/**
	 * Active affiliates: type user, approved, non-vendor (same definition as Admin_model::get_total_affiliates).
	 */
	public function count_active_affiliates(): int {
		$row = $this->db->query(
			"SELECT COUNT(*) AS c FROM users WHERE type = 'user' AND status = '1' AND (is_vendor = '0' OR is_vendor IS NULL)"
		)->row();
		return (int) ($row ? $row->c : 0);
	}

	/**
	 * Sum of completed withdrawal requests (wallet_requests.status = 1 = Complete per Wallet_model::$request_status).
	 */
	public function sum_completed_withdrawals_total(): float {
		$this->db->select_sum('total', 'amt');
		$this->db->where('status', '1');
		$row = $this->db->get('wallet_requests')->row();
		$v = $row && isset($row->amt) ? $row->amt : 0;
		return round((float) $v, 2);
	}

	/**
	 * Bundle for login live stats widget (values + optional custom labels from block_stats_settings).
	 */
	public function get_public_stats(): array {
		$this->load->model('Product_model');
		$this->load->helper('login_page_blocks');
		$login = $this->Product_model->getSettings('login');
		$cfg = login_page_stats_settings_parse($login['block_stats_settings'] ?? null);
		if (!empty($cfg['demo_values'])) {
			return [
				'active_affiliates' => 2488,
				'paid_withdrawals_total' => 186420.75,
				'label_active_affiliates' => $cfg['active_label'],
				'label_paid_withdrawals' => $cfg['withdrawals_label'],
			];
		}
		return [
			'active_affiliates' => $this->count_active_affiliates(),
			'paid_withdrawals_total' => $this->sum_completed_withdrawals_total(),
			'label_active_affiliates' => $cfg['active_label'],
			'label_paid_withdrawals' => $cfg['withdrawals_label'],
		];
	}

	/**
	 * Top affiliates by total wallet commission (Product_model::getPopulerUsers).
	 * Only active non-vendor partners: type = user (in getPopulerUsers SQL), status = 1, is_vendor = 0 or NULL
	 * (same spirit as count_active_affiliates). Wallet side unchanged: status > 0, type NOT vendor_sale_commission.
	 * Limit and privacy come from login setting block_top_earners_settings (JSON).
	 *
	 * @return list<array<string,mixed>>
	 */
	public function get_top_earners(): array {
		$this->load->model('Product_model');
		$this->load->helper('login_page_blocks');
		$login = $this->Product_model->getSettings('login');
		$raw = $login['block_top_earners_settings'] ?? null;
		$cfg = login_page_top_earners_settings_parse($raw);
		$limit = $cfg['display_limit'];
		$privacy = (int) $cfg['privacy_mode'] === 1;

		if (!empty($cfg['demo_rows'])) {
			$seed = login_page_top_earners_demo_seed_rows();
			$seed = array_slice($seed, 0, max(1, min(10, (int) $limit)));
			$rows = $seed;
		} else {
			$rows = $this->Product_model->getPopulerUsers([
				'limit' => $limit,
				'affiliate_partners_only' => true,
			]);
		}
		if (!is_array($rows)) {
			return [];
		}

		foreach ($rows as &$r) {
			$fn = isset($r['firstname']) ? (string) $r['firstname'] : '';
			$ln = isset($r['lastname']) ? (string) $r['lastname'] : '';
			$r['display_name'] = $this->format_top_earner_display_name($fn, $ln, $privacy);
		}
		unset($r);

		return $rows;
	}

	/**
	 * @param string $firstname Plain first name from users table
	 * @param string $lastname  Plain last name from users table
	 */
	private function format_top_earner_display_name(string $firstname, string $lastname, bool $privacy_mode): string {
		$fn = trim($firstname);
		$ln = trim($lastname);
		if ($fn === '' && $ln === '') {
			return '';
		}
		if (!$privacy_mode) {
			return trim($fn . ' ' . $ln);
		}
		if ($ln === '') {
			return $fn;
		}
		$initial = function_exists('mb_substr') ? mb_substr($ln, 0, 1, 'UTF-8') : substr($ln, 0, 1);
		if ($initial === '') {
			return $fn;
		}
		if ($fn === '') {
			return strtoupper($initial) . '.';
		}
		return trim($fn . ' ' . strtoupper($initial) . '.');
	}

	/**
	 * Masked display name for live activity toasts (always privacy-style).
	 */
	private function pulse_mask_display_name(string $firstname, string $lastname): string {
		$fn = trim($firstname);
		$ln = trim($lastname);
		if ($fn === '' && $ln === '') {
			return 'A partner';
		}
		if ($ln === '') {
			return $fn;
		}
		$initial = function_exists('mb_substr') ? mb_substr($ln, 0, 1, 'UTF-8') : substr($ln, 0, 1);
		$initial = $initial !== '' ? strtoupper($initial) . '.' : '';
		if ($fn === '') {
			return $initial !== '' ? 'Member ' . $initial : 'A partner';
		}
		return trim($fn . ' ' . $initial);
	}

	/**
	 * Recent affiliate registrations + paid commission lines for login-page social proof (English copy).
	 *
	 * @return list<array{type: string, text: string}>
	 */
	public function get_recent_activity(int $limit = 10): array {
		$limit = max(4, min(20, $limit));
		$CI = &get_instance();
		$CI->load->library('currency');

		$this->db->select('users.firstname, users.lastname, countries.name AS country_name', false);
		$this->db->from('users');
		$this->db->join('countries', 'countries.id = users.Country', 'left');
		$this->db->where('users.type', 'user');
		$this->db->where('users.status', 1);
		$this->db->where('(users.is_vendor = 0 OR users.is_vendor IS NULL)', null, false);
		$this->db->where('users.created_at >', date('Y-m-d H:i:s', time() - 86400 * 365));
		$this->db->order_by('users.created_at', 'DESC');
		$this->db->limit(10);
		$regs = $this->db->get()->result_array();

		$types = ['click_commission', 'sale_commission', 'affiliate_click_commission', 'form_click_commission', 'external_click_commission'];
		$this->db->select('wallet.amount, users.firstname, users.lastname, countries.name AS country_name', false);
		$this->db->from('wallet');
		$this->db->join('users', 'users.id = wallet.user_id', 'inner');
		$this->db->join('countries', 'countries.id = users.Country', 'left');
		$this->db->where('wallet.status', 3);
		$this->db->where_in('wallet.type', $types);
		$this->db->where('users.type', 'user');
		$this->db->where('users.status', 1);
		$this->db->where('(users.is_vendor = 0 OR users.is_vendor IS NULL)', null, false);
		$this->db->order_by('wallet.id', 'DESC');
		$this->db->limit(18);
		$wrows = $this->db->get()->result_array();

		$lines = [];
		foreach ($regs as $r) {
			$name = $this->pulse_mask_display_name((string) ($r['firstname'] ?? ''), (string) ($r['lastname'] ?? ''));
			$c = trim((string) ($r['country_name'] ?? ''));
			$loc = $c !== '' ? $c : 'your area';
			$lines[] = ['type' => 'registration', 'text' => $name . ' from ' . $loc . ' just joined'];
		}
		foreach ($wrows as $w) {
			$name = $this->pulse_mask_display_name((string) ($w['firstname'] ?? ''), (string) ($w['lastname'] ?? ''));
			$c = trim((string) ($w['country_name'] ?? ''));
			$loc = $c !== '' ? $c : 'your area';
			$amt = isset($w['amount']) ? (float) $w['amount'] : 0.0;
			if ($amt <= 0) {
				continue;
			}
			$lines[] = ['type' => 'commission', 'text' => $name . ' from ' . $loc . ' just earned ' . $CI->currency->c_format($amt)];
		}

		if ($lines === []) {
			return [];
		}
		shuffle($lines);
		return array_slice($lines, 0, $limit);
	}
}
