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
		$games_played = 0;
		$losses = 0;
		$points_minus = 0;
		$points_plus = 0;
		$wins = 0;
		foreach ($this->db->games as $game) {
			if ($game->cancelled != 1) {
				if ($data->id == $game->home_team_id) {
					$home_away = 'home';
				} elseif ($data->id == $game->away_team_id) {
					$home_away = 'away';
				} else {
					$home_away = false;
				};
				if ($home_away != false) {
					if ($game->winner_team_id != 0) {
						$games_played++;
						if ($data->id == $game->winner_team_id) {
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
			'id' => $data->id,
			'game_back' => '',
			'games' => $games_played,
			'losses' => $losses,
			'name' => $data->name,
			'points_minus' => $points_minus,
			'points_plus' => $points_plus,
			'ranking' => '',
			'wins' => $wins,
			'team_link' => '<a href="'.SM_TEAMS_URL.$data->slug.'/'.'">'.$data->name.'</a>',
		);
		foreach ($keys as $k => $v) {
			$this->$k = isset($v) ? $v : '';
		};
		unset($this->db);
	}
}
