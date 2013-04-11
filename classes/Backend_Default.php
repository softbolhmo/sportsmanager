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
	function __construct($data = '', $filter = '') {
		if (is_int($data) && $filter != '') {
			$this->query_row($filter, $data);
		} elseif (is_object($data) || is_array($data)) {
			$this->build($data);
		};
	}
	function query_row($filter, $data) {
		global $wpdb;
		$table = $wpdb->prefix.'sportsmanager_'.$filter;
		$q = "SELECT * FROM $table WHERE id = $data";
		$object = $wpdb->get_row($q);
		$this->build($object);
	}

	function build($data) {
		foreach ($data as $k => $v) {
			$this->$k = isset($v) ? $v : '';
		};
	}
}
