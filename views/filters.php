
<div id="sm_filter_container-<?php echo $filter; ?>" class="sm_filter_container">

<table class="sm_filter_table" border="0" cellspacing="0" cellpadding="0">
	<thead>
		<tr>

<?php foreach ($headers[$filter] as $k => $v) { ?>

<?php
	$mask_value = false;
	$restricted_type = "";
	$restricted_limit = "";
	if ($this->is_special_backend_key($k)) {
		$mask_value = true;
	};
	if ($this->is_restricted_backend_key($k)) {
		$restricted_type = isset($this->restricted_backend_keys[$k][0]) ? $this->restricted_backend_keys[$k][0] : '';
		$restricted_limit = isset($this->restricted_backend_keys[$k][1]) ? $this->restricted_backend_keys[$k][1] : '';
	};
?>

			<th class="column-<?php echo $k; ?> <?php echo $v[2] ? 'required' : ''; ?>" scope="col" data-column="<?php echo $k; ?>" data-filter="<?php echo $filter; ?>" data-mask-value="<?php echo $mask_value ? '1' : '0'; ?>" data-restricted-type="<?php echo $restricted_type; ?>" data-restricted-limit="<?php echo $restricted_limit; ?>" title="<?php echo $v[2] ? 'required' : ''; ?>"><?php echo $v[0]; ?></th>

<?php }; ?>

			<th class="column-delete" scope="col" data-column="delete" data-filter="<?php echo $filter; ?>">Delete</th>

		</tr>
	</thead>
	<tbody>

<?php foreach ($this->rows as $object) { ?>

		<tr id="<?php echo $filter.'-'.$object->id; ?>" data-row="<?php echo $object->id; ?>">

<?php foreach ($headers[$filter] as $k => $v) { ?>

<?php
	$value = '';
	$name = $object->$k;
	$mask_value = false;
	$restricted_type = "";
	$restricted_limit = "";
	if ($this->is_special_backend_key($k)) {
		$value = $object->$k;
		$name = $this->get_special_backend_key_name($k, $value);
		$mask_value = true;
	};
	if ($this->is_restricted_backend_key($k)) {
		$restricted_type = isset($this->restricted_backend_keys[$k][0]) ? $this->restricted_backend_keys[$k][0] : '';
		$restricted_limit = isset($this->restricted_backend_keys[$k][1]) ? $this->restricted_backend_keys[$k][1] : '';
	};
?>

			<td id="<?php echo $filter.'-'.$object->id.'-'.$k; ?>" class="column-<?php echo $k; ?> <?php echo $v[2] ? 'required' : ''; ?>" data-value="<?php echo $value; ?>" data-mask-value="<?php echo $mask_value ? '1' : '0'; ?>" data-restricted-type="<?php echo $restricted_type; ?>" data-restricted-limit="<?php echo $restricted_limit; ?>" title="<?php echo $v[2] ? 'required' : ''; ?>" tabindex="1"><?php echo $name; ?></td>

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

<?php if ($filter == 'scoresheets') { ?>
	<button class="sm_delete_rows_btn" onclick="javascript:alert('Not ready yet');"><?php echo $delete_rows; ?></button>
<?php }; ?>

</div>

</div><!--end .sm_filter_container-->

<?php $this->include_view('modal_edit_cell'); ?>
<?php $this->include_view('modal_edit_description'); ?>
<?php $this->include_view('modal_edit_stats'); ?>
<?php $this->include_view('modal_edit_infos'); ?>
<?php $this->include_view('modal_edit_players_id'); ?>
<?php $this->include_view('modal_add_row'); ?>
<?php $this->include_view('modal_add_rows'); ?>
<?php $this->include_view('modal_delete_row'); ?>
<?php $this->include_view('modal_set_session'); ?>
