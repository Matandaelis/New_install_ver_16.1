<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');

class Installversion extends MY_Controller {
    function __construct() {
        parent::__construct();

        $public_methods = array('remove_installer_dir');
        $current_method = isset($this->router) ? $this->router->method : '';

        if (in_array($current_method, $public_methods, true)) {
            return;
        }

        $this->loginID = $this->session->userdata('administrator');
        if(!isset($this->loginID['id'])){ redirect($this->admin_domain_url, 'refresh'); }
        ___construct(1);
    }


    public function remove_installer_dir() {
        header('Content-Type: application/json');
        
        $response = ['success' => false, 'message' => ''];

        $submitted = trim($this->input->post('license', true));

        $stored_key = $this->_get_stored_license_key();

        if (!$stored_key || !$submitted) {
            $response['message'] = 'Missing license data.';
            echo json_encode($response);
            return;
        }

        if (strcasecmp($stored_key, $submitted) !== 0) {
            $response['message'] = 'License verification failed.';
            echo json_encode($response);
            return;
        }

        $install_dir = FCPATH . 'install';

        if (!is_dir($install_dir)) {
            $response['success'] = true;
            $response['message'] = 'Installer directory already removed.';
            echo json_encode($response);
            return;
        }

        if ($this->delete_directory($install_dir)) {
            $response['success'] = true;
            $response['message'] = 'Installer directory removed successfully.';
        } else {
            $response['message'] = 'Unable to remove installer directory. Please check permissions or remove it manually.';
        }

        echo json_encode($response);
    }

    private function _get_stored_license_key() {
        $license_cache = APPPATH . 'license-easy-data-affiliateporsaas.json';
        if (file_exists($license_cache)) {
            $cache_data = @json_decode(file_get_contents($license_cache), true);
            if ($cache_data && isset($cache_data['license_key']) && $cache_data['license_key'] !== '') {
                return trim($cache_data['license_key']);
            }
        }

        $cfg_key = $this->config->item('codecanyon_license');
        if ($cfg_key && $cfg_key !== '') {
            return trim($cfg_key);
        }

        if (defined('CODECANYON_LICENCE') && CODECANYON_LICENCE !== '') {
            return trim(CODECANYON_LICENCE);
        }

        if (defined('LICENSE_EASY_KEY') && LICENSE_EASY_KEY !== '') {
            return trim(LICENSE_EASY_KEY);
        }

        $guard_cache = APPPATH . 'cache/license_check.json';
        if (file_exists($guard_cache)) {
            $guard_data = @json_decode(file_get_contents($guard_cache), true);
            if ($guard_data && isset($guard_data['license_key']) && $guard_data['license_key'] !== '') {
                return trim($guard_data['license_key']);
            }
        }

        return '';
    }

    public function ajax_validate_license() {
        if (!$this->input->is_ajax_request()) {
            show_404();
            return;
        }

        $code = $this->input->post('code');
        $json = [];

        if (!$code) {
            $json['errors']['purchase_code'] = 'Purchase code is required';
            echo json_encode($json);
            return;
        }

        $api_url = 'https://affiliatepro.org/index.php?rest_route=/license-easy/v1/validate';
        $domain = base_url();
        $payload = [
            'purchase_code' => $code,
            'product_slug'  => 'affiliateporsaas',
            'domain'        => $domain,
        ];

        $ch = curl_init($api_url);
        curl_setopt_array($ch, [
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_POST => true,
            CURLOPT_POSTFIELDS => json_encode($payload),
            CURLOPT_HTTPHEADER => ['Content-Type: application/json'],
            CURLOPT_TIMEOUT => 20,
        ]);

        $response = curl_exec($ch);
        $curl_error = curl_error($ch);
        curl_close($ch);

        if ($curl_error) {
            $json['errors']['purchase_code'] = 'Error connecting to API: '.$curl_error;
            echo json_encode($json);
            return;
        }

        $result = json_decode($response, true);
        if (isset($result['status']) && $result['status'] === 'success') {
            $json['response'] = [
                'valid'          => true,
                'buyer'          => $result['buyer'] ?? '',
                'license'        => $result['license_type'] ?? 'Regular License',
                'sold_at'        => $result['purchase_date'] ?? '',
                'purchase_count' => $result['purchase_count'] ?? 1,
            ];
        } else {
            $json['errors']['purchase_code'] = $result['message'] ?? 'Invalid purchase code';
        }

        echo json_encode($json);
    }

