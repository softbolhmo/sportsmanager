<?php
/**
 * Frontend club class
 * @package SportsManager
 * @subpackage classes
 */

/**
 * Class for frontend club object
 * @package SportsManager
 */
class SportsManager_Club extends SportsManager_Frontend_Default {
	function __construct($data = '', $db) {
		$this->filter = 'clubs';
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
		$default_clubs_url = get_option('sportsmanager_default_stats_url', '');
		$link = $default_clubs_url != '' ? $default_clubs_url.$data->slug : '#';
		$keys = array (
			'id' => isset($data->id) ? $data->id : '',
			'game_back' => '',
			'games' => $games_played,
			'losses' => $losses,
			'name' => isset($data->name) ? $data->name : '',
			'points_minus' => $points_minus,
			'points_plus' => $points_plus,
			'ranking' => '',
			'wins' => $wins,
			'club_link' => isset($data->name) ? '<a href="'.$link.'">'.$data->name.'</a>' : '',
		);
		foreach ($keys as $k => $v) {
			$this->$k = isset($v) ? $v : '';
		};
		unset($this->db);
	}
}
