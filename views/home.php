
<div class="sm_page_inside_container">

<form id="sm_save_options_form">
	<input type="hidden" name="action" value="sm_db" />
	<input type="hidden" name="do" value="set_options" />
	<p>Email: <input id="email" name="email" type="text" value="<?php echo $email; ?>" /></p>
	<p>Name: <input id="emailed" name="emailed" type="text" value="<?php echo $emailed; ?>" /></p>
	<button type="submit">Save options</button>
	<div id="sm_options_ajax_return" style="display:none;"></div>
</form>

<i>*Please take note that these personal infos are not sent anywhere. Their only purpose is to send you by email your backup.</i><b>Look at the source code, if you want...</b>

<form id="sm_do_backup_form">
	<button type="submit">Generate backup</button>
	<div id="sm_backup_ajax_return" style="display:none;"></div>
</form>

<i>*Backing up your data is very important. Make sure to look into your junk mail also.</i>

</div>
