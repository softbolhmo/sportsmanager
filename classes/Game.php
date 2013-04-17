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
		$home_club_id = isset($data->home_team_id) ? sm_search_array_objects_for('id', $data->home_team_id, $this->db->teams, 'club_id') : '';
		$away_club_id = isset($data->away_team_id) ? sm_search_array_objects_for('id', $data->away_team_id, $this->db->teams, 'club_id') : '';
		$keys = array (
			'id' => isset($data->id) ? $data->id : '',			
			'home_team_id' => isset($data->home_team_id) ? $data->home_team_id : '',
			'home_team_name' => $home_club_id != '' ? sm_search_array_objects_for('id', $home_club_id, $this->db->clubs, 'name') : '',
			'home_team_slug' => isset($data->home_team_id) ? sm_search_array_objects_for('id', $data->home_team_id, $this->db->teams, 'slug') : '',
			'away_team_id' => isset($data->away_team_id) ? $data->away_team_id : '',
			'away_team_name' => $away_club_id != '' ? sm_search_array_objects_for('id', $away_club_id, $this->db->clubs, 'name') : '',
			'away_team_slug' => isset($data->away_team_id) ? sm_search_array_objects_for('id', $data->away_team_id, $this->db->teams, 'slug') : '',
			'home_score' => isset($data->home_score) ? $data->home_score : '',
			'away_score' => isset($data->away_score) ? $data->away_score : '',
			'winner_team_id' => isset($data->winner_team_id) ? $data->winner_team_id : '',
			'date' => isset($data->date) ? ucfirst(strftime("%m-%d %R", strtotime($data->date))) : '',
			'date_str' => isset($data->date) ? ucfirst(strftime("%A, %e %B", strtotime($data->date))) : '',
			'time' => isset($data->date) ? strtotime($data->date) : '',
			'time_str' => isset($data->date) ? ucfirst(strftime("%R", strtotime($data->date))) : '',
			'type' => isset($data->type) ? $data->type : '',
			'location_id' => isset($data->location_id) ? $data->location_id : '',
			'location_name' => isset($data->location_id) ? sm_search_array_objects_for('id', $data->location_id, $this->db->locations, 'name') : '',
			'location_slug' => isset($data->location_id) ? sm_search_array_objects_for('id', $data->location_id, $this->db->locations, 'slug') : '',
			'cancelled' => isset($data->cancelled) ? $data->cancelled : ''
		);
		$keys['location_link'] = $keys['location_name'] != '' ? '<a href="#">'.$keys['location_name'].'</a>' : ''; //look into $data->info
		$keys['home_team_link'] = $keys['home_team_slug'] != '' ? '<a href="#">'.$keys['home_team_name'].'</a>' : '';
		$keys['away_team_link'] = $keys['away_team_slug'] != '' ? '<a href="#">'.$keys['away_team_name'].'</a>' : '';
		$keys['stats_link'] = '<a href="#">Stats</a>';
		foreach ($keys as $k => $v) {
			$this->$k = isset($v) ? $v : '';
		};
		unset($this->db);
	}
}
