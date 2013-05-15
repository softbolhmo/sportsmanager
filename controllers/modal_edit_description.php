<?php

$option_language = get_option('sportsmanager_language', 'en');
if ($option_language == '') $option_language = 'en';
$languages = $this->languages;
