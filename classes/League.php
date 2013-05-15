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
	function __construct($data = '', $db, $args) {
		$this->filter = 'leagues';
		$this->args = $args;
		parent::__construct($data, $db);
	}

	function build($data) {
		$keys = array (
			'id' => isset($data->id) ? $data->id : '',
			'slug' => isset($data->slug) ? $data->slug : '',
			'name' => isset($data->name) ? $data->name : ''
		);
		foreach ($keys as $k => $v) {
			$this->$k = isset($v) ? $v : '';
		};
		unset($this->args, $this->db);
	}
}
