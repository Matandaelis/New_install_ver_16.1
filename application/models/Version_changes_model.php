<?php
class Version_changes_model extends MY_Model{

	// function for one-time setup of new changes after the update
	public function update_changes() {
	    $this->load->library('session');

	    // Get the session status
	    $update_status = $this->session->userdata('update_status');
	    log_message('debug', 'Session Status: ' . $update_status);

	    if ($update_status === 'in_progress') {
	        redirect(base_url('admincontrol/system_update_report'));
	        exit();
	    }

	    $this->session->set_userdata('update_status', 'in_progress');

    $data_results = [["info" => "system update is started..."]];
    $updates = [
        'migrate_license_to_easy', 'import_database_changes', 'update_version_details', 'clear_image_cache_and_log_folders',
        'set_default_theme', 'unlink_deprecated_files', 'remove_deprecated_directory',
        'drop_deprecated_table', 'update_mail_templates', 'update_front_theme_colors'
    ];

	    try {
	        foreach ($updates as $update) {
	            if (method_exists($this, $update)) {
	                $data_results_sub = $this->$update();
	                if (!is_array($data_results_sub)) {
	                    throw new Exception("Function {$update} did not return an array.");
	                }
	                $data_results = array_merge($data_results, $data_results_sub);
	            } else {
	                throw new Exception("Function {$update} does not exist.");
	            }
	        }
    } catch (\Throwable $e) {
        // Catches both Exception and PHP 8 Error subclasses (TypeError, ValueError, etc.)
        $data_results[] = ["error" => get_class($e) . ': ' . $e->getMessage() . ' in ' . $e->getFile() . ':' . $e->getLine()];
    } finally {
        $this->session->set_userdata('update_status', 'completed');
    }

    $data_results[] = ["success" => "Process completed on " . date('d-m-Y H:i:s')];

    // Save update results
    if (!file_exists(APPPATH . "logs/system_update_logs")) {
        mkdir(APPPATH . "logs/system_update_logs", 0755, true);
    }

    $filename = time() . "-" . str_replace('.', '_', $this->config->item('app_version')) . ".json";
    file_put_contents(APPPATH . "logs/system_update_logs/" . $filename, json_encode($data_results, JSON_UNESCAPED_UNICODE));

	    redirect(base_url('admincontrol/system_update_report'));
	    exit();
	}

	private function import_database_changes(){
	    $resultArray = [["info"=>"database update is started..."]];

	    try {
	        // generate backup of existing database before migrate to new version
	        $this->load->dbutil();
	        $prefs = array(
	            'format'        => 'txt',
	            'filename'      => $this->db->database,
	            'add_drop'      => true,
	            'add_insert'    => true,
	            'newline'       => "\n"
	        );
	        $backup =& $this->dbutil->backup($prefs);
	        $db_name = 'dbbkp_before_ver_'.$this->config->item('app_version').'_at_'.time().'.sql';
	        $bk_path = APPPATH.'backup/'.$db_name;
	        $this->load->helper('file');
	        write_file($bk_path, $backup);
	        $resultArray[] = [
	            "success" => 'generated backup of existing database'
	        ];
	    } catch (Exception $e) {
	        $resultArray[] = [
	            "error" => $e->getMessage()
	        ];

	        $backup_failed = true;
	    }

	    if(isset($backup_failed)) {
	        return $resultArray;
	        die;
	    }

	    try {
	        // migrate database to new version
	        $resultArray[] = [
	            "info" => 'migrate database to new version started'
	        ];

	        $templine = '';
	        $mysql_updates = APPPATH.'updates/database_update_'.$this->config->item('app_version').'.data';
	        $file = fopen($mysql_updates,"r");

			// ADD THIS ERROR CHECK:
			if (!$file) {
			    $resultArray[] = [
			        "error" => "Could not open update file: " . $mysql_updates
			    ];
			    return $resultArray;
			}

	        while(! feof($file))
	        {
	            $line = fgets($file);
	            if (substr($line, 0, 2) == '--' || $line == '')
	                continue;
	            $templine .= $line;
	            if (str_contains($templine, 'SET @preparedStatement') && !str_contains($templine,'DEALLOCATE PREPARE alterIfNotExists'))
	                continue;

	            if (substr(trim($line), -1, 1) == ';') {
	                $templine = str_replace('@databaseName', "\"".$this->db->database."\"", $templine);
	                try {
	                    $this->db->db_debug = true;
	                    if(str_contains($templine, 'SET @preparedStatement')) {
	                        $qurisArray = $this->explodeSkipOne($templine);
	                        $this->db->trans_start();
	                        foreach ($qurisArray as $qerySQL) {

	                            if(strlen($qerySQL) > 5) {
	                                if(!$this->db->query($qerySQL)) {
	                                    log_message('error', json_encode($this->db->error()));
	                                    $resultArray[] = [
	                                        "error" => json_encode($this->db->error())
	                                    ];
	                                    $has_db_error  = true;
	                                } else {
	                                    $resultArray[] = [
	                                        "success" => $qerySQL
	                                    ];
	                                }
	                            }
	                        }
	                        $this->db->trans_complete();
	                    } else {
	                        if(!$this->db->query($templine)) {
	                            log_message('error', json_encode($this->db->error()));
	                            $resultArray[] = [
	                                "error" => json_encode($this->db->error())
	                            ];
	                            $has_db_error  = true;
	                        } else {
	                            $resultArray[] = [
	                                "success" => $templine
	                            ];
	                        }
	                    }
	                } catch (\Throwable $th) {
	                    log_message('error', $th->getMessage());
	                    $resultArray[] = [
	                        "error" => $th->getMessage()
	                    ];
	                    $has_db_error  = true;
	                }
	                $templine = '';
	            }
	        }
	        fclose($file);

			copy($mysql_updates, APPPATH.'backup/'.basename($mysql_updates));
			unlink($mysql_updates);

	        if(!isset($has_db_error)) {
	            $resultArray[] = [
	                "success" => 'Database updated successfully!'
	            ];
	        } else {
	            $data['warning'][] = [
	                "success" => 'Database may not be updated successfully!'
	            ];
	        }
	    } catch (Exception $e) {
	        $resultArray[] = [
	            "error" => $e->getMessage()
	        ];
	    }

	    return $resultArray;
	}

	public function update_version_details() {
	    // Initialize the results array
	    $data['results'] = [];
	    $data['results'][] = ["info" => "System version details are upgrading"];

	    try {
	        $oldVersion = defined('SCRIPT_VERSION') ? SCRIPT_VERSION : 'undefined';
	        $newVersion = $this->config->item('app_version');

        if ($oldVersion !== $newVersion && $newVersion) {
            if (update_config_option('app_version', $newVersion)) {
                $data['results'][] = ["success" => "System version is upgraded from {$oldVersion} to {$newVersion}"];
            } else {
                $data['results'][] = ["error" => "Failed to update configuration with new version"];
            }
	        } else {
	            $data['results'][] = ["info" => "System version details may be not defined or already a latest version available"];
	        }

	    } catch (Exception $e) {
	        // Catch any exceptions and log them
	        $data['results'][] = ["error" => $e->getMessage()];
	    }

	    return $data['results'];
	}

	private function clear_image_cache_and_log_folders() {
		$data['results'] = [["info"=>"system cache clearing"]];
		try {
			$folder_path = [];

			$folder_path[] =  FCPATH."assets/image_cache/cache/assets/images/form/favi/";
			$folder_path[] =  FCPATH."assets/image_cache/cache/assets/images/payments/";
			$folder_path[] =  FCPATH."assets/image_cache/cache/assets/images/product/upload/thumb/";
			$folder_path[] =  FCPATH."assets/image_cache/cache/assets/images/site/";
			$folder_path[] =  FCPATH."assets/image_cache/cache/assets/images/themes/";
			$folder_path[] =  FCPATH."assets/image_cache/cache/assets/images/wallet-icon/";
			$folder_path[] =  FCPATH."assets/image_cache/cache/assets/vertical/assets/images/users/";
			$folder_path[] =  FCPATH."application/logs/";
			$folder_path[] =  FCPATH."application/core/excel/Classes/";

			foreach ($folder_path as $key => $value) {

				$files = glob($value.'/*');

				foreach($files as $file) { 

					if(is_file($file))  {
						unlink($file);
						$data['results'] = [["success"=>$file." cleared"]];
					};  

				}

			}

			$data['results'] = [["success"=>"system cache clearing completed"]];
		} catch (Exception $e) {
			$data['results'][] = [
				"error" => $e->getMessage()
			];
		}

		return $data['results'];
	}

