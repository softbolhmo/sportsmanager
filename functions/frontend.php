<?php

function sm_frontend_table($atts) {
	extract(shortcode_atts(array (
		'league' => '',
		'season' => date('Y'),
	), $atts));
	$SM = new SportsManager_Frontend;
	$SM->display_table(array ('filter' => 'rankings'));
};

add_shortcode('SportsManager', 'sm_frontend_table');
