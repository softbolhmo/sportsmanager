<?php

/* REGISTER TAXONOMIES */
/* add custom taxonomies */
function LBO_taxonomies() {
	global $POST_TYPES;
	$taxonomies = array (
		(object) array (
			'slug' => 'unit',
			'labels' => array (
				'name' => __('Unit'),
				'singular_name' => __('Unit'),
			),
			'post_types' => array (
				'post',
				'page',
				'labs',
				'lectures'
			)
		),
		(object) array (
			'slug' => 'format',
			'labels' => array (
				'name' => __('Format'),
				'singular_name' => __('Format'),
			),
			'post_types' => array (
				'extras'
			)
		),
		(object) array (
			'slug' => 'graduation-year',
			'labels' => array (
				'name' => __('Graduation Year'),
				'singular_name' => __('Graduation Year'),
			),
			'post_types' => array (
				'post',
				'page',
				'labs',
				'lectures'
			)
		),
	);
	foreach ($taxonomies as $t) {
		$post_types = isset($t->post_types) ? $t->post_types : $POST_TYPES;
		register_taxonomy($t->slug, $post_types, array (
			'hierarchical' => true,
			'labels' => $t->labels,
			'show_ui' => true,
			'update_count_callback' => '_update_post_term_count',
			'query_var' => true,
			'rewrite' => array ('hierarchical' => true),
		));
	};
}

//add_action('init', 'LBO_taxonomies');
