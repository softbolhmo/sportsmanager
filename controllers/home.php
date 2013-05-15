<?php

$option_disable_intro = get_option('sportsmanager_disable_intro', 'enabled');
$option_email = get_option('sportsmanager_email', '');
$option_email_name = get_option('sportsmanager_email_name', '');
$option_language = get_option('sportsmanager_language', '');
$languages = array_merge(array ('' => array ('', '')), $this->languages);
$option_custom_class_table = get_option('sportsmanager_custom_class_table', '');
$option_default_clubs_url = $this->args->default_clubs_url;
$option_default_locations_url = $this->args->default_locations_url;
$option_default_players_url = $this->args->default_players_url;
$option_default_results_url = $this->args->default_results_url;
$option_default_stats_url = $this->args->default_stats_url;
$option_default_teams_url = $this->args->default_teams_url;