    // Keep other existing methods unchanged
    public function uninstall_script($password, $licence){
        // Demo Mode
        if (ENVIRONMENT === 'demo') {
            echo json_encode([
                'status' => 'error',
                'message' => 'Disabled on demo mode'
            ]);
            return;
        }

        $password = base64_decode($password);
        $licence = base64_decode($licence);

        $user = $this->db->query("SELECT * FROM users WHERE id=". (int)$this->loginID['id'])->row();
        
        $license_key = $this->config->item('codecanyon_license');
        if (!$license_key && defined('CODECANYON_LICENCE')) {
            $license_key = CODECANYON_LICENCE;
        }
        if (!$license_key && defined('LICENSE_EASY_KEY')) {
            $license_key = LICENSE_EASY_KEY;
        }
        
        if(strtolower($licence) != strtolower($license_key)){
            $json['errors']['licence'] = "Wrong licence number";
        }
        if(sha1($password) != $user->password){
            $json['errors']['password'] = "Wrong Admin Password..!";
        }

        if( !isset($json['errors']) ){
            $deactivation_message = '';
            
            if (file_exists(APPPATH . 'license-easy-universal-client.php')) {
                require_once APPPATH . 'license-easy-universal-client.php';
                $license_client = new License_Easy_Universal_Client(array(
                    'product_slug' => 'affiliateporsaas',
                    'api_url' => 'https://affiliatepro.org/index.php?rest_route=/license-easy/v1/',
                    'product_name' => 'AffiliatePorSaaS'
                ));
                $deactivate_result = $license_client->deactivate_license();
                if (isset($deactivate_result['success']) && $deactivate_result['success']) {
                    $deactivation_message = 'License deactivated successfully. ';
                } else {
                    $deactivation_message = 'License may already be deactivated or server unreachable. ';
                }
            }
            
            $database_file = APPPATH . 'config/database.php';
            if (file_exists($database_file)) {
                if (@unlink($database_file)) {
                    $license_data_file = APPPATH . 'license-easy-data-affiliateporsaas.json';
                    if (file_exists($license_data_file)) {
                        @unlink($license_data_file);
                    }
                    
                    $json['success'] = true;
                    $json['message'] = $deactivation_message . 'Script uninstalled successfully. Database configuration removed.';
                } else {
                    $json['warning'] = $deactivation_message . 'Failed to remove database configuration. Please delete application/config/database.php manually.';
                }
            } else {
                $json['success'] = true;
                $json['message'] = $deactivation_message . 'Database configuration already removed.';
            }
        }

        echo json_encode($json);
    }

    public function downloadDatabase(){
        $this->dbexport('download');
    }

    public function downloadDatabaseNewVersion(){
        $this->dbexport('download', true);
    }

    public function confirm_password(){
        $json = [];

        // Demo Mode
        if (ENVIRONMENT === 'demo') {
            echo json_encode([
                'status' => 'error',
                'message' => 'Disabled on demo mode'
            ]);
            return;
        }

        $lastLogin = $this->session->userdata('tmp_login');
        if(!$lastLogin){
            $forAction = $this->input->post('for', true);
            // Instead of loading a view, return a flag to show the password modal
            $json['show_password_modal'] = true;
            $json['for_action'] = $forAction;
        } else {
            $json['callback'] = true;
        }

        echo json_encode($json);
    }

