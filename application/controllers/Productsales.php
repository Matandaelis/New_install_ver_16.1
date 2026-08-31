<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

use App\User;

require APPPATH . 'hooks/Affiliate_Hook.php';

class Productsales extends MY_Controller {

	protected $userdetails;

	function __construct() {
		parent::__construct();

		$this->userdetails = $this->session->userdata('administrator');
		___construct(1);

		$this->checkSessionTimeout();
		
		// Load utility helper for spam protection
		$this->load->helper('utility');
	}

	public function index($only_review = false){

		$data['user'] = $userdetails = $this->userdetails;

		if(empty($userdetails)) redirect($this->admin_domain_url);

		set_default_language();

		$this->load->model('Form_model');
		$this->load->model('Product_model');
		$this->load->model('Wallet_model');


		$data['store_setting'] =	$this->Product_model->getSettings('store');
		
		$data['Product_model'] =	$this->Product_model;

		$data['totals'] = $this->Wallet_model->getTotals(array(), true);

		$filter = array();

		$get = $this->input->get(null,true);

		$filter['is_campaign_product'] = 1;
		
		$filter['product_status_in'] =	 '1';


		if(isset($get['seller_id']) && $get['seller_id']){

			$filter['seller_id'] = (int)$this->input->get('seller_id');

		}

		if($only_review == 'reviews'){

			$filter['product_status_in'] =	 '0,2,3';

		}

		$data['productlist'] = $this->Product_model->getAllProduct($userdetails['id'], $userdetails['type'], $filter);
 

		$data['client_count'] =$this->db->query('SELECT count(*) as total FROM users WHERE  type like "client"')->row()->total;

		$data['ordercount'] =$this->db->query('SELECT COUNT(op.id) AS total FROM `order_products` op LEFT JOIN `order` AS o ON o.id = op.order_id WHERE o.status > 0 ')->row()->total;

		$data['categories'] = $this->db->query("SELECT id,name FROM categories")->result_array();

		$data['vendors'] = $this->db->query("SELECT users.id,CONCAT(users.firstname,' ',users.lastname) as name FROM `product_affiliate` LEFT JOIN users ON users.id= user_id GROUP by user_id")->result_array();

		$this->load->library("socialshare");				

		$data['social_share_modal'] =  $this->socialshare->get_dynamic_social_share_btns();
		$data['currentTheme'] = User::getActiveTheme();
		$data['StoreStatus'] = User::getStoreStatus();

		if($only_review == 'reviews'){

			$this->view($data,'product_campaign/reviews');

		} else {
			
			$this->view($data,'product_campaign/index');

		}
	}

	public function listproduct_ajax($page = 1){
		

		$userdetails = $this->userdetails;

		if(empty($userdetails)) redirect($this->admin_domain_url);

		$get = $this->input->get(null,true);

		$post = $this->input->post(null,true);



		$filter = array(

			'page' => isset($get['page']) ? $get['page'] : $page,

			'limit' => 25,

		);

		$filter['is_campaign_product'] = 1;


		if(isset($post['seller_id']) && $post['seller_id']){

			$filter['seller_id'] = (int)$this->input->post('seller_id');

		}


		$filter['product_status_in'] =	 '1';

		if($only_review == 'reviews'){

			$filter['product_status_in'] =	 '0,2,3';

		}

		$data['default_commition'] =$this->Product_model->getSettings('productsetting');

		$record = $this->Product_model->getAllProduct($userdetails['id'], $userdetails['type'],$filter);
		
		$data['productlist'] = $record['data'];

		$data['pro_setting'] = $this->Product_model->getSettings('productsetting');

		$data['vendor_setting'] = $this->Product_model->getSettings('vendor');

		$json['view'] = $this->load->view("admincontrol/product_campaign/list", $data, true);

		$this->load->library('pagination');

		$this->pagination->cur_page = $filter['page'];

		$config['base_url'] = base_url('Productsales/listproduct_ajax');

		$config['per_page'] = $filter['limit'];

		$config['total_rows'] = $record['total'];

		$config['use_page_numbers'] = TRUE;

		$config['page_query_string'] = TRUE;

		$config['enable_query_strings'] = TRUE;

		$_GET['page'] = $filter['page'];

		$config['query_string_segment'] = 'page';
		
		$this->pagination->initialize($config);

		$json['pagination'] = $this->pagination->create_links();

		echo json_encode($json);
	}

