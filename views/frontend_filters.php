<?php
$headers = array (
	'game_stats' => array (
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
		'date_str' => array ('Jour', ''),
		'time_str' => array ('Heure', ''),
		'home_team_link' => array ('Home', ''),
		'home_score' => array ('', 'Home'),
		'away_score' => array ('', 'Away'),
		'away_team_link' => array ('Away', ''),
		'location_link' => array ('Terrain', ''),
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

//game_stats table
if ($this->args->filter == 'game_stats') {
	$players_id = array();
	foreach ($this->rows as $row) {
		$players_id[] = $row->player_id;
	};
	$this->rows = array ();
	foreach ($this->db->players as $row) {
		if (in_array($row->id, $players_id)) {
			$class = $this->objects->players->class;
			$this->rows[] = new $class($row, $this->db);
		};
	};
};

if (function_exists('order_array_objects_by')) {
	$this->rows = order_array_objects_by(array ('wins', 'points_plus'), $this->rows, true); //important for game back calculations
};
foreach ($this->rows as $k => $row) {
	//hits
	if (isset($row->singles, $row->doubles, $row->triples, $row->home_runs)) {
		$row->hits = $row->singles + $row->doubles + $row->triples + $row->home_runs;
	};
	//batting_average
	if (isset($row->hits, $row->at_bat)) {
		$num = $row->hits;
		$denom = $row->at_bat;
		$row->batting_average = $denom > 0 ? number_format(round($num / $denom, 3), 3) : number_format(0, 3);
	};
	//differential
	if (isset($row->points_plus, $row->points_minus)) {
		$row->differential = $row->points_plus - $row->points_minus;
		if ($row->differential > 0) $row->differential = '+'.$row->differential;
	};
	//game_back
	if (isset($this->rows[0]->wins, $this->rows[0]->wins, $row->wins, $row->losses)) {
		$leader_wins = $this->rows[0]->wins;
		$leader_losses = $this->rows[0]->losses;
		$team_wins = $row->wins;
		$team_losses = $row->losses;
		$row->game_back = (($leader_wins - $leader_losses) - ($team_wins - $team_losses)) / 2;
	};
	//on_base_percentage
	if (isset($row->hits, $row->bases_on_balls, $row->hits_by_pitch, $row->at_bat, $row->bunts)) {
		$num = ($row->hits + $row->bases_on_balls + $row->hits_by_pitch);
		$denom = ($row->at_bat + $row->bases_on_balls + $row->hits_by_pitch + $row->bunts);
		$row->on_base_percentage = $denom > 0 ? number_format(round($num / $denom, 3), 3) : number_format(0, 3);
	};
	//slugging_percentage
	if (isset($row->hits, $row->doubles, $row->triples, $row->home_runs, $row->at_bat)) {
		$num = ($row->hits + $row->doubles + $row->triples * 2 + $row->home_runs * 3);
		$denom = $row->at_bat;
		$row->slugging_percentage = $denom > 0 ? number_format(round($num / $denom, 3), 3) : number_format(0, 3);
	};
	//on_base_plus_slugging
	if (isset($row->on_base_percentage, $row->slugging_percentage)) {
		$row->on_base_plus_slugging = $row->on_base_percentage + $row->slugging_percentage;
	};
	//winning_percentage
	if (isset($row->wins, $row->games)) {
		$num = $row->wins;
		$denom = $row->games;
		$row->winning_percentage = $denom > 0 ? number_format(round($num / $denom, 3), 3) : number_format(0, 3);
	};
	//winner in bold
	if (isset($row->winner_team_id, $row->home_team_id, $row->home_team_id, $row->home_team_name, $row->away_team_name)) {
		switch ((int) $row->winner_team_id) {
			case (int) $row->home_team_id:
				$row->home_score = '<b>'.$row->home_score.'</b>';
				$row->home_team_link = '<b>'.$row->home_team_link.'</b>';
				$row->home_team_name = '<b>'.$row->home_team_name.'</b>';
				break;
			case (int) $row->away_team_id:
				$row->away_score = '<b>'.$row->away_score.'</b>';
				$row->away_team_link = '<b>'.$row->away_team_link.'</b>';
				$row->away_team_name = '<b>'.$row->away_team_name.'</b>';
				break;
		};
	};
	//cancelled games
	if (isset($row->cancelled) && $row->cancelled == 1) {
		$row->home_score = '-';
		$row->away_score = '-';
	};
};
if (function_exists('order_array_objects_by')) {
	$this->rows = order_array_objects_by(array ('wins', 'winning_percentage'), $this->rows, true);
};
foreach ($this->rows as $k => $row) {
	//ranking
	if (isset($row->ranking)) {
		$row->ranking = $k + 1;
	};
};
if ($this->args->filter == 'leaders_stats') {
	include(dirname(__FILE__).'/frontend_leader_box.php');
} elseif ($this->args->filter == 'game_results') {
	include(dirname(__FILE__).'/frontend_games_box.php');
} else {
	include(dirname(__FILE__).'/frontend_filter_table.php');
};
