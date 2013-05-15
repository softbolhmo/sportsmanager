
<!--start Sports Manager-->

<link rel="Shortcut Icon" href="<?php echo $favicon; ?>" />

<link rel="stylesheet" href="<?php echo $css; ?>" type="text/css" media="all" />

<script type="text/javascript">
	SM = {
		address: {},
		behaviors: {},
		filters: {},
		fn: {},
		hash: {},
		query: {},
		settings: {
			ajax_url: ajaxurl,
			autocomplete: {
				arrays: {
					"clubs_name": [],
					"game_type": [],
					"leagues_name": [],
					"locations_name": [],
					"players_name": [],
					"sports_slug": [],
					"teams_name": [],
					"users_name": [],
					"yes_no": []
				},
				map: {
					away_team_id: "teams_name",
					cancelled: "yes_no",
					captains_id: "players_name",
					club_id: "clubs_name",
					home_team_id: "teams_name",
					inactive: "yes_no",
					league_id: "leagues_name",
					location_id: "locations_name",
					player_id: "players_name",
					players_id: "players_name",
					sport: "sports_slug",
					type: "game_type",
					user_id: "users_name",
					winner_team_id: "teams_name",
				}
			},
			current_cell: "",
			intro: {
				disabled: "<?php echo get_option('sportsmanager_disable_intro', 'enabled'); ?>"
			},
			keycodes: {
				enter: 13,
				esc: 27,
				space: 32,
				left: 37,
				top: 38,
				right: 39,
				bottom: 40
			},
			SPORTSMANAGER_URL: "<?php echo SPORTSMANAGER_URL; ?>",
			WP_ADMIN_URL: "<?php echo WP_ADMIN_URL; ?>",
			SPORTSMANAGER_ADMIN_URL_PREFIX: "<?php echo SPORTSMANAGER_ADMIN_URL_PREFIX; ?>"
		}
	};

</script>

<script type="text/javascript" src="<?php echo $js; ?>"></script>

<!--end Sports Manager-->
