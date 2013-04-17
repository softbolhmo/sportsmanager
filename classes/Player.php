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
		$data->player_id = $this->get_player_id($data->user_id);
		$data->user = function_exists('_get_user') ? _get_user($data->user_id) : (object) array (); //TODO: make sure dependancy is ok
		foreach ($this->db->scoresheets as $scoresheet) {
			if ($scoresheet->player_id == $data->player_id) {
				$scoresheets[] = $scoresheet;
				$stats[] = json_decode($scoresheet->stats);
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
		$meta_keys = array (
			'facebook_id',
			'team'
		);
		if (isset($data->user->ID)) {
			$metas = object_metas('user', $data->user->ID, $meta_keys);
		};
		$keys = array (
			'id' => $data->id,
			'user_id' => $data->user_id,
			'player_id' => $data->player_id,
			'first_name' => isset($data->user->first_name) ? $data->user->first_name : '',
			'last_name' => isset($data->user->last_name) ? $data->user->last_name : '',
			'full_name' => isset($data->user->full_name) ? $data->user->full_name : '',
			'team_slug' => isset($metas['team']) ? $metas['team'] : '',
			'facebook_id' => isset($metas['facebook_id']) ? $metas['facebook_id'] : '',
			'player_link' => isset($data->user->user_nicename) ? SM_PLAYERS_URL.$data->user->user_nicename.'/' : '',
			//stats
			//'games' => count($scoresheets)
		);
		$keys['player_link'] = $data->user->user_nicename != '' ? '<a href="'.SM_PLAYERS_URL.$data->user->user_nicename.'/'.'">'.$keys['full_name'].'</a>' : '';
		foreach ((array) $stats_total as $k => $v) {
			$keys[$k] = $v;
		};
		foreach ($keys as $k => $v) {
			$this->$k = isset($v) ? $v : '';
		};
		unset($this->db);
	}

	function get_player_id($user_id) {
		foreach ($this->db->players as $player) {
			if ($player->user_id == $user_id) return $player->id;
		};
	}
}
