<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Admin-only: load a sample integration campaign (Marketing → Integration tools).
 *
 * Isolated from IntegrationModel so campaign CRUD stays in the model and this
 * onboarding/demo flow stays in one obvious place.
 */
class Integration_demo_seeder {

	/** Fixed name so we do not create duplicate demo campaigns. */
	const DEMO_CAMPAIGN_NAME = 'Demo: Sample link campaign';

	/** Canonical admin program used for every “Load demo” run (created or repaired automatically). */
	const DEMO_PROGRAM_NAME = 'Demo: Default program (auto-created)';

	/** Click commission: pay this amount per this many clicks (1 = “$X per 1 click”, not per 10). */
	const DEMO_CLICK_COMMISSION_AMOUNT = 1.0;
	const DEMO_CLICKS_PER_COMMISSION  = 1;

	/** @var CI_Controller */
	protected $CI;

	public function __construct()
	{
		$this->CI =& get_instance();
		$this->CI->load->model('IntegrationModel');
		$this->CI->load->model('Product_model');
		$this->CI->load->helper('url');
	}

	/**
	 * Create one admin (vendor_id 0) demo campaign: program tool + general_integration + link ad.
	 *
	 * @param string $target_base_url Optional; defaults to site front URL.
	 * @return array{success:bool,already?:bool,id?:int,error?:string,program_created?:bool,program_name?:string}
	 */
	public function seed_admin_demo_campaign($target_base_url = '')
	{
		$ensured = $this->ensure_demo_integration_program();
		if (empty($ensured['program']) || empty($ensured['program']['id'])) {
			return ['success' => false, 'error' => 'no_program'];
		}

		$program = $ensured['program'];
		$program_id = (int) $program['id'];
		$program_created = !empty($ensured['created']);

		// Same as admin form POST: default plugin icon must exist under product/upload/thumb for list thumbnails.
		$demo_thumb = $this->ensure_demo_featured_thumb_file();
		// Terms Settings (integration_tools.terms) — sample HTML from admin language (see admin.demo_campaign_terms_html).
		$demo_terms = $this->get_demo_terms_html();

		// Demo campaign already there: still auto-fix program commissions + link + thumb + terms (one UPDATE)
		if ($this->campaign_already_exists()) {
			$this->sync_demo_campaign_row($program_id, $demo_thumb, $demo_terms);
			return [
				'success'          => true,
				'already'          => true,
				'id'               => $this->existing_demo_campaign_id(),
				'program_refreshed'=> true,
				'program_created'  => $program_created,
				'program_name'     => isset($program['name']) ? (string) $program['name'] : self::DEMO_PROGRAM_NAME,
			];
		}

		$target = trim((string) $target_base_url);
		if ($target === '') {
			$target = rtrim(site_url(), '/') . '/';
		}

		$catRow = $this->CI->db->query('SELECT id FROM integration_category ORDER BY id ASC LIMIT 1')->row();
		$category = $catRow ? [(string) $catRow->id] : [];

		$comm = $this->build_commission_payload_for_tool();

		$data = array_merge([
			'name'                    => self::DEMO_CAMPAIGN_NAME,
			'program_id'              => (int) $program['id'],
			'target_link'             => $target,
			'type'                    => 'link_ads',
			'tool_type'               => 'program',
			'action_code'             => '',
			'general_code'            => '',
			'terms'                   => $demo_terms,
			'featured_image'          => ($demo_thumb !== '') ? $demo_thumb : '',
			'allow_for_radio'         => '1',
			'allow_for'               => ['0'],
			'allow_groups'            => [],
			'marketpostback'          => json_encode(['status' => '']),
			'category'                => $category,
			'recursion'               => '',
			'recursion_endtime_status'=> '',
			'recursion_endtime'       => '',
			'start_date'              => null,
			'end_date'                => null,
			'country_sortname'        => [],
			'country_name'            => [],
			'cookies_type'            => 0,
			'custom_cookies'          => 0,
			'program_tool_id'         => 0,
			'tool_integration_plugin' => 'general_integration',
			'action_click'            => 0,
			'action_amount'           => 0,
			'general_click'           => 0,
			'general_amount'          => 0,
			'admin_action_click'      => 0,
			'admin_action_amount'     => 0,
			'admin_general_click'     => 0,
			'admin_general_amount'    => 0,
			'status'                  => 1,
			'link_title'              => 'Shop now (demo)',
			'recursion_custom_time'   => 0,
			'integration_method'      => 'js_pixel',
			's2s_enabled'             => 0,
			's2s_direct_mode'         => 0,
			'deafult_featured_image'  => 'order.jpg',
		], $comm);

		$newId = $this->CI->IntegrationModel->editProgramTools($data, [], 'admin', 0);
		if ($newId) {
			return [
				'success'         => true,
				'already'         => false,
				'id'              => (int) $newId,
				'program_created' => $program_created,
				'program_name'    => isset($program['name']) ? (string) $program['name'] : self::DEMO_PROGRAM_NAME,
			];
		}

		return ['success' => false, 'error' => 'save_failed'];
	}

