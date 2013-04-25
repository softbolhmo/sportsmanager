<?php

$formulas = array(
	'percentage' => '%d/%d*100',
	'average' => '%d/%d',
);
$all_stats = array(
	'all' => array(
		array ('db-all', 'all'),
		array ('general')
	),


	'games' => array(
		array ('db', 'count'),
		array ('general')
	),
	'wins' => array(
		array ('db', 'count'),
		array ('general')
	),
	'losses' => array(
		array ('db', 'count'),
		array ('general')
	),


	'two_pointers' => array(
		array ('db', 'sum'),
		array ('basketball')
	),
	'three_pointers' => array(
		array ('db', 'sum'),
		array ('basketball')
	),
	'free_throws_made' => array(
		array ('db', 'sum'),
		array ('basketball')
	),
	'free_throws_attempted' => array(
		array ('db', 'sum'),
		array ('basketball')
	),
	'fouls' => array(
		array ('db', 'sum'),
		array ('basketball')
	),
	'at_bat' => array(
		array ('db', 'count'),
		array ('baseball')
	),


	'winner' => array(
		array ('null', array(), '', array()),
		array ('general')
	),
	'winning_percentage' => array(
		array ('math', array('wins', 'games'), $formulas['percentage'], array(1)),
		array ('general')
	),
	'point_average' => array(
		array ('math', array('two_pointers', 'three_pointers' , 'free_throws_made', 'games'), '(2*%d+3*%d+1*%d)/%d', array(3)),
		array ('basketball')
	),
	'foul_average' => array(
		array ('math', array('fouls', 'games'), $formulas['average'], array(1)),
		array ('basketball')
	),
	'free_throw_percentage' => array(
		array ('math', array('free_throws_made', 'free_throws_attempted'), $formulas['percentage'], array(1)),
		array ('basketball')
	),
);
