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
class SportsManager_Frontend extends SportsManager {
	function __construct() {
		parent::__construct();
		$this->dependancies = (object) array (
			'game_results' => (object) array (
				'tables' => array ('games', 'clubs', 'locations', 'teams'), //always name primary object first
				'args' => array ('display')
			),
			'game_stats' => (object) array (
				'tables' => array ('scoresheets', 'players'),
				'args' => array ('display')
			),
			'leaders_stats' => (object) array (
				'tables' => array ('players', 'scoresheets', 'games', 'users'),
				'args' => array ('display')
			),
			'pitching_stats' => (object) array (
				'tables' => array ('players', 'scoresheets', 'users'),
				'args' => array ('display')
			),
			'player_stats' => (object) array (
				'tables' => array ('players', 'scoresheets', 'users'),
				'args' => array ('display')
			),
			'players_stats' => (object) array (
				'tables' => array ('players', 'scoresheets', 'users'),
				'args' => array ('display')
			),
			'rankings' => (object) array (
				'tables' => array ('teams', 'clubs', 'games'),
				'args' => array ('display', array ('league_id', 'league_slug', 'league_name'))
			),
			'schedule' => (object) array (
				'tables' => array ('games', 'clubs', 'locations', 'teams'),
				'args' => array ('display')
			),
			'schedule_playoff' => (object) array (
				'tables' => array ('games', 'clubs', 'locations', 'teams'),
				'args' => array ('display')
			),
			'team_players_stats' => (object) array (
				'tables' => array ('players', 'clubs', 'scoresheets', 'teams', 'users'),
				'args' => array ('display')
			),
			'team_stats' => (object) array (
				'tables' => array ('players', 'clubs', 'scoresheets', 'teams', 'users'),
				'args' => array ('display')
			),
			'teams_stats' => (object) array (
				'tables' => array ('players', 'clubs', 'scoresheets', 'teams', 'users'),
				'args' => array ('display')
			)
		);
		$this->build();
	}

	function query_objects($filter) {
		global $wpdb;
		$table = $this->objects->$filter->table;
		$q = "SELECT * FROM $table ";
		$wheres = array ();
		if ($this->args->league_id != '' && in_array($filter, array ('games', 'scoresheets'))) {
			$wheres[] = "league_id = '".$this->args->league_id."'";
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
		if ($this->args->user_id != '' && in_array($filter, array ('players'))) {
			$wheres[] = "user_id = '".$this->args->user_id."'";
		};
		$q .= sm_where_string($wheres);
		if (is_int($this->args->top) && $this->args->top > 0) $q .= "LIMIT ".$this->args->top." ";
		return $wpdb->get_results($q);
	}

	function generate($args) {
		$this->build($args);
		$filter = $this->args->display; //watch out for name change, from display to filter
		if (!$this->verify_required_args($filter)) return false;
		if ($this->query_dependancies($this->args->display)) {
			$this->rows = array ();
			$primary_object = reset($this->dependancies->$filter->tables);
			foreach ($this->db->$primary_object as $row) {
				$class = $this->objects->$primary_object->class;
				$this->rows[] = new $class($row, $this->db);
			};
			if ($primary_object == 'teams') {
				if ($this->args->league_id != '') {
					$league_id = $this->args->league_id;
				} elseif ($this->args->league_name != '') {
					$league_id = $this->args->league_id;
				} elseif ($this->args->league_slug != '') {
					$league_id = $this->args->league_id;
				} else {
					$league_id = '';
				};
				foreach ($this->rows as $k => $v) {
					if ($league_id == '' || $v->league_id != $league_id) unset($this->rows[$k]);
				};
			};
			$this->include_view('frontend_filters');
		};
	}
}
