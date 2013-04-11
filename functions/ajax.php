<?php

function sm_autocomplete() {
	global $wpdb;
	$keys = array ('do', 'tab');
	foreach ($keys as $k) {
		$data->$k = isset($_REQUEST[$k]) ? $_REQUEST[$k] : '';
	};

	//load
	if ($data->do == 'load') {
		$SM = new SportsManager_Backend;
		if ($SM->query_dependancies($data->tab)) {
			$autocomplete = (object) array ();
			foreach (array ('clubs', 'leagues', 'locations', 'players', 'teams', 'users') as $filter) {
				$info = isset($SM->db->$filter) ? $SM->db->$filter : '';
				if ($info != '') {
					$items = array ();
					foreach ($info as $object) {
						if (isset($object->id, $object->name)) {
							$label = $object->name.(isset($object->season) ? ' '.$object->season : '').' ('.$object->id.')';
							$items[] = (object) array (
								'label' => $label,
								'value' => $object->id
							);
						};
					};
					$array = $items;
				} else {
					$array = array ();
				};
				$autocomplete->{$filter.'-name'} = $array;
			};
			$static = array (
				'sports-slug' => array (
					(object) array ('label' => 'Baseball', 'value' => 'baseball'),
					(object) array ('label' => 'Soccer/Football', 'value' => 'soccer')
				),
				'game-type' => array (
					(object) array ('label' => 'Regular Season (S)', 'value' => 'S'),
					(object) array ('label' => 'Finals (P1)', 'value' => 'P1'),
					(object) array ('label' => 'Semi-finals (P2)', 'value' => 'P2'),
					(object) array ('label' => 'Quarter-finals (P1)', 'value' => 'P3')
				),
				'yes-no' => array (
					(object) array ('label' => 'Yes', 'value' => '1'),
					(object) array ('label' => 'No', 'value' => '0')
				)				
			);
			foreach ($static as $k => $v) {
				$autocomplete->$k = $v;
			};
			echo json_encode($autocomplete);
		};
		die;
	};
}

add_action('wp_ajax_sm_autocomplete', 'sm_autocomplete');

function sm_db() {
	global $wpdb;
	$SM = new SportsManager_Backend;
	$data = (object) array ();
	$keys = array ('do', 'id', 'date_time', 'option_disable_intro', 'option_email', 'option_email_name');
	foreach ($keys as $k) {
		$key = str_replace('option_', '', $k);
		$data->$key = isset($_REQUEST[$k]) ? $_REQUEST[$k] : '';
	};

	//set_options
	if ($data->do == 'set_options') {
		$options = (object) array ();
		foreach (array ('disable_intro', 'email', 'email_name') as $k) {
			$key = SPORTSMANAGER_PREFIX.$k;
			$options->$key = $data->$k;
			update_option($key, $options->$key);
		};
		echo json_encode($options);
		die;
	};

	//backup_db
	if ($data->do == 'backup_db') {
		$file = SPORTSMANAGER_DIR.'backups/SportsManager---'.DB_NAME.'---'.$data->date_time.'.sql';
		$to = get_option('sportsmanager_email', '');
		$from = 'sportsmanager@'.get_option('mailserver_url', 'mail.example.com');
		if ($to != '') {
			$tables = array ();
			foreach ($SM->objects as $object) {
				$tables[] = $object->table;
			};
			if (sm_backup($file, DB_HOST, DB_USER, DB_PASSWORD, DB_NAME, $tables)) {
				add_filter('wp_mail_content_type', create_function('', 'return "text/html";'));
				//$to, $subject, $message, $headers, $attachments
				wp_mail(
					$to,
					'SPORTS MANAGER BACKUP',
					"<h1>SPORTS MANAGER BACKUP</h1>".
					"<p>Thank you for using this plugin.</p>",
					"From: Sports Manager Plugin <".$from.">",
					$file
				);
			} else {
				echo "no-backup-file";
			};
		} else {
			echo "no-email";
		};
		die;
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
		echo $wpdb->get_var("SELECT COUNT(*) FROM ".$SM->objects->{SPORTSMANAGER_FILTER}->table);
		die;
	};
}

add_action('wp_ajax_sm_row', 'sm_row');

function sm_session() {
	echo sm_define_session();
	die;
}

add_action('wp_ajax_sm_session', 'sm_session');
