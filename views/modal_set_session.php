
<div id="sm_set_session_modal" class="sm_modal_box sm_modal_edit_info">
	<div class="sm_modal_header">
		<table class="title">
			<tr>
				<td>Current League</td>
			</tr>
		</table>
	</div>

	<div class="sm_modal_inner">

<form id="sm_set_session_form">
	<input type="hidden" name="action" value="sm_session" />
	<select name="sm_league" tabindex="1">

<?php
	foreach ($leagues as $k => $v) {
		$selected = $k == $league ? ' selected="selected"' : '';
		echo '<option value="'.$k.'"'.$selected.'>'.$v.'</option>'."\n";
	};
?>

	</select>
	<select name="sm_season" tabindex="1">

<?php
	foreach ($years as $k => $v) {
		$selected = $k == $season ? ' selected="selected"' : '';
		echo '<option value="'.$k.'"'.$selected.'>'.$v.'</option>'."\n";
	};
?>

	</select>
	<select name="sm_sport" tabindex="1">

<?php
	foreach ($sports as $k => $v) {
		$selected = $k == $sport ? ' selected="selected"' : '';
		echo '<option value="'.$k.'"'.$selected.'>'.$v.'</option>'."\n";
	};
?>

	</select>
	<h3 id="sm_current_session"><?php echo strtoupper($league.' '.$season.' '.$sport); ?></h3>
	<div id="sm_session_ajax_return" class="sm_ajax_return"></div>
</form>

	</div>

	<div class="sm_modal_footer">
		<table id="sm_set_session_btn" class="save" tabindex="1">
			<tr>
				<td>Change</td>
			</tr>
		</table>
		<table class="loader">
			<tr>
				<td>Wait...</td>
			</tr>
		</table>
		<table class="close" tabindex="1">
			<tr>
				<td>Close</td>
			</tr>
		</table>
	</div>
</div>
