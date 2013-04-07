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
		$this->build();
	}

	function query_dependancies($filter) {
		$this->dependancies = (object) array (
			'game_results' => array ('games', 'locations', 'teams'), //always name primary object first
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
		$q .= $this->mysql_where_string($wheres);
		//if (is_int($this->args->top) && $this->args->top > 0) $q .= "LIMIT ".$this->args->top." "; //TODO: query limit
		return $wpdb->get_results($q, ARRAY_N); //$nowp->db->query_array($q);
	}

	function generate($args) {
		$this->build($args);
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
			$this->include_view('frontend_filters');
		};
	}
}
