<?php
error_reporting(1);

/**

 * CodeIgniter

 *

 * An open source application development framework for PHP

 *

 * This content is released under the MIT License (MIT)

 *

 * Copyright (c) 2014 - 2018, British Columbia Institute of Technology

 *

 * Permission is hereby granted, free of charge, to any person obtaining a copy

 * of this software and associated documentation files (the "Software"), to deal

 * in the Software without restriction, including without limitation the rights

 * to use, copy, modify, merge, publish, distribute, sublicense, and/or sell

 * copies of the Software, and to permit persons to whom the Software is

 * furnished to do so, subject to the following conditions:

 *

 * The above copyright notice and this permission notice shall be included in

 * all copies or substantial portions of the Software.

 *

 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR

 * IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,

 * FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE

 * AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER

 * LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,

 * OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN

 * THE SOFTWARE.

 *

 * @package	CodeIgniter

 * @author	EllisLab Dev Team

 * @copyright	Copyright (c) 2008 - 2014, EllisLab, Inc. (https://ellislab.com/)

 * @copyright	Copyright (c) 2014 - 2018, British Columbia Institute of Technology (http://bcit.ca/)

 * @license	http://opensource.org/licenses/MIT	MIT License

 * @link	https://codeigniter.com

 * @since	Version 1.0.0

 * @filesource

 */



/*

 *---------------------------------------------------------------

 * APPLICATION ENVIRONMENT

 *---------------------------------------------------------------

 *

 * You can load different configurations depending on your

 * current environment. Setting the environment also influences

 * things like logging and error reporting.

 *

 * This can be set to anything, but default usage is:

 *

 *     development

 *     testing

 *     production

 *		 demo

 * NOTE: If you change these, also change the error_reporting() code below

 */

define('ENVIRONMENT', isset($_SERVER['CI_ENV']) ? $_SERVER['CI_ENV'] : 'production');



/*

 *---------------------------------------------------------------

 * ERROR REPORTING

 *---------------------------------------------------------------

 *

 * Different environments will require different levels of error reporting.

 * By default development will show errors but testing and live will hide them.

 */

switch (ENVIRONMENT)
{
	case 'development':
		error_reporting(-1);
		ini_set('display_errors', 1);

	break;

	case 'testing':
	case 'production':
	case 'demo':

		ini_set('display_errors', 0);

		if (version_compare(PHP_VERSION, '5.3', '>='))
		{
			error_reporting(E_ALL & ~E_NOTICE & ~E_DEPRECATED & ~E_STRICT & ~E_USER_NOTICE & ~E_USER_DEPRECATED);
		}

		else
		{
			error_reporting(E_ALL & ~E_NOTICE & ~E_STRICT & ~E_USER_NOTICE);
		}

	break;

	default:

		header('HTTP/1.1 503 Service Unavailable.', TRUE, 503);
		echo 'The application environment is not set correctly.';
		exit(1); // EXIT_ERROR
}



/*

 *---------------------------------------------------------------

 * SYSTEM DIRECTORY NAME

 *---------------------------------------------------------------

 *

 * This variable must contain the name of your "system" directory.

 * Set the path if it is not in the same directory as this file.

 */

	$system_path = 'system';



/*

 *---------------------------------------------------------------

 * APPLICATION DIRECTORY NAME

 *---------------------------------------------------------------

 *

 * If you want this front controller to use a different "application"

 * directory than the default one you can set its name here. The directory

 * can also be renamed or relocated anywhere on your server. If you do,

 * use an absolute (full) server path.

 * For more info please see the user guide:

 *

 * https://codeigniter.com/user_guide/general/managing_apps.html

 *

 * NO TRAILING SLASH!

 */

	$application_folder = 'application';



