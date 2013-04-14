<?php

global $wpdb;
$league = $wpdb->get_var("SELECT name FROM ".$this->objects->leagues->table." WHERE slug = '".$_SESSION['sm_league']."'");
$season = $_SESSION['sm_season'];
$sport = ucfirst($_SESSION['sm_sport']);
$page_titles = array (
	'default' => '[Page not found]',
	'home' => 'Sports Manager ©',
	'clubs' => "Club Manager",
	'games' => "Game Manager",
	'leagues' => "League Manager",
	'locations' => "Location Manager",
	'players' => "Player Manager",
	'scoresheets' => "Scoresheet Manager",
	'teams' => "Team Manager",
	'import' => "Import Tool",
	'faq' => "FAQ",
	'donate' => "Donate"
);
$page_title = isset($page_titles[SPORTSMANAGER_FILTER]) ? $page_titles[SPORTSMANAGER_FILTER] : $page_titles['default'];
$session_string = '';
if ($league != '') $session_string .= "$league ";
if ($season != '') $session_string .= "($season) ";
if ($sport != '') $session_string .= "- $sport ";
if ($session_string == '') {
	$session_action = 'Define criteria';
} else {
	$session_action = 'change';
};
$session_string .= "<span class='sm_change_session_btn'>($session_action)</span>";
$page_subtitles = array (
	'default' => $session_string,
	'home' => 'Your favorite WordPress league manager.',
	'import' => 'Tool to import scoresheet',
	'faq' => 'Frequently asked questions',
	'donate' => 'Support Sports Manager development'
);
$page_subtitle = isset($page_subtitles[SPORTSMANAGER_FILTER]) ? $page_subtitles[SPORTSMANAGER_FILTER] : $page_subtitles['default'];
$prefix = SPORTSMANAGER_PREFIX;
$menu = array (
	'home' => "Home",
	'clubs' => "Clubs",
	'games' => "Games",
	'leagues' => "Leagues",
	'locations' => "Locations",
	'players' => "Players",
	'scoresheets' => "Scoresheets",
	'teams' => "Teams",
	'import' => "Import Tool",
	'faq' => "FAQ",
	'donate' => "Donate"
);
$active = SPORTSMANAGER_FILTER;
$count = $wpdb->get_var("SELECT COUNT(*) FROM ".$this->objects->leagues->table);
