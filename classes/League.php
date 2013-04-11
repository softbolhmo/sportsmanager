<?php
/**
 * Frontend league class
 * @package SportsManager
 * @subpackage classes
 */

/**
 * Class for frontend league object
 * @package SportsManager
 */
class SportsManager_League extends SportsManager_Frontend_Default {
	function __construct($data = '', $db) {
		$this->filter = 'leagues';
		parent::__construct($data, $db);
	}

	function build($data) {
		$keys = array (
			'id' => $data->id,
			'name' => $data->name,
			'slug' => $data->slug
		);
		foreach ($keys as $k => $v) {
			$this->$k = isset($v) ? $v : '';
		};
		unset($this->db);
	}
}
