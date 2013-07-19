
<div class="sm_page_inside_container">

<table class="sm_inside_tabs">
	<tr>

<td class="sm_left_menu">

<?php foreach ($faq as $k => $v) { $class = $k == 0 ? 'active' : ''; ?>
	<div class="sm_left_menu_item <?php echo $class; ?>" data-tab="<?php echo $v->slug; ?>"><?php echo $v->name; ?></div>
<?php }; ?>

</td>

<td class="sm_right_tabs">

<?php foreach ($faq as $k => $v) { $class = $k == 0 ? 'active' : ''; ?>
	<div class="sm_right_tab <?php echo $class; ?>" data-tab="<?php echo $v->slug; ?>">

<?php foreach ($v->questions as $q) { ?>
<div class="sm_faq_item">
	<div class="sm_faq_question">Q: <?php echo $q->question; ?></div>
	<div class="sm_faq_answer">A: <?php echo $q->answer; ?></div>
</div>
<?php }; ?>

	</div>
<?php }; ?>

</td>

	</tr>
</table>

</div>
