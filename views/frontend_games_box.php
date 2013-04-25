
<!--start Sports Manager-->

<?php
$this->rows = sm_order_array_objects_by('time', $this->rows);
foreach ($this->rows as $i => $row) {
	if (isset($row->time) && $row->time >= time()) {
		$next_game = $i;
		break;
	};
};
if (isset($next_game)) {
?>

<div class="sm_games_box_container">

<div class="next"></div>
<div class="previous"></div>

<div class="sm_games_box">

<?php
	for ($i = $next_game - 3; $i < $next_game + 4; $i++) {
		if (isset($this->rows[$i])) {
			$row = $this->rows[$i];
?>

<div class="sm_game_info <?php if ($i == $next_game) echo 'current'; ?>">

<div class="sm_home_info">
	<div class="sm_home_logo"><img src="http://graph.facebook.com//picture?type=square" width="50" height="50" alt="<?php echo $row->home_team_name; ?>" /></div>
	<div class="sm_home_result">
		<span><?php echo $row->home_team_name; ?></span><br/>
		<span><?php echo $row->home_score; ?></span>
	</div>
</div>
<div class="sm_versus">VS</div>
<div class="sm_away_info">
	<div class="sm_away_logo"><img src="http://graph.facebook.com//picture?type=square" width="50" height="50" alt="<?php echo $row->away_team_name; ?>" /></div>
	<div class="sm_away_result">
		<span><?php echo $row->away_team_name; ?></span><br/>
		<span><?php echo $row->away_score; ?></span>
	</div>
</div>
<div class="sm_date_info"><?php echo $row->date_str; ?></div>

</div>

<?php
		};
	};
?>

</div>

</div>

<?php }; ?>

<style>
.sm_games_box_container {
	position:relative;
	margin:10px;
	padding:10px;
	background:#f1f1f1;
	box-shadow:0 0 6px #000;
	border:#333;
	border-radius:3px;
}
.sm_games_box_container .next,
.sm_games_box_container .previous {
	position:absolute;
	top:20px;
	bottom:20px;
	width:30px;
	height:80%;
	cursor:pointer;
	background:#f1f1f1;
	box-shadow:0 0 6px #000;
	border:#333;
	border-radius:3px;
}
.sm_games_box_container .next:hover,
.sm_games_box_container .previous:hover {
	background-color:#fff;
}
.sm_games_box_container .next {
	right:0;
	margin:0 -10px 0 0;
	background:url(../images/black_arrows.png) no-repeat center center #f1f1f1;
}
.sm_games_box_container .previous {
	left:0;
	margin:0 0 0 -10px;
	background:url(../images/black_arrows.png) no-repeat center center #f1f1f1;
}
.sm_game_info {
	display:none;
	text-align:center;
}
.sm_game_info.current {
	display:block;
}
.sm_home_info,
.sm_away_info {
	width:50%;
	float:left;
}
.sm_versus {
	position:absolute;
	top:50%;
	left:50%;
	margin:-20px 0 0 -20px;
	width:40px;
	height:40px;
	font-weight:bold;
}
.sm_home_result,
.sm_away_result {
	padding:10px 20px;
}
</style>

<script type="text/javascript">
$(document).ready(function() {
	$(".sm_games_box_container .next, .sm_games_box_container .previous").click(function() {
		var button = $(this);
		var current = $(".sm_games_box_container .sm_game_info.current");
		if (button.hasClass("next")) {
			var new_current = current.next();
		} else if (button.hasClass("previous")) {
			var new_current = current.prev();
		};
		if (new_current.length != 0) {
			current.removeClass("current");
			new_current.addClass("current");
		};
	});
});
</script>

<!--end Sports Manager-->
