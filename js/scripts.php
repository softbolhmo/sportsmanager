<?php
	header('Content-Type: text/javascript');
	$js = '';
	$files = array (
		'jquery.dataTables.min.js',
		'init.js',
		'navigation.js',
		'autocomplete.js',
		'modals.js',
		'pages.js',
		'fn.js'
	);
	ob_start('ob_gzhandler');
	foreach ($files as $f) {
		$js .= @file_get_contents($f)."\n\n";
	}
	echo $js;
