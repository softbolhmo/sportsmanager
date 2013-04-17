<?php
$leaders = array (
	'batting_average' => array ('AVG', "Batting Average"),
	'hits' => array ('H', "Hits"),
	'home_runs' => array ('HR', "Home Runs"),
	'on_base_percentage' => array ('OBP', "On Base Percentage"),
	'runs_batted_in' => array ('RBI', "Runs Batted In"),
	'stolen_bases' => array ('SB', "Stolen Bases")
);
?>

<div class="leader_container">

<?php
$game_count = array ();
foreach ($this->db->games as $game) {
	if (strtotime($game->date) <= time() && $game->cancelled != 1) {
		foreach (array ($game->home_team_id, $game->away_team_id) as $team) {
			if (isset($game_count[$team])) {
				$game_count[$team]++;
			} else {
				$game_count[$team] = 1;
			};
		};
	};
};
$min_game_count = count($game_count) >= 1 ? min($game_count) : 0;

foreach ($leaders as $leader_k => $leader_v) {
	$this->rows = sm_order_array_objects_by(array ($leader_k), $this->rows, true);
	$leader = $this->rows[0];
?>

<div id="leader_box-<?php echo $this->args->filter;?>" class="leader_box <?php echo $this->args->filter;?>">

<a href="<?php echo SM_STATS_URL.$this->args->season.'/?sort='.$leader_k; ?>"><h3><?php echo $leader_v[1]; ?> (<?php echo $leader_v[0]; ?>)</h3></a>

<div class="profile_pic"><?php if (isset($leader->facebook_id)) { ?><img src="http://graph.facebook.com/<?php echo $leader->facebook_id; ?>/picture?type=square" width="50" height="50" alt="<?php echo $leader->facebook_id; ?>" /><?php }; ?></div>

<table class="display">
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

<div class="clear"></div>

</div>

<?php }; ?>

<div class="clear"></div>

</div>
