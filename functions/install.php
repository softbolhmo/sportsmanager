<?php

function sm_install_plugin() {
	global $wpdb;
	$table = $wpdb->prefix.SPORTSMANAGER_PREFIX."clubs"; 
	$q = "CREATE TABLE $table (
		id int(11) NOT NULL AUTO_INCREMENT,
		league_id int(11) NOT NULL,
		sport varchar(100) NOT NULL,
		name varchar(100) NOT NULL,
		slug varchar(100) NOT NULL,
		description text NOT NULL,
		small_logo_url varchar(256) NOT NULL,
		large_logo_url varchar(256) NOT NULL,
		PRIMARY KEY  (id)
	);";
	require_once(ABSPATH.'wp-admin/includes/upgrade.php');
	dbDelta($q);
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
