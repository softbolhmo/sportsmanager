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
	function __construct($data = '', $db = '', $args = '') {
		$this->filter = 'users';
		$this->args = $args;
		parent::__construct($data, $db);
	}

	function build($data) {
		$names = array ();
		$names['first'] = isset($data->ID) ? get_user_meta($data->ID, 'first_name', true) : '';
		$names['last'] = isset($data->ID) ? get_user_meta($data->ID, 'last_name', true) : '';
		$names['full'] = implode(' ', $names);
		if (in_array($names['full'], array ('', ' '))) $names['full'] = isset($data->display_name) ? $data->display_name : '';
		$keys = array (
			'id' => isset($data->ID) ? $data->ID : '',
			'slug' => isset($data->user_nicename) ? $data->user_nicename : '',
			'name' => $names['full'],
			'first_name' => $names['first'],
			'last_name' => $names['last'],
		);
		foreach ($keys as $k => $v) {
			$this->$k = isset($v) ? $v : '';
		};
		unset($this->args, $this->db);
	}
}
