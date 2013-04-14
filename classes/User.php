<?php
/**
 * Frontend user class
 * @package SportsManager
 * @subpackage classes
 */

/**
 * Class for frontend user object
 * @package SportsManager
 */
class SportsManager_User extends SportsManager_Frontend_Default {
	function __construct($data = '', $db = '') {
		$this->filter = 'users';
		parent::__construct($data, $db);
	}

	function build($data) {
		$first_name = get_user_meta($data->ID, 'first_name', true);
		$last_name = get_user_meta($data->ID, 'last_name', true);
		$name = $first_name.' '.$last_name;
		if ($name == ' ') $name = $data->display_name;
		$keys = array (
			'id' => $data->ID,
			'name' => $name,
		);
		foreach ($keys as $k => $v) {
			$this->$k = isset($v) ? $v : '';
		};
		unset($this->db);
	}
}
