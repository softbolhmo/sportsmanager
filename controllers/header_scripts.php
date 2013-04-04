<?php

$favicon = SPORTSMANAGER_URL.'images/favicon.ico';
$css = SPORTSMANAGER_URL.'css/style.css';
$js = SPORTSMANAGER_URL.'js/scripts.php';
$autocomplete_teams = sm_autocomplete_labels(isset($this->db->teams) ? $this->db->teams : '');
$autocomplete_leagues = sm_autocomplete_labels(isset($this->db->leagues) ? $this->db->leagues : '');
$autocomplete_locations = sm_autocomplete_labels(isset($this->db->locations) ? $this->db->locations : '');
$autocomplete_players = sm_autocomplete_labels(isset($this->db->players) ? $this->db->players : '');
$autocomplete_users = sm_autocomplete_labels(isset($this->db->players) ? $this->db->players : '');
