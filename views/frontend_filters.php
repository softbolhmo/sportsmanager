<?php

//stats_game table
if ($this->args->display == 'stats_game') {
	$players_id = array();
	foreach ($this->rows as $row) {
		$players_id[] = $row->player_id;
	};
	$this->rows = array ();
	foreach ($this->db->players as $row) {
		if (in_array($row->id, $players_id)) {
			$class = $this->objects->players->class;
			$this->rows[] = new $class($row, $this->db, $this->args);
		};
	};
};

$this->rows = sm_order_array_objects_by(array ('wins', 'points_plus'), $this->rows, true); //important for game back calculations
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
	if ((isset($row->cancelled) && $row->cancelled == 1) || ((isset($row->home_team_id) && $row->home_team_id == 0) || (isset($row->away_team_id) && $row->away_team_id == 0))) {
		$row->home_score = '-';
		$row->away_score = '-';
	};
};
$this->rows = sm_order_array_objects_by(array ('wins', 'winning_percentage', 'name'), $this->rows, true);
foreach ($this->rows as $k => $row) {
	//ranking
	if (isset($row->ranking)) {
		$row->ranking = $k + 1;
	};
	//game_back
	if (isset($this->rows[0]->wins, $this->rows[0]->wins, $row->wins, $row->losses)) {
		$leader_wins = $this->rows[0]->wins;
		$leader_losses = $this->rows[0]->losses;
		$team_wins = $row->wins;
		$team_losses = $row->losses;
		$row->game_back = (($leader_wins - $leader_losses) - ($team_wins - $team_losses)) / 2;
		if ($row->game_back == 0) $row->game_back = '-';
	};
};

if ($this->args->display == 'stats_leaders') {
	$this->include_view('frontend_leaders_box');
} elseif ($this->args->display == 'game_results') {
	$this->include_view('frontend_games_box');
} elseif ($this->args->display == 'player_stats') {
	$this->include_view('frontend_player_stats_box');
} else {
	$this->include_view('frontend_filter_table');
};
