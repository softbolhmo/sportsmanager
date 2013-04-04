<?php
/**
 * Backend plugin class
 * @package SportsManager
 * @subpackage classes
 */

/**
 * Class for backend plugin object
 * @package SportsManager
 */
class SportsManager_Backend {
	function __construct() {
		global $wpdb;
		$this->prefix = SPORTSMANAGER_PREFIX;
		$this->objects = (object) array (
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
			)
		);
		sm_install_plugin();
		//register_activation_hook(__FILE__,'sm_install_plugin');
		//register_deactivation_hook(__FILE__ , 'sm_uninstall_plugin');
		add_action('init', array (&$this, 'session'), 1);
		add_action('admin_menu', array (&$this, 'add_menu_items'));
	}

	function query_dependancies($filter) {
		$this->dependancies = (object) array (
			'games' => array ('games', 'leagues', 'locations', 'teams'), //always name primary object first
			'leagues' => array ('leagues'),
			'locations' => array ('locations'),
			'players' => array ('players'),
			'scoresheets' => array ('scoresheets', 'games', 'leagues', 'players'),
			'teams' => array ('teams', 'leagues'),
		);
		if (array_key_exists($filter, $this->dependancies)) {
			foreach ($this->dependancies->$filter as $dependancy) {
				if (!isset($this->db->$dependancy)) {
					$this->db->$dependancy = $this->query_objects($dependancy);
				};
			};
			return true;
		} else {
			return false;
		};
	}

	function query_objects($filter) {
		global $wpdb;
		$table = $wpdb->prefix.SPORTSMANAGER_PREFIX.$filter;
		$q = "SELECT * FROM $table ";
		$wheres = array ();
		if (isset($_SESSION['sm_league']) && $_SESSION['sm_league'] != '' && in_array($filter, array ('games', 'scoresheets', 'teams'))) {
			$id = $wpdb->get_var("SELECT id FROM ".$this->objects->leagues->table." WHERE slug = '".$_SESSION['sm_league']."'");
			$wheres[] = "league_id IN ('$id', '')";
		};
		if (isset($_SESSION['sm_season']) && $_SESSION['sm_season'] != '' && in_array($filter, array ('games', 'scoresheets', 'teams'))) {
			$wheres[] = "season IN ('".$_SESSION['sm_season']."', '')";
		};
		if (isset($_SESSION['sm_sport']) && $_SESSION['sm_sport'] != '' && in_array($filter, array ('games', 'scoresheets', 'teams'))) {
			$wheres[] = "sport IN ('".$_SESSION['sm_sport']."', '')";
		};
		$q .= $this->where_string($wheres);
		$objects = array ();
		$results = $wpdb->get_results($q);
		foreach ($results as $result) {
			$objects[] = new SportsManager_Backend_Default($result, $filter);
		};
		if ($filter == 'players') {
			foreach ((array) $objects as $object) {
				if (isset($object->user_id)) {
					foreach (array ('first_name', 'last_name') as $k) {
						if (!isset($object->$k)) {
							$v = get_user_meta($object->user_id, $k, true);
							if ($v != '') $object->$k = $v;
						};
						if (isset($object->first_name, $object->last_name)) {
							$object->name = $object->first_name.' '.$object->last_name;
						};
					};
				};
			};
		};
		return $objects;
	}

	function where_string($wheres) {
		if (!empty($wheres)) {
			$q = 'WHERE ';
			foreach ($wheres as $where) {
				$q .= $where.' AND ';
			};
			$q = rtrim($q, 'AND ').' ';
			return $q;
		};
	}

	function session() {
		if(!session_id()) session_start();
		$session = (object) array (
			'sm_league' => '(Select league)',
			'sm_season' => date('Y'),
			'sm_sport' => '(Select sport)'
		);
		foreach ($session as $k => $v) {
			if (!isset($_SESSION[$k])) $_SESSION[$k] = $v;
			if (isset($_REQUEST[$k])) $_SESSION[$k] = $_REQUEST[$k];
		};
	}

	/*
	 * REGISTER THE PAGE IN WP ADMIN
	 */
	function add_menu_items() {
		$plugin_pages = array ();
		//add parent
		//make sure user has custom capability in roles.php
		$plugin_pages[] = add_menu_page('Sports Manager', 'Sports Manager', 'edit_sportsmanager', 'sportsmanager', array (&$this, 'generate'));
		//add children
		foreach($plugin_pages as $plugin_page) {
			add_action('admin_head-'.$plugin_page, array (&$this, 'load_scripts'));
		};
	}
	
	/*
	 * VIEWS
	 */
	function include_view($s) {
		$f = SPORTSMANAGER_DIR.'controllers/'.$s.'.php';
		if (file_exists($f)) {include($f);};
		$f = SPORTSMANAGER_DIR.'views/'.$s.'.php';
		if (file_exists($f)) {include($f);} else {echo $f.' view file does not exist';};
	}

	function load_scripts() {
		$this->include_view('header_scripts');
	}

	function generate() {
		$views = array (
			'default' => 'home',
			'games' => 'filters',
			'locations' => 'filters',
			'leagues' => 'filters',
			'players' => 'filters',
			'scoresheets' => 'filters',
			'teams' => 'filters',
			'import' => 'import',
			'donate' => 'donate'
		);
		$view = isset($views[SPORTSMANAGER_FILTER]) ? $views[SPORTSMANAGER_FILTER] : $views['default'];
		if ($view == 'filters') {
			if ($this->query_dependancies(SPORTSMANAGER_FILTER)) {
				$primary_object = reset($this->dependancies->{SPORTSMANAGER_FILTER});
				$this->rows = $this->db->$primary_object;
				$this->include_view('header');
				$this->include_view($view);
				$this->include_view('footer');
			};
		} else {
			$this->include_view('header');
			$this->include_view($view);
			$this->include_view('footer');
		};
	}
}
