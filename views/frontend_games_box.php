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
if (isset($next_game)) {
?>

<div class="games_container">

<div class="next"></div>
<div class="previous"></div>

<div class="game_results">

<?php
	for ($i = $next_game - 3; $i < $next_game + 4; $i++) {
		if (isset($this->rows[$i])) {
			$row = $this->rows[$i];
?>

<div class="game_info <?php if ($i == $next_game) echo 'current'; ?>">

<div class="home_info">
	<div class="home_logo"><img src="http://graph.facebook.com//picture?type=square" width="50" height="50" alt="<?php echo $row->home_team_name; ?>" /></div>
	<div class="home_result">
		<span><?php echo $row->home_team_name; ?></span><br/>
		<span><?php echo $row->home_score; ?></span>
	</div>
</div>
<div class="vs">VS</div>
<div class="away_info">
	<div class="away_logo"><img src="http://graph.facebook.com//picture?type=square" width="50" height="50" alt="<?php echo $row->away_team_name; ?>" /></div>
	<div class="away_result">
		<span><?php echo $row->away_team_name; ?></span><br/>
		<span><?php echo $row->away_score; ?></span>
	</div>
</div>
<div class="date_info"><?php echo $row->date_str; ?></div>

</div>

<?php
		};
	};
?>

</div>

<div class="clear"></div>

</div>

<?php
};
