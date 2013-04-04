
<div class="sm_main_container wrap">
	<div class="sm_inner_container">

<div class="sm_page_header">
	<p class="sm_page_title"><?php echo $page_title; ?></p>
	<p class="sm_page_subtitle"><?php echo $page_subtitle; ?></p>
</div><!--end .sm_page_header-->
<div class="sm_page_menu">

<?php
	foreach ($menu as $k => $v) {
		if ($k == 'home') {
?>

<a href="<?php echo $admin_url; ?>" class="<?php echo $k == $active ? 'active' : ''; ?>"><?php echo $v; ?></a>

<?php } else { ?>

<a href="<?php echo $admin_url."&tab=".$k; ?>" class="<?php echo $k == $active ? 'active' : ''; ?>" data-tab="<?php echo $k; ?>"><?php echo $v; ?></a>

<?php
		};
	};
?>

<div class="clear"></div>

</div><!--end .sm_page_menu-->
<div class="sm_page_inside">
