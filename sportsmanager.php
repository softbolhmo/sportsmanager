<?php
/*
Plugin Name: Sports Manager
Plugin URI: http://lac.roix.ca/en/sportsmanager/
Description: Manage your sport league like a pro!
Version: 1.3.1
Author: Charles-Alexandre Lacroix
Author URI: http://lac.roix.ca/
License: GPL2
*/

/**
 * Plugin home page setup
 * @package SportsManager
 * @subpackage page
 */
$f = dirname(__FILE__).'/config.php';
if (file_exists($f)) {require_once($f);} else {die($f.' does not exist');};

sm_gather_files('functions', array ('ajax', 'backend', 'backup', 'extras', 'frontend', 'install', 'languages'));
sm_gather_files('classes', array ('SportsManager', 'Backend', 'Frontend', 'Backend_Default', 'Frontend_Default', 'Club', 'Game', 'League', 'Location', 'Player', 'Scoresheet', 'Team', 'FAQ', 'User', 'Role'));
$SM = new SportsManager_Backend();