	public function create(){
		if(!$this->userdetails){ redirect('admincontrol/dashboard', 'refresh'); }

		$this->load->model('Product_model');

		$data['checkout_template'] = get_available_checkout_template();

		$data['userdetails'] 	= $this->userdetails;
		
		$data['setting'] 	= $this->Product_model->getSettings('productsetting');

		$data['country_list'] = $this->db->query("SELECT name,id FROM countries")->result();

		$data['product'] = $this->Product_model->getProductById($id);

		$data['s3_setting'] = $this->Product_model->getSettings('s3_storage');

		$award_level = $this->Product_model->getSettings('award_level', 'status');
		$data['award_level_status'] = !empty($award_level['status']);
		$data['award_levels_list'] = $data['award_level_status'] ? $this->Product_model->getAll('award_level', false, 0, 'minimum_earning ASC') : [];

		$this->view($data, 'product_campaign/form');
	}

	public function update($id = null){

		$userdetails = $this->userdetails;

		if(empty($userdetails)) redirect($this->admin_domain_url);

		$this->load->model('Product_model');

		$product = $this->Product_model->getProductById($id);

		$product = json_decode(json_encode($product), true);

		$data['product'] = $this->Product_model->productDataWithMeta($product);

		$data['s3_setting'] = $this->Product_model->getSettings('s3_storage');

		if($data['product']){

			$data['seller'] = $this->db->query("SELECT * FROM product_affiliate WHERE product_id=". (int)$product['product_id'] ." ")->row();

			$data['seller_setting'] = $this->db->query("SELECT * FROM vendor_setting WHERE user_id=". (int)$data['seller']->user_id ." ")->row();

			$data['product_state'] = $this->db->query("SELECT * FROM states WHERE id=". (int)$data['product']['state_id'] )->row();

			$data['states'] = $this->db->query("SELECT * FROM states WHERE country_id=". (int)$data['product_state']->country_id )->result();

			$data['categories'] =$this->Product_model->getProductCategory($product['product_id']);

		}

		$data['checkout_template'] = get_available_checkout_template();

		$data['setting'] = $this->Product_model->getSettings('productsetting');

		$data['vendor_setting'] = $this->Product_model->getSettings('vendor');

		$data['country_list'] = $this->db->query("SELECT name,id FROM countries")->result();

		$award_level = $this->Product_model->getSettings('award_level', 'status');
		$data['award_level_status'] = !empty($award_level['status']);
		$data['award_levels_list'] = $data['award_level_status'] ? $this->Product_model->getAll('award_level', false, 0, 'minimum_earning ASC') : [];

		$this->view($data, 'product_campaign/form');
	}

