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
	function __construct($data = '', $db, $args) {
		$this->filter = 'locations';
		$this->args = $args;
		parent::__construct($data, $db);
	}

	function build($data) {
		$infos = json_decode($data->infos);
		$keys = array (
			'id' => isset($data->id) ? $data->id : '',
			'slug' => isset($data->slug) ? $data->slug : '',
			'name' => isset($data->name) ? $data->name : '',
			'address' => isset($infos->address) ? $infos->address : '',
			'zip_code' => isset($infos->zip_code) ? $infos->zip_code : '',
			'neighbourhood' => isset($infos->neighbourhood) ? $infos->neighbourhood : '',
			'coordinates' => isset($infos->coordinates) ? $infos->coordinates : '',
			'large_logo_url' => isset($data->large_logo_url) ? $data->large_logo_url : ''
		);
		//location
		$location_url = str_replace(array ('%id%', '%slug%'), array ($keys['id'], $keys['slug']), $this->args->default_locations_url);
		if ($location_url == '') $location_url = '#';
		$keys['location_link'] = '<a href="'.$location_url.'">'.$keys['name'].'</a>';
		foreach ($keys as $k => $v) {
			$this->$k = isset($v) ? $v : '';
		};
		unset($this->args, $this->db);
	}
}
