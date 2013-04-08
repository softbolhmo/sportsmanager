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
class SportsManager_Backend extends SportsManager {
	function __construct() {
		parent::__construct();
		$this->build(array (
			'league_slug' => isset($_SESSION['sm_league']) ? $_SESSION['sm_league'] : '',
			'season' => isset($_SESSION['sm_season']) ? $_SESSION['sm_season'] : '',
			'sport' => isset($_SESSION['sm_sport']) ? $_SESSION['sm_sport'] : ''
		));
		$this->add_user_roles();
		$this->edit_admin_roles();
		add_action('init', 'sm_define_session', 1);
		add_action('admin_menu', array (&$this, 'add_menu_items'));
	}

	function add_user_roles() {
		$roles = array (
			(object) array (
				'slug' => 'player',
				'name' => 'Player',
				'capabilities' => array (
					'read' => true
				)
			),
			(object) array (
				'slug' => 'captain',
				'name' => 'Captain',
				'capabilities' => array (
					'delete_posts' => true,
					'delete_published_posts' => true,
					'edit_posts' => true,
					'edit_published_posts' => true,
					'publish_posts' => true,
					'read' => true,
					'upload_files' => true
				)
			),
			(object) array (
				'slug' => 'executive',
				'name' => 'Executive',
				'capabilities' => array (
					'delete_posts' => true,
					'delete_published_posts' => true,
					'edit_posts' => true,
					'edit_published_posts' => true,
					'publish_posts' => true,
					'read' => true,
					'upload_files' => true
				)
			),
		);
		foreach ($roles as $role) {
			$role = new SportsManager_Role($role);
			$role->add_role();
		};
	}

	function edit_admin_roles() {
		$roles = array (
			(object) array (
				'slug' => 'administrator',
				'capabilities' => array ('all')
			),
			(object) array (
				'slug' => 'executive',
				'capabilities' => array ('games', 'scoresheets', 'import')
			)
		);
		foreach ($roles as $role) {
			$data = (object) array ('slug' => $role->slug);
			$capabilities = array ();
			foreach ($role->capabilities as $capability) {
				$capabilities[] = SPORTSMANAGER_CAPABILITY_PREFIX.$capability;
			};
			$role = new SportsManager_Role($data);
			$role->add_capability($capabilities);
			//$role->remove_capability(array ('', 'all', 'home', 'clubs', 'games', 'locations', 'leagues', 'players', 'scoresheets', 'teams', 'import', 'donate'));
		};
	}

	function add_menu_items() {
		$plugin_page = add_menu_page('Sports Manager', 'Sports Manager', 'edit_sportsmanager', 'sportsmanager', array (&$this, 'generate'), 'div', '26.6973216'); //26.6973216 is a random decimal number
		add_action('admin_head', array (&$this, 'include_styles'));
		add_action('admin_head-'.$plugin_page, array (&$this, 'include_scripts'));
	}

	function include_styles() {
		$this->include_view('header_styles');
	}

	function include_scripts() {
		$this->include_view('header_scripts');
	}

	function query_dependancies($filter) {
		$this->dependancies = (object) array (
			'clubs' => array ('clubs', 'leagues'), //always name primary object first
			'games' => array ('games', 'leagues', 'locations', 'teams'),
			'leagues' => array ('leagues'),
			'locations' => array ('locations'),
			'players' => array ('players'),
			'scoresheets' => array ('scoresheets', 'games', 'leagues', 'players'),
			'teams' => array ('teams', 'clubs', 'leagues'),
		);
		if (array_key_exists($filter, $this->dependancies)) {
			foreach ($this->dependancies->$filter as $dependancy) {
				$this->db->$dependancy = $this->query_objects($dependancy);
			};
			return true;
		} else {
			return false;
		};
	}

	function query_objects($filter) {
		global $wpdb;
		$table = $this->objects->$filter->table;
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
		$q .= $this->mysql_where_string($wheres);
		$objects = array ();
		$results = $wpdb->get_results($q);
		foreach ($results as $result) {
			$objects[] = new SportsManager_Backend_Default($result, $filter);
		};
		//this could go in the SportsManager_Backend_Default class... instead of going through each object after objects were defined, why not simply defining them properly in the first place?
		if ($filter == 'players') {
			foreach ((array) $objects as $object) {
				if (isset($object->user_id)) {
					foreach (array ('first_name', 'last_name') as $k) {
						if (!isset($object->$k)) {
							$v = get_user_meta($object->user_id, $k, true);
							if ($v != '') $object->$k = $v;
						};
					};
					if (isset($object->first_name, $object->last_name)) {
						$object->name = $object->first_name.' '.$object->last_name;
					};
				};
			};
		} elseif ($filter == 'teams') {
			foreach ((array) $objects as $object) {
				if (isset($object->club_id)) {
					$object->name = $wpdb->get_var("SELECT name FROM ".$this->objects->clubs->table." WHERE id = '".$object->club_id."'");
				};
			};
		};
		return $objects;
	}

	function generate() {
		$views = array (
			'default' => '404',
			'home' => 'home',
			'clubs' => 'filters',
			'games' => 'filters',
			'locations' => 'filters',
			'leagues' => 'filters',
			'players' => 'filters',
			'scoresheets' => 'filters',
			'teams' => 'filters',
			'import' => 'import',
			'faq' => 'faq',
			'donate' => 'donate'
		);
		$view = isset($views[SPORTSMANAGER_FILTER]) ? $views[SPORTSMANAGER_FILTER] : $views['default'];
		$this->include_view('header');
		if (current_user_can(SPORTSMANAGER_CAPABILITY_PREFIX.SPORTSMANAGER_FILTER) || current_user_can(SPORTSMANAGER_CAPABILITY_PREFIX.'all')) {
			if ($view == 'filters') {
				if ($this->query_dependancies(SPORTSMANAGER_FILTER)) {
					$primary_object = reset($this->dependancies->{SPORTSMANAGER_FILTER});
					$this->rows = $this->db->$primary_object;
					$this->include_view($view);
				};
			} else {
				$this->include_view($view);
			};
		} else {
			$this->include_view('unauthorized');
		};
		$this->include_view('footer');
	}
}
