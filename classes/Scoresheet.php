<?php
/**
 * Frontend scoresheet class
 * @package SportsManager
 * @subpackage classes
 */

/**
 * Class for frontend scoresheet object
 * @package SportsManager
 */
class SportsManager_Scoresheet extends SportsManager_Frontend_Default {
	function __construct($data = '', $db) {
		$this->filter = 'scoresheets';
		parent::__construct($data, $db);
	}

	function build($data) {
		$keys = array (
			'id' => $data->id,
			'league_id' => $data->league_id,
			'season' => $data->season,
			'sport' => $data->sport,
			'player_id' => $data->player_id,
			'stats' => $data->stats
		);
		foreach ($keys as $k => $v) {
			$this->$k = isset($v) ? $v : '';
		};
		unset($this->db);
	}
}