/*

 *---------------------------------------------------------------

 * VIEW DIRECTORY NAME

 *---------------------------------------------------------------

 *

 * If you want to move the view directory out of the application

 * directory, set the path to it here. The directory can be renamed

 * and relocated anywhere on your server. If blank, it will default

 * to the standard location inside your application directory.

 * If you do move this, use an absolute (full) server path.

 *

 * NO TRAILING SLASH!

 */

	$view_folder = '';





/*

 * --------------------------------------------------------------------

 * DEFAULT CONTROLLER

 * --------------------------------------------------------------------

 *

 * Normally you will set your default controller in the routes.php file.

 * You can, however, force a custom routing by hard-coding a

 * specific controller class/function here. For most applications, you

 * WILL NOT set your routing here, but it's an option for those

 * special instances where you might want to override the standard

 * routing in a specific front controller that shares a common CI installation.

 *

 * IMPORTANT: If you set the routing here, NO OTHER controller will be

 * callable. In essence, this preference limits your application to ONE

 * specific controller. Leave the function name blank if you need

 * to call functions dynamically via the URI.

 *

 * Un-comment the $routing array below to use this feature

 */

	// The directory name, relative to the "controllers" directory.  Leave blank

	// if your controller is not in a sub-directory within the "controllers" one

	// $routing['directory'] = '';



	// The controller class file name.  Example:  mycontroller

	// $routing['controller'] = '';



	// The controller function you wish to be called.

	// $routing['function']	= '';





/*

 * -------------------------------------------------------------------

 *  CUSTOM CONFIG VALUES

 * -------------------------------------------------------------------

 *

 * The $assign_to_config array below will be passed dynamically to the

 * config class when initialized. This allows you to set custom config

 * items or override any default config values found in the config.php file.

 * This can be handy as it permits you to share one application between

 * multiple front controller files, with each file containing different

 * config values.

 *

 * Un-comment the $assign_to_config array below to use this feature

 */

	// $assign_to_config['name_of_config_item'] = 'value of config item';







// --------------------------------------------------------------------

// END OF USER CONFIGURABLE SETTINGS.  DO NOT EDIT BELOW THIS LINE

// --------------------------------------------------------------------



/*

 * ---------------------------------------------------------------

 *  Resolve the system path for increased reliability

 * ---------------------------------------------------------------

 */



	// Set the current directory correctly for CLI requests

	if (defined('STDIN'))

	{

		chdir(dirname(__FILE__));

	}



	if (($_temp = realpath($system_path)) !== FALSE)

	{

		$system_path = $_temp.DIRECTORY_SEPARATOR;

	}

	else

	{

		// Ensure there's a trailing slash

		$system_path = strtr(

			rtrim($system_path, '/\\'),

			'/\\',

			DIRECTORY_SEPARATOR.DIRECTORY_SEPARATOR

		).DIRECTORY_SEPARATOR;

	}



	// Is the system path correct?

	if ( ! is_dir($system_path))

	{

		header('HTTP/1.1 503 Service Unavailable.', TRUE, 503);

		echo 'Your system folder path does not appear to be set correctly. Please open the following file and correct this: '.pathinfo(__FILE__, PATHINFO_BASENAME);

		exit(3); // EXIT_CONFIG

	}



