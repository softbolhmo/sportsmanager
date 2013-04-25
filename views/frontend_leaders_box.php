
<!--start Sports Manager-->

<div class="sm_leaders_box_container">

<?php
foreach ($leaders as $leader_k => $leader_v) {
	$this->rows = sm_order_array_objects_by(array ($leader_k), $this->rows, true);
	$leader = $this->rows[0];
?>

<div id="sm_leaders_box-<?php echo $this->args->filter;?>" class="sm_leaders_box <?php echo $this->args->filter;?>">

<a href="<?php echo $default_stats_url != '' ? $default_stats_url.$this->args->season.'/?sort='.$leader_k : '#'; ?>"><h3><?php echo $leader_v[1]; ?> (<?php echo $leader_v[0]; ?>)</h3></a>

<div class="profile_pic"><?php if (isset($leader->facebook_id)) { ?><img src="http://graph.facebook.com/<?php echo $leader->facebook_id; ?>/picture?type=square" width="50" height="50" alt="<?php echo $leader->facebook_id; ?>" /><?php }; ?></div>

<table class="sm_default_table_class">
	<tbody>

<?php
	$i = 0;
	foreach ($this->rows as $row) {
		if (isset($this->args->top) && $i >= $this->args->top) break;
		if (isset($row->$leader_k) && $row->$leader_k > 0) {
			if (in_array($leader_k, array ('batting_average', 'on_base_percentage')) && $row->at_bat < $min_game_count * 1) continue;
			$i++;
?>

		<tr>

			<td><?php echo $i; ?></td>
			<td><?php echo isset($row->player_link) ? $row->player_link : ''; ?></td>
			<td><?php echo $row->$leader_k; ?></td>

		</tr>

<?php
		};
	};
?>

	</tbody>
	<tfoot>
		<tr>
			<th></th>
			<th></th>
			<th></th>
		</tr>
	</tfoot>
</table>

</div>

<?php }; ?>

</div>

<style>
.sm_leaders_box_container {
}
</style>

<!--end Sports Manager-->
