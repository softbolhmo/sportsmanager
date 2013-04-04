<?php

/* REGISTER ROLES & CAPABILITIES */
/* remove useless roles */
foreach (array ('editor', 'author') as $role) {
	remove_role($role);
};

/* player */
add_role('player', 'Joueur', array (
    'read' => true
));

/* captain */
add_role('captain', 'Capitaine', array (
	'delete_posts' => true,
	'delete_published_posts' => true,
    'edit_posts' => true,
	'edit_published_posts' => true,
	'publish_posts' => true,
	'read' => true,
	'upload_files' => true
));

/* executive */
add_role('executive', 'Exécutif', array (
	'delete_posts' => true,
	'delete_published_posts' => true,
    'edit_posts' => true,
	'edit_published_posts' => true,
	'publish_posts' => true,
	'read' => true,
	'upload_files' => true
));

global $wp_roles;
foreach ($wp_roles->get_names() as $k => $v) {
	$role = get_role($k);
	if ($k != 'subscriber') {
		$role->add_cap('login');
		//$role->remove_cap('login');
	};

	//sportsmanager
	if (in_array($k, array ('administrator', 'executive'))) {
		$role->add_cap('edit_sportsmanager');
	};
};

function restrict_wp_admin() {
	if (is_admin() && !current_user_can('edit_posts')) {
		header('Location: '.NOWP_HOME_URL);
		die;
	};
}

//add_action('init', 'restrict_wp_admin');