	public function store(){


		$userdetails = $this->userdetails;

		$post = $this->input->post(null,true);

		if(!empty($post)){

			$product_id = (int)$this->input->post('product_id',true);

			$this->load->helper(array('form', 'url'));

			$this->load->library('form_validation');

			$this->form_validation->set_rules('product_url', __('admin.product_purchase_url'), 'required');

			$this->form_validation->set_rules('product_name', __('admin.product_name_'), 'required');

			$this->form_validation->set_rules('category[]', "Category", "required");

			$this->form_validation->set_rules('product_description', __('admin.product_description'), 'required' );

			$this->form_validation->set_rules('product_price', 'Product Price', 'required');

			$this->form_validation->set_rules('product_sku', 'Product SKU', 'required');

			
			if((isset($post['product_sale_period']) && !empty($post['product_sale_period'])) || (isset($post['product_sale_period_price']) && !empty($post['product_sale_period_price']))) {
				$this->form_validation->set_rules('product_sale_period', 'Sale Durataion', 'required');
				$this->form_validation->set_rules('product_sale_price', 'Sale Duration Price', 'required|numeric|greater_than[0]');
			}


			if($post['allow_country'] == "on"){

				$this->form_validation->set_rules('state_id', 'State', 'required' );

			}

			if( $post['product_recursion_type'] == 'custom' ){

				$this->form_validation->set_rules('product_recursion', 'Product Recursion', 'required');

				if( $post['product_recursion'] == 'custom_time' ){

					$this->form_validation->set_rules('recursion_custom_time', 'Custom Time', 'required|greater_than[0]');

				}

			}

			$product_recursion = ($post['product_recursion_type'] && $post['product_recursion_type'] != 'default') ? $post['product_recursion'] : "";

			$recursion_custom_time = ($product_recursion == 'custom_time' ) ? $post['recursion_custom_time'] : 0;


			if($this->form_validation->run()){

			$post = $this->input->post(null,true);			

			$errors = array();
			
			// Validate product-type-specific content for new products
			if($product_id == 0 && isset($post['product_type'])) {
				if($post['product_type'] == 'downloadable') {
					// Will check after file upload
				}
			}


			$downloadable_files = array();
			if($product_id){
					$product_details = $this->Product_model->getProductById($product_id);
					$_downloads = $this->Product_model->parseDownloads($product_details->downloadable_files,$product_details->product_type);
					foreach($_downloads as $key=> $value) {
						$isKeep =false;
						foreach($post['keep_files'] as $innerKey =>$innerValue) {
							if($innerValue == $key) {
								$downloadable_files[] = $_downloads[$key];
								unset($post['keep_files'][$innerKey]);
								$isKeep=true;
							}
							
						}
						if(!$isKeep){
							@unlink(APPPATH.'/downloads/'.$key);
						}
					}
				}

			$details = array(
				'is_campaign_product'		   => 1,

				'product_url'				   => isset($post['product_url']) ? $post['product_url'] : '',

				'product_name'                 =>  isset($post['product_name']) ? $post['product_name'] : '',

				'product_msrp'                 =>  isset($post['product_msrp']) ? (float)$post['product_msrp'] : 0,

				'product_description'          =>  isset($post['product_description']) ? $post['product_description'] : '',

				'product_price'                =>  isset($post['product_price']) ? (float)$post['product_price'] : 0,

				'product_sku'                  =>  isset($post['product_sku']) ? $post['product_sku'] : '',

				'product_type'                 =>  isset($post['product_type']) ? $post['product_type'] : 'virtual',

				'product_commision_type'       =>  isset($post['product_commision_type']) ? $post['product_commision_type'] : 'default',

				'state_id'                     =>  isset($post['allow_country']) && $post['allow_country'] == "on" ? (int)$post['state_id'] : 0,

				'product_commision_value'      =>  isset($post['product_commision_value']) ? (float)$post['product_commision_value'] : 0,

				'product_click_commision_type' =>  isset($post['product_click_commision_type']) ? $post['product_click_commision_type'] : 'default',

				'product_click_commision_ppc'  =>  isset($post['product_click_commision_ppc']) ? (float)$post['product_click_commision_ppc'] : 0,

				'product_click_commision_per'  =>  isset($post['product_click_commision_per']) ? (float)$post['product_click_commision_per'] : 0,

				'on_store'                     =>  isset($post['on_store']) ? (int)$post['on_store'] : 0,

				'allow_shipping'               =>  isset($post['allow_shipping']) ? (int)$post['allow_shipping'] : 1,

				'allow_upload_file'            =>  isset($post['allow_upload_file']) ? (int)$post['allow_upload_file'] : 0,

				'allow_comment'                =>  isset($post['allow_comment']) ? (int)$post['allow_comment'] : 0,

				'product_status'               =>  isset($post['product_status']) ? (int)$post['product_status'] : 1,

				'product_ipaddress'            =>  $_SERVER['REMOTE_ADDR'],

				'product_recursion_type'       =>  isset($post['product_recursion_type']) ? $post['product_recursion_type'] : 'default',

				'recursion_endtime'       =>  (isset($post['recursion_endtime_status']) && isset($post['recursion_endtime']) && $post['recursion_endtime']) ? date("Y-m-d H:i:s",strtotime($post['recursion_endtime'])) : null,

				'product_recursion'            =>  isset($product_recursion) ? $product_recursion : '',

				'recursion_custom_time'        =>  isset($recursion_custom_time) ? (int)$recursion_custom_time : 0,

				'product_short_description'    =>  isset($post['product_short_description']) ? $post['product_short_description'] : '',

				'product_tags'                 =>  isset($post['product_tags']) ? json_encode($post['product_tags']) : '',

				'product_slug'                 =>  '',

				'product_share_count'          =>  '',

				'product_banner'               =>  isset($post['product_banner']) ? $post['product_banner'] : '',

				'product_video'                =>  isset($post['product_video']) ? $post['product_video'] : '',

				'product_avg_rating'           =>  0,

				'product_variations'           =>  isset($post['product_variations']) ? json_encode($post['product_variations']) : '',

				'product_weight'               =>  isset($post['product_weight']) ? (float)$post['product_weight'] : 0.00,

				'product_length'               =>  isset($post['product_length']) ? (float)$post['product_length'] : 0.00,

				'product_width'                =>  isset($post['product_width']) ? (float)$post['product_width'] : 0.00,

				'product_height'               =>  isset($post['product_height']) ? (float)$post['product_height'] : 0.00,

				'view_statistics'              =>  0,

				'product_quantity'             =>  isset($post['product_quantity']) && $post['product_quantity'] !== '' ? $post['product_quantity'] : -1,

				'min_health_score'             =>  isset($post['min_health_score']) ? max(0, min(100, (float) $post['min_health_score'])) : 0,
				'min_award_level_id'           =>  (!empty($post['min_award_level_id']) && (int) $post['min_award_level_id'] > 0) ? (int) $post['min_award_level_id'] : null,
			);

				$details['product_featured_image'] = isset($post['product_featured_image_s3']) ? $post['product_featured_image_s3'] : '';
				$details['view'] = 0;
				if($_FILES['product_featured_image']['error'] != 0 && $product_id == 0 && @$post['product_featured_image_s3'] == ""){

					$errors['product_featured_image'] = 'Select Featured Image File!';

				} else if(!empty($_FILES['product_featured_image']['name'])){

					$upload_response = $this->upload_photo('product_featured_image','assets/images/product/upload/thumb');

					if($upload_response['success']){

						$details['product_featured_image'] = $upload_response['upload_data']['file_name'];

					}else{

						$errors['product_featured_image'] = $upload_response['msg'];

					}
				}

				if(!empty($_FILES['downloadable_file'])){

					$files = $_FILES['downloadable_file'];

					if(isset($_FILES['downloadable_file']['name']) && is_countable($_FILES['downloadable_file']['name']))
					$count_file = count($_FILES['downloadable_file']['name']);
					else
						$count_file=0;


					$this->load->helper('string');	

					for($i=0; $i<$count_file; $i++){

						$extension = pathinfo($files['name'][$i], PATHINFO_EXTENSION);
						if(!empty(trim($files['name'][$i]))){
							if($extension=='zip'){

								$FILES['downloadable_files']['name'] = md5(random_string('alnum', 10));

								$FILES['downloadable_files']['type'] = $files['type'][$i];

								$FILES['downloadable_files']['tmp_name'] = $files['tmp_name'][$i];

								$FILES['downloadable_files']['error'] = $files['error'][$i];

								$FILES['downloadable_files']['size'] = $files['size'][$i];    


								if(empty($FILES['downloadable_files']['error'])){

									move_uploaded_file($FILES['downloadable_files']['tmp_name'], APPPATH.'/downloads/'. $FILES['downloadable_files']['name']);

									$downloadable_files[] = array(

										'type' => $FILES['downloadable_files']['type'],

										'name' => $FILES['downloadable_files']['name'],

										'mask' => $files['name'][$i],

									);
									$details['downloadable_files']=json_encode($downloadable_filess);
								}else{

									$errors['downloadable_files'] = $FILES['downloadable_files']['error'];
								}

							} else {

								$zip = new ZipArchive();

								$zip_name = md5(random_string('alnum', 10));

								if ($zip->open(APPPATH.'/downloads/'.$zip_name, ZipArchive::CREATE) !== TRUE) {
									$errors['downloadable_files'] = "Sorry ZIP creation is not working currently.";
								}

								$zip->addFromString($files['name'][$i], file_get_contents($files['tmp_name'][$i]));

								$zip->close(); 

								$downloadable_files[] = array(

									'type' => 'application/x-zip-compressed',

									'name' =>$zip_name,

									'mask' => preg_replace('/\\.[^.\\s]{3,4}$/', '', $files['name'][$i]).'.zip',

								);
							}

						}
					}
										
					

				}

				// Process downloadable S3 files
				if (!empty($_POST['downloadable_file_s3'])) {
				    $s3Files = json_decode($_POST['downloadable_file_s3'], true);
				    
				    foreach ($s3Files as $s3file) {
				        // Generate a unique name for each file to prevent conflicts
				        $zip_name = md5(random_string('alnum', 10));

				        // Append S3 file details to the downloadable files list
				        $downloadable_files[] = [
				            'srctype' => 'AwsS3',
				            'name'    => $zip_name,
				            'url'     => $s3file['imageurl'],
				            'type'    => $s3file['type'],
				            'mask'    => $s3file['name'],
				        ];
				    }
			}
		$details['downloadable_files']=json_encode($downloadable_files);

		if(empty($errors)){
			
			if($product_id == 0) {
				if($post['product_type'] == 'downloadable') {
					if(empty($downloadable_files)) {
						$errors['downloadable_file'] = __('admin.please_upload_downloadable_files');
					}
				}
			}
		}
		
		if(empty($errors)){

				$old_product_data =[];

					if($product_id){
						$old_product_data = $this->db->query("SELECT * FROM product WHERE product_id = ". (int)$product_id)->row_array();
						$details['product_updated_date'] = date('Y-m-d H:i:s');
						$details['product_updated_by'] = $userdetails['id'];
						$this->Product_model->update_data('product', $details, array('product_id' => $product_id));
						$this->session->set_flashdata('success', __('admin.product_campaign_updated_successfully'));
					} else {
						$details['product_created_by'] = $userdetails['id'];
						$details['product_updated_by'] = $userdetails['id'];
						$details['product_updated_date'] = date('Y-m-d H:i:s');
						$details['product_created_date'] = date('Y-m-d H:i:s');
						$product_id = $this->Product_model->create_data('product', $details);
						$this->session->set_flashdata('success', __('admin.product_campaign_added_successfully'));
					}

					$seofilename = $this->friendly_seo_string($post['product_name']);

					$seofilename = strtolower($seofilename);

					$product_slug = $seofilename.'-'.$product_id;

					$this->db->query("UPDATE product SET product_slug = ". $this->db->escape($product_slug) ." WHERE product_id =". $product_id);

					$seller = '';

					if($product_id){

					$this->db->query("DELETE FROM product_categories WHERE product_id = {$product_id}");
					$this->db->query("DELETE FROM product_meta WHERE related_product_id = {$product_id} AND meta_key NOT IN ('funnel_upsells', 'funnel_price')");

						if(isset($post['product_sale_period']) && !empty($post['product_sale_period']) && isset($post['product_sale_price']) && !empty($post['product_sale_price'])) {
							$product_sale_period = explode(" - ", $post['product_sale_period']);
							$post['product_sale_start'] = date('Y-m-d H:i:s', strtotime($product_sale_period[0])); 
							$post['product_sale_end'] = date('Y-m-d H:i:s', strtotime($product_sale_period[1])); 
							$post['product_sale_price'] = $post['product_sale_price'];
						}


						if(isset($post['product_reviewer_name']) && !empty($post['product_reviewer_name'])) {
							$post['product_reviews'] = [];

							foreach ($post['product_reviewer_name'] as $key => $value) {
								array_push($post['product_reviews'], [
									'name' => $value,
									'comment' => $post['product_reviewer_comment'][$key],
								]);
							}

							$post['product_reviews'] = json_encode($post['product_reviews']);
						}
						if(isset($post['product_footer_name']) && !empty($post['product_footer_name'])) {
							$post['product_footer'] = [];

							foreach ($post['product_footer_name'] as $key => $value) {
								array_push($post['product_footer'], [
									'name' => $value,
									'description' => $post['product_footer_description'][$key],
								]);
							}

							$post['product_footer'] = json_encode($post['product_footer']);
						}

						$metaKeys = ['checkout_template', 'show_to_affiliates', 'product_launching_datetime', 'product_quantity', 'product_sale_start', 'product_sale_end', 'product_sale_price', 'product_checkout_terms', 'product_reviews','product_footer', 'show_to_featured'];

						foreach ($metaKeys as $metaKey) {
							if(isset($post[$metaKey]) && $post[$metaKey] != ""){

								if($metaKey === 'product_launching_datetime') {
									$post[$metaKey] = date('Y-m-d H:i:s', strtotime($post[$metaKey]));
								}

								$this->Product_model->create_data('product_meta', array(
									'related_product_id' => $product_id,
									'meta_key' => $metaKey,
									'meta_value' => $post[$metaKey]
								));
							}
						}

						
						if(isset($post['category']) && is_array($post['category'])){

							foreach ($post['category'] as $category_id) {

								$category = array(

									'product_id' => $product_id,

									'category_id' => $category_id,

								);



								$this->Product_model->create_data('product_categories', $category);
							}
						}

						$admin_comment = '';

						if(isset($post['admin_comment']) && $post['admin_comment']){

							$admin_comment = $post['admin_comment'];

						}


						if(isset($post['admin_sale_commission_type'])){

							$seller_comm = [

								'admin_sale_commission_type'      => $post['admin_sale_commission_type'],

								'admin_commission_value'          => $post['admin_commission_value'],

								'admin_click_commission_type'     => $post['admin_click_commission_type'],

								'admin_click_amount'              => $post['admin_click_amount'],

								'admin_click_count'               => $post['admin_click_count'],

							];

							$seller = $this->db->query("SELECT * FROM product_affiliate WHERE product_id=". (int)$product_id ." ")->row();

							$this->Product_model->assignToSeller($product_id, $details, $userdetails['id'], $admin_comment, 'admin', $seller_comm);

						}

					}





					if($seller){

						$product_data = $this->db->query("SELECT * FROM product WHERE product_id = ". (int)$product_id)->row_array();

						$this->load->model('Mail_model');

						if($old_product_data['product_status'] != $product_data['product_status']){

							$this->Mail_model->vendor_product_status_change($product_id, 'vendor', true);

						}

					}


					if ($post['action'] == 'save_close') {
						$json['location'] = base_url('Productsales/index');
					} else {
						$json['location'] = base_url('Productsales/update/'.$product_id);
					}


				} else {
					$json['errors'] = $errors;
				}

		} else {

			$json['errors'] = $this->form_validation->error_array();

			if(isset($json['errors']['category[]'])){

				$json['errors']['category_auto'] = $json['errors']['category[]'];

			}
			
			// Add product-type-specific validation even when basic validation fails
			$post = $this->input->post(null,true);
			if($product_id == 0 && isset($post['product_type'])) {
				if($post['product_type'] == 'downloadable') {
					// Check if any downloadable files were uploaded
					$hasFiles = false;
					if(!empty($_FILES['downloadable_file']['name'])) {
						if(is_array($_FILES['downloadable_file']['name'])) {
							foreach($_FILES['downloadable_file']['name'] as $filename) {
								if(!empty(trim($filename))) {
									$hasFiles = true;
									break;
								}
							}
						} elseif(!empty(trim($_FILES['downloadable_file']['name']))) {
							$hasFiles = true;
						}
					}
					if(!$hasFiles && empty($post['keep_files']) && empty($post['downloadable_file_s3'])) {
						$json['errors']['downloadable_file'] = __('admin.please_upload_downloadable_files');
					}
				} elseif($post['product_type'] == 'video') {
					if(empty($post['sub_product_type'])) {
						$json['errors']['sub_product_type'] = __('admin.please_select_lms_product_type');
					} elseif($post['sub_product_type'] == 'video') {
						// Check if video files were uploaded
						$hasFiles = false;
						if(!empty($_FILES['downloadable_file']['name'])) {
							if(is_array($_FILES['downloadable_file']['name'])) {
								foreach($_FILES['downloadable_file']['name'] as $filename) {
									if(!empty(trim($filename))) {
										$hasFiles = true;
										break;
									}
								}
							} elseif(!empty(trim($_FILES['downloadable_file']['name']))) {
								$hasFiles = true;
							}
						}
						if(!$hasFiles && empty($post['keep_files'])) {
							$json['errors']['video_files'] = __('admin.please_upload_video_files');
						}
					} elseif($post['sub_product_type'] == 'videolink') {
						if(empty($post['videolink'])) {
							$json['errors']['video_links'] = __('admin.please_add_video_links');
						}
					}
				}
			}

		}

			echo json_encode($json);

			die;

		}
	}

