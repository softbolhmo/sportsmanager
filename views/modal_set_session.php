
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
	<select name="sm_league" data-select="session_leagues" tabindex="1">
		<option class="blank" value="">Choose a league...</option>
	</select>
	<select name="sm_season" data-select="session_seasons" tabindex="1">
		<option class="blank" value="">Choose a season...</option>
	</select>
	<select name="sm_sport" data-select="session_sports" tabindex="1">
		<option class="blank" value="">Choose a sport...</option>
	</select>
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
