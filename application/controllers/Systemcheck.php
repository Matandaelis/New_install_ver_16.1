<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');

class Systemcheck extends MY_Controller {
    
    public function __construct() {
        parent::__construct();
        $this->load->model('Product_model');
        $this->load->helper('utility');
        
    }
    
    public function index() {
        try {
            // Get system requirements
            $data['serverReq'] = checkReq();
            $data['con'] = get_database_connection();
            
            // Get available languages securely
            $data['languages'] = $this->getAvailableLanguages();
            
            // Get current language info
            $data['current_lang_id'] = isset($_SESSION['userLang']) ? $_SESSION['userLang'] : 1;
            $data['current_lang_name'] = isset($_SESSION['userLangName']) ? $_SESSION['userLangName'] : 'English';
            
            // Load translations
            $this->loadTranslations($data['current_lang_id']);
            
            // Load the view
            $this->load->view('systemcheck/index', $data);
        } catch (Exception $e) {
            // Fallback view with minimal data
            $data['serverReq'] = [];
            $data['con'] = null;
            $data['languages'] = [['id' => 1, 'name' => 'English', 'flag' => '', 'status' => 1, 'is_default' => 1]];
            $data['current_lang_id'] = 1;
            $data['current_lang_name'] = 'English';
            
            $this->load->view('systemcheck/index', $data);
        }
    }
    
    public function change_language($language_id = null) {
        if (empty($language_id) || !is_numeric($language_id)) {
            show_404();
            return;
        }
        
        // Get language info securely from model
        $language = $this->Product_model->getLanguageById($language_id);
        
        if ($language && $language['status'] == 1) {
            $_SESSION['userLang'] = $language_id;
            $_SESSION['userLangName'] = $language['name'];
        }
        
        // Redirect back to system check
        redirect('systemcheck');
    }
    
    private function getAvailableLanguages() {
        try {
            $this->db->where('status', 1);
            $this->db->order_by('is_default', 'DESC');
            $this->db->order_by('name', 'ASC');
            return $this->db->get('language')->result_array();
        } catch (Exception $e) {
            // Fallback to default language
            return [
                ['id' => 1, 'name' => 'English', 'flag' => '', 'status' => 1, 'is_default' => 1]
            ];
        }
    }
    
    private function loadTranslations($userLang) {
        // This replicates the fillLang function logic but in a secure way
        $language = array();
        $lang_files = ['admin', 'client', 'store', 'user', 'front', 'template_simple'];
        
        // Load default language files first
        foreach ($lang_files as $file) {
            $lang_file = APPPATH . 'language/default/' . $file . '.php';
            if (file_exists($lang_file)) {
                $lang = array();
                include($lang_file);
                if (isset($lang) && is_array($lang)) {
                    foreach ($lang as $key => $value) {
                        $language[$file . '.' . $key] = $value;
                    }
                }
            }
        }
        
        // Load user-specific language files if not default
        if ($userLang != 1) {
            foreach ($lang_files as $file) {
                $lang_file = APPPATH . 'language/' . $userLang . '/' . $file . '.php';
                if (file_exists($lang_file)) {
                    $lang = array();
                    include($lang_file);
                    if (isset($lang) && is_array($lang)) {
                        foreach ($lang as $key => $value) {
                            if ($value) {
                                $language[$file . '.' . $key] = $value;
                            }
                        }
                    }
                }
            }
        }
        
        // Make translations available globally
        $GLOBALS['language'] = $language;
    }
}
