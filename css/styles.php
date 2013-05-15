<?php
	header('Content-Type: text/css');
	$css = '';
	$files = array (
		'pages.css',
		'modals.css',
		'filters.css'
	);
	ob_start('ob_gzhandler');
	foreach ($files as $f) {
		$css .= @file_get_contents($f)."\n";
	}
	echo $css;
