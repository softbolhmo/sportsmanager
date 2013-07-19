<?php

$headers = array (
	'clubs' => array (
		'id' => array ('ID', '', false),
		'league_id' => array ('League', '', true),
		'sport' => array ('Sport', '', true),
		'name' => array ('Name', '', true),
		'slug' => array ('Slug', '', true),
		'description' => array ('Description', '', false),
		'small_logo_url' => array ('Small Logo URL', '', false),
		'large_logo_url' => array ('Large Logo URL', '', false),
		'infos' => array ('Infos', '', false)
	),
	'games' => array (
		'id' => array ('ID', '', false),
		'league_id' => array ('League', '', true),
		'season' => array ('Season', '', true),
		'sport' => array ('Sport', '', true),
		'home_team_id' => array ('Home Team', '', true),
		'away_team_id' => array ('Away Team', '', true),
		'home_score' => array ('Home Score', '', false),
		'away_score' => array ('Away Score', '', false),
		'winner_team_id' => array ('Winner', '', false),
		'date' => array ('Date', '', false),
		'type' => array ('Type', '', true),
		'location_id' => array ('Location', '', false),
		'description' => array ('Description', '', false),
		'cancelled' => array ('Cancelled?', '', false)
	),
	'leagues' => array (
		'id' => array ('ID', '', false),
		'name' => array ('Name', '', true),
		'slug' => array ('Slug', '', true),
		'description' => array ('Description', '', false),
		'small_logo_url' => array ('Small Logo URL', '', false),
		'large_logo_url' => array ('Large Logo URL', '', false),
		'infos' => array ('Infos', '', false)
	),
	'locations' => array (
		'id' => array ('ID', '', false),
		'league_id' => array ('League', '', true),
		'name' => array ('Name', '', true),
		'slug' => array ('Slug', '', true),
		'description' => array ('Description', '', false),
		'small_logo_url' => array ('Small Logo URL', '', false),
		'large_logo_url' => array ('Large Logo URL', '', false),
		'infos' => array ('Infos', '', false)
	),
	'players' => array (
		'id' => array ('ID', '', false),
		'user_id' => array ('WP User ID', '', false),
		'slug' => array ('Slug', '', true),
		'description' => array ('Description', '', false),
		'small_logo_url' => array ('Small Logo URL', '', false),
		'large_logo_url' => array ('Large Logo URL', '', false),
		'infos' => array ('Infos', '', false)
	),
	'scoresheets' => array (
		'id' => array ('ID', '', false),
		'league_id' => array ('League', '', true),
		'season' => array ('Season', '', true),
		'sport' => array ('Sport', '', true),
		'game_id' => array ('Game ID', '', true),
		'player_id' => array ('Player ID', '', true),
		'stats' => array ('Stats', '', false)
	),
	'teams' => array (
		'id' => array ('ID', '', false),
		'club_id' => array ('Club', '', true),
		'season' => array ('Season', '', true),
		'players_id' => array ('Players IDs', '', false),
		'captains_id' => array ('Captains IDs', '', false),
		'inactive' => array ('Inactive?', '', false)
	)
);
$icon_url = SPORTSMANAGER_URL.'images/icon_trashcan.png';
$filter = $this->args->display;
$add_row = array (
	'default' => 'Add a row',
	'clubs' => 'Add a club',
	'games' => 'Add a game',
	'locations' => 'Add a location',
	'leagues' => 'Add a league',
	'players' => 'Add a player',
	'scoresheets' => 'Add a scoresheet',
	'teams' => 'Add a team'
);
$add_row = isset($add_row[$filter]) ? $add_row[$filter] : $add_row['default'];
$add_rows = array (
	'default' => 'Add multiple rows',
	'games' => 'Add multiple games',
);
$add_rows = isset($add_rows[$filter]) ? $add_rows[$filter] : $add_rows['default'];
$delete_rows = 'Delete a game';
