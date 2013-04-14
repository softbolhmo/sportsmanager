<?php
	header('Content-Type: text/javascript');
	$js = '';
	$files = array (
		'jquery.dataTables.min.js',
		'init.js',
		'behaviors.js',
		'fn.js'
	);
	foreach ($files as $f) {
		$js .= file_get_contents($f)."\n\n";
	}
	echo $js;
