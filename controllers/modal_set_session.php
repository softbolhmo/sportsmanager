<?php

global $wpdb;
$league = $wpdb->get_var("SELECT name FROM ".$this->objects->leagues->table." WHERE slug = '".$this->args->league_slug."'");
$season = $this->args->season;
$sport = ucfirst($this->args->sport);
$league_slugs = $wpdb->get_col("SELECT slug FROM ".$this->objects->leagues->table);
$league_names = $wpdb->get_col("SELECT name FROM ".$this->objects->leagues->table);
$leagues = array ('' => 'Choose league...');
foreach ($league_slugs as $k => $v) {
	$leagues[$v] = $league_names[$k];
};
$league = $this->args->league_slug;
$y = date('Y');
$years = array ('' => 'Choose season...');
for ($i = $y - 2; $i < $y + 3; $i++) {
	$years[$i] = $i;
};
$season = $this->args->season;
$sports = array_merge(array ('' => 'Choose sport...'), $this->sports);
$sport = $this->args->sport;
