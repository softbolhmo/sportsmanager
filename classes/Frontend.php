<?php
/**
 * Frontend plugin class
 * @package SportsManager
 * @subpackage classes
 */

/**
 * Class for frontend plugin object
 * @package SportsManager
 */
class SportsManager_Frontend {
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
	}

	function query_dependancies($filter) {
		$this->dependancies = (object) array (
			//always name primary object first
			'game_results' => array ('games', 'locations', 'teams'),
			'game_stats' => array ('scoresheets', 'players'),
			'leaders_stats' => array ('players', 'scoresheets', 'games'),
			'pitching_stats' => array ('players', 'scoresheets'),
			'player_stats' => array ('players', 'scoresheets'),
			'players_stats' => array ('players', 'scoresheets'),
			'rankings' => array ('teams', 'games'),
			'schedule' => array ('games', 'locations', 'teams'),
			'schedule_playoff' => array ('games', 'locations', 'teams'),
			'team_players_stats' => array ('players', 'scoresheets', 'teams'),
			'team_stats' => array ('players', 'scoresheets', 'teams'),
			'teams_stats' => array ('players', 'scoresheets', 'teams')
		);
		if (array_key_exists($filter, $this->dependancies)) {
			foreach ($this->dependancies->$filter as $dependancy) {
				//if (!isset($this->db->$dependancy)) { //removing this allows you to use the same SportsManager_Frontend object for multiple tables
					$this->db->$dependancy = $this->query_objects($dependancy);
				//};
			};
			return true;
		} else {
			echo "[SportsManager Error: no dependency defined for $filter.]";
			return false;
		};
	}

	function query_objects($filter) {
		global $wpdb;
		$table = $this->objects->$filter->table;
		$q = "SELECT * FROM $table ";
		$wheres = array ();
		if ($this->args->league_name != '' && in_array($filter, array ('games', 'scoresheets', 'teams'))) {
			$id = $nowp->db->query_var('id', $this->objects->leagues->table, "slug = '".$this->args->league_name."'");
			$wheres[] = "league_id = '$id'";
		};
		if ($this->args->season != '' && in_array($filter, array ('games', 'scoresheets', 'teams'))) {
			$wheres[] = "season = '".$this->args->season."'";
		};
		if ($this->args->game_id != '' && in_array($filter, array ('scoresheets'))) {
			$wheres[] = "game_id = '".$this->args->game_id."'";
		};
		if ($this->args->game_type != '' && in_array($filter, array ('games'))) {
			if ($this->args->game_type == 'playoff') {
				$wheres[] = "type IN ('P1', 'P2', 'P4')";
			};
		};
		if ($this->args->team_slug != '' && in_array($filter, array ('players'))) {
			$wheres[] = "team_slug = '".$this->args->team_slug."'";
		};
		if ($this->args->user_id != '' && in_array($filter, array ('players'))) {
			$wheres[] = "user_id = '".$this->args->user_id."'";
		};
		$q .= sm_where_string($wheres);
		//if (is_int($this->args->top) && $this->args->top > 0) $q .= "LIMIT ".$this->args->top." "; //TODO: query limit
		return $wpdb->get_results($q, ARRAY_N); //$nowp->db->query_array($q);
	}

	function display_table($args) {
		$defaults = (object) array (
			'filter' => '',
			'league_name' => '',
			'season' => '',
			'sport' => '',
			'game_id' => '',
			'game_type' => '',
			'team_id' => '',
			'team_slug' => '',
			'user_id' => '',
			'top' => '',
			'sortable' => true
		);
		if (isset($args)) {
			$args = (object) array_merge((array) $defaults, (array) $args);
		} else {
			$args = $defaults;
		};
		$this->args = (object) $args;
		if ($this->query_dependancies($this->args->filter)) {
			$filter = $this->args->filter;
			$this->rows = array ();
			$primary_object = reset($this->dependancies->$filter);
			foreach ($this->db->$primary_object as $row) {
				$class = $this->objects->$primary_object->class;
				$this->rows[] = new $class($row, $this->db);
			};
			if (isset($this->args->team)) {
				foreach ($this->rows as $k => $v) {
					if ($v->team_slug != $this->args->team) unset($this->rows[$k]);
				};
			};
			include(dirname(__FILE__).'/../views/frontend_filters.php');
		};
	}
}
