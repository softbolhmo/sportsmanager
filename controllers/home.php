<?php

$option_disable_intro = get_option('sportsmanager_disable_intro', 'enabled');
$option_email = get_option('sportsmanager_email', '');
$option_email_name = get_option('sportsmanager_email_name', '');
$option_language = get_option('sportsmanager_language', '');
$languages = array_merge(array ('' => array ('', '')), $this->languages);
$option_custom_class_table = get_option('sportsmanager_custom_class_table', '');