	private function set_default_theme() {
		try {
			$this->db->query("UPDATE `setting` SET `setting_value`='starter2026' WHERE `setting_type`='store' AND `setting_key`='theme'");
			$data['results'] = [["success"=>"default theme setting updated"]];
		} catch (Exception $e) {
			$data['results'][] = [
				"error" => $e->getMessage()
			];
		}

		return $data['results'];
	}



private function unlink_deprecated_files() {
	$data['results'] = [];
	$deleted_count = 0;
	
	try {
		$deprecated_files = [
			FCPATH."application/views/admincontrol/setting/install_new_version.php",
			FCPATH."assets/login/login/js/analytics.js",
			FCPATH."assets/vertical/assets/images/flags/Indian_flag.jpg",
			FCPATH."assets/vertical/assets/images/flags/french_flag.jpg",
			FCPATH."assets/vertical/assets/images/flags/germany_flag.jpg",
			FCPATH."assets/vertical/assets/images/flags/italy_flag.jpg",
			FCPATH."assets/vertical/assets/images/flags/russia_flag.jpg",
			FCPATH."assets/vertical/assets/images/flags/spain_flag.jpg",
			FCPATH."assets/vertical/assets/images/flags/us_flag.jpg",
			FCPATH."application/controllers/User_BK.php",
			FCPATH."application/models/Version_changes_model_completed.php",
			FCPATH."application/views/usercontrol/login/index1.php",
			FCPATH."application/views/usercontrol/login/index2.php",
			FCPATH."application/views/usercontrol/login/index3.php",
			FCPATH."application/views/usercontrol/login/index4.php",
			FCPATH."application/views/usercontrol/login/index5.php",
			FCPATH."application/views/usercontrol/login/index6.php",
			FCPATH."application/views/usercontrol/login/index7.php",
			FCPATH."application/views/usercontrol/login/index8.php",
			FCPATH."application/views/usercontrol/login/index9.php",
			FCPATH."application/views/usercontrol/login/login.php",
			FCPATH."application/views/usercontrol/dashboard/index.php",
			FCPATH."application/views/admincontrol/dashboard/index.php",
			FCPATH."application/views/admincontrol/includes/chat.php",
			FCPATH."application/views/admincontrol/includes/store.php",
			FCPATH."application/views/admincontrol/includes/sidebar.php",
			FCPATH."assets/plugins/chartist/css/chartist.min.css",
			FCPATH."assets/css/jquery.uploadPreviewer.css",
			FCPATH."assets/js/jssocials-1.4.0/jssocials.css",
			FCPATH."assets/js/jssocials-1.4.0/jssocials-theme-flat.css",
			FCPATH."assets/template/css/admin.style.css",
			FCPATH."assets/js/jquery.uploadPreviewer.js",
			FCPATH."assets/js/jssocials-1.4.0/jssocials.min.js",
			FCPATH."assets/plugins/chartist/js/chartist.min.js",
			FCPATH."assets/template/js/darkmode.js",
			FCPATH."application/core/Razorpay/libs/Requests-1.7.0/.coveralls.yml",
			FCPATH."application/core/Razorpay/libs/Requests-1.7.0/.gitignore",
			FCPATH."application/core/Razorpay/libs/Requests-1.7.0/.travis.yml",
			FCPATH."assets/notify/notification.mp3",
			FCPATH."assets/notify/notify.mp3",
			FCPATH."assets/data/last_check.txt",
			FCPATH."assets/plugins/store/lightgallery-all.min.js",
			FCPATH."assets/plugins/tree/Treant.js",
			FCPATH."assets/plugins/tree/Treant.css",
			FCPATH."assets/plugins/tree/collapsable.css",
			FCPATH."assets/plugins/toastr/toastr.js",
			FCPATH."assets/plugins/toastr/toastr.min.js",
			FCPATH."assets/plugins/toastr/toastr.css",
			FCPATH."assets/plugins/toastr/toastr.min.css",
			FCPATH."assets/plugins/magnific-popup/jquery.magnific-popup.min.js",
			FCPATH."assets/plugins/magnific-popup/jquery.magnific-popup.js",
			FCPATH."assets/plugins/magnific-popup/magnific-popup.css",
			FCPATH."application/controllers/Firstsetting.php",
			FCPATH."application/views/firstsetting/index.php",
			FCPATH."application/views/firstsetting/step.php",
			FCPATH."assets/images/themes/default.png",
			FCPATH."application/hooks/Router_Hook.php",
			FCPATH."application/core/excel/Classes/PHPExcel.php",
			FCPATH."application/views/common/css.php",
			FCPATH."assets/vertical/assets/plugins/dropzone/dist/dropzone.js",
			FCPATH."assets/css/base.css",
			FCPATH."assets/css/chat.css",
			FCPATH."assets/css/copy.svg",
			FCPATH."assets/vertical/assets/plugins/tinymce/themes/inlite/src/test/.eslintrc",
			FCPATH."assets/vertical/index.html",
			FCPATH."assets/js/app.js",
			FCPATH."assets/js/dashborad.js",
			FCPATH."assets/js/lightbox.js",
			FCPATH."assets/login/index1/css/presentation.css",
			FCPATH."assets/login/index1/js/main.js",
			FCPATH."assets/login/index1/js/demo.js",
			FCPATH."assets/login/index1/js/bootstrap.min.js",
			FCPATH."assets/login/index1/js/jquery.min.js",
			FCPATH."assets/login/index1/js/popper.min.js",
			FCPATH."assets/login/index2/css/presentation.css",
			FCPATH."assets/login/index2/js/main.js",
			FCPATH."assets/login/index2/js/demo.js",
			FCPATH."assets/login/index2/js/bootstrap.min.js",
			FCPATH."assets/login/index2/js/jquery.min.js",
			FCPATH."assets/login/index2/js/popper.min.js",
			FCPATH."assets/login/index3/css/presentation.css",
			FCPATH."assets/login/index3/css/dd.css",
			FCPATH."assets/login/index3/css/flags.css",
			FCPATH."assets/login/index3/css/toastr.min.css",
			FCPATH."assets/login/index3/js/main.js",
			FCPATH."assets/login/index3/js/bootstrap.min.js",
			FCPATH."assets/login/index3/image/blank.gif",
			FCPATH."assets/login/index3/image/flagssprite_small.png",
			FCPATH."assets/login/index3/image/Logo 1.png",
			FCPATH."assets/login/index3/image/padlock.png",
			FCPATH."assets/login/index3/image/user.png",
			FCPATH."assets/login/index4/css/presentation.css",
			FCPATH."assets/login/index4/js/main.js",
			FCPATH."assets/login/index4/js/demo.js",
			FCPATH."assets/login/index4/js/bootstrap.min.js",
			FCPATH."assets/login/index4/js/jquery.min.js",
			FCPATH."assets/login/index5/css/presentation.css",
			FCPATH."assets/login/index5/js/main.js",
			FCPATH."assets/login/index5/js/demo.js",
			FCPATH."assets/login/index5/js/bootstrap.min.js",
			FCPATH."assets/login/index5/js/jquery.min.js",
			FCPATH."assets/login/index6/css/presentation.css",
			FCPATH."assets/login/index6/js/main.js",
			FCPATH."assets/login/index6/js/demo.js",
			FCPATH."assets/login/index6/js/bootstrap.min.js",
			FCPATH."assets/login/index6/js/jquery.min.js",
			FCPATH."assets/login/index7/css/presentation.css",
			FCPATH."assets/login/index7/js/main.js",
			FCPATH."assets/login/index7/js/demo.js",
			FCPATH."assets/login/index7/js/bootstrap.min.js",
			FCPATH."assets/login/index7/js/jquery.min.js",
			FCPATH."assets/login/index8/css/presentation.css",
			FCPATH."assets/login/index8/js/main.js",
			FCPATH."assets/login/index8/js/demo.js",
			FCPATH."assets/login/index8/js/bootstrap.min.js",
			FCPATH."assets/login/index8/js/jquery.min.js",
			FCPATH."assets/login/index9/css/presentation.css",
			FCPATH."assets/login/index10/css/presentation.css",
			FCPATH."assets/login/index10/js/main.js",
			FCPATH."assets/login/index1/css/bootstrap.min.css",
			FCPATH."assets/login/index2/css/bootstrap.min.css",
			FCPATH."assets/login/index3/css/bootstrap.min.css",
			FCPATH."assets/login/index4/css/bootstrap.min.css",
			FCPATH."assets/login/index5/css/bootstrap.min.css",
			FCPATH."assets/login/index6/css/bootstrap.min.css",
			FCPATH."assets/login/index7/css/bootstrap.min.css",
			FCPATH."assets/login/index8/css/bootstrap.min.css",
			FCPATH."assets/login/index9/css/bootstrap.min.css",
			FCPATH."assets/login/index10/css/bootstrap.min.css",
			FCPATH."assets/login/index10/css/util.css",
			FCPATH."assets/login/index1/css/common.css",
			FCPATH."assets/login/index1/css/theme-01.css",
			FCPATH."assets/login/index2/css/common.css",
			FCPATH."assets/login/index2/css/theme-07.css",
			FCPATH."assets/login/index4/css/common.css",
			FCPATH."assets/login/index4/css/theme-06.css",
			FCPATH."assets/login/index5/css/common.css",
			FCPATH."assets/login/index5/css/theme-06.css",
			FCPATH."assets/login/index6/css/common.css",
			FCPATH."assets/login/index6/css/theme-06.css",
			FCPATH."assets/login/index7/css/common.css",
			FCPATH."assets/login/index7/css/theme-06.css",
			FCPATH."assets/login/index8/css/common.css",
			FCPATH."assets/login/index8/css/theme-06.css",
			FCPATH."assets/login/style.css",
			FCPATH."assets/js/jquery-1.10.2.min.js",
			FCPATH."assets/js/jquery-3.2.1.min.js",
			FCPATH."assets/login/multiple_pages/style.css",
			FCPATH."assets/admin/css/style.css",
			FCPATH."assets/template/summernote/summernote.css",
			FCPATH."assets/template/summernote/summernote.js",
			FCPATH."assets/template/summernote/summernote.js.map",
			FCPATH."assets/template/summernote/summernote.min.css",
			FCPATH."assets/template/summernote/summernote.min.js",
			FCPATH."assets/template/summernote/summernote.min.js.LICENSE.txt",
			FCPATH."assets/template/summernote/summernote.min.js.map",
			FCPATH."assets/template/summernote/summernote-bs4.css",
			FCPATH."assets/template/summernote/summernote-bs4.js",
			FCPATH."assets/template/summernote/summernote-bs4.js.map",
			FCPATH."assets/template/summernote/summernote-bs4.min.css",
			FCPATH."assets/template/summernote/summernote-bs4.min.js",
			FCPATH."assets/template/summernote/summernote-bs4.min.js.LICENSE.txt",
			FCPATH."assets/template/summernote/summernote-bs4.min.js.map",
			FCPATH."assets/template/summernote/summernote-bs5.js",
			FCPATH."assets/template/summernote/summernote-bs5.min.css",
			FCPATH."assets/template/summernote/summernote-bs5.min.js",
			FCPATH."assets/template/summernote/summernote-lite.css",
			FCPATH."assets/template/summernote/summernote-lite.js",
			FCPATH."assets/template/summernote/summernote-lite.js.map",
			FCPATH."assets/css/usercontrol-common.css",
			FCPATH."application/views/usercontrol/includes/sidebar.php",
			FCPATH."application/views/usercontrol/includes/topnav.php",
			FCPATH."assets/store/classified/dependencies/owl.carousel/css/owl.video.play.html",
			FCPATH."assets/template/css/all.css",
			FCPATH."assets/template/css/bootstrap.css",
			FCPATH."assets/template/css/bootstrap.css.map",
			FCPATH."assets/template/css/bootstrap.min.css.map",
			FCPATH."assets/template/css/bootstrap-icons.json",
			FCPATH."assets/template/css/bootstrap-icons.scss",
			FCPATH."assets/template/css/brands.min.css",
			FCPATH."assets/template/css/custom.css",
			FCPATH."assets/template/css/dark.css",
			FCPATH."assets/template/css/fonts.css",
			FCPATH."assets/template/css/LineIcons.css",
			FCPATH."assets/template/css/rtl.min.css",
			FCPATH."assets/template/css/summernote.css",
			FCPATH."assets/template/js/jquery-3.5.1.slim.min.js",
			FCPATH."assets/template/js/popper.min.js",
			FCPATH."assets/template/js/bootstrap.bundle.min.js.map",
			FCPATH."assets/template/js/bootstrap4-toggle.min.js",
			FCPATH."assets/template/js/footer-scripts.js",
			FCPATH."assets/template/js/jquery-jvectormap-2.0.5.min.js",
			FCPATH."assets/template/js/jquery-migrate-3.0.0.min.js",
			FCPATH."assets/template/js/jquery.canvasjs.min.js",
			FCPATH."assets/template/js/libs.min.js",
			FCPATH."assets/template/js/popper.min.js.map",
			FCPATH."assets/template/js/settings.js",
			FCPATH."assets/template/js/summernote.min.js",
			FCPATH."assets/template/js/summernote.min.js.map",
			FCPATH."assets/login/index1/js/theme-10.js",
			FCPATH."assets/login/index2/js/theme-10.js",
			FCPATH."assets/login/index3/js/theme-10.js",
			FCPATH."assets/login/index4/js/theme-10.js",
			FCPATH."assets/login/index5/js/theme-10.js",
			FCPATH."assets/login/index5/css/custom.css",
			FCPATH."assets/login/index6/js/theme-10.js",
			FCPATH."assets/login/index7/js/theme-10.js",
			FCPATH."assets/login/index8/js/theme-10.js",
			FCPATH."assets/login/index6/css/custom.css",
			FCPATH."assets/login/index7/css/custom.css",
			FCPATH."assets/login/index8/css/custom.css",
			FCPATH."assets/login/index9/js/script.js",
			FCPATH."assets/login/index9/js/bootstrap.min.js",
			FCPATH."assets/login/index9/js/jquery.min.js",
			FCPATH."assets/login/index10/css/main.css",
			FCPATH."assets/login/index10/css/animate.css",
			FCPATH."assets/login/index10/css/animsition.min.css",
			FCPATH."assets/login/index10/css/daterangepicker.css",
			FCPATH."assets/login/index10/css/font-awesome.min.css",
			FCPATH."assets/login/index10/css/hamburgers.min.css",
			FCPATH."assets/login/index10/css/icon-font.min.css",
			FCPATH."assets/login/index10/css/material-design-iconic-font.min.css",
			FCPATH."assets/login/index10/css/select2.min.css",
			FCPATH."assets/login/index11/css/main.css",
			FCPATH."assets/login/index11/css/bootstrap.css",
			FCPATH."assets/login/index11/css/intlTelInput.css",
			FCPATH."assets/login/index11/js/bootstrap.bundle.min.js",
			FCPATH."assets/login/index11/js/bootstrap.js",
			FCPATH."assets/login/index11/js/intlTelInput.js",
			FCPATH."assets/login/index11/js/jquery-3.4.1.min.js",
			FCPATH."assets/login/index11/js/popper.min.js",
			FCPATH."assets/login/index11/js/utils.js",
			FCPATH."assets/login/multiple_pages/css/responsive.css",
			FCPATH."application/views/document/api_document.php",
			FCPATH."application/views/document/document_header.php",
			FCPATH."application/views/document/document_sidebar.php",
			FCPATH."application/views/document/document_footer.php",
			FCPATH."application/views/document/doc_intro.php",
			FCPATH."application/views/document/doc_user.php",
			FCPATH."application/views/document/doc_dashboard.php",
			FCPATH."application/views/document/doc_aff_links.php",
			FCPATH."application/views/document/doc_my_logs.php",
			FCPATH."application/views/document/doc_my_network.php",
			FCPATH."application/views/document/doc_user_reports.php",
			FCPATH."application/views/document/doc_contact_to_admin.php",
			FCPATH."application/views/document/doc_user_payments.php",
			FCPATH."application/views/document/doc_payment_details.php",
			FCPATH."application/views/document/doc_category.php",
			FCPATH."application/views/document/doc_user_wallet.php",
			FCPATH."application/views/document/doc_my_order.php",
			FCPATH."application/views/document/doc_subscription_plan.php",
			FCPATH."application/views/document/doc_vendor_market_place.php",
			FCPATH."application/views/document/doc_vendor_market_tools.php",
			FCPATH."application/views/document/doc_notification.php",
			FCPATH."application/models/Todo_model.php",
			FCPATH."application/models/Exporter.php",
			FCPATH."application/models/Shorturl_model.php",
			FCPATH."application/models/Commision_model.php",
			FCPATH."application/libraries/Encode.php",
			FCPATH."assets/store/default/js/jquery-3.5.1.slim.min.js",
			FCPATH."assets/store/default/js/jquery.min.js",
			FCPATH."assets/store/default/js/bootstrap.min.js",
			FCPATH."assets/store/default/js/nouislider.min.js",
			FCPATH."assets/store/default/js/sweetalert2.all.min.js",
			FCPATH."assets/store/default/js/v14-store.js",
			FCPATH."assets/store/default/css/placeholder-loading.css",
			FCPATH."assets/store/default/css/sweetalert2.min.css",
			FCPATH."assets/store/default/css/nouislider.css",
			FCPATH."assets/store/lms/js/jquery.min.js",
			FCPATH."assets/store/lms/js/bootstrap.bundle.min.js",
			FCPATH."assets/store/lms/css/bootstrap.min.css",
			FCPATH."assets/store/lms/css/all.min.css",
			FCPATH."assets/store/default/css/bootstrap.css",
			FCPATH."assets/store/default/css/bootstrap.css.map",
			FCPATH."assets/store/default/css/bootstrap.min.css",
			FCPATH."assets/store/default/css/bootstrap.min.css.map",
			FCPATH."assets/store/default/css/bootstrap-grid.css",
			FCPATH."assets/store/default/css/bootstrap-grid.css.map",
			FCPATH."assets/store/default/css/bootstrap-grid.min.css",
			FCPATH."assets/store/default/css/bootstrap-grid.min.css.map",
			FCPATH."assets/store/default/css/bootstrap-reboot.css",
			FCPATH."assets/store/default/css/bootstrap-reboot.css.map",
			FCPATH."assets/store/default/css/bootstrap-reboot.min.css",
			FCPATH."assets/store/default/css/bootstrap-reboot.min.css.map",
			FCPATH."assets/store/default/css/slick-theme.css",
			FCPATH."assets/store/default/js/bootstrap.bundle.min.js",
			FCPATH."assets/store/default/js/bootstrap.bundle.min.js.map",
			FCPATH."assets/store/default/js/bootstrap.bundle.js",
			FCPATH."assets/store/default/js/bootstrap.bundle.js.map",
			FCPATH."assets/store/default/js/bootstrap.js",
			FCPATH."assets/store/default/js/bootstrap.js.map",
			FCPATH."assets/store/default/js/bootstrap.min.js.map",
			FCPATH."assets/store/default/js/slick.js",
			FCPATH."assets/store/default/js/custome.js",
			FCPATH."assets/store/shared/css/sweetalert2.min.css",
			FCPATH."assets/store/shared/js/sweetalert2.all.min.js",
			FCPATH."assets/admin/css/bootstrap-grid.css",
			FCPATH."assets/admin/css/bootstrap-grid.css.map",
			FCPATH."assets/admin/css/bootstrap-grid.min.css",
			FCPATH."assets/admin/css/bootstrap-grid.min.css.map",
			FCPATH."assets/admin/css/bootstrap-reboot.css",
			FCPATH."assets/admin/css/bootstrap-reboot.css.map",
			FCPATH."assets/admin/css/bootstrap-reboot.min.css",
			FCPATH."assets/admin/css/bootstrap-reboot.min.css.map",
			FCPATH."assets/admin/css/bootstrap.css",
			FCPATH."assets/admin/css/bootstrap.css.map",
			FCPATH."assets/admin/css/bootstrap.min.css",
			FCPATH."assets/admin/css/bootstrap.min.css.map",
			FCPATH."assets/admin/fonts/stylesheet.css",
			FCPATH."assets/admin/fonts/Poppins-Black.woff",
			FCPATH."assets/admin/fonts/Poppins-Black.woff2",
			FCPATH."assets/admin/fonts/Poppins-BlackItalic.woff",
			FCPATH."assets/admin/fonts/Poppins-BlackItalic.woff2",
			FCPATH."assets/admin/fonts/Poppins-Bold.woff",
			FCPATH."assets/admin/fonts/Poppins-Bold.woff2",
			FCPATH."assets/admin/fonts/Poppins-BoldItalic.woff",
			FCPATH."assets/admin/fonts/Poppins-BoldItalic.woff2",
			FCPATH."assets/admin/fonts/Poppins-ExtraBold.woff",
			FCPATH."assets/admin/fonts/Poppins-ExtraBold.woff2",
			FCPATH."assets/admin/fonts/Poppins-ExtraBoldItalic.woff",
			FCPATH."assets/admin/fonts/Poppins-ExtraBoldItalic.woff2",
			FCPATH."assets/admin/fonts/Poppins-ExtraLight.woff",
			FCPATH."assets/admin/fonts/Poppins-ExtraLight.woff2",
			FCPATH."assets/admin/fonts/Poppins-ExtraLightItalic.woff",
			FCPATH."assets/admin/fonts/Poppins-ExtraLightItalic.woff2",
			FCPATH."assets/admin/fonts/Poppins-Italic.woff",
			FCPATH."assets/admin/fonts/Poppins-Italic.woff2",
			FCPATH."assets/admin/fonts/Poppins-Light.woff",
			FCPATH."assets/admin/fonts/Poppins-Light.woff2",
			FCPATH."assets/admin/fonts/Poppins-LightItalic.woff",
			FCPATH."assets/admin/fonts/Poppins-LightItalic.woff2",
			FCPATH."assets/admin/fonts/Poppins-Medium.woff",
			FCPATH."assets/admin/fonts/Poppins-Medium.woff2",
			FCPATH."assets/admin/fonts/Poppins-MediumItalic.woff",
			FCPATH."assets/admin/fonts/Poppins-MediumItalic.woff2",
			FCPATH."assets/admin/fonts/Poppins-Regular.woff",
			FCPATH."assets/admin/fonts/Poppins-Regular.woff2",
			FCPATH."assets/admin/fonts/Poppins-SemiBold.woff",
			FCPATH."assets/admin/fonts/Poppins-SemiBold.woff2",
			FCPATH."assets/admin/fonts/Poppins-SemiBoldItalic.woff",
			FCPATH."assets/admin/fonts/Poppins-SemiBoldItalic.woff2",
			FCPATH."assets/admin/fonts/Poppins-Thin.woff",
			FCPATH."assets/admin/fonts/Poppins-Thin.woff2",
			FCPATH."assets/admin/fonts/Poppins-ThinItalic.woff",
			FCPATH."assets/admin/fonts/Poppins-ThinItalic.woff2",
			FCPATH."assets/admin/img/bg-main.png",
			FCPATH."assets/admin/img/logo.png",
			FCPATH."assets/admin/img/password.png",
			FCPATH."assets/admin/img/user.png",
			FCPATH."assets/admin/js/bootstrap.bundle.js",
			FCPATH."assets/admin/js/bootstrap.bundle.js.map",
			FCPATH."assets/admin/js/bootstrap.bundle.min.js",
			FCPATH."assets/admin/js/bootstrap.bundle.min.js.map",
			FCPATH."assets/admin/js/bootstrap.js",
			FCPATH."assets/admin/js/bootstrap.js.map",
			FCPATH."assets/admin/js/bootstrap.min.js",
			FCPATH."assets/admin/js/bootstrap.min.js.map",
			FCPATH."assets/admin/js/jquery-3.6.3.min.map",
			FCPATH."assets/admin/js/jquery.min.js",
			FCPATH."assets/admin/js/popper.min.js",
			FCPATH."assets/api/bootstrap.min.css",
			FCPATH."assets/api/bootstrap.min.js",
			FCPATH."assets/api/favicon.png",
			FCPATH."assets/api/font-awesome.min.css",
			FCPATH."assets/api/jquery.min.js",
			FCPATH."assets/api/jquery.slimscroll.min.js",
			FCPATH."assets/api/klorofil-common.js",
			FCPATH."assets/api/main.css",
			FCPATH."assets/api/no-logo-1.jpg",
			FCPATH."assets/api/style.css",
			FCPATH."assets/api/fonts/Linearicons-Free.eot",
			FCPATH."assets/api/fonts/Linearicons-Free.svg",
			FCPATH."assets/api/fonts/Linearicons-Free.ttf",
			FCPATH."assets/api/fonts/Linearicons-Free.woff",
			FCPATH."assets/api/fonts/Linearicons-Free.woff2",
			FCPATH."assets/css/404-css.css",
			FCPATH."assets/css/admin-common.css",
			FCPATH."assets/css/all.css",
			FCPATH."assets/css/api-document-custom.css",
			FCPATH."assets/css/bootstrap.css",
			FCPATH."assets/css/bootstrap.css.map",
			FCPATH."assets/css/bootstrap.min.css",
			FCPATH."assets/css/bootstrap.min.css.map",
			FCPATH."assets/css/bootstrap4-toggle.min.css",
			FCPATH."assets/css/bootstrap-grid.css",
			FCPATH."assets/css/bootstrap-grid.css.map",
			FCPATH."assets/css/bootstrap-grid.min.css",
			FCPATH."assets/css/bootstrap-grid.min.css.map",
			FCPATH."assets/css/bootstrap-reboot.css",
			FCPATH."assets/css/bootstrap-reboot.css.map",
			FCPATH."assets/css/bootstrap-reboot.min.css",
			FCPATH."assets/css/bootstrap-reboot.min.css.map",
			FCPATH."assets/css/bootstrap-style.css",
			FCPATH."assets/css/check.css",
			FCPATH."assets/css/common.css",
			FCPATH."assets/css/dark.css",
			FCPATH."assets/css/datepicker.css",
			FCPATH."assets/css/front-dark-mode-base.css",
			FCPATH."assets/css/fullcalendar.min.css",
			FCPATH."assets/css/icons.css",
			FCPATH."assets/css/jquery.dataTables.min.css",
			FCPATH."assets/css/jquery-confirm.min.css",
			FCPATH."assets/css/jquery-ui.css",
			FCPATH."assets/css/login.css",
			FCPATH."assets/css/magnific-popup.css",
			FCPATH."assets/css/newlogin.css",
			FCPATH."assets/css/parsley.css",
			FCPATH."assets/css/pretty-print-json.css",
			FCPATH."assets/css/rtl.min.css",
			FCPATH."assets/css/wallet.css",
			FCPATH."assets/fonts/dripicons-v2.eot",
			FCPATH."assets/fonts/dripicons-v2.svg",
			FCPATH."assets/fonts/dripicons-v2.ttf",
			FCPATH."assets/fonts/dripicons-v2.woff",
			FCPATH."assets/fonts/FontAwesome.otf",
			FCPATH."assets/fonts/fontawesome-webfont.eot",
			FCPATH."assets/fonts/fontawesome-webfont.svg",
			FCPATH."assets/fonts/fontawesome-webfont.ttf",
			FCPATH."assets/fonts/fontawesome-webfont.woff",
			FCPATH."assets/fonts/fontawesome-webfont.woff2",
			FCPATH."assets/fonts/ionicons.eot",
			FCPATH."assets/fonts/ionicons.svg",
			FCPATH."assets/fonts/ionicons.ttf",
			FCPATH."assets/fonts/ionicons.woff",
			FCPATH."assets/fonts/Material-Design-Iconic-Font.ttf",
			FCPATH."assets/fonts/Material-Design-Iconic-Font.woff2",
			FCPATH."assets/fonts/materialdesignicons-webfont.eot",
			FCPATH."assets/fonts/materialdesignicons-webfont.svg",
			FCPATH."assets/fonts/materialdesignicons-webfont.ttf",
			FCPATH."assets/fonts/materialdesignicons-webfont.woff",
			FCPATH."assets/fonts/materialdesignicons-webfont.woff2",
			FCPATH."assets/fonts/Nunito-Black.ttf",
			FCPATH."assets/fonts/Nunito-BlackItalic.ttf",
			FCPATH."assets/fonts/Nunito-Bold.ttf",
			FCPATH."assets/fonts/Nunito-BoldItalic.ttf",
			FCPATH."assets/fonts/Nunito-ExtraBold.ttf",
			FCPATH."assets/fonts/Nunito-ExtraBoldItalic.ttf",
			FCPATH."assets/fonts/Nunito-ExtraLight.ttf",
			FCPATH."assets/fonts/Nunito-ExtraLightItalic.ttf",
			FCPATH."assets/fonts/Nunito-Italic.ttf",
			FCPATH."assets/fonts/Nunito-Light.ttf",
			FCPATH."assets/fonts/Nunito-LightItalic.ttf",
			FCPATH."assets/fonts/Nunito-Regular.ttf",
			FCPATH."assets/fonts/Nunito-SemiBold.ttf",
			FCPATH."assets/fonts/Nunito-SemiBoldItalic.ttf",
			FCPATH."assets/fonts/Poppins-Bold.ttf",
			FCPATH."assets/fonts/Poppins-Medium.ttf",
			FCPATH."assets/fonts/Poppins-Regular.ttf",
			FCPATH."assets/fonts/Poppins-SemiBold.ttf",
			FCPATH."assets/fonts/themify.eot",
			FCPATH."assets/fonts/themify.svg",
			FCPATH."assets/fonts/themify.ttf",
			FCPATH."assets/fonts/themify.woff",
			FCPATH."assets/fonts/typicons.eot",
			FCPATH."assets/fonts/typicons.scss",
			FCPATH."assets/fonts/typicons.svg",
			FCPATH."assets/fonts/typicons.ttf",
			FCPATH."assets/fonts/typicons.woff",
			FCPATH."assets/js/angular.min.js",
			FCPATH."assets/js/angular.tool.js",
			FCPATH."assets/js/bootstrap-datepicker.js",
			FCPATH."assets/js/bootstrap.bundle.min.js",
			FCPATH."assets/js/bootstrap.bundle.min.js.map",
			FCPATH."assets/js/bootstrap.min.js",
			FCPATH."assets/js/bootstrap.min.js.map",
			FCPATH."assets/js/bootstrap4-toggle.js",
			FCPATH."assets/js/bootstrap4-toggle.min.js",
			FCPATH."assets/js/bootstrap4-toggle.min.js.map",
			FCPATH."assets/js/bootstrap-toggle.min.js",
			FCPATH."assets/js/bootstrap-toggle.min.js.map",
			FCPATH."assets/js/countdowntime.js",
			FCPATH."assets/js/dashboard.js",
			FCPATH."assets/js/daterangepicker.js",
			FCPATH."assets/js/detect.js",
			FCPATH."assets/js/external.min.js",
			FCPATH."assets/js/fastclick.js",
			FCPATH."assets/js/fggf.js",
			FCPATH."assets/js/form-handler.js",
			FCPATH."assets/js/front-dark-mode.js",
			FCPATH."assets/js/fullcalendar.min.js",
			FCPATH."assets/js/jquery-confirm.min.css",
			FCPATH."assets/js/jquery-confirm.min.js",
			FCPATH."assets/js/jquery-ui.js",
			FCPATH."assets/js/jquery.blockUI.js",
			FCPATH."assets/js/jquery.dataTables.min.js",
			FCPATH."assets/js/jquery.min.js",
			FCPATH."assets/js/jquery.nicescroll.js",
			FCPATH."assets/js/jquery.scrollTo.min.js",
			FCPATH."assets/js/jquery.slimscroll.js",
			FCPATH."assets/js/jquery.validate.min.js",
			FCPATH."assets/js/jscolor.js",
			FCPATH."assets/js/jssocials-1.4.0/jssocials-theme-classic.css",
			FCPATH."assets/js/jssocials-1.4.0/jssocials-theme-minima.css",
			FCPATH."assets/js/jssocials-1.4.0/jssocials-theme-plain.css",
			FCPATH."assets/js/jssocials-1.4.0/jssocials.js",
			FCPATH."assets/js/libs.min.js",
			FCPATH."assets/js/main.js",
			FCPATH."assets/js/main.min.js",
			FCPATH."assets/js/modernizr.min.js",
			FCPATH."assets/js/moment.js",
			FCPATH."assets/js/moment.min.js",
			FCPATH."assets/js/popper.js",
			FCPATH."assets/js/popper.min.js",
			FCPATH."assets/js/popper.min.js.map",
			FCPATH."assets/js/pretty-print-json.js",
			FCPATH."assets/js/setting.js",
			FCPATH."assets/js/vendor.js",
			FCPATH."assets/js/waves.js",
			FCPATH."assets/js/widgetcharts.js",
			FCPATH."assets/sweetalert/sweetalert.min.js",
			FCPATH."assets/vertical/assets/css/bootstrap.min.css",
			FCPATH."assets/vertical/assets/css/bootstrap.min.css.map",
			FCPATH."assets/vertical/assets/css/icons.css",
			FCPATH."assets/vertical/assets/css/icons.css.map",
			FCPATH."assets/vertical/assets/css/style.css",
			FCPATH."assets/vertical/assets/css/style.css.map",
			FCPATH."assets/vertical/assets/css/typicons.css",
			FCPATH."assets/vertical/assets/css/typicons.css.map",
			FCPATH."assets/vertical/assets/css/checkmark.png",
			FCPATH."assets/vertical/assets/fonts/dripicons-v2.eot",
			FCPATH."assets/vertical/assets/fonts/dripicons-v2.svg",
			FCPATH."assets/vertical/assets/fonts/dripicons-v2.ttf",
			FCPATH."assets/vertical/assets/fonts/dripicons-v2.woff",
			FCPATH."assets/vertical/assets/fonts/fontawesome-webfont.eot",
			FCPATH."assets/vertical/assets/fonts/fontawesome-webfont.svg",
			FCPATH."assets/vertical/assets/fonts/fontawesome-webfont.ttf",
			FCPATH."assets/vertical/assets/fonts/fontawesome-webfont.woff",
			FCPATH."assets/vertical/assets/fonts/fontawesome-webfont.woff2",
			FCPATH."assets/vertical/assets/fonts/FontAwesome.otf",
			FCPATH."assets/vertical/assets/fonts/ionicons.eot",
			FCPATH."assets/vertical/assets/fonts/ionicons.svg",
			FCPATH."assets/vertical/assets/fonts/ionicons.ttf",
			FCPATH."assets/vertical/assets/fonts/ionicons.woff",
			FCPATH."assets/vertical/assets/fonts/materialdesignicons-webfont.eot",
			FCPATH."assets/vertical/assets/fonts/materialdesignicons-webfont.svg",
			FCPATH."assets/vertical/assets/fonts/materialdesignicons-webfont.ttf",
			FCPATH."assets/vertical/assets/fonts/materialdesignicons-webfont.woff",
			FCPATH."assets/vertical/assets/fonts/materialdesignicons-webfont.woff2",
			FCPATH."assets/vertical/assets/fonts/themify.eot",
			FCPATH."assets/vertical/assets/fonts/themify.svg",
			FCPATH."assets/vertical/assets/fonts/themify.ttf",
			FCPATH."assets/vertical/assets/fonts/themify.woff",
			FCPATH."assets/vertical/assets/fonts/typicons.eot",
			FCPATH."assets/vertical/assets/fonts/typicons.scss",
			FCPATH."assets/vertical/assets/fonts/typicons.svg",
			FCPATH."assets/vertical/assets/fonts/typicons.ttf",
			FCPATH."assets/vertical/assets/fonts/typicons.woff",
			FCPATH."assets/vertical/assets/images/bg-account.png",
			FCPATH."assets/vertical/assets/images/bg-login.png",
			FCPATH."assets/vertical/assets/images/bg-login2.jpg",
			FCPATH."assets/vertical/assets/images/favicon.ico",
			FCPATH."assets/vertical/assets/images/logo.png",
			FCPATH."assets/vertical/assets/images/no-data-2.png",
			FCPATH."assets/vertical/assets/images/no-image.jpg",
			FCPATH."assets/vertical/assets/images/no-logo-1.jpg",
			FCPATH."assets/vertical/assets/images/no-logo-coming-soon.png",
			FCPATH."assets/vertical/assets/images/NoLogo.png",
			FCPATH."assets/vertical/assets/images/no_data_found.png",
			FCPATH."assets/vertical/assets/images/no_image_yet.png",
			FCPATH."assets/vertical/assets/images/guide/c_prog_1.png",
			FCPATH."assets/vertical/assets/images/users/avatar-1.jpg",
			FCPATH."assets/vertical/assets/images/users/avatar-2.jpg",
			FCPATH."assets/vertical/assets/images/users/avatar-3.jpg",
			FCPATH."assets/vertical/assets/images/users/avatar-4.jpg",
			FCPATH."assets/vertical/assets/images/users/avatar-5.jpg",
			FCPATH."assets/vertical/assets/images/users/avatar-6.jpg",
			FCPATH."assets/webfonts/fa-brands-400.ttf",
			FCPATH."assets/webfonts/fa-brands-400.woff2",
			FCPATH."assets/webfonts/fa-regular-400.ttf",
			FCPATH."assets/webfonts/fa-regular-400.woff2",
			FCPATH."assets/webfonts/fa-solid-900.ttf",
			FCPATH."assets/webfonts/fa-solid-900.woff2",
			FCPATH."assets/webfonts/fa-v4compatibility.ttf",
			FCPATH."assets/webfonts/fa-v4compatibility.woff2",
		];

		foreach($deprecated_files as $file) {
			if(is_file($file)) {
				if(unlink($file)) {
					$data['results'][] = ["success" => $file." deleted successfully"];
					$deleted_count++;
				} else {
					$data['results'][] = ["error" => "Failed to delete: ".$file];
				}
			} else {
				$data['results'][] = ["info" => "File not found (already deleted): ".$file];
			}
		}
		
		// Add summary
		$data['results'][] = ["summary" => "Total files deleted: ".$deleted_count." out of ".count($deprecated_files)." files"];
		
	} catch (Exception $e) {
		$data['results'][] = [
			"error" => $e->getMessage()
		];
	}

	return $data['results'];
}

public function remove_deprecated_directory(){
    $results = [];
    $removed_count = 0;
    $total_count = 0;
    
    try {
        $deprecated_directories = [
            FCPATH."application/core/paytm",
            FCPATH."application/core/Razorpay",
            FCPATH."application/core/stripe",
            FCPATH."application/core/xendit",
            FCPATH."application/core/yandex",
            FCPATH."application/core/doctorin",
            FCPATH."application/libraries/paypal",
            FCPATH."application/deposit_payments",
            FCPATH."application/membership_payment",
            FCPATH."application/payments",
            FCPATH."application/third_party/src",
            FCPATH."assets/images/payments",
            FCPATH."assets/login/login",
            FCPATH."assets/login/login/css",
            FCPATH."assets/login/login/js",
            FCPATH."assets/login/login/fonts",
            FCPATH."assets/login/css",
            FCPATH."assets/login/js",
            FCPATH."assets/login/fonts",
            FCPATH."application/core/excel/Classes",
            FCPATH."assets/vertical/assets/plugins/alertify/",
            FCPATH."assets/vertical/assets/plugins/bootstrap-datepicker",
            FCPATH."assets/vertical/assets/plugins/bootstrap-colorpicker",
            FCPATH."assets/vertical/assets/plugins/bootstrap-inputmask",
            FCPATH."assets/vertical/assets/plugins/bootstrap-maxlength",
            FCPATH."assets/vertical/assets/plugins/bootstrap-rating",
            FCPATH."assets/vertical/assets/plugins/bootstrap-session-timeout",
            FCPATH."assets/vertical/assets/plugins/bootstrap-touchspin",
            FCPATH."assets/vertical/assets/plugins/c3",
            FCPATH."assets/vertical/assets/plugins/colorpicker",
            FCPATH."assets/vertical/assets/plugins/dropify",
            FCPATH."assets/vertical/assets/plugins/animate",
            FCPATH."assets/vertical/assets/plugins/d3",
            FCPATH."assets/vertical/assets/plugins/chart.js",
            FCPATH."assets/vertical/assets/plugins/datatables",
            FCPATH."assets/vertical/assets/plugins/flot-chart",
            FCPATH."assets/vertical/assets/plugins/fullcalendar",
            FCPATH."assets/vertical/assets/plugins/gmaps",
            FCPATH."assets/vertical/assets/plugins/ion-rangeslider",
            FCPATH."assets/vertical/assets/plugins/jquery-confirm",
            FCPATH."assets/vertical/assets/plugins/jquery-sparkline",
            FCPATH."assets/vertical/assets/plugins/jquery-ui",
            FCPATH."assets/vertical/assets/plugins/moment",
            FCPATH."assets/vertical/assets/plugins/nestable",
            FCPATH."assets/vertical/assets/plugins/parsleyjs",
            FCPATH."assets/vertical/assets/plugins/prism",
            FCPATH."assets/vertical/assets/plugins/select2",
            FCPATH."assets/vertical/assets/plugins/summernote",
            FCPATH."assets/vertical/assets/plugins/sweet-alert2",
            FCPATH."assets/vertical/assets/plugins/tabledit",
            FCPATH."assets/vertical/assets/plugins/timepicker",
            FCPATH."assets/vertical/assets/plugins/tiny-editable",
            FCPATH."assets/vertical/assets/plugins/videoslider",
            FCPATH."assets/vertical/assets/plugins/x-editable",
            FCPATH."assets/vertical/assets/images/widgets",
            FCPATH."assets/vertical/assets/images/small",
            FCPATH."assets/vertical/assets/images/colorpicker",
            FCPATH."assets/vertical/assets/plugins/chartist/",
            FCPATH."assets/plugins/toastr",
            FCPATH."assets/plugins/magnific-popup",
            FCPATH."assets/plugins/gojs",
            FCPATH."assets/plugins/images",
            FCPATH."assets/plugins/productpage",
            FCPATH."assets/plugins/roboto",
            FCPATH."application/views/admincontrol/pagebuilder",
            FCPATH."application/views/admincontrol/template_editor",
            FCPATH."assets/vertical/assets/plugins/jquery-knob",
            FCPATH."assets/vertical/assets/plugins/jvectormap",
            FCPATH."assets/vertical/assets/plugins/magnific-popup",
            FCPATH."assets/vertical/assets/plugins/morris",
            FCPATH."assets/vertical/assets/plugins/raphael",
            FCPATH."assets/vertical/assets/plugins/RWD-Table-Patterns",
            FCPATH."assets/vertical/assets/plugins/skycons",
            FCPATH."assets/vertical/assets/plugins/clockpicker",
            FCPATH."assets/vertical/assets/plugins/dropzone",
            FCPATH."assets/vertical/assets/plugins",
            FCPATH."application/views/usercontrol/login/login",
            FCPATH."assets/document_css",
            FCPATH."assets/document_img",
            FCPATH."assets/document_scripts",
            FCPATH."assets/document_vendor",
            FCPATH."assets/login/index10/js",
            FCPATH."assets/js/summernote-0.8.12-dist",
            FCPATH."assets/login/index3/js",
            FCPATH."assets/plugins/color-picker",
            FCPATH."application/views/auth/user/assets/img",
            FCPATH."application/errors",
            FCPATH."application/views/admincontrol/setting/steps",
            FCPATH."application/views/firstsetting",
            FCPATH."assets/plugins/jvectormap",
            FCPATH."assets/plugins/RWD-Table-Patterns",
            FCPATH."assets/plugins/jquery-knob",
            FCPATH."assets/plugins/chartist",
            FCPATH."assets/plugins/store/ckeditor",
            FCPATH."assets/plugins/tree/vendor",
            FCPATH."assets/store/default/fonts",
            FCPATH."assets/store/default/fontawesome",
            FCPATH."assets/store/lms/js",
            FCPATH."assets/template/store",
            FCPATH."assets/admin",
            FCPATH."assets/api",
            FCPATH."assets/css",
            FCPATH."assets/fonts",
            FCPATH."assets/js",
            FCPATH."assets/sweetalert",
            FCPATH."assets/vertical",
            FCPATH."assets/webfonts",
        ];

        $total_count = count($deprecated_directories);

        foreach($deprecated_directories as $deprecated_directory){
            if(is_dir($deprecated_directory)) {
                try {
                    $result = self::remove_deprecated_directory_folder_and_files($deprecated_directory);
                    
                    // Double-check if directory was actually removed
                    if(!is_dir($deprecated_directory)) {
                        $results[] = ["success" => $deprecated_directory . " removed successfully"];
                        $removed_count++;
                    } else {
                        $results[] = ["error" => "Failed to remove " . $deprecated_directory];
                    }
                } catch (Exception $e) {
                    $results[] = ["error" => "Failed to remove " . $deprecated_directory . " - " . $e->getMessage()];
                }
            } else {
                $results[] = ["info" => $deprecated_directory . " does not exist (already removed)"];
            }
        }
        
        // Add summary
        $results[] = ["summary" => "Total directories removed: " . $removed_count . " out of " . $total_count . " directories"];
        
    } catch (\Exception $e) {
        $results[] = ["error" => "General error: " . $e->getMessage()];
    }
    
    return $results;
}

private function remove_deprecated_directory_folder_and_files($deprecated_directory){
    // Validate input
    if (!is_dir($deprecated_directory)) {
        return false;
    }
    
    // Normalize path - use DIRECTORY_SEPARATOR for cross-platform compatibility
    $deprecated_directory = rtrim($deprecated_directory, DIRECTORY_SEPARATOR) . DIRECTORY_SEPARATOR;
    
    try {
        // Get all files and directories, including hidden ones
        $files = glob($deprecated_directory . '{,.}*', GLOB_MARK | GLOB_BRACE);
        
        // Filter out . and .. entries
        $files = array_filter($files, function($file) {
            $basename = basename($file);
            return $basename !== '.' && $basename !== '..';
        });
        
        foreach($files as $file){
            if(is_dir($file)) {
                // Recursively remove subdirectory
                if (!self::remove_deprecated_directory_folder_and_files($file)) {
                    return false;
                }
            } else {
                // Remove file with proper error checking
                if (is_file($file)) {
                    // Check if file is writable before attempting to delete
                    if (!is_writable($file)) {
                        // Try to make it writable
                        chmod($file, 0644);
                    }
                    
                    if (!unlink($file)) {
                        error_log("Failed to delete file: " . $file);
                        return false;
                    }
                }
            }
        }
        
        // Remove the directory itself
        // Check if directory is writable
        if (!is_writable($deprecated_directory)) {
            chmod($deprecated_directory, 0755);
        }
        
        if (!rmdir($deprecated_directory)) {
            error_log("Failed to remove directory: " . $deprecated_directory);
            return false;
        }
        
        return true;
        
    } catch (Exception $e) {
        error_log("Error removing directory " . $deprecated_directory . ": " . $e->getMessage());
        return false;
    }
}


private function remove_deprecated_directory_folder_and_files_alternative($deprecated_directory){
    if (!is_dir($deprecated_directory)) {
        return false;
    }
    
    try {
        $iterator = new RecursiveIteratorIterator(
            new RecursiveDirectoryIterator($deprecated_directory, RecursiveDirectoryIterator::SKIP_DOTS),
            RecursiveIteratorIterator::CHILD_FIRST
        );
        
        foreach ($iterator as $file) {
            if ($file->isDir()) {
                if (!rmdir($file->getRealPath())) {
                    error_log("Failed to remove directory: " . $file->getRealPath());
                    return false;
                }
            } else {
                if (!unlink($file->getRealPath())) {
                    error_log("Failed to delete file: " . $file->getRealPath());
                    return false;
                }
            }
        }
        
        // Remove the main directory
        if (!rmdir($deprecated_directory)) {
            error_log("Failed to remove main directory: " . $deprecated_directory);
            return false;
        }
        
        return true;
        
    } catch (Exception $e) {
        error_log("Error removing directory " . $deprecated_directory . ": " . $e->getMessage());
        return false;
    }
}