/*

 * -------------------------------------------------------------------

 *  Now that we know the path, set the main path constants

 * -------------------------------------------------------------------

 */

	// The name of THIS file

	define('SELF', pathinfo(__FILE__, PATHINFO_BASENAME));



	// Path to the system directory

	define('BASEPATH', $system_path);



	// Path to the front controller (this file) directory

	define('FCPATH', dirname(__FILE__).DIRECTORY_SEPARATOR);



	// Name of the "system" directory

	define('SYSDIR', basename(BASEPATH));

	define('MAIN_PATH', __DIR__);



	// The path to the "application" directory

	if (is_dir($application_folder))

	{

		if (($_temp = realpath($application_folder)) !== FALSE)

		{

			$application_folder = $_temp;

		}

		else

		{

			$application_folder = strtr(

				rtrim($application_folder, '/\\'),

				'/\\',

				DIRECTORY_SEPARATOR.DIRECTORY_SEPARATOR

			);
		}
	}

	elseif (is_dir(BASEPATH.$application_folder.DIRECTORY_SEPARATOR))

	{

		$application_folder = BASEPATH.strtr(

			trim($application_folder, '/\\'),

			'/\\',

			DIRECTORY_SEPARATOR.DIRECTORY_SEPARATOR

		);

	}

	else

	{

		header('HTTP/1.1 503 Service Unavailable.', TRUE, 503);

		echo 'Your application folder path does not appear to be set correctly. Please open the following file and correct this: '.SELF;

		exit(3); // EXIT_CONFIG

	}

	define('APPPATH', $application_folder.DIRECTORY_SEPARATOR);


	// The path to the "views" directory

	if ( ! isset($view_folder[0]) && is_dir(APPPATH.'views'.DIRECTORY_SEPARATOR))

	{

		$view_folder = APPPATH.'views';

	}

	elseif (is_dir($view_folder))

	{

		if (($_temp = realpath($view_folder)) !== FALSE)

		{

			$view_folder = $_temp;

		}

		else

		{

			$view_folder = strtr(

				rtrim($view_folder, '/\\'),

				'/\\',

				DIRECTORY_SEPARATOR.DIRECTORY_SEPARATOR

			);

		}

	}

	elseif (is_dir(APPPATH.$view_folder.DIRECTORY_SEPARATOR))

	{

		$view_folder = APPPATH.strtr(

			trim($view_folder, '/\\'),

			'/\\',

			DIRECTORY_SEPARATOR.DIRECTORY_SEPARATOR

		);

	}

	else

	{

		header('HTTP/1.1 503 Service Unavailable.', TRUE, 503);

		echo 'Your view folder path does not appear to be set correctly. Please open the following file and correct this: '.SELF;

		exit(3); // EXIT_CONFIG

	}

define('VIEWPATH', $view_folder.DIRECTORY_SEPARATOR);

ini_set('memory_limit', '-1');

header('Access-Control-Allow-Origin: *');

header('Access-Control-Allow-Headers: Origin, X-Requested-With, Content-Type, Accept');

header('X-XSS-Protection:0');

error_reporting(E_ERROR | E_PARSE);
 
$php_version_infomration=phpversion();
if ($php_version_infomration < 7.4) 
{
  require APPPATH .'views/errors/html/error_php_version.php' ;
	exit();
}

// Define the path to the database configuration file.
$dbConfigPath = APPPATH . 'config/database.php';

