<?php

global $wpdb;
$league = $wpdb->get_var("SELECT name FROM ".$this->objects->leagues->table." WHERE slug = '".$this->args->league_slug."'");
$season = $this->args->season;
$sport = ucfirst($this->args->sport);
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
$page_title = isset($page_titles[$this->args->display]) ? $page_titles[$this->args->display] : $page_titles['default'];
$session_string = '';
if ($league != '') $session_string .= "$league ";
if ($season != '') $session_string .= "($season) ";
if ($season != '' && $sport != '') $session_string .= "- ";
if ($sport != '') $session_string .= "$sport ";
if ($session_string == '') {
	$session_action = 'Define criteria';
} else {
	$session_action = 'change';
};
$session_string .= "<span class='sm_change_session_btn'>($session_action)</span>";
$page_subtitles = array (
	'default' => $session_string,
	'home' => 'Your favorite WordPress league manager',
	'import' => 'Tool to import scoresheets and schedule',
	'faq' => 'Frequently asked questions',
	'donate' => 'Support Sports Manager development'
);
$page_subtitle = isset($page_subtitles[$this->args->display]) ? $page_subtitles[$this->args->display] : $page_subtitles['default'];
$prefix = $this->prefix;
$menu = array ( //the only occurrence where the standard objects are not alphabetically sorted
	'home' => "Home",
	'leagues' => "Leagues",
	'clubs' => "Clubs",
	'players' => "Players",
	'teams' => "Teams",
	'locations' => "Locations",
	'games' => "Games",
	'scoresheets' => "Scoresheets",
	'import' => "Import Tool",
	'faq' => "FAQ",
	'donate' => "Donate"
);
$active = $this->args->display;
$count = $wpdb->get_var("SELECT COUNT(*) FROM ".$this->objects->leagues->table);
