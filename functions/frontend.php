<?php

function sm_frontend_table($atts) {
	$SM = new SportsManager_Frontend;
	extract(shortcode_atts((array) $SM->args, $atts));
	$SM->generate($atts);
};

add_shortcode('SportsManager', 'sm_frontend_table');
