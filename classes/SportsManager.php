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
			'faq' => (object) array (
				'class' => 'SportsManager_FAQ',
				'table' => $wpdb->prefix.$this->prefix.'faq'
			),
			'users' => (object) array (
				'class' => 'SportsManager_User',
				'table' => $wpdb->prefix.'users'
			)
		);
		$this->args = (object) array (
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
		$this->db = (object) array ();
		$this->languages = array (
			'en' => array ('English', 'English'),
			'fr' => array ('French', 'Français')
		);
		$this->sports = array (
			'baseball' => array ('Baseball', 'Baseball'),
			'basketball' => array ('Basketball', 'Basketball'),
			'cricket' => array ('Cricket', 'Cricket'),
			'handball' => array ('Handball', 'Handball'),
			'hockey' => array ('Hockey', 'Hockey'),
			'football' => array ('Football', 'Football'),
			'soccer' => array ('Soccer', 'Soccer'),
			'rugby' => array ('Rugby', 'Rugby'),
			'volleyball' => array ('Volleyball', 'Volleyball'),
			'waterpolo' => array ('Water Polo', 'Water Polo')
		);
	}

	function query_leagues() {
		global $wpdb;
		$leagues = array ();
		$table = $this->objects->leagues->table;
		$q = "SELECT name, slug FROM $table ORDER BY name ASC";
		$results = $wpdb->get_results($q);
		if ($results != null) {
			foreach ($results as $result) {
				$leagues[$result->slug] = $result->name;
			};
		};
		return $leagues;
	}

	function query_seasons() {
		global $wpdb;
		$seasons = array ();
		$objects = array ('games', 'scoresheets', 'teams');
		foreach ($objects as $object) {
			$table = $this->objects->$object->table;
			$q = "SELECT season FROM $table GROUP BY season ORDER BY season ASC";
			$results = $wpdb->get_col($q);
			$seasons = array_merge($seasons, $results);
		};
		return array_unique($seasons);
	}

	function query_sports() {
		global $wpdb;
		$sports = array ();
		$objects = array ('clubs', 'games', 'scoresheets');
		foreach ($objects as $object) {
			$table = $this->objects->$object->table;
			$q = "SELECT sport FROM $table GROUP BY sport ORDER BY sport ASC";
			$results = $wpdb->get_col($q);
			$sports = array_merge($sports, $results);
		};
		return array_unique($sports);
	}

	function query_faq() {
		global $wpdb;
		$faq = array ();
		$table = $this->objects->faq->table;
		$q = "SELECT * FROM $table";
		$results = $wpdb->get_results($q);
		if ($results != null) {
			foreach ($results as $result) {
				$faq[] = new SportsManager_FAQ($result);
			};
		};
		return sm_group_array_objects_by('type', $faq);
	}

	function query_users($role = '') {
		global $wpdb;
		$users = array ();
		$results = get_users('role='.$role);
		foreach ($results as $result) {
			$objects[] = new SportsManager_User($result);
		};
		return sm_order_array_objects_by('name', $users);
	}

	function query_dependancies($filter) {
		if (array_key_exists($filter, $this->dependancies)) {
			foreach ($this->dependancies->$filter->tables as $dependancy) {
				if ($dependancy == 'users') {
					$objects = array ();
					foreach (array ('player', 'executive') as $role) { //'captain' was removed
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

	function verify_required_args($filter) {
		if ($filter != '' && isset($this->dependancies->$filter)) {
			foreach ($this->dependancies->$filter->args as $arg) {
				if (is_string ($arg)) {
					if ($this->args->$arg == '') {
						$error = "<p>(Sports Manager Error: '%s' attribute is required)</p>\n";
						echo sprintf($error, $arg);
						return false;
					};
				} elseif (is_array($arg)) {
					$missing_attr = true;
					$too_many_attr = true;
					foreach ($arg as $k) {
						if ($this->args->$k != '') {
							$missing_attr = false;
							break;
						};
					};
					if ($missing_attr) {
						$error = "<p>(Sports Manager Error: at least one of '%s' attributes is required)</p>\n";
						echo sprintf($error, implode("', '", $arg));
						return false;
					};
				};
			};
			return true;
		} else {
			$error = "<p>(Sports Manager Error: '%s' attribute is required or invalid)</p>\n";
			echo sprintf($error, 'display');
			return false;
		};
	}

	function build($args = array ()) {
		global $wpdb;
		foreach ($args as $k => $v) {
			$this->args->$k = $v;
		};
		if ($this->args->league_id == '' && $this->args->league_slug != '') {
			$table = $this->objects->leagues->table;
			$slug = $this->args->league_slug;
			$q = "SELECT id FROM $table WHERE slug = '$slug'";
			$this->args->league_id = $wpdb->get_var($q);
		} elseif ($this->args->league_id == '' && $this->args->league_slug == '' && $this->args->league_name != '') {
			$table = $this->objects->leagues->table;
			$name = $this->args->league_name;
			$q = "SELECT id FROM $table WHERE name = '$name'";
			$this->args->league_id = $wpdb->get_var($q);
		};
	}

	function include_view($s) {
		$f = SPORTSMANAGER_DIR.'controllers/'.$s.'.php';
		if (file_exists($f)) {include($f);};
		$f = SPORTSMANAGER_DIR.'views/'.$s.'.php';
		if (file_exists($f)) {include($f);} else {echo $f.' view file does not exist';};
	}
}