	/**
	 * Refresh the named demo campaign row after “Load demo”: program_id always; featured_image / terms when non-empty.
	 * (Replaces separate sync methods — same SQL effect, single UPDATE.)
	 */
	protected function sync_demo_campaign_row($program_id, $thumb_filename = '', $terms_html = '')
	{
		$update = [
			'program_id' => (int) $program_id,
		];

		$fn = trim((string) $thumb_filename);
		if ($fn !== '') {
			$update['featured_image'] = $fn;
		}

		$terms = (string) $terms_html;
		if ($terms !== '') {
			$update['terms'] = $terms;
		}

		$this->CI->db->where('name', self::DEMO_CAMPAIGN_NAME);
		$this->CI->db->where('(vendor_id IS NULL OR vendor_id = 0)', null, false);
		$this->CI->db->update('integration_tools', $update);
	}

	/**
	 * Sample terms HTML for the “Terms Settings” card (matches integration_tools_form name="terms").
	 */
	protected function get_demo_terms_html()
	{
		if (function_exists('__')) {
			$t = __('admin.demo_campaign_terms_html');
			if (is_string($t) && $t !== '' && $t !== 'admin.demo_campaign_terms_html') {
				return $t;
			}
		}

		return '<p><strong>Sample terms (demo only).</strong> Replace this with your real terms and conditions before going live.</p><ul><li>Affiliates must comply with applicable laws and your brand guidelines.</li><li>Commissions follow the program and campaign settings shown in the admin.</li><li>Invalid or fraudulent traffic may be voided.</li><li>This text is for demonstration only and is not legal advice.</li></ul>';
	}

	/**
	 * Admin form copies plugins_icons → product/upload/thumb when saving; programmatic seed must do the same.
	 * Uses general_integration default icon (order.jpg) — same as Integration controller integration_tools save.
	 *
	 * @return string Filename for integration_tools.featured_image, or '' if source missing
	 */
	protected function ensure_demo_featured_thumb_file()
	{
		$filename = 'order.jpg';
		$src      = FCPATH . 'assets/images/plugins_icons/' . $filename;
		$destDir  = FCPATH . 'assets/images/product/upload/thumb/';
		$dest     = $destDir . $filename;

		if (! is_file($src)) {
			log_message('error', 'Integration_demo_seeder: missing default icon at ' . $src);
			return '';
		}

		if (! is_dir($destDir)) {
			@mkdir($destDir, 0755, true);
		}

		if (! is_file($dest) || (int) @filesize($dest) === 0) {
			@copy($src, $dest);
		}

		return is_file($dest) && (int) @filesize($dest) > 0 ? $filename : '';
	}

	// -------------------------------------------------------------------------
	// Internals
	// -------------------------------------------------------------------------

	protected function campaign_already_exists()
	{
		$n = $this->CI->db->query(
			'SELECT id FROM integration_tools WHERE name = ? AND (vendor_id IS NULL OR vendor_id = 0) LIMIT 1',
			[self::DEMO_CAMPAIGN_NAME]
		)->num_rows();

		return $n > 0;
	}

	protected function existing_demo_campaign_id()
	{
		$row = $this->CI->db->query(
			'SELECT id FROM integration_tools WHERE name = ? AND (vendor_id IS NULL OR vendor_id = 0) LIMIT 1',
			[self::DEMO_CAMPAIGN_NAME]
		)->row_array();

		return $row ? (int) $row['id'] : 0;
	}

	/**
	 * Commission / status columns applied to the demo program on every “Load demo” (insert or UPDATE).
	 *
	 * @return array<string,mixed>
	 */
	protected function get_demo_program_commission_defaults()
	{
		$clickAmt = (float) self::DEMO_CLICK_COMMISSION_AMOUNT;
		$clickN   = (int) self::DEMO_CLICKS_PER_COMMISSION;

		return [
			'commission_type'                   => 'percentage',
			'commission_sale'                   => 10,
			'sale_status'                       => 1,
			'commission_number_of_click'        => $clickN,
			'commission_click_commission'       => $clickAmt,
			'click_status'                      => 1,
			'admin_commission_type'             => 'percentage',
			'admin_commission_sale'             => 10,
			'admin_commission_number_of_click'  => $clickN,
			'admin_commission_click_commission' => $clickAmt,
			'admin_click_status'                => 1,
			'admin_sale_status'                 => 1,
			'click_allow'                       => 'first_click',
			'status'                            => 1,
		];
	}

