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
	function __construct($data = '', $db, $args) {
		$this->filter = 'scoresheets';
		$this->args = $args;
		parent::__construct($data, $db);
	}

	function build($data) {
		$keys = array (
			'id' => isset($data->id) ? $data->id : '',
			'league_id' => isset($data->league_id) ? $data->league_id : '',
			'season' => isset($data->season) ? $data->season : '',
			'sport' => isset($data->sport) ? $data->sport : '',
			'player_id' => isset($data->player_id) ? $data->player_id : '',
			'stats' => isset($data->stats) ? $data->stats : ''
		);
		foreach ($keys as $k => $v) {
			$this->$k = isset($v) ? $v : '';
		};
		unset($this->args, $this->db);
	}
}
