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
	function __construct($data = '', $db, $args) {
		$this->filter = 'clubs';
		$this->args = $args;
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
		$infos = json_decode($data->infos);
		$keys = array (
			'id' => isset($data->id) ? $data->id : '',
			'slug' => isset($data->slug) ? $data->slug : '',
			'game_back' => '',
			'games' => $games_played,
			'losses' => $losses,
			'name' => isset($data->name) ? $data->name : '',
			'points_minus' => $points_minus,
			'points_plus' => $points_plus,
			'ranking' => '',
			'wins' => $wins,
			'small_logo_url' => isset($data->small_logo_url) ? $data->small_logo_url : '',
			'large_logo_url' => isset($data->large_logo_url) ? $data->large_logo_url : '',
			'website_url' => isset($infos->website_url) ? $infos->website_url : '',
			'facebook_page_url' => isset($infos->facebook_page_url) ? $infos->facebook_page_url : '',
			'email' => isset($infos->email) ? $infos->email : ''
		);
		//club
		$club_url = str_replace(array ('%id%', '%slug%'), array ($keys['id'], $keys['slug']), $this->args->default_clubs_url);
		if ($club_url == '') $club_url = '#';
		$keys['club_link'] = '<a href="'.$club_url.'">'.$keys['name'].'</a>';
		foreach ($keys as $k => $v) {
			$this->$k = isset($v) ? $v : '';
		};
		unset($this->args, $this->db);
	}
}
