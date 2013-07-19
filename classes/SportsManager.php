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
		$this->default_args = (object) array (
			'display' => '',
			'filter' => '',
			'game_id' => '',
			'game_type' => '',
			'lang' => 'en',
			'location_id' => '',
			'location_name' => '',
			'location_slug' => '',
			'league_id' => '',
			'league_name' => '',
			'league_slug' => '',
			'player_id' => '',
			'player_slug' => '',
			'season' => '',
			'seasons_array' => array (),
			'sport' => '',
			'team_id' => '',
			'team_name' => '',
			'team_slug' => '',
			'user_id' => '',
			'top' => 0,
			'sortable' => true,
			'default_clubs_url' => get_option('sportsmanager_default_clubs_url', ''),
			'default_locations_url' => get_option('sportsmanager_default_locations_url', ''),
			'default_players_url' => get_option('sportsmanager_default_players_url', ''),
			'default_results_url' => get_option('sportsmanager_default_results_url', ''),
			'default_stats_url' => get_option('sportsmanager_default_stats_url', ''),
			'default_teams_url' => get_option('sportsmanager_default_teams_url', '')
		);
		$this->options = array (
			'disable_intro',
			'email',
			'email_name',
			'language',
			'custom_class_table',
			'default_clubs_url',
			'default_locations_url',
			'default_players_url',
			'default_results_url',
			'default_stats_url',
			'default_teams_url'
		);
		$this->db = (object) array ();
		$this->restricted_backend_keys = array (
			'season' => array ('slug', '5'),
			'slug' => array ('slug')
		);
		$this->special_backend_keys = array (
			'away_team_id' => array ('query', 'club_id', 'teams', '_team_name'),
			'cancelled' => array ('constant', 'yes_no'),
			'club_id' => array ('query', 'name', 'clubs'),
			'home_team_id' => array ('query', 'club_id', 'teams', '_team_name'),
			'inactive' => array ('constant', 'yes_no'),
			'league_id' => array ('query', 'name', 'leagues'),
			'location_id' => array ('query', 'name', 'locations'),
			'sport' => array ('constant', 'sports'),
			'winner_team_id' => array ('query', 'club_id', 'teams', '_team_name'),
			'_team_name' => array ('query', 'name', 'clubs'),
		);
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
		$this->yes_no = array (
			'0' => array ('No', 'No'),
			'1' => array ('Yes', 'Yes')
		);
		$this->lang = (object) array ();
	}

	function __($k) {
		return isset($this->lang->{$this->args->lang}[$k]) ? $this->lang->{$this->args->lang}[$k] : $k;
	}

	function is_restricted_backend_key($k) {
		return isset($this->restricted_backend_keys[$k]);
	}

	function is_special_backend_key($k) {
		return isset($this->special_backend_keys[$k]);
	}

	function get_special_backend_key_name($k, $v) {
		global $wpdb;
		if (isset($this->special_backend_keys[$k])) {
			$key = $this->special_backend_keys[$k];
			if ($key[0] == 'query') {
				$name = $v != '' ? $wpdb->get_var("SELECT ".$key[1]." FROM ".$this->objects->{$key[2]}->table." WHERE id = $v") : '';
				if (isset($key[3])) $name = $this->get_special_backend_key_name($key[3], $name);
			} elseif ($key[0] == 'constant') {
				if (isset($this->{$key[1]}[$v])) {
					$constant = $this->{$key[1]}[$v];
					if (is_array($constant)) {
						$name = $this->{$key[1]}[$v][0];
					} else {
						$name =  $this->{$key[1]}[$v];
					};
				} else {
					$name = '';
				};
			} else {
				$name = $v;
			};
			if (!in_array($v, array('', 0)) && $name == '') $name = '?';
		} else {
			$name = $v;
		};
		return $name;
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
			$users[] = new SportsManager_User($result);
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
				if (is_string($arg)) {
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
		$this->args = clone $this->default_args;
		foreach ($args as $k => $v) {
			$this->args->$k = $v;
		};
		//leagues
		if ($this->args->league_id == '' && $this->args->league_slug != '') {
			$table = $this->objects->leagues->table;
			$slug = $this->args->league_slug;
			$q = "SELECT id FROM $table WHERE slug = '$slug' ORDER BY id DESC LIMIT 1";
			$this->args->league_id = $wpdb->get_var($q);
			if ($this->args->league_id == '') $this->args->league_id = 0;
		} elseif ($this->args->league_id == '' && $this->args->league_slug == '' && $this->args->league_name != '') {
			$table = $this->objects->leagues->table;
			$name = $this->args->league_name;
			$q = "SELECT id FROM $table WHERE name = '$name' ORDER BY id DESC LIMIT 1";
			$this->args->league_id = $wpdb->get_var($q);
			if ($this->args->league_id == '') $this->args->league_id = 0;
		};
		//locations
		if ($this->args->location_id == '' && $this->args->location_slug != '') {
			$table = $this->objects->locations->table;
			$slug = $this->args->location_slug;
			$q = "SELECT id FROM $table WHERE slug = '$slug' ORDER BY id DESC LIMIT 1";
			$this->args->location_id = $wpdb->get_var($q);
			if ($this->args->location_id == '') $this->args->location_id = 0;
		} elseif ($this->args->location_id == '' && $this->args->location_slug == '' && $this->args->location_name != '') {
			$table = $this->objects->locations->table;
			$name = $this->args->location_name;
			$q = "SELECT id FROM $table WHERE name = '$name' ORDER BY id DESC LIMIT 1";
			$this->args->location_id = $wpdb->get_var($q);
			if ($this->args->location_id == '') $this->args->location_id = 0;
		};
		//teams
		if ($this->args->team_id == '' && $this->args->team_slug != '') {
			$table = $this->objects->clubs->table;
			$slug = $this->args->team_slug;
			$q = "SELECT id FROM $table WHERE slug = '$slug'";
			$club_id = $wpdb->get_var($q);
			$table = $this->objects->teams->table;
			$season = $this->args->season != '' && is_numeric($this->args->season) ? " AND season = '".$this->args->season."' " : ' ';
			$q = "SELECT id FROM $table WHERE club_id = '$club_id'".$season."ORDER BY id DESC LIMIT 1";
			$this->args->team_id = $wpdb->get_var($q);
			if ($this->args->team_id == '') $this->args->team_id = 0;
		} elseif ($this->args->team_id == '' && $this->args->team_slug == '' && $this->args->team_name != '') {
			$table = $this->objects->clubs->table;
			$name = $this->args->team_name;
			$q = "SELECT id FROM $table WHERE name = '$name'";
			$club_id = $wpdb->get_var($q);
			$table = $this->objects->teams->table;
			$season = $this->args->season != '' && is_numeric($this->args->season) ? " AND season = '".$this->args->season."' " : ' ';
			$q = "SELECT id FROM $table WHERE club_id = '$club_id'".$season."ORDER BY id DESC LIMIT 1";
			$this->args->team_id = $wpdb->get_var($q);
			if ($this->args->team_id == '') $this->args->team_id = 0;
		};
	}

	function include_language($k) {
		$f = SPORTSMANAGER_DIR.'languages/'.$k.'.php';
		if (file_exists($f)) {include($f);} else {echo $f.' language file does not exist';};
	}

	function include_view($k) {
		$f = SPORTSMANAGER_DIR.'controllers/'.$k.'.php';
		if (file_exists($f)) {include($f);};
		$f = SPORTSMANAGER_DIR.'views/'.$k.'.php';
		if (file_exists($f)) {include($f);} else {echo $f.' view file does not exist';};
	}
}
