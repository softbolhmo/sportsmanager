<?php

/* GET USER LIST */
function get_user_list($role) {
	global $wpdb;
	$user_list = array ();
	$users = $wpdb->get_results("SELECT ID, display_name FROM $wpdb->users ORDER BY display_name ASC");
	foreach ($users as $user) {
		$id = (int) $user->ID;
		$wp_user = new WP_User($id);
		if ($wp_user->roles[0] == $role) {
			$display = stripslashes($user->display_name);
			$user_list[$id] = array (
				'name' => $display,
				'value' => $id
			);
		};
	}
	return $user_list;
}
