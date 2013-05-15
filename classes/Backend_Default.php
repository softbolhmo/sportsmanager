<?php
/**
 * Default backend object class
 * @package SportsManager
 * @subpackage classes
 */

/**
 * Class for default backend object
 * @package SportsManager
 */
class SportsManager_Backend_Default {
	function __construct($data = '', $filter) {
		$this->filter = $filter;
		if (is_int($data)) {
			$this->query_row($data);
		} elseif (is_object($data) || is_array($data)) {
			$this->build($data);
		};
	}
	function query_row($data) {
		global $wpdb;
		$table = $wpdb->prefix.'sportsmanager_'.$filter;
		$q = "SELECT * FROM $table WHERE id = $data";
		$data = $wpdb->get_row($q);
		$this->build($data);
	}

	function build($data) {
		global $wpdb, $SM;
		foreach ($data as $k => $v) {
			$this->$k = isset($v) ? $v : '';
		};
		if ($this->filter == 'players') {
			$names = array ('first' => '', 'last' => '', 'full' => '');
			$data->user = (object) array ();
			if (isset($this->user_id)) {
				foreach (array ('first_name', 'last_name', 'display_name') as $k) {
					$v = get_user_meta($this->user_id, $k, true);
					if ($v != '') $data->user->$k = $v;
				};
				$data->user->name = get_user_meta($this->user_id, 'display_name', true);
			};
			if (isset($this->infos)) $infos = json_decode($this->infos);
			$names['first'] = isset($infos->first_name) && !in_array($infos->first_name, array ('', '0')) ? $infos->first_name : (isset($datauser->first_name) ? $data->user->first_name : '');
			$names['last'] = isset($infos->last_name) && !in_array($infos->last_name, array ('', '0')) ? $infos->last_name : (isset($data->user->last_name) ? $data->user->last_name : '');
			$names['full'] = implode(' ', $names);
			if (in_array($names['full'], array ('', ' '))) $names['full'] = isset($data->user->name) ? $data->user->name : '';
			$this->first_name = $names['first'];
			$this->last_name = $names['last'];
			$this->name = $names['full'];
		} elseif ($this->filter == 'teams') {
			if (isset($this->club_id)) {
				$this->name = $wpdb->get_var("SELECT name FROM ".$SM->objects->clubs->table." WHERE id = '".$this->club_id."'");
			};
		};
	}
}
