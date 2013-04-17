
<div id="sm_filter_container-<?php echo $filter; ?>" class="sm_filter_container">

<table class="sm_filter_table" border="0" cellspacing="0" cellpadding="0">
	<thead>
		<tr>

<?php foreach ($headers[$filter] as $k => $v) { ?>

			<th class="column-<?php echo $k; ?> <?php echo $v[2] ? 'required' : ''; ?>" scope="col" data-column="<?php echo $k; ?>" data-filter="<?php echo $filter; ?>" title="<?php echo $v[2] ? 'required' : ''; ?>"><?php echo $v[0]; ?></th>

<?php }; ?>

			<th class="column-delete" scope="col" data-column="delete" data-filter="<?php echo $filter; ?>">Delete</th>

		</tr>
	</thead>
	<tbody>

<?php foreach ($this->rows as $object) { ?>

		<tr id="<?php echo $filter.'-'.$object->id; ?>" data-row="<?php echo $object->id; ?>">

<?php foreach ($headers[$filter] as $k => $v) { ?>

			<td id="<?php echo $filter.'-'.$object->id.'-'.$k; ?>" class="column-<?php echo $k; ?> <?php echo $v[2] ? 'required' : ''; ?>" title="<?php echo $v[2] ? 'required' : ''; ?>" tabindex="1"><?php echo $object->$k; ?></td>

<?php }; ?>

			<td id="<?php echo $filter.'-'.$object->id.'-'; ?>delete" class="column-delete" tabindex="1"><button class="sm_delete_row_btn"><img src="<?php echo $icon_url; ?>" /></button></td>

		</tr>

<?php }; ?>

	</tbody>
</table>

<div class="clear"></div>

<div class="sm_below_filter_table">
	<button class="sm_add_row_btn"><?php echo $add_row; ?></button>

<?php if ($filter == 'games') { ?>
	<button class="sm_add_rows_btn"><?php echo $add_rows; ?></button>
<?php }; ?>

</div>

</div><!--end .sm_filter_container-->

<?php $SM->include_view('modal_edit_cell'); ?>
<?php $SM->include_view('modal_edit_description'); ?>
<?php $SM->include_view('modal_edit_stats'); ?>
<?php $SM->include_view('modal_edit_infos'); ?>
<?php $SM->include_view('modal_edit_players_id'); ?>
<?php $SM->include_view('modal_add_row'); ?>
<?php $SM->include_view('modal_add_rows'); ?>
<?php $SM->include_view('modal_delete_row'); ?>
<?php $SM->include_view('modal_set_session'); ?>