if (!file_exists($dbConfigPath)) {
    $installDir = dirname(__FILE__) . DIRECTORY_SEPARATOR . 'install';
    
    if (is_dir($installDir)) {
        $protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ? 'https' : 'http';
        $host = $_SERVER['HTTP_HOST'];
        $baseDir = rtrim(dirname($_SERVER['SCRIPT_NAME']), '/\\');
        $installPath = rtrim($baseDir, '/') . '/install/index.php';
        header("Location: {$protocol}://{$host}{$installPath}");
        exit;
    } else {
        $licenseCache = dirname(__FILE__) . DIRECTORY_SEPARATOR . 'application' . DIRECTORY_SEPARATOR . 'license-easy-data-affiliateporsaas.json';
        $wasInstalled = file_exists($licenseCache);
        
        if ($wasInstalled) {
            die('<!DOCTYPE html><html><head><meta charset="utf-8"><title>Reinstallation Required</title><style>body{font-family:Arial,sans-serif;background:#f5f5f5;margin:0;padding:50px;text-align:center}div{background:white;padding:40px;border-radius:8px;box-shadow:0 2px 10px rgba(0,0,0,0.1);max-width:600px;margin:0 auto}h1{color:#e74c3c;margin-top:0;margin-bottom:15px}p{color:#666;line-height:1.6}.subtitle{background:#f8f9fa;padding:15px;border-radius:6px;color:#495057;font-size:15px;margin:20px 0}code{background:#f8f8f8;padding:2px 8px;border-radius:3px;color:#e74c3c}ol{text-align:left;padding-left:20px;line-height:1.8}.note{background:#fff3cd;border-left:4px solid #ffc107;padding:12px;margin:20px 0;text-align:left}a{color:#007bff;text-decoration:none}a:hover{text-decoration:underline}</style></head><body><div><h1>🔄 Reinstallation Required</h1><div class="subtitle">The database configuration was removed (uninstall/update), but the installation directory is missing.</div><div class="note"><strong>To Reinstall After Update:</strong><ol><li>Download the latest version from CodeCanyon</li><li>Extract the <strong>update package</strong> (or full package)</li><li>Upload all files to your server via FTP</li><li>Ensure the <code>install/</code> folder exists in your server root</li><li>Visit this URL again to complete the installation</li></ol></div><p style="margin-top:30px;color:#999;font-size:14px">Need help? <a href="https://affiliatepro.org/support/" target="_blank">Contact Support</a></p></div></body></html>');
        } else {
            die('<!DOCTYPE html><html><head><meta charset="utf-8"><title>Installation Required</title><style>body{font-family:Arial,sans-serif;background:#f5f5f5;margin:0;padding:50px;text-align:center}div{background:white;padding:40px;border-radius:8px;box-shadow:0 2px 10px rgba(0,0,0,0.1);max-width:600px;margin:0 auto}h1{color:#28a745;margin-top:0;margin-bottom:15px}p{color:#666;line-height:1.6}.subtitle{background:#e8f5e9;padding:15px;border-radius:6px;color:#2e7d32;font-size:15px;margin:20px 0}code{background:#f8f8f8;padding:2px 8px;border-radius:3px;color:#e74c3c}ol{text-align:left;padding-left:20px;line-height:1.8}.note{background:#d4edda;border-left:4px solid #28a745;padding:12px;margin:20px 0;text-align:left}a{color:#007bff;text-decoration:none}a:hover{text-decoration:underline}</style></head><body><div><h1>📦 Fresh Installation Required</h1><div class="subtitle">The script has not been installed yet. Let\'s get started!</div><div class="note"><strong>Installation Steps:</strong><ol><li>Download the latest version from CodeCanyon</li><li>Extract the <strong>full installation package</strong></li><li>Upload all files to your server via FTP</li><li>Ensure the <code>install/</code> folder exists in your server root</li><li>Visit this URL again to start the installation wizard</li></ol></div><p style="margin-top:30px;color:#999;font-size:14px">Need help? <a href="https://affiliatepro.org/support/" target="_blank">Contact Support</a></p></div></body></html>');
        }
    }
}


