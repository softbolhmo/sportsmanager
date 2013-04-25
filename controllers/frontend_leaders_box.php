<?php

$leaders = array (
	'batting_average' => array ('AVG', "Batting Average"),
	'hits' => array ('H', "Hits"),
	'home_runs' => array ('HR', "Home Runs"),
	'on_base_percentage' => array ('OBP', "On Base Percentage"),
	'runs_batted_in' => array ('RBI', "Runs Batted In"),
	'stolen_bases' => array ('SB', "Stolen Bases")
);
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
$default_stats_url = get_option('sportsmanager_default_stats_url', '');
