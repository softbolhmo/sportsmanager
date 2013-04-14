<?php
	header('Content-Type: text/javascript');
	$js = '';
	$files = array (
		//'json.js',
		//'jquery-ui-1.10.2.custom.min.js', //'jquery-ui.min.js',
		//'jquery.jeditable.min.js',
		//'jquery.address.min.js',
		'jquery.dataTables.min.js',
		'init.js',
		'behaviors.js',
		'fn.js'
	);
	foreach ($files as $f) {
		$js .= file_get_contents($f)."\n\n";
	}
	echo $js;