	public function delete(){
		$post = $this->input->post(null,true);

		if(isset($post['product'])){

			foreach($post['product'] as $id){
				if(!empty($id)) {
					$orderProduct = $this->db->query('SELECT id FROM order_products WHERE product_id = '.$id)->row();

					if(empty($orderProduct)) {
						$this->Product_model->deleteproducts((int)$id);
					} else {
						$this->session->set_flashdata('error', __('admin.some_order_product_not_deleted'));
					}
				}

			}

			$this->session->set_flashdata('success', __('admin.product_is_deleted_successfully'));


		}  else {

			$id = (int)$this->input->get('delete_id');

			$orderProduct = $this->db->query('SELECT id FROM order_products WHERE product_id = '.$id)->row();

			if(empty($orderProduct)) {
				$res = $this->Product_model->deleteproducts($id);

				$this->session->set_flashdata('success', __('admin.product_is_deleted_successfully'));
			} else {
				$this->session->set_flashdata('error', __('admin.order_product_not_deleted'));
			}

		}

		redirect(base_url() . 'Productsales/index');
	}

	public function integration_code_modal(){
		if(!$this->userdetails){ redirect('admincontrol/dashboard', 'refresh'); }

		$data['product'] = $this->db->query('select * from product where product_id='.(int)$this->input->post('id',true))->row();

		echo $this->load->view('admincontrol/product_campaign/integration_code_modal', $data, true);
		die;
	}


