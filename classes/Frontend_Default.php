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
}