require APPPATH . "config/database.php";
$config['db_debug'] = FALSE;
if (!isset($db["default"]["database"])) {
    $installDir = dirname(__FILE__) . DIRECTORY_SEPARATOR . 'install';
    
    if (is_dir($installDir)) {
        header("Location: install/index.php");
        exit;
    } else {
        $licenseCache = dirname(__FILE__) . DIRECTORY_SEPARATOR . 'application' . DIRECTORY_SEPARATOR . 'license-easy-data-affiliateporsaas.json';
        $wasInstalled = file_exists($licenseCache);
        
        if ($wasInstalled) {
            die('<!DOCTYPE html><html><head><meta charset="utf-8"><title>Database Reconfiguration Required</title><style>body{font-family:Arial,sans-serif;background:#f5f5f5;margin:0;padding:50px;text-align:center}div{background:white;padding:40px;border-radius:8px;box-shadow:0 2px 10px rgba(0,0,0,0.1);max-width:600px;margin:0 auto}h1{color:#e74c3c;margin-top:0;margin-bottom:15px}p{color:#666;line-height:1.6}.subtitle{background:#f8f9fa;padding:15px;border-radius:6px;color:#495057;font-size:15px;margin:20px 0}code{background:#f8f8f8;padding:2px 8px;border-radius:3px;color:#e74c3c}ol{text-align:left;padding-left:20px;line-height:1.8}.note{background:#fff3cd;border-left:4px solid #ffc107;padding:12px;margin:20px 0;text-align:left}a{color:#007bff;text-decoration:none}a:hover{text-decoration:underline}</style></head><body><div><h1>🔄 Database Reconfiguration Required</h1><div class="subtitle">The database configuration is incomplete after update/reinstall, and the installation directory is missing.</div><div class="note"><strong>To Reconfigure After Update:</strong><ol><li>Download the latest version from CodeCanyon</li><li>Extract the <strong>update package</strong> (or full package)</li><li>Upload all files to your server via FTP</li><li>Ensure the <code>install/</code> folder exists in your server root</li><li>Refresh this page to reconfigure the database</li></ol></div><p style="margin-top:30px;color:#999;font-size:14px">Need help? <a href="https://affiliatepro.org/support/" target="_blank">Contact Support</a></p></div></body></html>');
        } else {
            die('<!DOCTYPE html><html><head><meta charset="utf-8"><title>Initial Setup Required</title><style>body{font-family:Arial,sans-serif;background:#f5f5f5;margin:0;padding:50px;text-align:center}div{background:white;padding:40px;border-radius:8px;box-shadow:0 2px 10px rgba(0,0,0,0.1);max-width:600px;margin:0 auto}h1{color:#28a745;margin-top:0;margin-bottom:15px}p{color:#666;line-height:1.6}.subtitle{background:#e8f5e9;padding:15px;border-radius:6px;color:#2e7d32;font-size:15px;margin:20px 0}code{background:#f8f8f8;padding:2px 8px;border-radius:3px;color:#e74c3c}ol{text-align:left;padding-left:20px;line-height:1.8}.note{background:#d4edda;border-left:4px solid #28a745;padding:12px;margin:20px 0;text-align:left}a{color:#007bff;text-decoration:none}a:hover{text-decoration:underline}</style></head><body><div><h1>📦 Initial Setup Required</h1><div class="subtitle">Database configuration needs to be set up. The installation directory is missing.</div><div class="note"><strong>Setup Steps:</strong><ol><li>Download the latest version from CodeCanyon</li><li>Extract the <strong>full installation package</strong></li><li>Upload all files to your server via FTP</li><li>Ensure the <code>install/</code> folder exists in your server root</li><li>Refresh this page to start the setup wizard</li></ol></div><p style="margin-top:30px;color:#999;font-size:14px">Need help? <a href="https://affiliatepro.org/support/" target="_blank">Contact Support</a></p></div></body></html>');
        }
    }
}


$sess_driver = 'files';
$sess_cookie_name = 'affiliatepro';
$sess_save_path = sys_get_temp_dir();
$connection = mysqli_connect(
	$db["default"]["hostname"],
	$db["default"]["username"],
	$db["default"]["password"],
	$db["default"]["database"],
	$db["default"]["dbport"]
);

if($connection){
	$result = $connection->query("SELECT 1 FROM `ci_session`");
    if(isset($result->num_rows) && $result->num_rows > 0){
    	$sess_driver = 'database';
		$sess_cookie_name = 'ci_session';
		$sess_save_path = 'ci_session';
    }
}
else
{

	require APPPATH .'views/errors/html/error_db.php' ;
	exit();
}

define('SESS_DRIVER',$sess_driver);
define('SESS_COOKIE_NAME',$sess_cookie_name);
define('SESS_SAVE_PATH',$sess_save_path);



require APPPATH.'third_party/eloquent.php';

require_once BASEPATH.'core/CodeIgniter.php';
 