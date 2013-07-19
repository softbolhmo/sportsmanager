
<!--start Sports Manager-->

<div class="sm_player_stats_box_container">
	<div class="sm_player_stats_menu">

<?php
	foreach ($tabs as $tab => $value) {
		$class = $tab == date('Y') ? 'active' : ''
?>
	<div class="sm_player_stats_menu_item <?php echo $class; ?>" data-tab="<?php echo $tab; ?>"><?php echo $value; ?></div>
<?php }; ?>

	</div>
	<div class="sm_player_stats_tabs">

<?php
	foreach ($tabs as $tab => $value) {
		$class = $tab == date('Y') ? 'active' : '';
?>

<div class="sm_player_stats_tabs_item <?php echo $class; ?>" data-tab="<?php echo $tab; ?>">

<?php
	$args = array (
		'display' => 'stats_players',
		'league_id' => $league_id,
		'season' => $tab,
		'sport' => $sport,
		'player_id' => $player_id,
		'sortable' => false
	);
	if ($tab == 'all') unset($args['season']);
	$this->generate($args);
?>

</div>

<?php }; ?>

	</div>
</div>

<style>
.sm_player_stats_box_container {
}
.sm_player_stats_menu_item{
	padding:5px;
	display:inline-block;
	cursor:pointer;
}
.sm_player_stats_menu_item.active{
	background:#f9f9f9;
	border:1px solid #ccc;
	border-bottom:none;
}
.sm_player_stats_tabs_item{
	padding:0 10px;
	display:none;
	background:#f9f9f9;
	box-shadow:0 0 10px rgba(0,0,0,.3);
}
.sm_player_stats_tabs_item.active{
	display:block;
}
.sm_player_stats_box_container .sm_filter_table{
	padding:0;
}
</style>

<script type="text/javascript">
$(document).ready(function() {
	$(".sm_player_stats_menu_item").click(function() {
		if (!$(this).hasClass("active")) {
			var container = $(this).parents(".sm_player_stats_box_container");
			var tab = $(this).attr("data-tab");
			$(".sm_player_stats_menu_item").removeClass("active");
			$(this).addClass("active");
			container.find(".sm_player_stats_tabs_item").removeClass("active");
			container.find(".sm_player_stats_tabs_item[data-tab='" + tab + "']").addClass("active");
		}
		return false;
	});
});
</script>

<!--end Sports Manager-->
