<?php
/**
 * Default frontend object class
 * @package SportsManager
 * @subpackage classes
 */

/**
 * Class for default frontend object
 * @package SportsManager
 */
class SportsManager_Frontend_Default {
	function __construct($data = '', $db) {
		$this->db = $db;
		$this->build($data);
	}

	function get_element_where($array, $key, $value, $property = '') { //why is this here?
		foreach ($array as $k => $v) {
			if ($v->$key == $value) {
				if ($property == '') {
					return $array[$k];
				} else {
					return $array[$k]->$property;
				};
				break;
			};
		};
	}
}
