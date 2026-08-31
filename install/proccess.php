<?php
error_reporting(0);
ini_set('display_errors', 0);
ob_start();

	session_start();

	$post = $_POST;
	$json = array();
	$force_step2 = false;

	try {
		include_once 'helper.php';
	} catch (Exception $e) {
		$json['errors']['system'] = 'System error: ' . $e->getMessage();
		echo json_encode($json);
		exit;
	}
	
	header('Content-Type: application/json');

	if (isset($post['page']) && $post['page'] == 'step1') {
		$d['checkIsInstall'] = checkIsInstall();
		$json['html'] = view('steps/step1',$d);
	} else if (isset($post['page']) && $post['page'] == 'step2') {
		$post['access_type'] = 'direct_access';
		$post['root_url'] = root_url();
		$post['base_url'] = getBaseUrl();
			
		if(!isset($post['username']) || $post['username'] == '') { $json['errors']['username'] = 'Username is required!'; }
		if(!isset($post['purchase_code']) || $post['purchase_code'] == '') { $json['errors']['purchase_code'] = 'Codecanyon Purchase Code is required!'; }
		if(!isset($post['db_hostname']) || $post['db_hostname'] == '') { $json['errors']['db_hostname'] = 'Database Hostname is required!'; }
		if(!isset($post['db_port']) || $post['db_port'] == '') { $json['errors']['db_port'] = 'Database Port is required!'; }
		if(!isset($post['db_username']) || $post['db_username'] == '') { $json['errors']['db_username'] = 'Database Username is required!'; }
		if(!isset($post['db_database']) || $post['db_database'] == '') { $json['errors']['db_database'] = 'Database Database is required!'; }

		if(!isset($json['errors'])){
			try {
				try {
					mysqli_report(MYSQLI_REPORT_STRICT);
					$db = new \mysqli(
						$post['db_hostname'], 
						$post['db_username'], 
						html_entity_decode($post['db_password'], ENT_QUOTES, 'UTF-8'), 
						$post['db_database'], 
						$post['db_port']
					);
					
					if (isset($db->affected_rows)) { $db->close(); }
					else{ $json['errors']['db_database'] = "Error: Could not connect to the database please make sure the database server, username and password is correct!"; }
				} catch (mysqli_sql_exception  $e) {
					$json['errors']['db_database'] = "Error: Could not connect to the database please make sure the database server, username and password is correct!";
				}
			} catch(Exception $e) {
				$error['db_database'] = "Error: Could not connect to the database please make sure the database server, username and password is correct!";
			}
		}

		if(!isset($json['errors'])){
			try {
				$result = installScript($post);
				if (is_array($result)) {
					$json = array_merge($json, $result);
				} else {
					$json['errors']['install'] = 'installScript returned invalid data';
				}
				if (isset($json['success'])) {
					$force_step2 = 'step3';
				}
			} catch (Exception $e) {
				$json['errors']['install'] = 'Installation error: ' . $e->getMessage();
				$json['debug_trace'] = $e->getTraceAsString();
			}
		}
	}

	if ($force_step2 == 'step3') {
		try {
			$view_data['base_url'] = getBaseUrl();
			$view_data['license']  = isset($post['purchase_code']) ? $post['purchase_code'] : '';
		
			$html = view('steps/step3',$view_data);
			
			// Ensure valid UTF-8 encoding for JSON
			if (!mb_check_encoding($html, 'UTF-8')) {
				$html = mb_convert_encoding($html, 'UTF-8', 'UTF-8');
			}
			
			$json['html'] = $html;
		} catch (Exception $e) {
			$json['errors']['view'] = 'Error rendering step3: ' . $e->getMessage();
		}
	}

	if ($force_step2 == 'step1') {
		$d['checkIsInstall'] = checkIsInstall();
		$json['html'] = view('steps/step1',$d);
	}

	// Clear any unexpected output and send clean JSON
	$output = ob_get_clean();
	
	// Only add debug output in development
	if (!empty($output) && !isset($json['debug'])) {
		// $json['debug_output'] = $output; // Commented out for production
	}
	
	// Ensure we have valid JSON
	if (empty($json)) {
		$json = array('errors' => array('system' => 'No data to return'));
	}
	
	// Test JSON encoding
	$json_output = json_encode($json);
	if ($json_output === false) {
		// JSON encoding failed, send basic error
		echo json_encode(array(
			'errors' => array(
				'json' => 'JSON encoding failed: ' . json_last_error_msg(),
				'data_keys' => array_keys($json)
			)
		));
	} else {
		echo $json_output;
	}
	exit;