	public function addProductCampaignClick(){



		$content = file_get_contents("php://input");

		if($content){
			parse_str($content, $data);
		} else {
			$data = $this->input->get(null);
		}

		if(isset($data['af_id']) && isset($data['product_id'])) {

			list($affiliate_id,$click_product_id) = explode("-", _encrypt_decrypt(parse_affiliate_id($data['af_id']),'decrypt'));
			
			$affiliate_id = (int) $affiliate_id;

			$vendor_setting = $this->Product_model->getSettings('vendor');

			$store_setting = $this->Product_model->getSettings('store');
 
			if($affiliate_id > 0) {

				$Affiliate_Hook = new Affiliate_Hook;

				if($Affiliate_Hook->is_suspicious_click($affiliate_id)) {
					die('suspicious click!');
				}
				
				$product_id = (int) _encrypt_decrypt(parse_affiliate_id($data['product_id']),'decrypt');

				if((int)$click_product_id !== (int)$product_id) {
					die('multi-product exception!');
				}

				$product = $this->db->query('select * from product where is_campaign_product = 1 AND product_status = 1 AND product_id='.$product_id)->row_array();

				if(empty($product)) {
					die('campaign product not available!');
				}

				$_user = $this->Product_model->getUserDetails($affiliate_id);

				if (!$this->Product_model->user_can_promote_market_campaign_for_user_id($affiliate_id, $product)) {
					market_promotion_http_block();
				}

				$is_vendor = $_user['is_vendor'] == 1;

				if(! $this->allowCommissionFromVendorPanelMode($product['product_created_by'], $is_vendor)) {
					die(__('admin.commission_blocked_from_vendor_panel_mode'));
				}

				if ($store_setting['store_mode'] == 'cart') {
					die('restricted panel!');
				}


				$restricted_vendors = $this->get_restricted_vendors($affiliate_id,$product['product_slug']);

				//updated function - sales mode restricet
				if(in_array($affiliate_id, $restricted_vendors) || in_array($product['product_created_by'], $restricted_vendors)) {

					die('restricted user!');
				}

				$this->load->model('Product_model');

					//add view statistics 

					if(isset($product_id) && isset($affiliate_id))
					{
						if(isset($_COOKIE))
						$session_id = $_COOKIE['product_view_ck'];
						else
							$session_id=null;
						$Affiliate_Hook = new Affiliate_Hook; 
	 
						if(!isset($session_id))
						{
							$session_id = uniqid().rand();
							$time = (60 * 60);
							setcookie("product_view_ck", $session_id, time()+$time, "/" ); 
						}
	 					
						$ip = $Affiliate_Hook->get_client_ip();
						$viewData = array(
				            'user_id'  => (int)$affiliate_id,
				            'product_id'  => (int)$product_id,
				            'link' => '', 
				            'ip'      =>  $ip,
				            'session_id'      =>  $session_id, 
				            'created_at'      =>  date('Y-m-d H:m:s'),

				        );

						$viewcheck = spam_protect_and_log($viewData, 'product');
						if($viewcheck!=2)
						{

							$this->db->set('view_statistics', 'view_statistics+1', FALSE);
							$this->db->where('product_id', (int)$product_id);
							$this->db->update('product');
						}  
					}
					
 

				// end view code.. 
				
				$match = $this->Product_model->getProductAction($product_id, $affiliate_id);
				
				if ($match == 0){
					$this->Product_model->setClicks($product_id, $affiliate_id);
				} else {

					$this->Product_model->getProductActionIncrese($product_id, $affiliate_id);
				}
				
				$wallet_group_id = time().rand(10,100);

				$transaction_id = $this->Product_model->giveClickCommition($product, $affiliate_id, 0, $wallet_group_id);
				
				if((int)$transaction_id > 0) {
					if ($vendor_setting['admin_click_status'] == 1) {

						$this->Product_model->giveAdminClickCommition($product, $wallet_group_id);
					}
					
					$this->Product_model->referClick($product, $affiliate_id, 0, $wallet_group_id);

					echo "success";
				}

			}
		} else {
			die('invalid request!');
		}
	}

