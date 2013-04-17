<?php

$headers = array (
	'pitching_stats' => array (
		'full_name' => array ('Joueur', "Name"),
		'pitching_games' => array ('G', "Games Pitched"),
		'pitching_wins' => array ('W', "Wins"),
		'pitching_losses' => array ('L', "Losses"),
		'pitching_saves' => array ('SV', "Saves"),
		'pitching_innings_pitched' => array ('IP', "Innings Pitched"),
		'pitching_runs_allowed' => array ('R', "Runs allowed"),
		'pitching_earned_runs' => array ('ER', "Earned Runs"),
		'pitching_earned_runs_average' => array ('ERA', "Earned Runs Average"),
		'pitching_strike_outs' => array ('K', "Strike Outs"),
		'pitching_bases_on_balls' => array ('BB', "Bases on Balls"),
	),
	'player_stats' => array (
		'games' => array ('G', "Games Played"),
		'at_bat' => array ('AB', "At Bat"),
		'runs' => array ('R', "Runs"),
		'hits' => array ('H', "Hits"),
		//'bunts' => array ('B', "Bunts"),
		'singles' => array ('1B', "Singles"),
		'doubles' => array ('2B', "Doubles"),
		'triples' => array ('3B', "Triples"),
		'home_runs' => array ('HR', "Home Runs"),
		'batting_average' => array ('AVG', "Batting Average"),
		'runs_batted_in' => array ('RBI', "Runs Batted In"),
		'bases_on_balls' => array ('BB', "Bases on Balls"),
		'strike_outs' => array ('SO', "Strike Outs"),
		//'hits_by_pitch' => array ('HBP', "Hits By Pitch"),
		'stolen_bases' => array ('SB', "Stolen Bases"),
		//'caught_stealing' => array ('CS', "Caught Steling"),
		'on_base_percentage' => array ('OBP', "On Base Percentage"),
		'slugging_percentage' => array ('SLG', "Slugging Average"),
		'on_base_plus_slugging' => array ('OPS', "On Base Plus Slugging"),
		//'reached_on_error' => array ('ROE', "Reached On Error"),
		//'fielders_choice' => array ('FC', "Fielder's Choice")
	),
	'players_stats' => array (
		'player_link' => array ('Joueur', "Name"),
		'games' => array ('G', "Games Played"),
		'at_bat' => array ('AB', "At Bat"),
		'runs' => array ('R', "Runs"),
		'hits' => array ('H', "Hits"),
		//'bunts' => array ('B', "Bunts"),
		'singles' => array ('1B', "Singles"),
		'doubles' => array ('2B', "Doubles"),
		'triples' => array ('3B', "Triples"),
		'home_runs' => array ('HR', "Home Runs"),
		'batting_average' => array ('AVG', "Batting Average"),
		'runs_batted_in' => array ('RBI', "Runs Batted In"),
		'bases_on_balls' => array ('BB', "Bases on Balls"),
		'strike_outs' => array ('SO', "Strike Outs"),
		//'hits_by_pitch' => array ('HBP', "Hits By Pitch"),
		'stolen_bases' => array ('SB', "Stolen Bases"),
		//'caught_stealing' => array ('CS', "Caught Steling"),
		'on_base_percentage' => array ('OBP', "On Base Percentage"),
		'slugging_percentage' => array ('SLG', "Slugging Average"),
		'on_base_plus_slugging' => array ('OPS', "On Base Plus Slugging"),
		//'reached_on_error' => array ('ROE', "Reached On Error"),
		//'fielders_choice' => array ('FC', "Fielder's Choice")
	),
	'rankings' => array (
		'ranking' => array ('#', ''),
		'team_link' => array ('Name', ''),
		'games' => array ('G', 'Games'),
		'wins' => array ('W', 'Wins'),
		'losses' => array ('L', 'Losses'),
		'points_plus' => array ('RS', 'Runs Scored'),
		'points_minus' => array ('RA', 'Runs Allowed'),
		'differential' => array ('DIFF', 'Differential'),
		'winning_percentage' => array ('%', 'Winning Percentage'),
		'game_back' => array ('GB', 'Games Back')
	),
	'schedule' => array (
		'date' => array ('Date', ''),
		'date_str' => array ('Day', ''),
		'time_str' => array ('Hour', ''),
		'home_team_link' => array ('Home', ''),
		'home_score' => array ('', 'Home'),
		'away_score' => array ('', 'Away'),
		'away_team_link' => array ('Away', ''),
		'location_link' => array ('Location', ''),
		'stats_link' => array ('Stats', '')
	),
	'schedule_playoff' => array (
		'date' => array ('Date', ''),
		'date_str' => array ('Jour', ''),
		'time_str' => array ('Heure', ''),
		'type' => array ('', ''),
		'home_team_link' => array ('Home', ''),
		'home_score' => array ('', 'Home'),
		'away_score' => array ('', 'Away'),
		'away_team_link' => array ('Away', ''),
		'location_link' => array ('Terrain', ''),
		'stats_link' => array ('Stats', '')
	),
	'team_players_stats' => array (
		'player_link' => array ('Joueur', "Name"),
		'games' => array ('G', "Games Played"),
		'at_bat' => array ('AB', "At Bat"),
		'runs' => array ('R', "Runs"),
		'hits' => array ('H', "Hits"),
		//'bunts' => array ('B', "Bunts"),
		'singles' => array ('1B', "Singles"),
		'doubles' => array ('2B', "Doubles"),
		'triples' => array ('3B', "Triples"),
		'home_runs' => array ('HR', "Home Runs"),
		'batting_average' => array ('AVG', "Batting Average"),
		'runs_batted_in' => array ('RBI', "Runs Batted In"),
		'bases_on_balls' => array ('BB', "Bases on Balls"),
		'strike_outs' => array ('SO', "Strike Outs"),
		//'hits_by_pitch' => array ('HBP', "Hits By Pitch"),
		'stolen_bases' => array ('SB', "Stolen Bases"),
		//'caught_stealing' => array ('CS', "Caught Steling"),
		'on_base_percentage' => array ('OBP', "On Base Percentage"),
		'slugging_percentage' => array ('SLG', "Slugging Average"),
		'on_base_plus_slugging' => array ('OPS', "On Base Plus Slugging"),
		//'reached_on_error' => array ('ROE', "Reached On Error"),
		//'fielders_choice' => array ('FC', "Fielder's Choice")
	),
);

$filter = $this->args->display;
$rows = $this->rows;
if (in_array($filter, array ('schedule'))) {
	$rows = sm_order_array_objects_by('time', $rows, true);
	foreach ($rows as $i => $row) {
		if (isset($row->time) && $row->time >= time()) {
			$next_game = $i;
			break;
		};
	};
} elseif (in_array($filter, array('rankings'))) {
	$rows = sm_order_array_objects_by('ranking', $rows);
};
$option_custom_class_table = get_option('sportsmanager_custom_class_table', '');
$sortable = isset($this->args->sortable) && !$this->args->sortable ? '' : 'sortable';
