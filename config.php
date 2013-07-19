<?php
/**
 * Plugin configuration, setup constants
 * @package SportsManager
 * @subpackage setup
 */

/**
 * Define constants
 * @return void
 */
function sm_constants() {
	define('SPORTSMANAGER_VERSION', '1.3.1');
	define('SPORTSMANAGER_PREFIX', 'sportsmanager_');
	define('SPORTSMANAGER_CAPABILITY_PREFIX', 'edit_sportsmanager_');
	if (!defined('WP_CONTENT_URL')) define('WP_CONTENT_URL', rtrim(get_option('siteurl'), '/').'/'.'wp-content/');
	if (!defined('WP_CONTENT_DIR')) define('WP_CONTENT_DIR', rtrim(ABSPATH, '/').'/'.'wp-content/');
	if (!defined('WP_PLUGIN_URL')) define('WP_PLUGIN_URL', rtrim(WP_CONTENT_URL, '/').'/'.'plugins/');
	if (!defined('WP_PLUGIN_DIR')) define('WP_PLUGIN_DIR', rtrim(WP_CONTENT_DIR, '/').'/'.'plugins/');
	if (!defined('WP_ADMIN_URL')) define('WP_ADMIN_URL', rtrim(get_admin_url(), '/').'/');
	define('SPORTSMANAGER_ADMIN_URL_PREFIX', rtrim(WP_ADMIN_URL, '/').'/'.'admin.php?page=sportsmanager');
	define('SPORTSMANAGER_URL', rtrim(WP_PLUGIN_URL, '/').'/'.'sportsmanager/');
	define('SPORTSMANAGER_DIR', rtrim(WP_PLUGIN_DIR, '/').'/'.'sportsmanager/');
	if (!defined('SPORTSMANAGER_FILTER')) {
		if (isset($_REQUEST['tab'])) {
			define('SPORTSMANAGER_FILTER', $_REQUEST['tab']);
		} else {
			define('SPORTSMANAGER_FILTER', 'home');
		};
	};
}

/**
 * Gather all required files (functions or classes)
 * @param string $type
 * @param array $files
 */
function sm_gather_files($type, $files, $is_folder = false) {
	foreach ($files as $file) {
		if ($type != '') $type .= '/';
		$f = SPORTSMANAGER_DIR.$type.$file.'.php';
		if (file_exists($f)) {require_once($f);} else {die($f.' '.$type.' file does not exist');};
	};
}

sm_constants();
