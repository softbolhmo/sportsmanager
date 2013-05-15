<?php

$headers = array (
	//baseball
	'batting_average' => array ('AVG', "Batting Average", array ('baseball')),
	'hits' => array ('H', "Hits", array ('baseball')),
	'home_runs' => array ('HR', "Home Runs", array ('baseball')),
	'on_base_percentage' => array ('OBP', "On Base Percentage", array ('baseball')),
	'runs_batted_in' => array ('RBI', "Runs Batted In", array ('baseball')),
	'stolen_bases' => array ('SB', "Stolen Bases", array ('baseball'))
);

$filter = $this->args->display;
$rows = $this->rows;
$season = $this->args->season;
$sport = $this->args->sport;
$top = $this->args->top;
$game_count = array ();
foreach ($this->db->games as $game) {
	if (strtotime($game->date) <= time() && $game->cancelled != 1) {
		foreach (array ($game->home_team_id, $game->away_team_id) as $team) {
			if (isset($game_count[$team])) {
				$game_count[$team]++;
			} else {
				$game_count[$team] = 1;
			};
		};
	};
};
$min_game_count = count($game_count) >= 1 ? min($game_count) : 0;
$option_custom_class_table = get_option('sportsmanager_custom_class_table', '');
$default_stats_url = get_option('sportsmanager_default_stats_url', '');
