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
	define('SPORTSMANAGER_VERSION', '1.0');
	define('SPORTSMANAGER_PREFIX', 'sportsmanager_');
	if (!defined('WP_CONTENT_URL')) define('WP_CONTENT_URL', rtrim(get_option('siteurl'), '/').'/'.'wp-content/');
	if (!defined('WP_CONTENT_DIR')) define('WP_CONTENT_DIR', rtrim(ABSPATH, '/').'/'.'wp-content/');
	if (!defined('WP_PLUGIN_URL')) define('WP_PLUGIN_URL', rtrim(WP_CONTENT_URL, '/').'/'.'plugins/');
	if (!defined('WP_PLUGIN_DIR')) define('WP_PLUGIN_DIR', rtrim(WP_CONTENT_DIR, '/').'/'.'plugins/');
	define('SPORTSMANAGER_ADMIN_URL_PREFIX', rtrim(get_admin_url(), '/').'/'.'admin.php?page=sportsmanager');
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
 * @param bool $is_folder
 */
function sm_gather_files($type, $files, $is_folder = false) {
	foreach ($files as $file) {
		if ($is_folder) $file .= '/'.$file;
		$f = SPORTSMANAGER_DIR.$type.'/'.$file.'.php';
		if (file_exists($f)) {require_once($f);} else {die($f.' '.$type.' file does not exist');};
	};
}

sm_constants();