    public function migrateDatabase(){
        $files = $_FILES['database'];
        $json = array();

        if(!isset($files['name']) || $files['name'] == ''){
            $json['errors']['database'] = 'Please select a database file';
        } else {
            $ext = pathinfo($files['name'], PATHINFO_EXTENSION);
            if($ext != 'sql'){
                $json['errors']['database'] = 'Please select .sql file';
            }
        }

        if(!isset($json['errors'])){
            $this->dbexport('backup');
            $json['errors'] = [];
            $templine = '';
            $destination = "updates_database.sql";
            unlink($destination);
            file_put_contents($destination, file_get_contents($files['tmp_name'])); 
            $file = fopen($destination,"r");
            while(! feof($file))
            {
                $line = fgets($file);
                if (substr($line, 0, 2) == '--' || $line == '')
                continue;
                $templine .= $line;
                if (substr(trim($line), -1, 1) == ';') {
                    try {
                        $this->db->db_debug = false;
                        if(!@$this->db->query($templine)) {
                            $json['errors'][] = $this->db->error();
                        }
                    } catch (\Throwable $th) {
                        $json['errors'][] = $th->getMessage();
                    }
                    $templine = '';
                }
            }
            fclose($file);

            unlink($destination);
            
            // Instead of loading a view, return success data for modal
            $json['success'] = true;
            $json['show_success_modal'] = true;
            $json['success_message'] = 'Database Migrated Successfully';
            $json['success_description'] = 'Your database has been updated successfully.';
            $json['next_button_text'] = 'Next: Migrate Files';
            $json['next_step'] = 1; // or whatever step number you need
        }

        echo json_encode($json);
    }

    public function dbexport($action = "download", $withData = false) {
        $this->load->dbutil();

        if($action == "backup") {
            $prefs = array(
                'format'        => 'txt',
                'filename'      => $this->db->database,
                'add_drop'      => true,
                'add_insert'    => true,
                'newline'       => "\n"
            );
        } else {
            $prefs = array(
                'format'        => 'txt',
                'filename'      => $this->db->database,
                'add_drop'      => false,
                'add_insert'    => false,
                'newline'       => "\n"
            );
        }

        $backup =& $this->dbutil->backup($prefs);

        $db_name = 'database_backup_'.SCRIPT_VERSION.'_'.time().'.sql';
        $bk_path = 'application/backup/'.$db_name;

        if($action == 'download') {
            if($withData) {
                $backup .= $this->get_default_data();
            }
            $this->load->helper('download');
            force_download($db_name, $backup);
        } else {
            $this->load->helper('file');
            write_file($bk_path, $backup);
        }
    }

    public function get_default_data(){
        $default_data_queries = "";

        $tables = ['countries','mail_templates','setting','cities','states','theme_pages'];
        $customs = ['currency','language','users'];

        foreach ($customs as $key => $table) {
            $data = [];
            if($table == 'users'){
                $data = $this->db->query("SELECT * FROM {$table} WHERE id = 1")->result_array();
            }
            else if($table == 'language'){
                $data = $this->db->query("SELECT * FROM {$table} WHERE id = 1")->result_array();
            }
            else if($table == 'currency'){
                $data = $this->db->query("SELECT * FROM {$table} WHERE currency_id = 1")->result_array();
            }

            foreach ($data as $row) {
                $default_data_queries .= "INSERT INTO {$table} SET ";
                $datas = [];
                foreach ($row as $column => $d) {
                    $datas[] ="`{$column}`= ". $this->db->escape($d);
                }
                
                $default_data_queries .=  implode(",", $datas) .";\n";
            }
        }

        foreach ($tables as $key => $table) {
            $data = $this->db->query("SELECT * FROM {$table}")->result_array();
            foreach ($data as $row) {
                $default_data_queries .= "INSERT INTO {$table} SET ";
                $datas = [];
                foreach ($row as $column => $d) {
                    $datas[] ="`{$column}`= ". $this->db->escape($d);
                }
                
                $default_data_queries .=  implode(",", $datas) .";\n";
            }
        }

        $default_data_queries = str_replace("DEFAULT 'NULL'", "DEFAULT NULL", $default_data_queries);

        return $default_data_queries;
    }

    private function delete_directory($dir) {
        if (!is_dir($dir)) {
            return true;
        }

        $items = scandir($dir);
        foreach ($items as $item) {
            if ($item === '.' || $item === '..') {
                continue;
            }
            $path = $dir . DIRECTORY_SEPARATOR . $item;
            if (is_dir($path)) {
                if (!$this->delete_directory($path)) {
                    return false;
                }
            } else {
                if (!@unlink($path)) {
                    return false;
                }
            }
        }

        return @rmdir($dir);
    }
}
