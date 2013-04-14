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
			if (isset($this->user_id)) {
				foreach (array ('first_name', 'last_name') as $k) {
					if (!isset($this->$k)) {
						$v = get_user_meta($this->user_id, $k, true);
						if ($v != '') $this->$k = $v;
					};
				};
				if (isset($this->first_name, $this->last_name)) {
					$this->name = $this->first_name.' '.$this->last_name;
				};
			};
		} elseif ($this->filter == 'teams') {
			if (isset($this->club_id)) {
				$this->name = $wpdb->get_var("SELECT name FROM ".$SM->objects->clubs->table." WHERE id = '".$this->club_id."'");
			};
		};
	}
}
