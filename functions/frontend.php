<?php

function sm_frontend_table($atts) {
	extract(shortcode_atts(array (
		'display' => '',
		'league' => '',
		'season' => date('Y'),
	), $atts));
	$SM = new SportsManager_Frontend;
	$SM->generate($atts);
};

add_shortcode('SportsManager', 'sm_frontend_table');