	public function drop_deprecated_table() {
	    $this->db->trans_start();  // Start transaction

	    try {
	        if($this->db->table_exists('bt_custom_field')) {
	            $this->db->query("DROP TABLE `bt_custom_field`");
	        }

	        if($this->db->table_exists('bt_custom_field_status')) {
	            $existingData = $this->db->select('response, response_validate')->get('bt_custom_field_status')->row();

	            $this->updateOrInsertSetting('withdrawalpayment_bank_transfer', 'bt_custom_field', $existingData->response);
	            $this->updateOrInsertSetting('withdrawalpayment_bank_transfer', 'response_validate', $existingData->response_validate);

	            $this->db->query("DROP TABLE `bt_custom_field_status`");
	        }

	        //To drop another table
	        if($this->db->table_exists('your_table_name')) {
			    $this->db->query("DROP TABLE `your_table_name`");
			}

	        $data['results'][] = ["success" => 'deprecated table dropped successfully'];
			    } catch (\CI_DB_exception $e) {
			        $data['results'][] = ["error" => $e->getMessage()];
			    }

			    $this->db->trans_complete();  // Complete the transaction

			    if ($this->db->trans_status() === FALSE)
			    {
			        $data['results'][] = ["error" => 'Transaction failed'];
			    }

			    return $data['results'];
		}

	
	private function updateOrInsertSetting($type, $key, $value) {
	    // Validate parameters
	    if (!is_string($type) || !is_string($key)) {
	        throw new InvalidArgumentException('Type and key must be strings.');
	    }

	    $fieldsData = [
	        'setting_type' => $type,
	        'setting_key' => $key,
	        'setting_value' => $value,
	        'setting_status' => 1,
	        'setting_ipaddress' => '::1',
	        'setting_is_default' => 0
	    ];

	    $this->db->where(['setting_type' => $type, 'setting_key' => $key]);

	    if ($this->db->count_all_results('setting') > 0) {
	        // Update
	        if ($this->db->update('setting', $fieldsData)) {
	            return true;
	        } else {
	            return false; // Update failed
	        }
	    } else {
	        // Insert
	        if ($this->db->insert('setting', $fieldsData)) {
	            return true;
	        } else {
	            return false; // Insert failed
	        }
	    }
	}

