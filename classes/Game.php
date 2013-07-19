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
	function __construct($data = '', $db, $args) {
		$this->filter = 'games';
		$this->args = $args;
		parent::__construct($data, $db);
	}

	function build($data) {
		$home_team = isset($data->home_team_id) ? sm_search_array_objects_for('id', $data->home_team_id, $this->db->teams) : '';
		$home_club = isset($home_team->club_id) ? sm_search_array_objects_for('id', $home_team->club_id, $this->db->clubs) : '';
		$away_team = isset($data->away_team_id) ? sm_search_array_objects_for('id', $data->away_team_id, $this->db->teams) : '';
		$away_club = isset($away_team->club_id) ? sm_search_array_objects_for('id', $away_team->club_id, $this->db->clubs) : '';
		$location = isset($data->location_id) ? sm_search_array_objects_for('id', $data->location_id, $this->db->locations) : '';
		$keys = array (
			'id' => isset($data->id) ? $data->id : '',		
			'season' =>	isset($data->season) ? $data->season : '',
			'home_team_id' => isset($data->home_team_id) ? $data->home_team_id : '',
			'home_team_name' => isset($home_club->name) ? $home_club->name : '',
			'home_team_season' => isset($home_team->season) ? $home_team->season : '',
			'home_team_slug' => isset($home_club->slug) ? $home_club->slug : '',
			'home_team_small_logo_url' => isset($home_club->small_logo_url) ? $home_club->small_logo_url : '',
			'away_team_id' => isset($data->away_team_id) ? $data->away_team_id : '',
			'away_team_name' => isset($away_club->name) ? $away_club->name : '',
			'away_team_season' => isset($away_team->season) ? $away_team->season : '',
			'away_team_slug' => isset($away_club->slug) ? $away_club->slug : '',
			'away_team_small_logo_url' => isset($away_club->small_logo_url) ? $away_club->small_logo_url : '',
			'home_score' => isset($data->home_score) ? $data->home_score : '',
			'away_score' => isset($data->away_score) ? $data->away_score : '',
			'winner_team_id' => isset($data->winner_team_id) ? $data->winner_team_id : '',
			'date' => isset($data->date) ? $data->date : '', //ucfirst(strftime("%m-%d %R", strtotime($data->date))) : '',
			'date_str' => isset($data->date) ? ucfirst(strftime("%A, %e %B", strtotime($data->date))) : '',
			'time' => isset($data->date) ? strtotime($data->date) : '',
			'time_str' => isset($data->date) ? ucfirst(strftime("%R", strtotime($data->date))) : '',
			'type' => isset($data->type) ? $data->type : '',
			'location_id' => isset($data->location_id) ? $data->location_id : '',
			'location_name' => isset($location->name) ? $location->name : '',
			'location_slug' => isset($location->slug) ? $location->slug : '',
			'description' => isset($data->description) ? json_decode($data->description) : '',
			'cancelled' => isset($data->cancelled) ? $data->cancelled : ''
		);
		//location
		$location_url = str_replace(array ('%id%', '%slug%'), array ($keys['location_id'], $keys['location_slug']), $this->args->default_locations_url);
		if ($location_url == '') $location_url = '#';
		$keys['location_link'] = '<a href="'.$location_url.'">'.$keys['location_name'].'</a>';
		//home team
		$home_team_url = str_replace(array ('%id%', '%slug%', '%season%'), array ($keys['home_team_id'], $keys['home_team_slug'], $keys['home_team_season']), $this->args->default_teams_url);
		if ($home_team_url == '') $home_team_url = '#';
		$keys['home_team_link'] = '<a href="'.$home_team_url.'">'.$keys['home_team_name'].'</a>';
		//away team
		$away_team_url = str_replace(array ('%id%', '%slug%', '%season%'), array ($keys['away_team_id'], $keys['away_team_slug'], $keys['away_team_season']), $this->args->default_teams_url);
		if ($away_team_url == '') $away_team_url = '#';
		$keys['away_team_link'] = '<a href="'.$away_team_url.'">'.$keys['away_team_name'].'</a>';
		//results
		$results_url = str_replace(array ('%id%'), array ($keys['id']), $this->args->default_results_url);
		if ($results_url == '') $results_url = '#';
		$keys['stats_link'] = '<a href="'.$results_url.'">Stats</a>';
		foreach ($keys as $k => $v) {
			$this->$k = isset($v) ? $v : '';
		};
		unset($this->args, $this->db);
	}
}
