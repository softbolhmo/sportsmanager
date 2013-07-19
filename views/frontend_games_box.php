
<!--start Sports Manager-->

<?php if (isset($next_game)) { ?>

<div class="sm_games_box_container">

<div class="arrow next"></div>
<div class="arrow previous"></div>

<div class="sm_games_box">

<?php
	for ($i = $next_game - 3; $i < $next_game + 4; $i++) {
		if (isset($rows[$i])) {$row = $rows[$i];
?>

<div class="sm_game_info <?php if ($i == $next_game) echo 'active'; ?>">

<div class="sm_home_info">
	<div class="sm_home_logo"><img src="<?php echo $row->home_team_small_logo_url; ?>" width="50" height="50" alt="<?php echo $row->home_team_name; ?>" /></div>
	<div class="sm_home_result">
		<span><?php echo $row->home_team_name; ?></span><br/>
		<span><?php echo $row->home_score; ?></span>
	</div>
</div>
<div class="sm_versus">VS</div>
<div class="sm_away_info">
	<div class="sm_away_logo"><img src="<?php echo $row->away_team_small_logo_url; ?>" width="50" height="50" alt="<?php echo $row->away_team_name; ?>" /></div>
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

<div class="sm_clear"></div>

</div>

<?php }; ?>

<style>
.sm_games_box_container {
	position:relative;
	margin:10px auto;
	padding:10px 60px;
	max-width:600px;
	background:#f5f5f5;
	border:1px solid rgba(0,0,0,.15);
	border-radius:4px;
}
.sm_games_box_container .next,
.sm_games_box_container .previous {
	position:absolute;
	top:0;
	width:50px;
	height:100%;
	cursor:pointer;
	border-radius:2px;
}
.sm_games_box_container .next:hover,
.sm_games_box_container .previous:hover {
	background-color:#fff;
}
.sm_games_box_container .next {
	right:0;
	margin:0;
	background:url(<?php echo SPORTSMANAGER_URL; ?>images/arrow_right.png) no-repeat center center #f5f5f5;
}
.sm_games_box_container .previous {
	left:0;
	margin:0;
	background:url(<?php echo SPORTSMANAGER_URL; ?>images/arrow_left.png) no-repeat center center #f5f5f5;
}
.sm_game_info {
	display:none;
	text-align:center;
}
.sm_game_info.active {
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
	$(".sm_games_box_container .arrow").click(function() {
		var button = $(this);
		var active = $(".sm_games_box_container .sm_game_info.active");
		if (button.hasClass("next")) {
			var new_active = active.next();
		} else if (button.hasClass("previous")) {
			var new_active = active.prev();
		}
		if (new_active.length > 0) {
			active.removeClass("active");
			new_active.addClass("active");
		}
		return false;
	});
});
</script>

<!--end Sports Manager-->
