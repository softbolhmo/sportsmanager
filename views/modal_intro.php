
<div id="sm_intro_modal" class="sm_modal_box sm_modal_intro pale">
	<div class="sm_modal_header">
		<table class="title">
			<tr>
				<td>Intro</td>
				<td class="right_info">These steps can alway be reviewed by clicking the 'Show Intro' button in the footer.</td>
			</tr>
		</table>
	</div>

	<div class="sm_modal_inner">

<div class="sm_intro_step_indicator">
	<table>
		<tr>

<td class="active" data-step="0"><img src="<?php echo $flag_url; ?>" width="16" height="16" /></td>
<td data-step="1">1</td>
<td data-step="2">2</td>
<td data-step="3">3</td>
<td data-step="4">4</td>
<td data-step="5">5</td>
<td data-step="6">6</td>
<td data-step="7">7</td>
<td data-step="8">8</td>
<td data-step="9"><img src="<?php echo $check_url; ?>" width="16" height="16" /></td>

		</tr>
	</table>
</div>

<div class="sm_intro_step_container">
	<div class="arrow next"></div>
	<div class="arrow previous"></div>

<?php
	foreach ($steps as $k => $step) {
		$class = '';
		if ($k == 0) {
			$class = 'active first';
		} else if ($k == count($steps) - 1) {
			$class = 'last';
		};
?>

<div id="step_<?php echo $k; ?>" class="sm_intro_step <?php echo $class; ?>" data-step="<?php echo $k; ?>">
	<table>
		<tr>
			<td><?php echo $step; ?></td>
		</tr>
	</table>
</div>

<?php }; ?>

</div>

	</div>

	<div class="sm_modal_footer">
		<table class="close solo" tabindex="1">
			<tr>
				<td>Close</td>
			</tr>
		</table>
	</div>
</div>