	public function placeOrder($productID, $affiliateID) {

		$product_id = (int) _encrypt_decrypt(parse_affiliate_id($productID),'decrypt');

		list($affiliate_id,$click_product_id) = explode("-", _encrypt_decrypt(parse_affiliate_id($affiliateID),'decrypt'));

		$affiliate_id = (int) $affiliate_id;

		$restricted_vendors = $this->get_restricted_vendors();

		$product = $this->db->query('select * from product where is_campaign_product = 1 AND product_status = 1 AND product_id='.$product_id)->row_array();

		if(empty($product)) {
			die("product not found!");
		}

		if(in_array($product['product_created_by'], $restricted_vendors)) {
			die("restricted vendor!");
		}

		$Affiliate_Hook = new Affiliate_Hook;

		if($Affiliate_Hook->is_suspicious_click($affiliate_id)) {
			$blockAffiliateCommission = true;
		}

		if(in_array($affiliate_id, $restricted_vendors)) {
			$blockAffiliateCommission = true;
		}

		$refer_id = isset($blockAffiliateCommission) ? null : $affiliate_id;

		$this->load->model('cart');
		$this->cart->clearCart();
		$this->cart->add($product_id, 1, null, $refer_id, $product);
		redirect(base_url() . 'store/checkout');
	}

