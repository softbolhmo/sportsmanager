<?php

/* WORDPRESS FUNCTIONALITY */
/* thumbnail support */
add_theme_support('post-thumbnails');

/* page excerpt support */
add_post_type_support('page', 'excerpt');

/* add custom column to media table */
function LBO_add_custom_column_to_media_table($column) {
	$column['categories'] = 'Categories';
	return $column;
}

//add_filter('manage_media_columns', 'LBO_add_custom_column_to_media_table');

function LBO_custom_media_column_content($column_name, $post_id) {
	switch ($column_name) {
		case 'categories':
			$categories = wp_get_post_categories($post_id);
			$o = '';
			foreach($categories as $c) {
				$c = get_category($c);
				$o .= $c->name.', ';
			};
			$o = rtrim($o, ', ');
			echo $o;
			break;
	};
}

//add_filter('manage_media_custom_column', 'LBO_custom_media_column_content', 10, 2);

/* remove html from comments */
add_filter('comment_text', 'wp_filter_nohtml_kses');
add_filter('comment_text_rss', 'wp_filter_nohtml_kses');
add_filter('comment_excerpt', 'wp_filter_nohtml_kses');
