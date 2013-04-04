<?php

global $wpdb;
$league = $wpdb->get_var("SELECT name FROM ".$this->objects->leagues->table." WHERE slug = '".$_SESSION['sm_league']."'");
$season = $_SESSION['sm_season'];
$sport = ucfirst($_SESSION['sm_sport']);
$league_slugs = $wpdb->get_col("SELECT slug FROM ".$this->objects->leagues->table);
$league_names = $wpdb->get_col("SELECT name FROM ".$this->objects->leagues->table);
$leagues = array ('' => 'Chose league...');
foreach ($league_slugs as $k => $v) {
	$leagues[$v] = $league_names[$k];
};
$league = $_SESSION['sm_league'];
$y = date('Y');
$years = array ('' => 'Chose year...');
for ($i = $y - 2; $i < $y + 3; $i++) {
	$years[$i] = $i;
};
$season = $_SESSION['sm_season'];
$sports = array (
	'' => 'Chose sport...',
	'baseball' => 'Baseball',
	'basketball' => 'Basketball',
	'volleyball' => 'Volley-ball',
);
$sport = $_SESSION['sm_sport'];
