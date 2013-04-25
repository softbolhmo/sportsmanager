
<div class="sm_main_container wrap">
	<div class="sm_inner_container">

<div class="sm_page_header fancyfont">
	<table>
		<tr>

<td class="sm_page_title"><?php echo $page_title; ?></td>
<td class="sm_page_subtitle"><?php echo $page_subtitle; ?></td>

		</tr>
	</table>
</div><!--end .sm_page_header-->
<div class="sm_page_menu">

<?php
	foreach ($menu as $k => $v) {
		$class = $k == $active ? 'active ' : '';
		if (in_array($k, array ('clubs', 'games', 'locations', 'players', 'scoresheets', 'teams'))) $class .= $count == 0 ? 'disabled ' : '';
		if ($k == 'home') {
?>

<div class="sm_page_menu_item <?php echo $class; ?>"><?php echo $v; ?></div>

<?php } else { ?>

<div class="sm_page_menu_item <?php echo $class; ?>" data-tab="<?php echo $k; ?>"><?php echo $v; ?></div>

<?php
		};
	};
?>

<div class="clear"></div>

</div><!--end .sm_page_menu-->
<div class="sm_page_inside">