	public function update_mail_templates() {
		try {
			
	    	$newMailTemplates = [['subscription_status_change', 'Subscription Status Changed', 'Subscription Status Changed', '<p>Dear [[firstname]],</p>\r\n                <p>Your subscription status has been changed to [[status_text]]</p>\r\n                <p>Comment: [[comment]] </p>\r\n                [[website_name]]<br />\r\n                Support Team</p>', '', NULL, NULL, '', 'comment,planname,price,expire_at,started_at,status_text,firstname,lastname,email,username,website_url,website_name,website_logo,name'], ['subscription_buy', 'Subscription Buy', 'Subscription Buy', '<h2>Thanks for your order</h2>\r\n\r\n<p>Welcome to Prime. As a Prime member, enjoy these great benefits. If you have any questions, call us any time at or simply reply to this email.</p>\r\n', 'New Subscription Buy From [[firstname]] [[lastname]]', NULL, NULL, '<h2>Thanks for your order</h2>\r\n\r\n<p>Welcome to Prime. As a Prime member, enjoy these great benefits. If you have any questions, call us any time at or simply reply to this email.</p>\r\n', 'planname,price,expire_at,started_at,firstname,lastname,email,username,website_url,website_name,website_logo,name'], ['subscription_expire_notification', 'Subscription Expire Notification', 'Your Subscription Will Be Expired Soon.', '<p>Dear [[firstname]],</p>\r\n<p>Your subscription for plan <strong>[[planname]]</strong> will expire soon.</p>\r\n<p>Expiry Date: [[expire_at]]</p>\r\n<p>Please renew to continue enjoying our services.</p>\r\n<p><br />[[website_name]]<br />Support Team</p>', NULL, NULL, NULL, NULL, 'planname,price,expire_at,started_at,firstname,lastname,email,username,website_url,website_name,website_logo,name'], ['wallet_noti_on_hold_wallet', 'Wallet Status Change To On Hold', '[[amount]] is put on hold in your wallet', '<p>Dear [[name]],</p>\n        <p>Transactions #[[id]] status changed to [[new_status]]. amount is [[amount]]</p>\n        <p><br />\n        [[website_name]]<br />\n        Support Team</p>\n', '', NULL, NULL, NULL, 'amount,id,name,new_status,user_email,website_name,website_logo,name'], ['new_user_request', 'New User Request', 'User Registration Successfull', '<p>Dear [[firstname]] [[lastname]],</p>\r\n\r\n<p>User account has been registered successfully on [[website_name]], please wait while system admin apporve&nbsp;your request.<br />\r\nWe will inform you once account has been approved, Thank You.</p>\r\n\r\n<p>Support Team<br />\r\n[[website_name]]</p>\r\n', 'New User Registration - Approval Pending', NULL, NULL, '<p>Dear Admin,</p>\r\n\r\n<p>New user has been registered on [[website_name]], apporval is pending yet!</p>\r\n\r\n<p>User Details</p>\r\n\r\n<p>Name : [[firstname]][[lastname]]<br />\r\nEmail :&nbsp;[[email]]<br />\r\nUsername : [[username]]<br />\r\nSupport Team<br />\r\n[[website_name]]</p>', 'firstname,lastname,email,username,website_name,website_logo'], ['new_user_approved', 'New User Request Approved', 'User Account Approved', '<p>Dear [[firstname]] [[lastname]],</p>\r\n\r\n<p>Your new user account registration request is accepted by admin, you can login and use services.</p>\r\n\r\n<p>[[website_name]]<br />\r\nSupport Team</p>\r\n', 'User Account Approved', NULL, NULL, '<p>Dear Admin,</p>\r\n\r\n<p>You have approced registration request of user having</p>\r\n\r\n<p>Name : [[firstname]]&nbsp;[[lastname]]</p>\r\n\r\n<p>Email : [[email]]</p>\r\n\r\n<p>Username : [[username]]</p>\r\n\r\n<p>&nbsp;</p>\r\n\r\n<p>Support Team</p>\r\n\r\n<p>[[website_name]]</p>\r\n', 'firstname,lastname,email,username,website_name,website_logo'], ['new_user_declined', 'New User Request Declined', 'User Account Declined', '<p>Dear [[firstname]] [[lastname]],</p>\r\n\r\n<p>Your new user account registration request is declined by admin, for more information please contact supprt team</p>\r\n\r\n<p>[[website_name]]<br />\r\nSupport Team</p>\r\n', 'User Account Declined', NULL, NULL, '<p>Dear Admin,</p>\r\n\r\n<p>You have declined registration request of user having</p>\r\n\r\n<p>Name : [[firstname]]&nbsp;[[lastname]]</p>\r\n\r\n<p>Email : [[email]]</p>\r\n\r\n<p>Username : [[username]]</p>\r\n\r\n<p>&nbsp;</p>\r\n\r\n<p>Support Team</p>\r\n\r\n<p>[[website_name]]</p>\r\n', 'firstname,lastname,email,username,website_name,website_logo'], ['new_vendor_deposit_request', 'New Vendor Deposit Request', 'New Deposit Request Added', '<p>Dear [[firstname]] [[lastname]],</p>\r\n\r\n<p>Your deposit request of amount [[amount]] is added, if your balance not updated please contact support team</p>\r\n\r\n<p>[[website_name]]<br /> \r\n Support Team</p>', 'New Deposit Request Added', NULL, NULL, '<p>Dear Admin,</p>\r\n\r\n<p>You have new deposit request of amount [[amount]] from vendor having</p>\r\n\r\n<p>Name : [[firstname]]&nbsp;[[lastname]]</p>\r\n\r\n<p>Email : [[email]]</p>\r\n\r\n<p>Username : [[username]]</p>\r\n\r\n<p>&nbsp;</p>\r\n\r\n<p>Support Team</p>\r\n\r\n<p>[[website_name]]</p>', 'status,amount,firstname,lastname,email,username,website_name,website_logo'], ['vendor_deposit_request_updated', 'Deposit Request Updated', 'Deposit Request Updated', '<p>Dear [[firstname]] [[lastname]],</p>\r\n\r\n<p>Your deposit request of amount [[amount]] is updated to [[status]], if have any queries please contact support team</p>\r\n\r\n<p>[[website_name]]<br /> \r\n Support Team</p>', 'Deposit Request Updated', NULL, NULL, '<p>Dear Admin,</p>\r\n\r\n<p>You have changed status of deposit request to [[status]] from vendor having</p>\r\n\r\n<p>Name : [[firstname]]&nbsp;[[lastname]]</p>\r\n\r\n<p>Email : [[email]]</p>\r\n\r\n<p>Username : [[username]]</p>\r\n\r\n<p>&nbsp;</p>\r\n\r\n<p>Support Team</p>\r\n\r\n<p>[[website_name]]</p>', 'status,amount,firstname,lastname,email,username,website_name,website_logo'],['user_level_changed', 'Change user level', 'Your user level changed', '<p>Dear,</p><p>Your level changed from [[from_level]] to [[to_level]]</p>                     <p><br>                 [[website_name]]<br>                 Support Team</p>             ', NULL, NULL, NULL, NULL, 'from_level,to_level,website_name']];


	    	// tickets notification templates
	    	$newMailTemplates[] = ['ticket_created_email', 'Ticket Created Email', 'New ticket #[[ticket_id]] has been created', '<p>Dear [[firstname]],&nbsp;</p><p><br></p><p>Your ticket has been created successfully on the system. Please note down below the ticket number for future reference.</p><p><br></p><p>Ticket ID:</p><p><span style="font-size: 1rem;">[[ticket_id]]</span><br></p><p><br></p><p><span style="font-size: 1rem;">Ticket Status:</span><br></p><p><span style="font-size: 1rem;">[[ticket_status]]</span><br></p><p><br></p><p><br></p><p>Subject :</p><p><span style="font-size: 1rem;">[[ticket_subject]]</span><br></p><p><br></p><p><br></p><p>Message:</p><p><span style="font-size: 1rem;">[[ticket_body]]</span><br></p><p><br></p><p><span style="font-size: 1rem;">We will contact you very soon.</span><br></p><p><br></p><p><span style="font-size: 1rem;">Thank You</span><br></p><p><span style="font-size: 1rem;">Support Team</span><br></p>', 'New user ticket #[[ticket_id]] has been created', NULL, NULL, '<p>Dear Admin, </p><p><br></p><p>The user has created a new ticket on your site [[website_name]]. <br></p><p><br></p><p>Username:</p><p><span style="font-size: 1rem;">[[username]]</span><br></p><p><br></p><p>Email:</p><p><span style="font-size: 1rem;">[[email]]</span><br></p><p><br></p><p>Name:</p><p><span style="font-size: 1rem;">[[firstname]] [[lastname]]</span><br></p><p><br></p><p>Ticket ID:</p><p><span style="font-size: 1rem;">[[ticket_id]]</span><br></p><p><br></p><p>Ticket Status:</p><p><span style="font-size: 1rem;">[[ticket_status]]</span><br></p><p><br></p><p><span style="font-size: 1rem;">Subject :</span><br></p><p><span style="font-size: 1rem;">[[ticket_subject]]</span><br></p><p><br></p><p><br></p><p>Message:</p><p><span style="font-size: 1rem;">[[ticket_body]]</span><br></p><p><br></p><p><br></p><p>Thank You</p><p><span style="font-size: 1rem;">[[website_name]]</span><br></p><p><br></p>',  'ticket_id,ticket_status,ticket_subject,ticket_body,ticket_datetime,firstname,lastname,email,username,website_name,website_logo'];

	    	$newMailTemplates[] = ['ticket_reply_email', 'Ticket Replied Email', 'You have a new reply on ticket #[[ticket_id]]', '<p>Dear [[firstname]], </p><p><br></p><p>You have a reply from the support team on your ticket #[[ticket_id]]</p><p><br></p><p>Ticket ID:</p><p><span style="font-size: 1rem;">[[ticket_id]]</span><br></p><p><br></p><p><span style="font-size: 1rem;">Ticket Status:</span><br></p><p><span style="font-size: 1rem;">[[ticket_status]]</span><br></p><p><br></p><p><span style="font-size: 1rem;">Subject :</span><br></p><p><span style="font-size: 1rem;">[[ticket_subject]]</span><br></p><p><br></p><p>Message from the support team<br></p><p><span style="font-size: 1rem;">[[ticket_reply_message]]</span></p><p><span style="font-size: 1rem;"><br></span></p><p><span style="font-size: 1rem;">Time</span></p><p><span style="font-size: 1rem;">[[reply_datetime]]</span></p><p><span style="font-size: 1rem;"><br></span></p><p><span style="font-size: 1rem;">Thank You</span><br></p>', 'User added a new reply on ticket #[[ticket_id]]', NULL, NULL, '<p>Dear Admin, </p><p><br></p><p>User added a new reply on ticket #[[ticket_id]]</p><p><br></p><p>Username:</p><p><span style="font-size: 1rem;">[[username]]</span><br></p><p><br></p><p>Email:</p><p><span style="font-size: 1rem;">[[email]]</span><br></p><p><br></p><p>Name:</p><p><span style="font-size: 1rem;">[[firstname]] [[lastname]]</span></p><p><br></p><p>Ticket ID:</p><p><span style="font-size: 1rem;">[[ticket_id]]</span><br></p><p><br></p><p><span style="font-size: 1rem;">Ticket Status:</span><br></p><p><span style="font-size: 1rem;">[[ticket_status]]</span><br></p><p><br></p><p><span style="font-size: 1rem;">Subject :</span><br></p><p><span style="font-size: 1rem;">[[ticket_subject]]</span><br></p><p><br></p><p>Message from user<br></p><p><span style="font-size: 1rem;">[[ticket_reply_message]]</span></p><p><span style="font-size: 1rem;"><br></span></p><p><span style="font-size: 1rem;">Time</span></p><p><span style="font-size: 1rem;">[[reply_datetime]]</span></p><p><span style="font-size: 1rem;"><br></span></p><p><span style="font-size: 1rem;">Thank You</span></p>',   'ticket_id,ticket_status,ticket_subject,ticket_body,ticket_reply_message,reply_datetime,firstname,lastname,email,username,website_name,website_logo'];

	    	$newMailTemplates[] = ['ticket_status_email', 'Ticket Status Change Email', 'Ticket #[[ticket_id]] status has been updated', '<p>Dear [[firstname]],&nbsp;</p><p><br></p><p>The status of a ticket having id [[ticket_id]] has been updated, please log in to [[website_name]] to see full details of the ticket.</p><p><br></p><p>Ticket ID:</p><p><span style="font-size: 1rem;">[[ticket_id]]</span><br></p><p><br></p><p><span style="font-size: 1rem;">Ticket Status:</span><br></p><p><span style="font-size: 1rem;">[[ticket_status]]</span><br></p><p><br></p><p><span style="font-size: 1rem;">Subject :</span><br></p><p><span style="font-size: 1rem;">[[ticket_subject]]</span><br></p><p><br></p><p><span style="font-size: 1rem;">Thank You</span></p><p><span style="font-size: 1rem;">Support Team<br></span><br></p>',  'Ticket #[[ticket_id]] status has been updated', NULL, NULL, '<p>Dear Admin,&nbsp;</p><p><br></p><p>The status of the ticket having id [[ticket_id]] has been updated.</p><p><br></p><p>Username:</p><p><span style="font-size: 1rem;">[[username]]</span><br></p><p><br></p><p>Email:</p><p><span style="font-size: 1rem;">[[email]]</span><br></p><p><br></p><p>Name:</p><p><span style="font-size: 1rem;">[[firstname]] [[lastname]]</span></p><p><span style="font-size: 1rem;"><br></span></p><p>Ticket ID:</p><p><span style="font-size: 1rem;">[[ticket_id]]</span><br></p><p><br></p><p><span style="font-size: 1rem;">Ticket Status:</span><br></p><p><span style="font-size: 1rem;">[[ticket_status]]</span><br></p><p><br></p><p><span style="font-size: 1rem;">Subject :</span><br></p><p><span style="font-size: 1rem;">[[ticket_subject]]</span><br></p><p><br></p><p><span style="font-size: 1rem;">Thank You</span></p><p><span style="font-size: 1rem;">Support Team<br></span></p>', 'ticket_id,ticket_status,ticket_subject,ticket_body,firstname,lastname,email,username,website_name,website_logo'];


	    	for ($i=0; $i < sizeof($newMailTemplates); $i++) { 
	    		$this->db->query("INSERT INTO `mail_templates` (`unique_id`, `name`, `subject`, `text`, `admin_subject`, `client_subject`, `client_text`, `admin_text`, `shortcode`) SELECT * FROM (SELECT '".$newMailTemplates[$i][0]."' AS `unique_id`, '".$newMailTemplates[$i][1]."' AS `name`, '".$newMailTemplates[$i][2]."' AS `subject`, '".$newMailTemplates[$i][3]."' AS `text`, '".$newMailTemplates[$i][4]."' AS `admin_subject`, '".$newMailTemplates[$i][5]."' AS `client_subject`, '".$newMailTemplates[$i][6]."' AS `client_text`, '".$newMailTemplates[$i][7]."' AS `admin_text`,'".$newMailTemplates[$i][8]."' AS `shortcode`) AS tmp WHERE NOT EXISTS ( SELECT `unique_id` FROM `mail_templates` WHERE `unique_id` = '".$newMailTemplates[$i][0]."' ) LIMIT 1;");
	    	}

	    	$data['results'][] = ["info"=>"mail templates updated..."];
    	} catch (Exception $e) {
			$data['results'][] = [
				"error" => $e->getMessage()
			];
		}

		return $data['results'];
    }

