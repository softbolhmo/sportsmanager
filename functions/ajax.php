<?php

function sm_autocomplete() {
	global $wpdb;
	$SM = new SportsManager_Backend;
	$data = (object) array ();
	$keys = array ('do', 'tab');
	foreach ($keys as $k) {
		$data->$k = isset($_REQUEST[$k]) ? $_REQUEST[$k] : '';
	};

	//load
	if ($data->do == 'load') {
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
				$autocomplete->{$filter.'_name'} = $array;
			};
			$static = array (
				'game_type' => array (
					(object) array ('label' => 'Exhibition (E)', 'value' => 'E'),
					(object) array ('label' => 'Regular Season (S)', 'value' => 'S'),
					(object) array ('label' => 'Finals (P1)', 'value' => 'P1'),
					(object) array ('label' => 'Semi-finals (P2)', 'value' => 'P2'),
					(object) array ('label' => 'Quarter-finals (P4)', 'value' => 'P4'),
					(object) array ('label' => '3rd Place Consolation (C3)', 'value' => 'C3')
				),
				'yes_no' => array (
					(object) array ('label' => 'Yes', 'value' => '1'),
					(object) array ('label' => 'No', 'value' => '0')
				),
				'sports_slug' => array (),
				'session_leagues' => array (),
				'session_seasons' => array (),
				'session_sports' => array ()				
			);
			//sports-slug
			foreach ($SM->sports as $k => $v) {
				$static['sports_slug'][] = (object) array ('label' => $v[0], 'value' => $k);
			};
			//session-leagues
			foreach ($SM->query_leagues() as $k => $v) {
				$static['session_leagues'][] = (object) array ('label' => $v, 'value' => $k);
			};
			//session-seasons
			foreach ($SM->query_seasons() as $k => $v) {
				$static['session_seasons'][] = (object) array ('label' => $v, 'value' => $v);
			};
			//session-sports
			foreach ($SM->query_sports() as $k => $v) {
				if ($v != '') {
					$label = isset($SM->sports[$v][0]) ? $SM->sports[$v][0] : '"'.$v.'"';
					$static['session_sports'][] = (object) array ('label' => $label, 'value' => $v);
				};
			};
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
		foreach ($SM->options as $k) {
			$key = 'option_'.$k;
			$value = isset($$key) ? $$key : '';
			$key = $SM->prefix.$k;
			$options->$key = $value;
			update_option($key, $options->$key);
		};
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

	//file
	$csv = file_get_contents($import_file_url);
	$rows = explode("\n", $csv);
	if (count($rows) < 4) die;
	$infos = explode($import_delimiter, trim($rows[0]));

	//games
	if ($data->do == 'games') {
		$infos = array ();
		$keys = explode($import_delimiter, trim($rows[2]));
		unset($rows[0], $rows[1], $rows[2]);
		$table = array ();
		foreach ($rows as $row) {
			if ($row != '') {
				$row = explode($import_delimiter, trim($row));
				$row = array_combine($keys, $row);
				if (is_numeric($row['league_id']) && $row['league_id'] != 0) $table[] = $row;
			};
		};
		$games = array ();
		foreach ($table as $row) {
			$games[] = $row;
		};
		foreach ($games as $game) {
			$wpdb->insert(
				$SM->objects->games->table,
				$game
			);
		};
		die;
	};

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
	$response = (object) array ('name' => '', 'value' => '');
	$data = (object) array ();
	$keys = array ('do', 'id', 'column', 'value');
	foreach ($keys as $k) {
		$data->$k = isset($_REQUEST[$k]) ? $_REQUEST[$k] : '';
	};

	//add_new
	if ($data->do == 'add_new') {
		$wpdb->insert(
			$SM->objects->{$SM->args->display}->table,
			array ('id' => '')
		);
		echo $wpdb->insert_id;
		die;
	};

	//edit
	if ($data->do == 'edit' && isset($data->id)) {
		$keys = array ('filter', 'id', 'column');
		$values = explode('-', $data->id);
		$info = (object) array_combine($keys, $values);
		$data->value = stripslashes($data->value);
		$wpdb->update(
			$SM->objects->{$info->filter}->table,
			array (
				$info->column => $data->value
			),
			array ('id' => $info->id)
		);
		$response->value = $wpdb->get_var("SELECT $info->column FROM ".$SM->objects->{$info->filter}->table." WHERE id = $info->id");
		$response->name = $SM->get_special_backend_key_name($info->column, $response->value);
		echo json_encode($response);
		die;
	};

	//delete
	if ($data->do == 'delete' && isset($data->id)) {
		$wpdb->query("DELETE FROM ".$SM->objects->{$SM->args->display}->table." WHERE id = $data->id");
		echo $wpdb->get_var("SELECT COUNT(*) FROM ".$SM->objects->{$SM->args->display}->table);
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