	/**
	 * Always return the canonical demo program: find by name, or insert; then APPLY defaults (UPDATE)
	 * so old rows with 0 / Disable are repaired every time admin clicks “Load demo”.
	 *
	 * @return array{program:array|null,created:bool}
	 */
	protected function ensure_demo_integration_program()
	{
		$db = $this->CI->db;

		$row = $db->query(
			'SELECT * FROM integration_programs WHERE name = ? AND (vendor_id IS NULL OR vendor_id = 0) ORDER BY id ASC LIMIT 1',
			[self::DEMO_PROGRAM_NAME]
		)->row_array();

		$created = false;
		if (!$row) {
			if (!$this->insert_minimal_admin_program()) {
				return ['program' => null, 'created' => false];
			}
			$id = (int) $db->insert_id();
			$row = $db->get_where('integration_programs', ['id' => $id])->row_array();
			$created = true;
		}

		if (!empty($row['id'])) {
			$this->normalize_demo_program_commission_fields((int) $row['id']);
			$row = $db->get_where('integration_programs', ['id' => (int) $row['id']])->row_array();
		}

		return ['program' => $row ?: null, 'created' => $created];
	}

	/**
	 * Force demo-friendly sale/click settings on the demo program row.
	 */
	protected function normalize_demo_program_commission_fields($program_id)
	{
		$this->CI->db->where('id', (int) $program_id)->update(
			'integration_programs',
			$this->get_demo_program_commission_defaults()
		);
	}

	/**
	 * @return bool insert ok
	 */
	protected function insert_minimal_admin_program()
	{
		$now = date('Y-m-d H:i:s');

		// Fixed demo numbers — never copy market_vendor (often all zeros / disabled).
		$row = array_merge(
			[
				'vendor_id'   => null,
				'name'        => self::DEMO_PROGRAM_NAME,
				'created_at'  => $now,
			],
			$this->get_demo_program_commission_defaults()
		);

		return (bool) $this->CI->db->insert('integration_programs', $row);
	}

	/**
	 * @return array Keys for IntegrationModel::editProgramTools (commission_type / commission or referlevel_*)
	 */
	protected function build_commission_payload_for_tool()
	{
		$row = $this->CI->db->query(
			"SELECT commission_type, commission FROM integration_tools WHERE commission IS NOT NULL AND commission != '' ORDER BY id DESC LIMIT 1"
		)->row_array();

		if ($row && !empty($row['commission'])) {
			$ctype = isset($row['commission_type']) ? trim((string) $row['commission_type']) : '';
			if ($ctype === '') {
				$ctype = 'custom';
			}
			if ($ctype === 'custom') {
				$dec = json_decode($row['commission'], true);
				if (!is_array($dec)) {
					$dec = [];
				}
				$out = [
					'commission_type' => 'custom',
					'referlevel'      => (isset($dec['referlevel']) && is_array($dec['referlevel'])) ? $dec['referlevel'] : [],
				];
				$setting = $this->CI->Product_model->getSettings('referlevel');
				$max_level = isset($setting['levels']) ? (int) $setting['levels'] : 3;
				for ($i = 1; $i <= $max_level; $i++) {
					$key = 'referlevel_' . $i;
					if (isset($dec[$key]) && is_array($dec[$key])) {
						$out[$key] = $dec[$key];
					} else {
						$lvl = $this->CI->Product_model->getSettings($key);
						$out[$key] = is_array($lvl) ? $lvl : [];
					}
				}

				return $out;
			}

			return [
				'commission_type' => ($ctype !== '') ? $ctype : 'percentage',
				'commission'      => $row['commission'],
			];
		}

		$out = [
			'commission_type' => 'custom',
			'referlevel'      => $this->CI->Product_model->getSettings('referlevel'),
		];
		if (!is_array($out['referlevel'])) {
			$out['referlevel'] = [];
		}
		$setting = $this->CI->Product_model->getSettings('referlevel');
		$max_level = isset($setting['levels']) ? (int) $setting['levels'] : 3;
		for ($i = 1; $i <= $max_level; $i++) {
			$key = 'referlevel_' . $i;
			$lvl = $this->CI->Product_model->getSettings($key);
			$out[$key] = is_array($lvl) ? $lvl : [];
		}

		return $out;
	}
}
