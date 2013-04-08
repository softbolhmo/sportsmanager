<?php

function sm_install_plugin() {
	global $wpdb;
	require_once(ABSPATH.'wp-admin/includes/upgrade.php');
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
								PRIMARY KEY  (id)
							);",
		'games'			=> "CREATE TABLE @table_name@ (
								id int(11) NOT NULL AUTO_INCREMENT,
								league_id int(11) NOT NULL,
								season year(4) NOT NULL,
								sport varchar(100) NOT NULL,
								home_team_id int(11) NOT NULL,
								away_team_id int(11) NOT NULL,
								home_score int(11) NOT NULL,
								away_score int(11) NOT NULL,
								winner_team_id int(11) NOT NULL,
								date datetime NOT NULL,
								type varchar(2) NOT NULL,
								location_id int(11) NOT NULL,
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
								PRIMARY KEY  (id)
							)",
		'locations'		=> "CREATE TABLE @table_name@ (
								id int(11) NOT NULL AUTO_INCREMENT,
								name varchar(100) NOT NULL,
								slug varchar(100) NOT NULL,
								description text NOT NULL,
								small_logo_url varchar(256) NOT NULL,
								large_logo_url varchar(256) NOT NULL,
								PRIMARY KEY  (id)
							)",
		'players'		=> "CREATE TABLE @table_name@ (
								id int(11) NOT NULL AUTO_INCREMENT,
								user_id int(11) NOT NULL,
								PRIMARY KEY  (id)
							)",
		'scoresheets'	=> "CREATE TABLE @table_name@ (
								id int(11) NOT NULL AUTO_INCREMENT,
								league_id int(11) NOT NULL,
								season year(4) NOT NULL,
								sport varchar(100) NOT NULL,
								game_id int(11) NOT NULL,
								player_id int(11) NOT NULL,
								stats text NOT NULL,
								PRIMARY KEY  (id)
							)",
		'teams'			=> "CREATE TABLE @table_name@ (
								id int(11) NOT NULL AUTO_INCREMENT,
								club_id int(11) NOT NULL,
								season year(4) NOT NULL,
								players_id text NOT NULL,
								PRIMARY KEY  (id)
							)"
	);
	foreach ($tables as $k => $v) {
		$table_name = $wpdb->prefix.SPORTSMANAGER_PREFIX.$k;
		$q = str_replace('@table_name@', $table_name, $v);
		dbDelta($q);
	};
/*
If there are errors when plugin is activated, put this piece of code at the very end of /wp-admin/includes/plugin.php:

add_action('activated_plugin', 'save_plugin_activation_error');

function save_plugin_activation_error() {
	file_put_contents(ABSPATH. 'wp-content/uploads/2013/04/error_activation.html', ob_get_contents());
}
*/
}

register_activation_hook(SPORTSMANAGER_DIR.'sportsmanager.php', 'sm_install_plugin');

function sm_uninstall_plugin() {
	global $wpdb;
	$tables = array ('clubs', 'games', 'leagues', 'locations', 'players', 'scoresheets', 'teams');
	foreach ($tables as $table) {
		$q = "DROP TABLE IF EXISTS ".$wpdb->prefix.SPORTSMANAGER_PREFIX.$table;
		die($q);
		$wpdb->query($q);
	};
}

register_uninstall_hook(SPORTSMANAGER_DIR.'sportsmanager.php', 'sm_uninstall_plugin');
