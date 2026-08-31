<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');

class Payment extends MY_Controller {
	function __construct() {
		parent::__construct();
	}
	
	public function call_payment_function($code, $function){
		
		$arg_list = func_get_args();
		$json = array();
		$filename = APPPATH."/withdrawal_payment/controllers/{$code}.php";
		if(is_file($filename)){
			require $filename;
			$obj = new $code($this);
			unset($arg_list[0],$arg_list[1]);
			$json = call_user_func_array(array($obj, $function), $arg_list);
		}

		echo json_encode($json);
	}

	private function load_response($json){
		echo json_encode($json);die;
	}

	public function installPayementGateway(){
		$json = []; // Initialize json array

		// Demo Mode
		if (ENVIRONMENT === 'demo') {
			echo json_encode([
				'status' => 'error',
				'message' => 'Disabled on demo mode'
			]);
			return;
		}
		// Demo Mode
		
		$tmp_path = APPPATH.'cache/tmp/';
		$foldername = 'payout';
		$upload_path = APPPATH.'cache/tmp/'.$foldername."/";

		if(!is_dir($tmp_path)){
			if (!mkdir($tmp_path, 0777)) {
				$json['warning'] = "Can't create folder";
				$this->load_response($json);
			}
		}

		if(!$_FILES['plugin'] || !isset($_FILES['plugin']['name']) || !isset($_FILES['plugin']['tmp_name'])){
			$json['warning'] = "Choose zip file to install";
			$this->load_response($json);
		}
		 
		if (strtolower(substr(strrchr($_FILES['plugin']['name'], '.'), 1)) != 'zip') {
			$json['warning'] = "Allow only zip file..";
			$this->load_response($json);
		}

		$zip = new ZipArchive();
		if ($zip->open($_FILES["plugin"]["tmp_name"])) {
			$zip->extractTo($upload_path);
			$zip->close();
		} else {
			$json['warning'] = "Can't extract zip file";
			$this->load_response($json);
		}

		if (is_dir($upload_path . 'upload/')) {
			$files = array();
			$path = $upload_path . 'upload/*';
			foreach ((array)glob($path) as $file) {
				if (is_dir($file)) {
					$files[] = $file;
				}
			}

			$allowed = array(
				'admin_settings',
				'views',
				'controllers',
				'logo',
				'user_settings',
				'confirm_view',
			);

			$safe = true;
			$destination = ''; 
			foreach ($files as $file) {
				$destination = str_replace('\\', '/', substr($file, strlen($upload_path . 'upload/'))); 
				if(!in_array($destination, $allowed)){
					$safe = false;
					break;
				}
			}

			if (!$safe) {
				$json['warning'] = 'This folder is not allowed '. $destination;
				$this->load_response($json);
			}


			$files = array();
			$path = array($upload_path . 'upload/*');
			while (count($path) != 0) {
				$next = array_shift($path);
				foreach ((array)glob($next) as $file) {
					if (is_dir($file)) {
						$path[] = $file . '/*';
					}
					$files[] = $file;
				}
			}
 
			// Auto-add translations for the installed gateway
			$translation_info = null;
			try {
				$this->load->helper('gateway_translation');
				$translation_helper = new Gateway_translation_helper();
				
				// Extract gateway code from the uploaded files before they're moved
				$upload_controller_path = $upload_path . 'upload/controllers/';
				$gateway_code = null;
				
				if (is_dir($upload_controller_path)) {
					$controller_files = glob($upload_controller_path . '*.php');
					foreach ($controller_files as $file) {
						$filename = basename($file, '.php');
						if ($filename !== 'index') {
							$gateway_code = $filename;
							break;
						}
					}
				}
				
				if ($gateway_code) {
					$translation_keys = $translation_helper->extract_translation_keys($gateway_code, $upload_path);
					if (!empty($translation_keys)) {
						$result = $translation_helper->add_gateway_translations($gateway_code, $translation_keys);
						$translation_info = [
							'gateway_code' => $gateway_code,
							'keys_count' => count($translation_keys),
							'result' => $result ? 'success' : 'failed'
						];
					}
				}
			} catch (Exception $e) {
				// Silent fail - translation is optional
			}

			foreach ($files as $key => $file) {
				$destination = str_replace('\\', '/', substr($file, strlen($upload_path . 'upload/')));
				 
				$source='logo/';
				$checklogo= str_contains($destination,$source);

				if($checklogo>=1){
					$destination=str_replace("logo/","",$destination);
					$path = FCPATH.'assets/withdrawal_payment/'. $destination;
				}
				else
				{
					$path = APPPATH . 'withdrawal_payment/'. $destination;
				} 

				if (is_dir($file) && !is_dir($path)) {
					mkdir($path, 0777);
				}

				if (is_file($file)) {
					rename($file, $path);
				}
			}
			
			deleteDir($upload_path);
			
			$json['location'] = 1;
			$json['success'] = true;
			$json['message'] = 'Module installed successfully';
			
			// Add translation info to response if available
			if ($translation_info) {
				$json['translation_info'] = $translation_info;
			}
			$this->session->set_flashdata('success', 'Module installed successfully');
		} else {
			$json['warning'] = 'No upload folder found in the ZIP file';
		}
		
		// Ensure we always have a response
		if (empty($json)) {
			$json['warning'] = 'Unknown error occurred during installation';
		}
		
		$this->load_response($json);
	}

	public function delete_plugin($code){

		// Demo Mode
		if (ENVIRONMENT === 'demo') {
			$this->session->set_flashdata('error', __('admin.demo_mode'));
			redirect('admincontrol/withdrawal_payment_gateways');
			return;
		}
		// Demo Mode

		$filename = APPPATH."/withdrawal_payment/controllers/{$code}.php";
		if(is_file($filename)){
			$files= [
				APPPATH."withdrawal_payment/admin_settings/{$code}.php",
				APPPATH."withdrawal_payment/views/{$code}.php",
				APPPATH."withdrawal_payment/controllers/{$code}.php",
				APPPATH."withdrawal_payment/user_settings/{$code}.php",
				FCPATH."assets/withdrawal_payment/{$code}.png"
			];

			foreach ($files as $key => $file) {
				if (is_file($file)) {
					unlink($file);
				}
			}
		}

		// Remove translations when deleting gateway
		$this->load->helper('gateway_translation');
		$translation_helper = new Gateway_translation_helper();
		$translation_helper->remove_gateway_translations($code);
		
		$this->session->set_flashdata('success', 'Module deleted successfully');
		$this->load->model('Setting_model');
		$this->Setting_model->clear('withdrawalpayment_'.$code);
		redirect('admincontrol/withdrawal_payment_gateways');
	}
}