<?php

function sm_db() {
	global $wpdb;
	$SM = new SportsManager_Backend;
	$data = (object) array ();
	$keys = array ('do', 'id', 'date_time', 'email', 'emailed', 'sport');
	foreach ($keys as $k) {
		$data->$k = isset($_REQUEST[$k]) ? $_REQUEST[$k] : '';
	};

	//set_options
	if ($data->do == 'set_options') {
		foreach (array ('email', 'emailed') as $k) {
			update_option(SPORTSMANAGER_PREFIX.$k, $data->$k);
		};
		die();
	};

	//backup_db
	if ($data->do == 'backup_db') {
		$file = SPORTSMANAGER_DIR.'backups/'.DB_NAME.'---'.$data->date_time.'.sql';
		$tables = array ();
		foreach ($SM->objects as $object) {
			$tables[] = $object->table;
		};
		sm_backup($file, DB_HOST, DB_USER, DB_PASSWORD, DB_NAME, $tables);
		add_filter('wp_mail_content_type', create_function('', 'return "text/html";'));
		//$to, $subject, $message, $headers, $attachments
		wp_mail(
			get_option('sportsmanager_email'),
			'SPORTS MANAGER BACKUP',
			"<h1>SPORTS MANAGER BACKUP</h1>".
			"<p>Thank you for using this plugin.</p>",
			"", //"From: My Name <myname@mydomain.com>",
			$file
		);
	};
}

add_action('wp_ajax_sm_db', 'sm_db');

function sm_row() {
	global $wpdb;
	$SM = new SportsManager_Backend;
	$data = (object) array ();
	$keys = array ('do', 'id', 'column', 'value');
	foreach ($keys as $k) {
		$data->$k = isset($_REQUEST[$k]) ? $_REQUEST[$k] : '';
	};

	//add_new
	if ($data->do == 'add_new') {
		$wpdb->insert(
			$SM->objects->{SPORTSMANAGER_FILTER}->table,
			array ('id' => '')
		);
		echo $wpdb->insert_id;
		die;
	};

	//edit
	if ($data->do == 'edit' && isset($data->id)) {
		$keys = array ('filter', 'id', 'column');
		$values = explode('-', $data->id);
		$data = array_combine($keys, $values);
		$data = (object) $data;
		$data->value = stripslashes($_REQUEST['value']);
		$wpdb->update(
			$SM->objects->{SPORTSMANAGER_FILTER}->table,
			array (
				$data->column => $data->value
			),
			array ('id' => $data->id)
		);
		echo $wpdb->get_var("SELECT $data->column FROM ".$SM->objects->{SPORTSMANAGER_FILTER}->table." WHERE id = $data->id");
		die;
	};

	//delete
	if ($_REQUEST['do'] == 'delete' && isset($_REQUEST['id'])) {
		$wpdb->query("DELETE FROM ".$SM->objects->{SPORTSMANAGER_FILTER}->table." WHERE id = $data->id");
		die;
	};
}

add_action('wp_ajax_sm_row', 'sm_row');

function sm_session() {
	if (!session_id()) session_start();
	$out = array ();
	$session = (object) array (
		'sm_league' => '',
		'sm_season' => date('Y'),
		'sm_sport' => ''
	);
	foreach ($session as $k => $v) {
		if (!isset($_SESSION[$k])) $_SESSION[$k] = $v;
		if (isset($_REQUEST[$k])) $_SESSION[$k] = $_REQUEST[$k];
		$out[] = $_SESSION[$k];
	};
	echo strtoupper(implode(' ', $out));
	die;
}

add_action('wp_ajax_sm_session', 'sm_session');
