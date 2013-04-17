<?php
/**
 * Frontend team class
 * @package SportsManager
 * @subpackage classes
 */

/**
 * Class for frontend team object
 * @package SportsManager
 */
class SportsManager_Team extends SportsManager_Frontend_Default {
	function __construct($data = '', $db) {
		$this->filter = 'teams';
		parent::__construct($data, $db);
	}

	function build($data) {
		$club = isset($data->club_id) ? sm_search_array_objects_for('id', $data->club_id, $this->db->clubs) : (object) array ();
		$games_played = 0;
		$losses = 0;
		$points_minus = 0;
		$points_plus = 0;
		$wins = 0;
		foreach ($this->db->games as $game) {
			if ($game->cancelled != 1) {
				if (isset($data->id) && $data->id == $game->home_team_id) {
					$home_away = 'home';
				} elseif (isset($data->id) && $data->id == $game->away_team_id) {
					$home_away = 'away';
				} else {
					$home_away = false;
				};
				if ($home_away != false) {
					if ($game->winner_team_id != 0) {
						$games_played++;
						if (isset($data->id) && $data->id == $game->winner_team_id) {
							$wins++;
						} else {
							$losses++;
						};
						switch ($home_away) {
							case 'home':
								$points_plus += $game->home_score;
								$points_minus += $game->away_score;
								break;
							case 'away':
								$points_plus += $game->away_score;
								$points_minus += $game->home_score;
								break;
						};
					};
				};
			};
		};
		$keys = array (
			'id' => isset($data->id) ? $data->id : '',
			'game_back' => '',
			'games' => $games_played,
			'losses' => $losses,
			'league_id' => isset($club->league_id) ? $club->league_id : '',
			'name' => isset($club->name) ? $club->name : '',
			'points_minus' => $points_minus,
			'points_plus' => $points_plus,
			'ranking' => '',
			'wins' => $wins,
			'team_link' => isset($club->name) ? '<a href="#">'.$club->name.'</a>' : '' //look into $data->info
		);
		foreach ($keys as $k => $v) {
			$this->$k = isset($v) ? $v : '';
		};
		unset($this->db);
	}
}
