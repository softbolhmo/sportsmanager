<?php
/**
 * Frontend location class
 * @package SportsManager
 * @subpackage classes
 */

/**
 * Class for frontend location object
 * @package SportsManager
 */
class SportsManager_Location extends SportsManager_Frontend_Default {
	function __construct($data = '', $db) {
		$this->filter = 'locations';
		parent::__construct($data, $db);
	}

	function build($data) {
		$keys = array (
			'id' => $data->id,
			'name' => $data->name,
			'address' => $data->address,
			'zip_code' => $data->zip_code,
			'neighbourhood' => $data->neighbourhood,
			'link' => isset($data->slug) ? SM_LOCATIONS_URL.$data->slug.'/' : '',
		);
		foreach ($keys as $k => $v) {
			$this->$k = isset($v) ? $v : '';
		};
		unset($this->db);
	}
}
