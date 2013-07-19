
<!--start Sports Manager-->

<div class="sm_leaders_box_container">

<?php
	foreach ($headers as $stat_k => $stat_v) {
		if (in_array('all', $stat_v[2]) || in_array($sport, $stat_v[2])) {
			$rows = sm_order_array_objects_by(array ($stat_k), $rows, true);
			$leader = isset($rows[0]) ? $rows[0] : '';
			$img_link = isset($leader->facebook_id) ? 'http://graph.facebook.com/'.$leader->facebook_id.'/picture?type=square' : '';
			$stat_url = str_replace(array ('%slug%'), array ($stat_k), $this->args->default_stats_url);
			if ($stat_url == '') $stat_url = '#';
?>

<div id="sm_leaders_box-<?php echo $filter;?>" class="sm_leaders_box <?php echo $filter;?>">

<table class="sm_leader_box_header">
	<tr>

<td class="stat_title"><a href="<?php echo $stat_url; ?>"><h4><?php echo $stat_v[1]; ?> (<?php echo $stat_v[0]; ?>)</h4></a></td>
<td class="profile_pic"><img src="http://graph.facebook.com/<?php echo $leader->facebook_id; ?>/picture?type=square" width="50" height="50" alt="<?php echo $leader->facebook_id; ?>" /></td>

	</tr>
</table>

<table class="sm_default_table_class <?php echo $option_custom_class_table; ?>">
	<tbody>

<?php
	$i = 0;
	foreach ($rows as $row) {
		if (isset($top) && $i >= $top) break;
		if (isset($row->$stat_k) && $row->$stat_k > 0) {
			if (in_array($stat_k, array ('batting_average', 'on_base_percentage')) && $row->at_bat < $min_game_count * 1) continue;
			$i++;
?>

<tr>
	<td><?php echo $i; ?></td>
	<td><?php echo isset($row->player_link) ? $row->player_link : ''; ?></td>
	<td><?php echo $row->$stat_k; ?></td>
</tr>

<?php
		} else {
			if ($i >= 1) break;
			$i++;
?>

<tr>
	<td class="sm_empty" colspan="3">-</td>
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

<div class="sm_clear"></div>

<?php
		};
	};
?>

</div>

<style>
.sm_leaders_box_container {
}
.sm_leaders_box {
	position:relative;
	margin:10px;
	padding:10px;
	width:270px;
	float:left;
	overflow:hidden;
}
.sm_leader_box_header {
	width:100%;
	vertical-align:middle;
}
.sm_leader_box_header .stat_title {
	height:50px;
	text-align:center;
}
.sm_leader_box_header .profile_pic {
	width:50px;
}
.sm_leader_box_header .profile_pic img {
	margin:0;
	padding:0;
	display:block;
}
</style>

<!--end Sports Manager-->
