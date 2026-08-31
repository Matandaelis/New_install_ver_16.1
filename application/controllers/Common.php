<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Common extends MY_Controller {
	function __construct() {
		parent::__construct();
		$this->load->model('user_model', 'user');
		$this->load->model('Product_model');
		___construct(1);
	}
	public function term_condition()
	{
		
		$language_id=1;
		
		$row = $this->db->query("SELECT * FROM language where 	is_default=1")->row_array();
		if(count($row)>0)
			$language_id=$row['id'];

		if (isset($this->session) && $this->session->userdata('userLang') !== FALSE)
			$language_id=$this->session->userdata('userLang');

		$data['store'] = $this->Product_model->getSettings('store');
		$data['page'] 	= $this->Product_model->getSettingsWithLanaguage('tnc',$language_id);
		$data['LanguageHtml'] = $this->Product_model->getLanguageHtml('AuthController');
 
		
		$this->load->view('term-condition', $data);
	}

	public function api_home()
	{
		$this->load->view('document/api_home');
	}

	public function api_document()
	{
		$this->load->model('PagebuilderModel');
		$register_form = $this->PagebuilderModel->getSettings('registration_builder');

		$data['registration_fields'] = json_decode($register_form['registration_builder'],1);
		$data['SiteSetting'] = $this->Product_model->getSiteSetting();

		$this->load->view('document/user/header');
		$this->load->view('document/user/sidebar');
		$this->load->view('document/user/index', $data);
		$this->load->view('document/user/footer', $data);
	}

	public function admin_api_document()
	{
		$data['SiteSetting'] = $this->Product_model->getSiteSetting();

		$this->load->view('document/admin/header');
		$this->load->view('document/admin/sidebar');
		$this->load->view('document/admin/index', $data);
		$this->load->view('document/admin/footer', $data);
	}

	/**
	 * Public JS error reporting endpoint — no admin auth required.
	 * Accepts errors from user dashboard, store, and login theme pages.
	 * Writes to the same js_errors.json log read by the admin debugger.
	 */
	public function js_error_log()
	{
		$this->output->set_content_type('application/json');
		if ($this->config->item('js_error_debug_enabled') === false) {
			echo json_encode(['ok' => false, 'disabled' => true]);
			return;
		}
		if ($this->input->server('REQUEST_METHOD') !== 'POST') { echo json_encode(['ok' => false]); return; }
		$msg  = trim((string)$this->input->post('msg'));
		$type = trim((string)$this->input->post('type'));
		$path = trim((string)$this->input->post('path'));
		if ($msg === '') { echo json_encode(['ok' => false]); return; }
		$file       = APPPATH . 'logs/js_errors.json';
		$normalPath = $path ?: '?';
		$normalType = $type ?: 'runtime';
		$now        = date('c');
		$log        = [];
		if (file_exists($file)) { $raw = @file_get_contents($file); if ($raw) $log = json_decode($raw, true) ?: []; }
		$found = false;
		foreach ($log as &$entry) {
			if (isset($entry['msg'], $entry['path']) && $entry['msg'] === $msg && $entry['path'] === $normalPath) {
				$entry['time']      = $now;
				$entry['last_seen'] = $now;
				$entry['type']      = $normalType;
				$entry['count']     = ($entry['count'] ?? 1) + 1;
				if (!isset($entry['first_seen'])) $entry['first_seen'] = $now;
				$found = true;
				break;
			}
		}
		unset($entry);
		if (!$found) {
			$log[] = [
				'time'       => $now,
				'first_seen' => $now,
				'last_seen'  => $now,
				'count'      => 1,
				'path'       => $normalPath,
				'type'       => $normalType,
				'msg'        => $msg,
			];
		}
		if (count($log) > 500) $log = array_slice($log, -500);
		@file_put_contents($file, json_encode($log));
		echo json_encode(['ok' => true]);
	}

	public function js_page_clean()
	{
		$this->output->set_content_type('application/json');
		if ($this->config->item('js_error_debug_enabled') === false) {
			echo json_encode(['ok' => false, 'disabled' => true]);
			return;
		}
		if ($this->input->server('REQUEST_METHOD') !== 'POST') { echo json_encode(['ok' => false]); return; }
		$path = trim((string)$this->input->post('path'));
		if ($path === '') { echo json_encode(['ok' => false]); return; }
		$file = APPPATH . 'logs/js_errors.json';
		$log  = [];
		if (file_exists($file)) { $raw = @file_get_contents($file); if ($raw) $log = json_decode($raw, true) ?: []; }
		$before = count($log);
		$log = array_values(array_filter($log, function($e) use ($path) {
			return !(isset($e['path']) && $e['path'] === $path);
		}));
		@file_put_contents($file, json_encode($log));
		echo json_encode(['ok' => true, 'removed' => $before - count($log)]);
	}

}