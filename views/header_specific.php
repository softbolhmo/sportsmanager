
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
					"clubs-name": [],
					"game-type": [],
					"leagues-name": [],
					"locations-name": [],
					"players-name": [],
					"sports-slug": [],
					"teams-name": [],
					"users-name": [],
					"yes-no": []
				},
				map: {
					away_team_id: "teams-name",
					cancelled: "yes-no",
					club_id: "clubs-name",
					home_team_id: "teams-name",
					league_id: "leagues-name",
					location_id: "locations-name",
					player_id: "players-name",
					players_id: "players-name",
					sport: "sports-slug",
					type: "game-type",
					user_id: "users-name",
					winner_team_id: "teams-name",
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
