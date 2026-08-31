<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Setting extends MY_Controller {
	function __construct() {
		parent::__construct();
		$this->load->model('Product_model');
		$this->load->library('user_agent');
		___construct(1);
	}

	public function getModal(){
		$data = array();
		$input = $this->input->post(null,true);
		$key = $input['key'];
		$type = $input['type'];

		$data['skey'] = $type;
		$data['setting_key'] = $key;
		
		

		$data['db_value'] = $this->Product_model->getSettings($key);
		$data['html'] = $this->load->view("common/setting_model",$data,true);

		echo json_encode($data);die;
	}

	public function saveSetting(){
		$input = $this->input->post(null,true);
		$this->Setting_model->save($input['setting_key'], $input['settings']);

		$data['success'] = 1;
		echo json_encode($data);die;
	}
}