	/**
	 * Migrate old license system to License Easy
	 * 
	 * This runs once after updating from old version to new License Easy version.
	 * Checks if license needs migration and creates necessary files.
	 * 
	 * @return array Results array for logging
	 */
	private function migrate_license_to_easy() {
		$resultArray = [["info" => "License Easy migration check started..."]];

		try {
		// Check if already fully migrated (both cache and config exist)
		$license_cache = APPPATH . 'license-easy-data-affiliateporsaas.json';
		$config_has_license = $this->config->item('codecanyon_license');

		if (file_exists($license_cache) && !empty($config_has_license)) {
			$resultArray[] = ["success" => "License already migrated to License Easy. System is ready."];
			return $resultArray;
		}
		
		$resultArray[] = ["info" => "Starting license migration process..."];

		// Try to find license key from multiple sources
		$old_license_key = null;
		
		// 1. Try from JSON cache first (if already migrated)
		if (file_exists($license_cache)) {
			$cache_content = file_get_contents($license_cache);
			$cache_data = json_decode($cache_content, true);
			if ($cache_data && isset($cache_data['license_key']) && !empty($cache_data['license_key'])) {
				$old_license_key = $cache_data['license_key'];
				$resultArray[] = ["success" => "Found license key in JSON cache"];
			}
		}
		
		// 2. Try install/version.php (for old versions)
		if (empty($old_license_key)) {
			$old_version_file = FCPATH . 'install/version.php';
			if (file_exists($old_version_file)) {
				$version_content = file_get_contents($old_version_file);
				if (preg_match("/define\s*\(\s*['\"]CODECANYON_LICENCE['\"]\s*,\s*['\"]([^'\"]+)['\"]\s*\)/", $version_content, $matches)) {
					$old_license_key = $matches[1];
					$resultArray[] = ["success" => "Found license key in install/version.php"];
				}
			}
		}
		
		// 3. Try from config
		if (empty($old_license_key)) {
			$old_license_key = $this->config->item('codecanyon_license');
			if (!empty($old_license_key)) {
				$resultArray[] = ["info" => "Found license key in config"];
			}
		}
		
		// 4. Try from constants
		if (empty($old_license_key) && defined('CODECANYON_LICENCE')) {
			$old_license_key = CODECANYON_LICENCE;
			if (!empty($old_license_key)) {
				$resultArray[] = ["info" => "Found license key in constants"];
			}
		}

		if (empty($old_license_key)) {
			$debug_info = [
				'cache_exists' => file_exists($license_cache) ? 'yes' : 'no',
				'version_file_exists' => file_exists(FCPATH . 'install/version.php') ? 'yes' : 'no',
				'config_has_license' => !empty($this->config->item('codecanyon_license')) ? 'yes' : 'no',
				'constant_defined' => defined('CODECANYON_LICENCE') ? 'yes' : 'no'
			];
			$resultArray[] = ["warning" => "No license key found in any source. Debug: " . json_encode($debug_info)];
			return $resultArray;
		}

			// Load License Easy client
			$client_file = APPPATH . 'license-easy-universal-client.php';
			if (!file_exists($client_file)) {
				$resultArray[] = ["error" => "License Easy client file not found!"];
				return $resultArray;
			}

		require_once $client_file;

		// Initialize License Easy client
		if (!isset($GLOBALS['aff_license'])) {
			$GLOBALS['aff_license'] = new License_Easy_Universal_Client([
				'product_slug' => 'affiliateporsaas',
				'api_url' => 'https://affiliatepro.org/index.php?rest_route=/license-easy/v1/',
				'product_name' => 'AffiliatePorSaaS'
			]);
		}

			// Get current domain
			$domain = $this->config->item('base_url');
			if (empty($domain)) {
				$protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ? 'https' : 'http';
				$host = $_SERVER['HTTP_HOST'] ?? 'localhost';
				$script_path = rtrim(dirname($_SERVER['SCRIPT_NAME']), '/');
				$domain = $protocol . '://' . $host . $script_path . '/';
			}

		// Try to activate/reactivate with License Easy
		$resultArray[] = ["info" => "Attempting to reactivate license with License Easy..."];
		
		$activation_result = $GLOBALS['aff_license']->activate_license($old_license_key, $domain);

		if ($activation_result['success']) {
			$resultArray[] = ["success" => "License successfully activated on License Easy server!"];
		} else {
			$error_msg = isset($activation_result['message']) ? $activation_result['message'] : 'Unknown error';
			$resultArray[] = ["warning" => "License Easy API response: " . $error_msg];
			$resultArray[] = ["info" => "Continuing with offline migration..."];
		}

		// ALWAYS write license keys to config.php (even if API call failed)
		$this->load->helper('config_writer');
		update_config_option('codecanyon_license', $old_license_key);
		update_config_option('license_easy_key', $old_license_key);
		$resultArray[] = ["success" => "License keys written to config.php"];

		// ALWAYS create JSON cache file (even if API call failed)
		if (!file_exists($license_cache)) {
			$basic_license_data = [
				'license_key' => $old_license_key,
				'domain' => $domain,
				'activated_at' => date('Y-m-d H:i:s'),
				'status' => 'active',
				'migrated_from_old_system' => true
			];
			file_put_contents($license_cache, json_encode($basic_license_data, JSON_PRETTY_PRINT));
			$resultArray[] = ["success" => "License cache file created: " . $license_cache];
		}

		$resultArray[] = ["success" => "Migration completed! License Easy integration is active."];

		} catch (Exception $e) {
			$resultArray[] = ["error" => "Migration exception: " . $e->getMessage()];
		}

		return $resultArray;
	}

