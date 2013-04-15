<?php

$sports = array('baseball', 'basketball', 'football', 'soccer', 'tennis');
$rand = rand(0, 4);
$favicon = SPORTSMANAGER_URL.'images/sports/'.$sports[$rand].'.ico';
$css = SPORTSMANAGER_URL.'css/styles.php';
$js = SPORTSMANAGER_URL.'js/scripts.php';
