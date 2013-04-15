<?php
/**
 * Frontend game class
 * @package SportsManager
 * @subpackage classes
 */

/**
 * Class for frontend game object
 * @package SportsManager
 */
class SportsManager_Game extends SportsManager_Frontend_Default {
	function __construct($data = '', $db) {
		$this->filter = 'games';
		parent::__construct($data, $db);
	}

	function build($data) {
		$keys = array (
			'id' => $data->id,			
			'home_team_id' => $data->home_team_id,
			'home_team_name' => $this->get_element_where($this->db->teams, 'id', $data->home_team_id, 'name'),
			'home_team_slug' => $this->get_element_where($this->db->teams, 'id', $data->home_team_id, 'slug'),
			'away_team_id' => $data->away_team_id,
			'away_team_name' => $this->get_element_where($this->db->teams, 'id', $data->away_team_id, 'name'),
			'away_team_slug' => $this->get_element_where($this->db->teams, 'id', $data->away_team_id, 'slug'),
			'home_score' => $data->home_score,
			'away_score' => $data->away_score,
			'winner_team_id' => $data->winner_team_id,
			'date' => ucfirst(strftime("%m-%d %R", strtotime($data->date))),
			'date_str' => ucfirst(strftime("%A, %e %B", strtotime($data->date))),
			'time' => strtotime($data->date),
			'time_str' => ucfirst(strftime("%R", strtotime($data->date))),
			'type' => $data->type,
			'location_id' => $data->location_id,
			'location_name' => $this->get_element_where($this->db->locations, 'id', $data->location_id, 'name'),
			'location_slug' => $this->get_element_where($this->db->locations, 'id', $data->location_id, 'slug'),
			'cancelled' => $data->cancelled
		);
		$keys['location_link'] = $keys['location_name'] != '' ? '<a href="'.SM_LOCATIONS_URL.$keys['location_slug'].'/'.'">'.$keys['location_name'].'</a>' : '';
		$keys['home_team_link'] = $keys['home_team_slug'] != '' ? '<a href="'.SM_TEAMS_URL.$keys['home_team_slug'].'/'.'">'.$keys['home_team_name'].'</a>' : '';
		$keys['away_team_link'] = $keys['away_team_slug'] != '' ? '<a href="'.SM_TEAMS_URL.$keys['away_team_slug'].'/'.'">'.$keys['away_team_name'].'</a>' : '';
		$keys['stats_link'] = '<a href="'.SM_GAMES_URL.$keys['id'].'/'.'">Stats</a>';
		foreach ($keys as $k => $v) {
			$this->$k = isset($v) ? $v : '';
		};
		unset($this->db);
	}
}
