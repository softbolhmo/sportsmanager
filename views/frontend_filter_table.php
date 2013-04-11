<?php
if (function_exists('order_array_objects_by')) {
	$this->rows = order_array_objects_by('time', $this->rows);
};
foreach ($this->rows as $i => $row) {
	if (isset($row->time) && $row->time >= time()) {
		$next_game = $i;
		break;
	};
};
?>

<div id="filter_table-<?php echo $this->args->filter;?>" class="filter_table <?php echo $this->args->filter;?> <?php echo (isset($this->args->sortable) && !$this->args->sortable) ? '' : 'sortable'; ?>">

<table class="display">
	<thead>
		<tr>

<?php foreach ($headers[$this->args->filter] as $k => $v) { ?>

			<th class="column-<?php echo $k; ?>" scope="col" data-column="<?php echo $k; ?>" data-filter="<?php echo $this->args->filter; ?>" title="<?php echo $v[1]; ?>"><?php echo $v[0]; ?></th>

<?php }; ?>

		</tr>
	</thead>
	<tbody>

<?php foreach ($this->rows as $i => $row) { ?>

		<tr id="<?php echo $this->args->filter.'-'.$row->id; ?>"
		class="<?php echo isset($row->cancelled) && $row->cancelled == 1 ? 'cancelled' : ''; ?> <?php echo isset($next_game) && $i == $next_game ? 'upcoming' : ''; ?>" title="<?php if (isset($row->cancelled) && $row->cancelled == 1) {echo 'Game cancelled';} elseif (isset($next_game) && $i == $next_game) {echo 'Upcoming game';} else {echo '';}; ?>" data-row="<?php echo $row->id; ?>">

<?php foreach ($headers[$this->args->filter] as $k => $v) { ?>

			<td id="<?php echo $this->args->filter.'-'.$row->id.'-'.$k; ?>" class="column-<?php echo $k; ?>" title="<?php echo $v[1]; ?>"><?php echo isset($row->$k) ? $row->$k : 0; ?></td>

<?php }; ?>

		</tr>

<?php }; ?>

	</tbody>

	<tfoot>
		<tr>

<?php foreach ($headers[$this->args->filter] as $k => $v) { ?>

			<th class="column-<?php echo $k; ?>" scope="col" data-column="<?php echo $k; ?>" data-filter="<?php echo $this->args->filter; ?>" title="<?php echo $v[1]; ?>"><?php echo count($this->rows) >= 10 ? $v[0] : ''; ?></th>

<?php }; ?>

		</tr>
	</tfoot>

</table>

</div><!--end .filter_table-->
