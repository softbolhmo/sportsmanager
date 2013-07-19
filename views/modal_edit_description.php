
<div id="sm_edit_description_modal" class="sm_modal_box sm_modal_edit_info">
	<div class="sm_modal_header">
		<table class="title">
			<tr>
				<td>Edit Description</td>
			</tr>
		</table>
	</div>

	<div class="sm_modal_inner">

<form id="sm_edit_description_form">

<table class="sm_inside_tabs">
	<tr>

<td class="sm_left_menu">
<?php foreach ($languages as $k => $v) { $class = $k == $option_language ? 'active' : ''; ?>
	<div class="sm_left_menu_item <?php echo $class; ?>" data-tab="<?php echo $k; ?>"><?php echo $v[0]; ?></div>
<?php }; ?>
</td>

<td class="sm_right_tabs">
<?php foreach ($languages as $k => $v) { $class = $k == $option_language ? 'active' : ''; ?>
	<div class="sm_right_tab <?php echo $class; ?>" data-tab="<?php echo $k; ?>">

<textarea class="sm_current_input" name="current_cell" value="" data-lang="<?php echo $k; ?>" tabindex="1"></textarea>

	</div>
<?php }; ?>
</td>

	</tr>
</table>

</form>

	</div>

	<div class="sm_modal_footer">
		<table id="sm_edit_description_btn" class="save" tabindex="1">
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
