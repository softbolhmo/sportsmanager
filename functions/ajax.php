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
	die;
}

add_action('wp_ajax_sm_autocomplete', 'sm_autocomplete');

function sm_db() {
	global $wpdb;
	$SM = new SportsManager_Backend;
	$data = (object) array ();
	$keys = array ('do', 'id', 'date_time', 'value');
	foreach ($keys as $k) {
		$data->$k = isset($_REQUEST[$k]) ? $_REQUEST[$k] : '';
	};
	parse_str($data->value);

	//set_options
	if ($data->do == 'set_options') {
		$options = (object) array ();
		foreach (array ('disable_intro', 'email', 'email_name', 'language') as $k) {
			$key = 'option_'.$k;
			$value = isset($$key) ? $$key : '';
			$key = SPORTSMANAGER_PREFIX.$k;
			$options->$key = $value;
			update_option($key, $options->$key);
		};
		//echo json_encode($options);
		die;
	};

	//backup_db
	if ($data->do == 'backup_db') {
		$file = SPORTSMANAGER_DIR.'backups/SportsManager---'.DB_NAME.'---'.$data->date_time.'.sql';
		sm_prepare_backup($file, true);
		die;
	};
	die;
}

add_action('wp_ajax_sm_db', 'sm_db');

function sm_import() {
	global $wpdb;
	$SM = new SportsManager_Backend;
	$data = (object) array ();
	$keys = array ('do', 'value');
	foreach ($keys as $k) {
		$data->$k = isset($_REQUEST[$k]) ? $_REQUEST[$k] : '';
	};
	parse_str($data->value);
	$csv = file_get_contents($import_file_url);
	$rows = explode("\n", $csv);
	if (count($rows) < 4) die;
	$infos = explode($import_delimiter, trim($rows[0]));

	//scoresheet
	if ($data->do == 'scoresheet') {
		$infos = array (
			'id' => '',
			'league_id' => $infos[2],
			'season' => $infos[4],
			'sport' => $infos[6],
			'game_id' => $infos[8]
		);
		$keys = explode($import_delimiter, trim($rows[2]));
		unset($rows[0], $rows[1], $rows[2]);
		$table = array ();
		foreach ($rows as $row) {
			if ($row != '') {
				$row = explode($import_delimiter, trim($row));
				$row = array_combine($keys, $row);
				if (is_numeric($row['player_id']) && $row['player_id'] != 0) $table[] = $row;
			};
		};
		$scoresheets = array ();
		foreach ($table as $row) {
			$scoresheet = $infos;
			$scoresheet['player_id'] = $row['player_id'];
			unset($row['player_id']);
			$scoresheet['stats'] = json_encode($row);
			$scoresheets[] = $scoresheet;
		};
		foreach ($scoresheets as $scoresheet) {
			$wpdb->insert(
				$SM->objects->scoresheets->table,
				$scoresheet
			);
		};
		die;
	};
	die;
}

add_action('wp_ajax_sm_import', 'sm_import');
	
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
		$data->value = stripslashes($data->value);
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
	if ($data->do == 'delete' && isset($data->id)) {
		$wpdb->query("DELETE FROM ".$SM->objects->{SPORTSMANAGER_FILTER}->table." WHERE id = $data->id");
		echo $wpdb->get_var("SELECT COUNT(*) FROM ".$SM->objects->{SPORTSMANAGER_FILTER}->table);
		die;
	};
	die;
}

add_action('wp_ajax_sm_row', 'sm_row');

function sm_session() {
	echo sm_define_session();
	die;
}

add_action('wp_ajax_sm_session', 'sm_session');
