
<div class="sm_page_inside_container">

<table class="sm_inside_tabs">
	<tr>

<td class="sm_left_menu">
	<div class="sm_left_menu_item active" data-tab="welcome">Welcome</div>
	<div class="sm_left_menu_item" data-tab="options">Options</div>
	<div class="sm_left_menu_item" data-tab="backup">Backup</div>
</td>

<td class="sm_right_tabs">
	<div class="sm_right_tab active" data-tab="welcome">

<p>Welcome to Sports Manager</p>

	</div>
	<div class="sm_right_tab" data-tab="options">

<form id="sm_save_options_form">
	<h3>Profile</h3>

<div class="sm_control_group">
	<div class="sm_controls">
		<label class="sm_checkbox">
			<input id="sm_option_disable_intro" name="option_disable_intro" type="checkbox" value="disabled" <?php if ($option_disable_intro == 'disabled') echo 'checked="checked" '; ?>/>
			Disable intro
		</label>
	</div>
</div>

<div class="sm_control_group">
	<label class="sm_control_label">Email</label>
	<div class="sm_controls">
		<input id="sm_option_email" name="option_email" type="text" value="<?php echo $option_email; ?>" placeholder="name@mail.com" />
	</div>
</div>

<div class="sm_control_group">
	<label class="sm_control_label">Name</label>
	<div class="sm_controls">
		<input id="sm_option_email_name" name="option_email_name" type="text" value="<?php echo $option_email_name; ?>" />
	</div>
</div>

<div class="sm_control_group">
	<label class="sm_control_label">Language</label>
	<div class="sm_controls">
		<select id="sm_option_language" name="option_language">
			<option value="<?php echo $option_language; ?>"><?php echo $languages[$option_language][1]; ?></option>

<?php foreach ($languages as $k => $v) { if ($k != $option_language) { ?>
			<option value="<?php echo $k; ?>"><?php echo $v[1]; ?></option>
<?php }}; ?>

		</select>
	</div>
</div>

	<h3>Frontend CSS</h3>

<div class="sm_control_group">
	<label class="sm_control_label">Custom CSS class for tables</label>
	<div class="sm_controls">
		<input id="sm_option_custom_class_table" name="option_custom_class_table" type="text" value="<?php echo $option_custom_class_table; ?>" placeholder="custom_table my_sm_table" />
	</div>
</div>

	<h3>Default URLs</h3>

<div class="sm_control_group">
	<label class="sm_control_label">Default URL for clubs</label>
	<div class="sm_controls">
		<input id="sm_option_default_clubs_url" name="option_default_clubs_url" type="text" value="<?php echo $option_default_clubs_url; ?>" placeholder="http://myleague.com/teams/slug" />
	</div>
</div>

<div class="sm_control_group">
	<label class="sm_control_label">Default URL for locations</label>
	<div class="sm_controls">
		<input id="sm_option_default_locations_url" name="option_default_locations_url" type="text" value="<?php echo $option_default_locations_url; ?>" placeholder="http://myleague.com/locations/slug" />
	</div>
</div>

<div class="sm_control_group">
	<label class="sm_control_label">Default URL for players</label>
	<div class="sm_controls">
		<input id="sm_option_default_players_url" name="option_default_players_url" type="text" value="<?php echo $option_default_players_url; ?>" placeholder="http://myleague.com/players/slug" />
	</div>
</div>

<div class="sm_control_group">
	<label class="sm_control_label">Default URL for results</label>
	<div class="sm_controls">
		<input id="sm_option_default_results_url" name="option_default_results_url" type="text" value="<?php echo $option_default_results_url; ?>" placeholder="http://myleague.com/results/id" />
	</div>
</div>

<div class="sm_control_group">
	<label class="sm_control_label">Default URL for stats</label>
	<div class="sm_controls">
		<input id="sm_option_default_stats_url" name="option_default_stats_url" type="text" value="<?php echo $option_default_stats_url; ?>" placeholder="http://myleague.com/stats/id" />
	</div>
</div>

<div class="sm_control_group">
	<label class="sm_control_label">Default URL for teams</label>
	<div class="sm_controls">
		<input id="sm_option_default_teams_url" name="option_default_teams_url" type="text" value="<?php echo $option_default_teams_url; ?>" placeholder="http://myleague.com/teams/slug/season" />
	</div>
</div>

	<button type="submit" id="sm_save_options_btn">Save options</button>
	<div id="sm_options_ajax_return" class="sm_ajax_return"></div>
</form>
<i>*Please take note that these personal infos are not sent anywhere. Their only purpose is to send you by email your backup.</i><b>Look at the source code, if you want...</b>

	</div>
	<div class="sm_right_tab" data-tab="backup">

<form id="sm_do_backup_form">
	<button type="submit" id="sm_do_backup_btn">Generate backup</button>
	<div id="sm_backup_ajax_return" class="sm_ajax_return"></div>
</form>
<i>*Backing up your data is very important. Make sure to look into your junk mail also.</i>

	</div>
</td>

	</tr>
</table>

</div>
