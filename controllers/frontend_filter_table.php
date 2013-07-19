<?php
$common_stats = array (
	'games' => array ('G', "Games Played", array ('all')),

	//common
	'assists' => array ('AST', "Assists", array ('basketball', 'hockey')),
	'blocks' => array ('BLK', "Blocks", array ('basketball')),
	'goals' => array ('G', "Goals", array ('hockey', 'soccer')),
	'steals' => array ('STL', "Steals", array ('basketball')),
	'turnovers' => array ('TO', "Turnovers", array ('basketball')),

	//baseball
	'at_bat' => array ('AB', "At Bat", array ('baseball')),
	'runs' => array ('R', "Runs", array ('baseball')),
	'hits' => array ('H', "Hits", array ('baseball')),
	//'bunts' => array ('B', "Bunts", array ('baseball')),
	'singles' => array ('1B', "Singles", array ('baseball')),
	'doubles' => array ('2B', "Doubles", array ('baseball')),
	'triples' => array ('3B', "Triples", array ('baseball')),
	'home_runs' => array ('HR', "Home Runs", array ('baseball')),
	'batting_average' => array ('AVG', "Batting Average", array ('baseball')),
	'runs_batted_in' => array ('RBI', "Runs Batted In", array ('baseball')),
	'bases_on_balls' => array ('BB', "Bases on Balls", array ('baseball')),
	'strike_outs' => array ('SO', "Strike Outs", array ('baseball')),
	//'hits_by_pitch' => array ('HBP', "Hits By Pitch", array ('baseball')),
	'stolen_bases' => array ('SB', "Stolen Bases", array ('baseball')),
	//'caught_stealing' => array ('CS', "Caught Steling", array ('baseball')),
	'on_base_percentage' => array ('OBP', "On Base Percentage", array ('baseball')),
	'slugging_percentage' => array ('SLG', "Slugging Average", array ('baseball')),
	'on_base_plus_slugging' => array ('OPS', "On Base Plus Slugging", array ('baseball')),
	//'reached_on_error' => array ('ROE', "Reached On Error", array ('baseball')),
	//'fielders_choice' => array ('FC', "Fielder's Choice", array ('baseball')),

	//basketball
	'points' => array ('PTS', "Points", array ('basketball')),
	'field_goals_made' => array ('FGM', "Field Goals Made", array ('basketball')),
	'field_goals_attempted' => array ('FGA', "Field Goals Attempted", array ('basketball')),
	'three_point_field_goals_made' => array ('3FGM', "Three-Point Field Goals Made", array ('basketball')),
	'three_point_field_goals_attempted' => array ('3FGA', "Three-Point Field Goals Attempted", array ('basketball')),
	'free_throws_made' => array ('FTM', "Free Throws Made", array ('basketball')),
	'free_throws_attempted' => array ('FTA', "Free Throws Attempted", array ('basketball')),
	'offensive_rebounds' => array ('OREB', "Offensive Rebounds", array ('basketball')),
	'defensive_rebounds' => array ('DREB', "Defensive Rebounds", array ('basketball')),
	'personal_fouls' => array ('PF', "Personal Fouls", array ('basketball')),

	//hockey
	'goals_against_average' => array ('GAA', "Goals Against Average", array ('hockey')),
	'hockey_points' => array ('P', "Points", array ('hockey')),
	'save_percentage' => array ('S%', "Save Percentage", array ('hockey')),

	//soccer
);
$headers = array (
	'rankings' => array (
		'ranking' => array ('#', '', array ('all')),
		'team_link' => array ('Name', '', array ('all')),
		'games' => array ('G', 'Games', array ('all')),
		'wins' => array ('W', 'Wins', array ('all')),
		'losses' => array ('L', 'Losses', array ('all')),

		//baseball
		'points_plus' => array ('+', 'Points Plus', array ('baseball')),
		'points_minus' => array ('-', 'Points Minus', array ('baseball')),
		'differential' => array ('+/-', 'Differential', array ('baseball')),
		'winning_percentage' => array ('%', 'Winning Percentage', array ('baseball')),
		'game_back' => array ('GB', 'Games Back', array ('baseball'))
	),
	'schedule' => array (
		'date' => array ('Date', '', array ('all')),
		'date_str' => array ('Day', '', array ('all')),
		'time_str' => array ('Hour', '', array ('all')),
		'home_team_link' => array ('Home', '', array ('all')),
		'home_score' => array ('', 'Home', array ('all')),
		'away_score' => array ('', 'Away', array ('all')),
		'away_team_link' => array ('Away', '', array ('all')),
		'location_link' => array ('Location', '', array ('all')),
		'stats_link' => array ('Stats', '', array ('all'))
	),
	'stats_game' => array_merge(array ('player_link' => array ('Player', "Name", array ('all'))), $common_stats),
	'stats_pitching' => array (
		'player_link' => array ('Player', "Name", array ('all')),

		//baseball
		'pitching_games' => array ('G', "Games Pitched", array ('baseball')),
		'pitching_wins' => array ('W', "Wins", array ('baseball')),
		'pitching_losses' => array ('L', "Losses", array ('baseball')),
		'pitching_saves' => array ('SV', "Saves", array ('baseball')),
		'pitching_innings_pitched' => array ('IP', "Innings Pitched", array ('baseball')),
		'pitching_runs_allowed' => array ('R', "Runs allowed", array ('baseball')),
		'pitching_earned_runs' => array ('ER', "Earned Runs", array ('baseball')),
		'pitching_earned_runs_average' => array ('ERA', "Earned Runs Average", array ('baseball')),
		'pitching_strike_outs' => array ('K', "Strike Outs", array ('baseball')),
		'pitching_bases_on_balls' => array ('BB', "Bases on Balls", array ('baseball')),
	),
	'stats_players' => array_merge(array ('player_link' => array ('Player', "Name", array ('all'))), $common_stats),
	'stats_team_players' => array_merge(array ('player_link' => array ('Player', "Name", array ('all'))), $common_stats),
	'stats_team' => array_merge(array ('team_link' => array ('Team', "Name", array ('all'))), $common_stats)
);

$filter = $this->args->display;
$rows = $this->rows;
$season = $this->args->season;
$sport = $this->args->sport;
if (in_array($filter, array('rankings'))) {
	$rows = sm_order_array_objects_by('ranking', $rows);
} elseif (is_int(strpos($filter, 'schedule'))) {
	$rows = sm_order_array_objects_by('time', $rows);
	foreach ($rows as $i => $row) {
		if (isset($row->time) && $row->time >= time() && $row->cancelled != 1) {
			$next_game = $i;
			break;
		};
	};
} elseif (in_array($filter, array ('stats'))) {
	//$rows = sm_order_array_objects_by('batting_average', $rows, true);
};
$option_custom_class_table = get_option('sportsmanager_custom_class_table', '');
$sortable = isset($this->args->sortable) && !$this->args->sortable ? '' : 'sortable';
