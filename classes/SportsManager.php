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
			)
		);
		$this->args = (object) array ();
		register_activation_hook(SPORTSMANAGER_DIR.'sportsmanager.php', array (&$this, 'install_plugin'));
		register_uninstall_hook($this->plugin_name, array('SportsManager', 'uninstall')); //register_uninstall_hook(SPORTSMANAGER_DIR.'sportsmanager.php', array (&$this, 'uninstall_plugin'));
	}

	function install_plugin() {
		global $wpdb;
		$q = file_get_contents(SPORTSMANAGER_DIR.'structure.sql');
		if ($q != false) {
			echo "IT WORKED";
			die;
			$wpdb->query($q);
		};
	}

	function uninstall_plugin() {
		if (defined('WP_UNINSTALL_PLUGIN')) {
			global $wpdb;
			$q = '';
			foreach ($this->objects as $object) {
				$q .= "DROP TABLE ".$object->table." \n";
			};
			$wpdb->query($q);
		};
	}

	function build($args) {
		$defaults = array (
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
}