	public function update_product_settings(){
		$status = $this->input->post('status');
		$setting_key = $this->input->post('setting_key');
		$product_id = $this->input->post('product_id');
		
		$update = $this->Setting_model->update_product_settings($status, $setting_key, $product_id);
		echo $update;
	}

	public function duplicate_product($product_id = null) {
		header('Content-Type: application/json');
		
		if (!$product_id) {
			echo json_encode([
				'success' => false,
				'message' => 'Product ID is required'
			]);
			return;
		}

		$this->load->model('Product_model');
		
		// Get the original product
		$original_product = $this->Product_model->getProductById($product_id);
		
		if (!$original_product) {
			echo json_encode([
				'success' => false,
				'message' => 'Product not found'
			]);
			return;
		}

		$this->db->trans_start();

		try {
			// Prepare duplicate product data - Include ALL columns from database schema
			$product_data = [
				'is_campaign_product' => $original_product->is_campaign_product,
				'product_url' => $original_product->product_url,
				'product_name' => $original_product->product_name . ' (Copy)',
				'product_description' => $original_product->product_description,
				'product_short_description' => $original_product->product_short_description,
				'product_tags' => $original_product->product_tags,
				'product_msrp' => $original_product->product_msrp,
				'product_price' => $original_product->product_price,
				'product_sku' => $original_product->product_sku . '_copy_' . time(),
				'product_slug' => $original_product->product_slug . '_copy_' . time(),
				'product_share_count' => '0', // Reset share count
				'product_click_count' => 0,
				'product_view_count' => 0,
				'product_sales_count' => 0,
				'product_featured_image' => $original_product->product_featured_image,
				'product_banner' => $original_product->product_banner,
				'product_video' => $original_product->product_video,
				'product_type' => $original_product->product_type,
				'product_commision_type' => $original_product->product_commision_type,
				'product_commision_value' => $original_product->product_commision_value,
				'product_status' => 1, // Set as active
				'product_ipaddress' => $this->input->ip_address(),
				'product_created_date' => date('Y-m-d H:i:s'),
				'product_updated_date' => date('Y-m-d H:i:s'),
				'product_created_by' => $this->userdetails['id'],
				'product_updated_by' => $this->userdetails['id'],
				'product_click_commision_type' => $original_product->product_click_commision_type,
				'product_click_commision_ppc' => $original_product->product_click_commision_ppc,
				'product_click_commision_per' => $original_product->product_click_commision_per,
				'product_total_commission' => '0', // Reset total commission
				'product_recursion_type' => $original_product->product_recursion_type,
				'product_recursion' => $original_product->product_recursion,
				'recursion_custom_time' => $original_product->recursion_custom_time,
				'recursion_endtime' => $original_product->recursion_endtime,
				'view' => 0,
				'on_store' => 0, // Set as draft - admin needs to review before making live
				'downloadable_files' => $original_product->downloadable_files,
				'allow_shipping' => $original_product->allow_shipping,
				'allow_upload_file' => $original_product->allow_upload_file,
				'allow_comment' => $original_product->allow_comment,
				'state_id' => $original_product->state_id,
				'product_avg_rating' => 0,
				'product_variations' => $original_product->product_variations,
				'product_weight' => $original_product->product_weight,
				'product_length' => $original_product->product_length,
				'product_width' => $original_product->product_width,
				'product_height' => $original_product->product_height,
				'view_statistics' => 0,
				'product_quantity' => $original_product->product_quantity
			];

			// Insert the duplicate product
			$this->db->insert('product', $product_data);
			$new_product_id = $this->db->insert_id();

			// Copy product categories
			$categories = $this->db->select('category_id')
				->from('product_categories')
				->where('product_id', $product_id)
				->get()
				->result();

			foreach ($categories as $category) {
				$this->db->insert('product_categories', [
					'product_id' => $new_product_id,
					'category_id' => $category->category_id
				]);
			}

			// Copy product affiliate settings
			$affiliate = $this->db->select('*')
				->from('product_affiliate')
				->where('product_id', $product_id)
				->get()
				->row();

			if ($affiliate) {
				$this->db->insert('product_affiliate', [
					'product_id' => $new_product_id,
					'user_id' => $affiliate->user_id,
					'commission_type' => $affiliate->commission_type,
					'commission_rate' => $affiliate->commission_rate,
					'commission_amount' => $affiliate->commission_amount,
					'date_added' => date('Y-m-d H:i:s')
				]);
			}

			// Copy product meta data
			$meta_data = $this->db->select('*')
				->from('product_meta')
				->where('related_product_id', $product_id)
				->get()
				->result();

			foreach ($meta_data as $meta) {
				$this->db->insert('product_meta', [
					'related_product_id' => $new_product_id,
					'meta_key' => $meta->meta_key,
					'meta_value' => $meta->meta_value
				]);
			}

			$this->db->trans_complete();

			if ($this->db->trans_status() === FALSE) {
				echo json_encode([
					'success' => false,
					'message' => 'Failed to duplicate product'
				]);
			} else {
				echo json_encode([
					'success' => true,
					'message' => 'Product duplicated successfully',
					'product_id' => $new_product_id
				]);
			}

		} catch (Exception $e) {
			$this->db->trans_rollback();
			echo json_encode([
				'success' => false,
				'message' => 'Error: ' . $e->getMessage()
			]);
		}
	}
}