<?php

/*
If there are errors when plugin is activated, put this piece of code at the very end of /wp-admin/includes/plugin.php:

add_action('activated_plugin', 'save_plugin_activation_error');
function save_plugin_activation_error() {
	file_put_contents(ABSPATH. 'wp-content/uploads/2013/04/error_activation.html', ob_get_contents());
}
add_action('deactivated_plugin', 'save_plugin_deactivation_error');
function save_plugin_deactivation_error() {
	file_put_contents(ABSPATH. 'wp-content/uploads/2013/04/error_deactivation.html', ob_get_contents());
}
*/
function sm_activate_plugin() {
	global $wpdb;
	require_once(ABSPATH.'wp-admin/includes/upgrade.php');
	update_option(SPORTSMANAGER_PREFIX.'disable_intro', 'enabled');
	//$file = SPORTSMANAGER_DIR.'backups/SportsManager---'.DB_NAME.'---UPGRADINGv'.SPORTSMANAGER_VERSION.'.sql';
	//sm_prepare_backup($file);
	$tables = array (
		'clubs'			=> "CREATE TABLE @table_name@ (
								id int(11) NOT NULL AUTO_INCREMENT,
								league_id int(11) NOT NULL,
								sport varchar(100) NOT NULL,
								name varchar(100) NOT NULL,
								slug varchar(100) NOT NULL,
								description text NOT NULL,
								small_logo_url varchar(256) NOT NULL,
								large_logo_url varchar(256) NOT NULL,
								infos text NOT NULL,
								PRIMARY KEY  (id)
							);",
		'games'			=> "CREATE TABLE @table_name@ (
								id int(11) NOT NULL AUTO_INCREMENT,
								league_id int(11) NOT NULL,
								season varchar(5) NOT NULL,
								sport varchar(100) NOT NULL,
								home_team_id int(11) NOT NULL,
								away_team_id int(11) NOT NULL,
								home_score int(11) NOT NULL,
								away_score int(11) NOT NULL,
								winner_team_id int(11) NOT NULL,
								date datetime NOT NULL,
								type varchar(2) NOT NULL,
								location_id int(11) NOT NULL,
								description text NOT NULL,
								cancelled int(1) NOT NULL,
								PRIMARY KEY  (id)
							)",
		'leagues'		=> "CREATE TABLE @table_name@ (
								id int(11) NOT NULL AUTO_INCREMENT,
								name varchar(100) NOT NULL,
								slug varchar(100) NOT NULL,
								description text NOT NULL,
								small_logo_url varchar(256) NOT NULL,
								large_logo_url varchar(256) NOT NULL,
								infos text NOT NULL,
								PRIMARY KEY  (id)
							)",
		'locations'		=> "CREATE TABLE @table_name@ (
								id int(11) NOT NULL AUTO_INCREMENT,
								league_id int(11) NOT NULL,
								name varchar(100) NOT NULL,
								slug varchar(100) NOT NULL,
								description text NOT NULL,
								small_logo_url varchar(256) NOT NULL,
								large_logo_url varchar(256) NOT NULL,
								infos text NOT NULL,
								PRIMARY KEY  (id)
							)",
		'players'		=> "CREATE TABLE @table_name@ (
								id int(11) NOT NULL AUTO_INCREMENT,
								user_id int(11) NOT NULL,
								slug varchar(100) NOT NULL,
								description text NOT NULL,
								small_logo_url varchar(256) NOT NULL,
								large_logo_url varchar(256) NOT NULL,
								infos text NOT NULL,
								PRIMARY KEY  (id)
							)",
		'scoresheets'	=> "CREATE TABLE @table_name@ (
								id int(11) NOT NULL AUTO_INCREMENT,
								league_id int(11) NOT NULL,
								season varchar(5) NOT NULL,
								sport varchar(100) NOT NULL,
								game_id int(11) NOT NULL,
								player_id int(11) NOT NULL,
								stats text NOT NULL,
								PRIMARY KEY  (id)
							)",
		'teams'			=> "CREATE TABLE @table_name@ (
								id int(11) NOT NULL AUTO_INCREMENT,
								club_id int(11) NOT NULL,
								season varchar(5) NOT NULL,
								players_id text NOT NULL,
								captains_id text NOT NULL,
								inactive int(1) NOT NULL,
								PRIMARY KEY  (id)
							)",
		'faq'		=> "CREATE TABLE @table_name@ (
								id int(11) NOT NULL AUTO_INCREMENT,
								type varchar(100) NOT NULL,
								question text NOT NULL,
								answer text NOT NULL,
								PRIMARY KEY  (id)
							)"
	);
	foreach ($tables as $k => $v) {
		$table_name = $wpdb->prefix.SPORTSMANAGER_PREFIX.$k;
		$q = str_replace('@table_name@', $table_name, $v);
		dbDelta($q);
	};
}

register_activation_hook(SPORTSMANAGER_DIR.'sportsmanager.php', 'sm_activate_plugin');

function sm_deactivate_plugin() {
	//$file = SPORTSMANAGER_DIR.'backups/SportsManager---'.DB_NAME.'---DEACTIVATION.sql';
	//sm_prepare_backup($file);
	foreach (array ('disable_intro') as $k) { //, 'email', 'email_name', 'language', 'custom_class_table'
		delete_option(SPORTSMANAGER_PREFIX.$k);
	};
}

register_deactivation_hook(SPORTSMANAGER_DIR.'sportsmanager.php', 'sm_deactivate_plugin');

function sm_uninstall_plugin() {
	global $wpdb;
	$tables = array ('clubs', 'games', 'leagues', 'locations', 'players', 'scoresheets', 'teams', 'faq');
	foreach ($tables as $table) {
		$q = "DROP TABLE IF EXISTS ".$wpdb->prefix.SPORTSMANAGER_PREFIX.$table;
		$wpdb->query($q);
	};
	$keys = array (
		'disable_intro',
		'email',
		'email_name',
		'language',
		'custom_class_table',
		'default_clubs_url',
		'default_locations_url',
		'default_players_url',
		'default_results_url',
		'default_stats_url',
		'default_teams_url'
	);
	foreach ($keys as $k) {
		delete_option(SPORTSMANAGER_PREFIX.$k);
	};
}

register_uninstall_hook(SPORTSMANAGER_DIR.'sportsmanager.php', 'sm_uninstall_plugin');
