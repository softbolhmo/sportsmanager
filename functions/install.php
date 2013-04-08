<?php

function sm_install_plugin() {
	global $wpdb;
	$q = file_get_contents(SPORTSMANAGER_DIR.'structure.sql');
	if ($q != false) {
		$wpdb->query($q);
	};
}

register_activation_hook(SPORTSMANAGER_DIR.'sportsmanager.php', 'sm_install_plugin');

function sm_uninstall_plugin() {
	if (defined('WP_UNINSTALL_PLUGIN')) {
		global $wpdb;
		$q = '';
		$tables = array ('clubs', 'games', 'leagues', 'locations', 'players', 'scoresheets', 'teams');
		foreach ($tables as $table) {
			$q .= "DROP TABLE ".$wpdb->prefix.SPORTSMANAGER_PREFIX.$table." \n";
		};
		$wpdb->query($q);
	};
}

register_uninstall_hook(SPORTSMANAGER_DIR.'sportsmanager.php', 'sm_uninstall_plugin');
