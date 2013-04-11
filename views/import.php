
<div class="sm_page_inside_container">

<form id="sm_import_scoresheet_form" enctype="multipart/form-data" action="?page=sportsmanager_import&action=import" method="post">
	<input type="hidden" name="max_file_size" value="100000" />
	<p>Select import type: <select id="object_type" name="object_type">
		<option value="scoresheets">Scoresheet</option>
	</select></p>
	<p>Choose a file to upload: <input type="file" id="uploaded_file" name="uploaded_file" /></p>
	<p>Define delimiter: <input type="text" id="delimiter" name="delimiter" /></p>
	<input type="submit" value="Upload File" />
</form>

<?php
if (isset($_GET['action']) && $_GET['action'] == 'import' && isset($_FILES['uploaded_file']['tmp_name']) && $_FILES['uploaded_file']['tmp_name'] != '') {
	if (isset($_POST['delimiter']) && $_POST['delimiter'] != '') {
		$delimiter = $_POST['delimiter'];
		$csv = file_get_contents($_FILES['uploaded_file']['tmp_name']);
		$rows = explode("\n", $csv);
		if (isset($_POST['object_type']) && $_POST['object_type'] != '') {
?>

<p><a href="?page=sportsmanager_import">Back to import page</a></p>

<?php
			$object_type = $_POST['object_type'];
			switch ($object_type) {
				case 'scoresheets':
					$infos = explode($delimiter, trim($rows[0]));
					$infos = array (
						'id' => '',
						'league_id' => $infos[2],
						'season' => $infos[4],
						'sport' => $infos[6],
						'game_id' => $infos[8]
					);
					$keys = explode($delimiter, trim($rows[2]));
					unset($rows[0], $rows[1], $rows[2]);
					$table = array ();
					foreach ($rows as $row) {
						$row = explode($delimiter, $row);
						$row = array_combine($keys, $row);
						if (is_numeric($row['player_id']) && $row['player_id'] != 0) $table[] = $row;
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
?>

<h2>Scoresheets have been added to the database.</h2>
<p><a href="?page=sportsmanager_scoresheets">Edit Scoresheets</a></p>

<?php
					break;
			};
		};
	};
};
?>

</div>
