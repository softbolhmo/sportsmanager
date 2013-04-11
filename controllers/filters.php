<?php

global $SM;
$headers = array (
	'clubs' => array (
		'id' => array ('ID', ''),
		'league_id' => array ('League ID', ''),
		'sport' => array ('Sport', ''),
		'name' => array ('Name', ''),
		'slug' => array ('Slug', ''),
		'description' => array ('Description', ''),
		'small_logo_url' => array ('Small Logo URL', ''),
		'large_logo_url' => array ('Large Logo URL', '')
	),
	'games' => array (
		'id' => array ('ID', ''),
		'league_id' => array ('League ID', ''),
		'season' => array ('Season', ''),
		'sport' => array ('Sport', ''),
		'home_team_id' => array ('Home Team ID', ''),
		'away_team_id' => array ('Away Team ID', ''),
		'home_score' => array ('Home Score', ''),
		'away_score' => array ('Away Score', ''),
		'winner_team_id' => array ('Winner ID', ''),
		'date' => array ('Date', ''),
		'type' => array ('Type', ''),
		'location_id' => array ('Location ID', ''),
		'cancelled' => array ('Cancelled?', '')
	),
	'leagues' => array (
		'id' => array ('ID', ''),
		'name' => array ('Name', ''),
		'slug' => array ('Slug', ''),
		'description' => array ('Description', ''),
		'small_logo_url' => array ('Small Logo URL', ''),
		'large_logo_url' => array ('Large Logo URL', '')
	),
	'locations' => array (
		'id' => array ('ID', ''),
		'name' => array ('Name', ''),
		'slug' => array ('Slug', ''),
		'description' => array ('Description', ''),
		'small_logo_url' => array ('Small Logo URL', ''),
		'large_logo_url' => array ('Large Logo URL', '')
	),
	'players' => array (
		'id' => array ('ID', ''),
		'user_id' => array ('WP User ID', ''),
	),
	'scoresheets' => array (
		'id' => array ('ID', ''),
		'league_id' => array ('League ID', ''),
		'season' => array ('Season', ''),
		'sport' => array ('Sport', ''),
		'game_id' => array ('Game ID', ''),
		'player_id' => array ('Player ID', ''),
		'stats' => array ('Stats', '')
	),
	'teams' => array (
		'id' => array ('ID', ''),
		'club_id' => array ('Club ID', ''),
		'season' => array ('Season', ''),
		'players_id' => array ('Players ID', '')
	)
);
$icon_url = SPORTSMANAGER_URL.'images/icon_trashcan.png';
$filter = SPORTSMANAGER_FILTER;
