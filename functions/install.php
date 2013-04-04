<?php

function sm_install_plugin() {
	global $wpdb;
	$table_prefix = $wpdb->prefix.'sportsmanager_';
	$structure = array (
		'games' => (object) array (
			'name' => $table_prefix.'games',
			'columns' => array (
				array ('id', 'int(11)', 'NOT NULL AUTO_INCREMENT'),
				array ('league_id', 'int(11)', 'NOT NULL'),
				array ('season', 'year(4)', 'NOT NULL'),
				array ('sport', 'varchar(100)', 'NOT NULL'),
				array ('home_team_id', 'int(11)', 'NOT NULL'),
				array ('away_team_id', 'int(11)', 'NOT NULL'),
				array ('home_score', 'int(11)', 'NOT NULL'),
				array ('away_score', 'int(11)', 'NOT NULL'),
				array ('winner_team_id', 'int(11)', 'NOT NULL'),
				array ('date', 'datetime', 'NOT NULL'),
				array ('location_id', 'int(11)', 'NOT NULL'),
				array ('cancelled', 'int(1)', 'NOT NULL')
			),
			'primary' => 'id'
		),
		'leagues' => (object) array (
			'name' => $table_prefix.'leagues',
			'columns' => array (
				array ('id', 'int(11)', 'NOT NULL AUTO_INCREMENT'),
				array ('name', 'varchar(100)', 'NOT NULL'),
				array ('slug', 'varchar(100)', 'NOT NULL')
			),
			'primary' => 'id'
		),
		'locations' => (object) array (
			'name' => $table_prefix.'locations',
			'columns' => array (
				array ('id', 'int(11)', 'NOT NULL AUTO_INCREMENT'),
				array ('name', 'varchar(100)', 'NOT NULL'),
				array ('slug', 'varchar(100)', 'NOT NULL')
			),
			'primary' => 'id'
		),
		'players' => (object) array (
			'name' => $table_prefix.'players',
			'columns' => array (
				array ('id', 'int(11)', 'NOT NULL AUTO_INCREMENT'),
				array ('user_id', 'int(11)', 'NOT NULL'),
				array ('team_slug', 'varchar(100)', 'NOT NULL')
			),
			'primary' => 'id'
		),
		'scoresheets' => (object) array (
			'name' => $table_prefix.'scoresheets',
			'columns' => array (
				array ('id', 'int(11)', 'NOT NULL AUTO_INCREMENT'),
				array ('league_id', 'int(11)', 'NOT NULL'),
				array ('season', 'year(4)', 'NOT NULL'),
				array ('sport', 'varchar(100)', 'NOT NULL'),
				array ('game_id', 'int(11)', 'NOT NULL'),
				array ('player_id', 'int(11)', 'NOT NULL'),
				array ('stats', 'text', 'NOT NULL')
			),
			'primary' => 'id'
		),
		'teams' => (object) array (
			'name' => $table_prefix.'teams',
			'columns' => array (
				array ('id', 'int(11)', 'NOT NULL AUTO_INCREMENT'),
				array ('league_id', 'int(11)', 'NOT NULL'),
				array ('season', 'year(4)', 'NOT NULL'),
				array ('sport', 'varchar(100)', 'NOT NULL'),
				array ('name', 'varchar(100)', 'NOT NULL'),
				array ('slug', 'varchar(100)', 'NOT NULL')
			),
			'primary' => 'id'
		)
	);
	foreach ($structure as $table) {
		$q = "CREATE TABLE IF NOT EXISTS ".$table->name." (\n";
		foreach ($table->columns as $column) {
			$q .= "".$column[0]." ".$column[1]." ".$column[2].",\n";
		};
		$q .= isset($table->primary) ? "PRIMARY KEY (".$table->primary.")\n" : '';
		$q .= ") ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;\n";
		$wpdb->query($q);
	};
}

function sm_uninstall_plugin() {
	global $wpdb;
	$table_prefix = $wpdb->prefix.'sportsmanager_';
	$structure = array (
		'games' => (object) array (
			'name' => $table_prefix.'games',
		),
		'leagues' => (object) array (
			'name' => $table_prefix.'leagues',
		),
		'locations' => (object) array (
			'name' => $table_prefix.'locations',
		),
		'players' => (object) array (
			'name' => $table_prefix.'players',
		),
		'scoresheets' => (object) array (
			'name' => $table_prefix.'scoresheets',
		),
		'teams' => (object) array (
			'name' => $table_prefix.'teams',
		)
	);
	foreach ($structure as $table) {
		$q = "DROP TABLE ".$table->name;
		$wpdb->query($q);
	};
}
