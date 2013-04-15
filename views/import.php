
<div class="sm_page_inside_container">

<table class="sm_inside_tabs">
	<tr>

<td class="sm_left_menu">
	<div class="sm_left_menu_item active" data-tab="import_data">Import data</div>
	<div class="sm_left_menu_item" data-tab="recover_backup">Recover backup</div>
</td>

<td class="sm_right_tabs">
	<div class="sm_right_tab active" data-tab="import_data">


<form id="sm_import_form">
	<p>Select import type: <select id="sm_import_type" name="import_type">
		<option value=""></option>
		<option value="scoresheet">Scoresheet</option>
	</select></p>

<div class="sm_import_type_info" data-type="scoresheet">

<p>To import a scoresheet after a game, fill out this blank scoresheets.</p>

<table class="sm_import_blanks">
	<tr>

<?php foreach ($sports as $k => $v) { $class = !file_exists(SPORTSMANAGER_DIR.'imports/'.$k.'.csv') ? 'disabled' : ''; ?>
	<td><a href="<?php echo SPORTSMANAGER_URL.'imports/'.$k.'.csv'; ?>" class="<?php echo $class; ?>" target="_blank"><?php echo $v[0]; ?></a></td>
<?php }; ?>

	</tr>
</table>

</div>

	<p>Choose a .csv file to upload: <input type="text" id="sm_import_file_url" name="import_file_url" /><button class="sm_import_upload_file">Upload</button></p>
	<p>Define delimiter: <select id="sm_import_delimiter" name="import_delimiter">
		<option value=""></option>
		<option value=",">Comma (,)</option>
		<option value=";">Semi-colon (;)</option>
	</select></p>
	<button type="submit" id="sm_import_btn">Import file</button>
	<div id="sm_import_ajax_return" class="sm_ajax_return"></div>
</form>

	</div>
	<div class="sm_right_tab" data-tab="recover_backup">

<p>Not ready yet...</p>

	</div>
</td>

	</tr>
</table>

</div>
