<?php
/**
 * Default plugin class
 * @package SportsManager
 * @subpackage classes
 */

/**
 * Class for plugin object
 * @package SportsManager
 */
class SportsManager {
	function __construct() {
		global $wpdb;
		$this->plugin_name = 'sportsmanager';
		$this->prefix = SPORTSMANAGER_PREFIX;
		$this->objects = (object) array (
			'clubs' => (object) array (
				'class' => 'SportsManager_Club',
				'table' => $wpdb->prefix.$this->prefix.'clubs'
			),
			'games' => (object) array (
				'class' => 'SportsManager_Game',
				'table' => $wpdb->prefix.$this->prefix.'games'
			),
			'leagues' => (object) array (
				'class' => 'SportsManager_League',
				'table' => $wpdb->prefix.$this->prefix.'leagues'
			),
			'locations' => (object) array (
				'class' => 'SportsManager_Location',
				'table' => $wpdb->prefix.$this->prefix.'locations'
			),
			'players' => (object) array (
				'class' => 'SportsManager_Player',
				'table' => $wpdb->prefix.$this->prefix.'players'
			),
			'scoresheets' => (object) array (
				'class' => 'SportsManager_Scoresheet',
				'table' => $wpdb->prefix.$this->prefix.'scoresheets'
			),			
			'teams' => (object) array (
				'class' => 'SportsManager_Team',
				'table' => $wpdb->prefix.$this->prefix.'teams'
			),
			'users' => (object) array (
				'class' => 'SportsManager_User',
				'table' => $wpdb->prefix.'users'
			)
		);
		$this->args = (object) array ();
		$this->db = (object) array ();
		$this->languages = array (
			'en' => 'English',
			//'fr' => 'Français',
		);
		$this->sports = array (
			'baseball' => 'Baseball',
			'basketball' => 'Basketball',
			'cricket' => 'Cricket',
			'handball' => 'Handball',
			'hockey' => 'Hockey',
			'football' => 'Football',
			'soccer' => 'Soccer',
			'rugby' => 'Rugby',
			'volleyball' => 'Volleyball',
			'waterpolo' => 'Water Polo'
		);
	}

	function query_users($role = '') {
		global $wpdb;
		$table = $this->objects->users->table;
		$q = "SELECT ID, display_name FROM $table ORDER BY display_name ASC";
		$objects = array ();
		$users = get_users('role='.$role);
		foreach ($users as $user) {
			$objects[] = new SportsManager_User($user);
		};
		return $this->order_array_objects_by('name', $objects);
	}

	function query_dependancies($filter) {
		if (array_key_exists($filter, $this->dependancies)) {
			foreach ($this->dependancies->$filter as $dependancy) {
				if ($dependancy == 'users') {
					$objects = array ();
					foreach (array ('player', 'captain', 'executive') as $role) {
						$objects = array_merge($objects, $this->query_users($role));
					};
				} else {
					$objects = $this->query_objects($dependancy);
				};
				$this->db->$dependancy = $objects;
			};
			return true;
		} else {
			return false;
		};
	}

	function build($args = array ()) {
		$defaults = array (
			'display' => '',
			'filter' => '',
			'game_id' => '',
			'game_type' => '',
			'league_id' => '',
			'league_name' => '',
			'league_slug' => '',
			'season' => '',
			'sport' => '',
			'team_id' => '',
			'team_slug' => '',
			'user_id' => '',
			'top' => '',
			'sortable' => true
		);
		if (isset($args)) {
			$args = (object) array_merge($defaults, $args);
		} else {
			$args = (object) $defaults;
		};
		$this->args = $args;
	}

	function include_view($s) {
		$f = SPORTSMANAGER_DIR.'controllers/'.$s.'.php';
		if (file_exists($f)) {include($f);};
		$f = SPORTSMANAGER_DIR.'views/'.$s.'.php';
		if (file_exists($f)) {include($f);} else {echo $f.' view file does not exist';};
	}

	function mysql_where_string($wheres) {
		if (!empty($wheres)) {
			$q = 'WHERE ';
			foreach ($wheres as $where) {
				$q .= $where.' AND ';
			};
			$q = rtrim($q, 'AND ').' ';
			return $q;
		};
	}

	function order_array_objects_by($keys, $objects, $reverse = false) {
		if ($keys == '') return $objects;
		if (!is_array($keys)) $keys = (array) $keys;
		foreach (array_reverse($keys) as $key) {
			$order = array ();
			foreach ($objects as $i => $object) {
				$order[$i] = isset($object->$key) ? $object->$key : '';
			};
			asort($order);
			$in_order = array ();
			foreach ($order as $k => $v) {
				$in_order[] = $objects[$k];
			};
			if ($reverse) $in_order = array_reverse($in_order);
			$objects = $in_order;
		};
		return $objects;
	}
}
