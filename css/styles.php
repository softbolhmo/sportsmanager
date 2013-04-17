<?php
	header('Content-Type: text/css');
	$css = '';
	$files = array (
		'pages.css',
		'modals.css',
		'filters.css'
	);
	foreach ($files as $f) {
		$css .= file_get_contents($f)."\n";
	}
	echo $css;
