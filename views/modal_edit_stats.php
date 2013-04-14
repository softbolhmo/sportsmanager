
<div id="sm_edit_stats_modal" class="sm_modal_box sm_modal_edit_info">
	<div class="sm_modal_header">
		<table class="title">
			<tr>
				<td>Edit Stats</td>
			</tr>
		</table>
	</div>

	<div class="sm_modal_inner">

<form id="sm_edit_stats_form">

<table class="" border="0" cellspacing="0" cellpadding="0">
	<thead>
		<tr>
			<th>Stat</th>
			<th>Code</th>
			<th>Value</th>
		</tr>
	</thead>
	<tbody>

<?php foreach ($stats as $k => $v) { ?>

		<tr class="stat <?php foreach ($v[2] as $sport) echo $sport.' '; ?>">
			<td><?php echo $v[1]; ?></td>
			<td><?php echo $v[0]; ?></td>
			<td><input type="text" id="stats-<?php echo $k; ?>" name="<?php echo $k; ?>" value="" tabindex="1" /></td>
		</tr>

<?php }; ?>

	</tbody>
</table>

</form>

	</div>

	<div class="sm_modal_footer">
		<table id="sm_edit_stats_btn" class="save" tabindex="1">
			<tr>
				<td>Save</td>
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
