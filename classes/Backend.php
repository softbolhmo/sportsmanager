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
		$this->dependancies = (object) array (
			'clubs' => array ('clubs', 'leagues'), //always name primary object first
			'games' => array ('games', 'leagues', 'locations', 'teams'),
			'leagues' => array ('leagues'),
			'locations' => array ('locations'),
			'players' => array ('players', 'users'),
			'scoresheets' => array ('scoresheets', 'games', 'leagues', 'players'),
			'teams' => array ('teams', 'clubs', 'leagues', 'players'),
		);
		if (!session_id()) session_start();
		$this->build(array (
			'league_slug' => isset($_SESSION['sm_season']) ? $_SESSION['sm_league'] : '',
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
				'capabilities' => array ('games', 'scoresheets', 'import', 'faq', 'donate')
			),
			/*
			(object) array (
				'slug' => 'subscriber',
				'capabilities' => array ()
			)
			*/
		);
		foreach ($roles as $role) {
			$data = (object) array ('slug' => $role->slug);
			$capabilities = array ('edit_sportsmanager');
			foreach ($role->capabilities as $capability) {
				$capabilities[] = SPORTSMANAGER_CAPABILITY_PREFIX.$capability;
			};
			$role = new SportsManager_Role($data);
			$role->add_capability($capabilities);
			//$role->remove_capability(array ('', 'all', 'home', 'clubs', 'games', 'locations', 'leagues', 'players', 'scoresheets', 'teams', 'import', 'faq', 'donate'));
		};
	}

	function add_menu_items() {
		$plugin_page = add_menu_page('Sports Manager', 'Sports Manager', 'edit_sportsmanager', 'sportsmanager', array (&$this, 'generate'), 'div', '26.6973216'); //26.6973216 is a random decimal number
		add_action('admin_head', array (&$this, 'include_general'));
		add_action('admin_head-'.$plugin_page, array (&$this, 'include_specific'));
	}

	function include_general() {
		$this->include_view('header_general');
	}

	function include_specific() {
		wp_enqueue_style('thickbox');
		wp_enqueue_script('media-upload');
		wp_enqueue_script('thickbox');
		$this->include_view('header_specific');
	}

	function query_objects($filter) {
		global $wpdb;
		$table = $this->objects->$filter->table;
		$q = "SELECT * FROM $table ";
		$wheres = array ();
		if (isset($this->args->league_slug) && $this->args->league_slug != '' && in_array($filter, array ('games', 'scoresheets', 'teams'))) {
			$id = $wpdb->get_var("SELECT id FROM ".$this->objects->leagues->table." WHERE slug = '".$this->args->league_slug."'");
			$wheres[] = "league_id IN ('$id', '')";
		};
		if (isset($this->args->season) && $this->args->season != '' && in_array($filter, array ('games', 'scoresheets', 'teams'))) {
			$wheres[] = "season IN ('".$this->args->season."', '')";
		};
		if (isset($this->args->sport) && $this->args->sport != '' && in_array($filter, array ('games', 'scoresheets', 'teams'))) {
			$wheres[] = "sport IN ('".$this->args->sport."', '')";
		};
		$q .= $this->mysql_where_string($wheres);
		$objects = array ();
		$results = $wpdb->get_results($q);
		foreach ($results as $result) {
			$objects[] = new SportsManager_Backend_Default($result, $filter);
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
