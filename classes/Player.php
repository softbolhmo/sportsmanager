<?php
/**
 * Frontend player class
 * @package SportsManager
 * @subpackage classes
 */

/**
 * Class for frontend player object
 * @package SportsManager
 */
class SportsManager_Player extends SportsManager_Frontend_Default {
	function __construct($data = '', $db) {
		$this->filter = 'players';
		parent::__construct($data, $db);
	}

	function build($data) {
		$scoresheets = array ();
		$stats = array ();
		$data->user = isset($data->user_id) ? sm_search_array_objects_for('id', $data->user_id, $this->db->users) : (object) array ();
		foreach ($this->db->scoresheets as $scoresheet) {
			if (isset($scoresheet->player_id, $data->id) && $scoresheet->player_id == $data->id) {
				$scoresheets[] = $scoresheet;
				$decode = isset ($scoresheet->stats) ? json_decode($scoresheet->stats) : (object) array ();
				$stats[] = !is_null($decode) ? $decode : (object) array ();
			};
		};
		$stats_keys = array ();
		$stats_total = (object) array ();
		foreach ($stats as $stat) {
			foreach ((array) $stat as $k => $v) {
				if (!in_array($k, $stats_keys)) {
					$stats_keys[] = $k;
					$stats_total->$k = 0;
				};
			};
		};
		foreach ($stats as $stat) {
			foreach ($stats_keys as $stats_key) {
				if (isset($stat->$stats_key)) {
					$stats_total->$stats_key += $stat->$stats_key;
				};
			};
		};
		$infos = json_decode($data->infos);
		$names = array ();
		$names['first'] = isset($infos->first_name) && !in_array($infos->first_name, array ('', '0')) ? $infos->first_name : (isset($data->user->first_name) ? $data->user->first_name : '');
		$names['last'] = isset($infos->last_name) && !in_array($infos->last_name, array ('', '0')) ? $infos->last_name : (isset($data->user->last_name) ? $data->user->last_name : '');
		$names['full'] = implode(' ', $names);
		if (in_array($names['full'], array ('', ' '))) $names['full'] = isset($data->user->name) ? $data->user->name : '';
		$keys = array (
			'id' => isset($data->id) ? $data->id : '',
			'user_id' => isset($data->user_id) ? $data->user_id : '',
			'first_name' => $names['first'],
			'last_name' => $names['last'],
			'name' => $names['full'],
			'nick_name' => isset($infos->nick_name) && !in_array($infos->nick_name, array ('', '0')) ? $infos->nick_name : '',
			'facebook_id' => isset($infos->facebook_id) && !in_array($infos->facebook_id, array ('', '0')) ? $infos->facebook_id : '',
			'birth_date' => isset($infos->birth_date) && !in_array($infos->birth_date, array ('', '0')) ? $infos->birth_date : '',
			'hometown' => isset($infos->home_town) && !in_array($infos->home_town, array ('', '0')) ? $infos->home_town : '',
			'player_link' => '<a href="#">'.$names['full'].'</a>',
			//stats
			//'games' => count($scoresheets) //no because in one scoresheet, you can enter stats for multiple games, or even an entire year! It also depends on what league your refering to!
		);
		foreach ((array) $stats_total as $k => $v) {
			$keys[$k] = $v;
		};
		foreach ($keys as $k => $v) {
			$this->$k = isset($v) ? $v : '';
		};
		unset($this->db);
	}
}
