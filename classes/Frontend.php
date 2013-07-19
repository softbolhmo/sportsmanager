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
			'list_games' => (object) array (
				'tables' => array ('games', 'clubs', 'locations', 'teams'),
				'args' => array ('display')
			),
			'list_locations' => (object) array (
				'tables' => array ('locations'),
				'args' => array ('display', array ('league_id', 'league_slug', 'league_name'))
			),
			'list_players' => (object) array (
				'tables' => array ('players', 'users'),
				'args' => array ('display', array ('league_id', 'league_slug', 'league_name'))
			),
			'list_teams' => (object) array (
				'tables' => array ('teams', 'clubs'),
				'args' => array ('display', array ('league_id', 'league_slug', 'league_name'), 'sport')
			),
			'rankings' => (object) array (
				'tables' => array ('teams', 'clubs', 'games'),
				'args' => array ('display', array ('league_id', 'league_slug', 'league_name'))
			),
			'player_stats' => (object) array (
				'tables' => array ('players', 'scoresheets', 'users'),
				'args' => array ('display', array ('league_id', 'league_slug', 'league_name'), 'seasons_array', 'sport')
			),
			'schedule' => (object) array (
				'tables' => array ('games', 'clubs', 'locations', 'teams'),
				'args' => array ('display')
			),
			'stats_game' => (object) array (
				'tables' => array ('scoresheets', 'players', 'users'),
				'args' => array ('display', 'game_id', 'sport')
			),
			'stats_leaders' => (object) array (
				'tables' => array ('players', 'scoresheets', 'games', 'users'),
				'args' => array ('display', array ('league_id', 'league_slug', 'league_name'), 'season', 'sport', 'top')
			),
			'stats_pitching' => (object) array (
				'tables' => array ('players', 'scoresheets', 'users'),
				'args' => array ('display', array ('league_id', 'league_slug', 'league_name'), 'season', 'sport')
			),
			'stats_players' => (object) array (
				'tables' => array ('players', 'scoresheets', 'users'),
				'args' => array ('display', array ('league_id', 'league_slug', 'league_name'), 'sport')
			),
			'stats_team_players' => (object) array (
				'tables' => array ('players', 'clubs', 'scoresheets', 'teams', 'users'),
				'args' => array ('display', 'season', 'sport', array ('team_id', 'team_slug', 'team_name'))
			),
			'stats_team' => (object) array (
				'tables' => array ('teams', 'clubs', 'games', 'players', 'scoresheets', 'users'),
				'args' => array ('display', array ('league_id', 'league_slug', 'league_name'), 'season', 'sport')
			)
		);
		$this->build();
	}

	function query_objects($filter) {
		global $wpdb;
		$table = $this->objects->$filter->table;
		$q = "SELECT * FROM $table ";
		$wheres = array ();
		if ($this->args->league_id !== '' && in_array($filter, array ('games', 'locations', 'scoresheets'))) {
			if ($this->args->league_id == 0) return array ();
			$wheres[] = "league_id = '".$this->args->league_id."'";
		};
		if ($this->args->season !== '' && in_array($filter, array ('games', 'scoresheets', 'teams'))) {
			$wheres[] = "season = '".$this->args->season."'";
		};
		if ($this->args->game_id !== '' && in_array($filter, array ('games', 'scoresheets'))) {
			$column = $filter == 'games' ? 'id' : 'game_id';
			$wheres[] = $column." = '".$this->args->game_id."'";
		};
		if ($this->args->display == 'schedule' && in_array($filter, array ('games'))) {
			if ($this->args->game_type == 'all') {
				$wheres[] = "type IN ('S', 'E', 'P1', 'P2', 'P4', 'C3')";
			} elseif ($this->args->game_type == 'playoff') {
				$wheres[] = "type IN ('P1', 'P2', 'P4', 'C3')";
			} else {
				$wheres[] = "type IN ('S', 'E')";
			};
			if ($this->args->team_id !== '') {
				$wheres[] = "(home_team_id = '".$this->args->team_id."' OR away_team_id = '".$this->args->team_id."')";
			};
		};
		if ($this->args->location_id !== '' && in_array($filter, array ('locations'))) {
			if ($this->args->location_id == 0) return array ();
			$wheres[] = "id = '".$this->args->location_id."'";
		};
		if ($this->args->player_id !== '' && in_array($filter, array ('players'))) {
			$wheres[] = "id = '".$this->args->player_id."'";
		};
		if ($this->args->player_slug !== '' && in_array($filter, array ('players'))) {
			$wheres[] = "slug = '".$this->args->player_slug."'";
		};
		if ($this->args->team_id !== '' && $this->args->display != 'schedule' && in_array($filter, array ('teams'))) {
			if ($this->args->team_id == 0) return array ();
			$wheres[] = "id = '".$this->args->team_id."'";
		};
		if ($this->args->user_id !== '' && in_array($filter, array ('players'))) {
			$wheres[] = "user_id = '".$this->args->user_id."'";
		};
		if (!is_int(strpos($this->args->display, 'list_')) && in_array($filter, array ('teams'))) {
			$wheres[] = "inactive = '0'";
		};
		$q .= sm_where_string($wheres);
		//if (is_int($this->args->top) && $this->args->top > 0) $q .= "LIMIT ".$this->args->top." ";
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
				if (isset($row->slug) && $row->slug == '') continue;
				$this->rows[] = new $class($row, $this->db, $this->args);
			};
			if ($primary_object == 'players') {
				if (in_array($this->args->display, array ('list_players', 'stats_team_players')) && $this->args->team_id !== '') {
					global $wpdb;
					$table = $this->objects->teams->table;
					$team_id = $this->args->team_id;
					if ($this->args->team_id == 0) {
						$this->rows = array ();
					} else {
						$q = "SELECT players_id FROM $table WHERE id = '$team_id'";
						$players_id = json_decode($wpdb->get_var($q));
						foreach ($this->rows as $k => $v) {
							if (!is_array($players_id) || !in_array($v->id, $players_id)) unset($this->rows[$k]);
						};
					};
				};
			};
			if ($primary_object == 'teams') { //we do this filtering here because there is no league_id column on team row... maybe the sql query could be refined instead
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
			if (in_array($this->args->display, array ('list_games', 'list_locations', 'list_players', 'list_teams'))) {
				return $this->rows;
			} else {
				$this->include_view('frontend_filters');
			};
		};
	}
}
