<?php

function sm_define_session() {
	if (!session_id()) session_start();
	$out = array ();
	$session = (object) array (
		'sm_league' => '',
		'sm_season' => '',
		'sm_sport' => ''
	);
	foreach ($session as $k => $v) {
		if (isset($_REQUEST[$k])) {
			$_SESSION[$k] = $_REQUEST[$k];
		};
		$out[] = isset($_SESSION[$k]) ? $_SESSION[$k] : $session->$k;
	};
	return strtoupper(implode(' ', $out));
};
