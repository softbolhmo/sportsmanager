
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
					'teams-name': <?php echo $autocomplete_teams; ?>,
					'leagues-name': <?php echo $autocomplete_leagues; ?>,
					'locations-name': <?php echo $autocomplete_locations; ?>,
					'players-name': <?php echo $autocomplete_players; ?>,
					'users-username': <?php echo $autocomplete_users; ?> 
				},
				map: {
					away_team_id: 'teams-name',
					home_team_id: 'teams-name',
					winner_team_id: 'teams-name',
					league_id: 'leagues-name',
					location_id: 'locations-name',
					player_id: 'players-name',
					user_id: 'users-username'
				}
			},
			current_cell: '',
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
			SPORTSMANAGER_ADMIN_URL_PREFIX: "<?php echo SPORTSMANAGER_ADMIN_URL_PREFIX; ?>"
		}
	};

</script>

<script type="text/javascript" src="<?php echo $js; ?>"></script>

<!--end Sports Manager-->
