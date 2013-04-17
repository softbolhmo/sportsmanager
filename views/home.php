
<div class="sm_page_inside_container">

<table class="sm_inside_tabs">
	<tr>

<td class="sm_left_menu">
	<div class="sm_left_menu_item active" data-tab="options">Options</div>
	<div class="sm_left_menu_item" data-tab="backup">Backup</div>
</td>

<td class="sm_right_tabs">
	<div class="sm_right_tab active" data-tab="options">

<form id="sm_save_options_form">
	<p>Disable intro: <input id="sm_option_disable_intro" name="option_disable_intro" type="checkbox" value="disabled" <?php if ($option_disable_intro == 'disabled') echo 'checked="checked" '; ?>/></p>
	<p>Email: <input id="sm_option_email" name="option_email" type="text" value="<?php echo $option_email; ?>" /></p>
	<p>Name: <input id="sm_option_email_name" name="option_email_name" type="text" value="<?php echo $option_email_name; ?>" /></p>
	<p>Language: <select id="sm_option_language" name="option_language">
		<option value="<?php echo $option_language; ?>"><?php echo $languages[$option_language][1]; ?></option>

<?php foreach ($languages as $k => $v) { if ($k != $option_language) { ?>
		<option value="<?php echo $k; ?>"><?php echo $v[1]; ?></option>
<?php }}; ?>

	</select></p>
	<p>Custom CSS class for frontend tables e.g. <i>custom_table my_sm_table</i>: <input id="sm_option_custom_class_table" name="option_custom_class_table" type="text" value="<?php echo $option_custom_class_table; ?>" /></p>
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
