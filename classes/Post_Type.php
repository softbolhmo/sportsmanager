<?php

/* ALL POST TYPES */
$POST_TYPES = array (
	'post',
	'pages',
	'events',
	'faq',
	'locations',
	'honnors',
	'partners',
	'teams'
);


/* REGISTER POST TYPES */
function LBO_post_types() {
	$post_types = array (
		(object) array (
			'slug' => 'events',
			'rewrite' => 'evenements',
			'label' => __('Évènements'),
		),
		(object) array (
			'slug' => 'faq',
			'rewrite' => 'faq',
			'label' => __('FAQ'),
		),
		(object) array (
			'slug' => 'locations',
			'rewrite' => 'terrains',
			'label' => __('Terrains'),
		),
		(object) array (
			'slug' => 'honnors',
			'rewrite' => 'honneurs',
			'label' => __('Honneurs'),
		),
		(object) array (
			'slug' => 'partners',
			'rewrite' => 'partenaires',
			'label' => __('Partenaires'),
		),
		(object) array (
			'slug' => 'teams',
			'rewrite' => 'equipes',
			'label' => __('Équipes'),
		)
	);
	foreach ($post_types as $pt) {
		if (!isset($pt->rewrite)) $pt->rewrite = $pt->slug;
		register_post_type(
			$pt->slug, array (
				'label' => $pt->label,
				'public' => true,
				'show_ui' => true,
				'show_in_nav_menus' => false,
				'menu_position' => 5,
				'rewrite' => array ('slug' => $pt->rewrite),
				'supports' => array (
					'comments',
					'custom-fields',
					'editor',
					'thumbnail',
					'title',
				),
				'taxonomies' => array ('category', 'post_tag')
			)
		);
	};
}

add_action('init', 'LBO_post_types');