	/**
	 * Splits the provided string based on ';' character and returns an array.
	 * 
	 * The function skips the second element of the split array, and instead appends
	 * it to the first element.
	 *
	 * @param string $weapon The string to be split.
	 * @return array|null The array with the split elements, or null if the input string is empty.
	 * @throws InvalidArgumentException if the string is empty.
	 */
	public function explodeSkipOne(string $weapon) {
	    if (!$weapon) {
	        throw new InvalidArgumentException('Input string cannot be empty.');
	    }

	    $spiltthum = explode(';', $weapon);
	    $ThuImg = [];
	    $arraySize = sizeof($spiltthum);
	    
	    for ($i = 0; $i < $arraySize; $i++){
	        if($i == 1) {
	            $ThuImg[0] .= $spiltthum[$i];
	        } else {
	            $ThuImg[] = $spiltthum[$i];
	        }
	    }
	    
	    return $ThuImg;
	}


	/**
	 * Store version update details.
	 *
	 * This function inserts a new record into the 'version_update' table
	 * with the current application version and license code.
	 *
	 * @return void
	 */
	public function store_version_update_details()
	{
	    $data = [
	        'code' => CODECANYON_LICENCE,
	        'script_version' => $this->config->item('app_version')
	    ];

	    // Insert the data into the table
	    if (!$this->db->insert('version_update', $data)) {
	        // Handle error, e.g., log the error message, throw an exception, etc.
	        log_message('error', 'Failed to store version update details.');
	    }
	}

	/**
	 * Update front-end Multiple Pages theme colors to the new blue/purple palette.
	 * Replaces old orange defaults with the redesigned color scheme.
	 */
	private function update_front_theme_colors() {
		$data['results'] = [];

		$color_map = [
			'front_header_color_before_scroll' => 'transparent',
			'front_header_button_color_before_scroll' => '#4361ee',
			'front_header_button_text_color_before_scroll' => '#ffffff',
			'front_header_button_hover_color_before_scroll' => '#3a56d4',
			'front_header_color_after_scroll' => '#ffffff',
			'front_header_button_color_after_scroll' => '#4361ee',
			'front_header_button_text_color_after_scroll' => '#ffffff',
			'front_header_button_hover_color_after_scroll' => '#f72585',
			'front_button_color' => '#4361ee',
			'front_button_hover_color' => '#3a56d4',
			'front_button_text_color' => '#ffffff',
			'front_runner_bar_color' => '#4361ee',
			'front_runner_bar_text_color' => '#ffffff',
			'front_theme_text_color' => '#4361ee',
			'front_faq_before_hover_color' => '#f8fafc',
			'front_faq_after_hover_color' => '#4361ee',
			'front_footer_color' => '#0f172a',
			'bottom_banner_before_footer' => '#4361ee',
			'header_menu_bg_color_responsive' => '#0f172a'
		];

		try {
			foreach ($color_map as $key => $value) {
				$this->db->where('setting_key', $key);
				$this->db->where('setting_type', 'theme');
				$q = $this->db->get('theme_colors');

				if ($q->num_rows() > 0) {
					$this->db->where('setting_key', $key);
					$this->db->where('setting_type', 'theme');
					$this->db->update('theme_colors', ['setting_value' => $value]);
				} else {
					$this->db->insert('theme_colors', [
						'setting_value' => $value,
						'setting_key' => $key,
						'setting_status' => 1,
						'setting_is_default' => 0,
						'setting_type' => 'theme',
						'setting_ipaddress' => $_SERVER['REMOTE_ADDR'] ?? '127.0.0.1',
					]);
				}

				$data['results'][] = ["success" => "front theme color $key updated"];
			}
		} catch (Exception $e) {
			$data['results'][] = ["error" => $e->getMessage()];
		}

		return $data['results'];
	}

}