<?php

/* CUSTOM USER META BOX */
class LBO_user_meta_boxes {
	function __construct() {
		$this->setup();
		add_action('show_user_profile', array (&$this, 'add_user_meta_box'));
		add_action('edit_user_profile', array (&$this, 'add_user_meta_box'));
		add_action('personal_options_update', array (&$this, 'save_user_meta_box'));
		add_action('edit_user_profile_update', array (&$this, 'save_user_meta_box'));
	}

	function setup() {
		$this->user_meta_boxes = array (
			'user_meta_box_baseball' => array (
				'id' => 'user_meta_box_baseball',
				'title' => 'Baseball Info',
				'fields' => array (
					array (
						'name' => 'Équipe',
						'desc' => "Équipe du joueur (slug, e.g. blue-ribbons)",
						'id' => 'team',
						'type' => 'text',
						'default' => ''
					),
					array (
						'name' => 'Position',
						'desc' => "Position du joueur",
						'id' => 'position',
						'type' => 'select',
						'options' => array (
							array (
								'name' => 'Catcher',
								'value' => 'catcher'
							)
						)
					),
					array (
						'name' => 'Facebook ID',
						'desc' => "ID du joueur (e.g. charles.a.lacroix)",
						'id' => 'facebook_id',
						'type' => 'text',
						'default' => ''
					)
				)
			),
		);
	}

	/* add meta box */
	function add_user_meta_box($user) {
		foreach ($this->user_meta_boxes as $user_meta_box) {
?>

<h3><?php echo $user_meta_box['title'] ?></h3>

<table class="form-table">
	<tbody>

<?php
		foreach ($user_meta_box['fields'] as $field) {
			$meta = get_the_author_meta($field['id'], $user->ID);
?>

		<tr>
			<th>
				<label for="<?php echo $field['id']; ?>"><?php echo $field['name']; ?></label>
			</th>
			<td>

<?php
	switch ($field['type']) {
		case 'text':
			$meta_ = $meta ? $meta : $field['default'];
?>
			
				<input type="text" name="<?php echo $field['id']; ?>" id="<?php echo $field['id']; ?>" value="<?php echo $meta_; ?>" class="regular-text">
				<span class="description"><?php echo $field['desc']; ?></span>

<?php
			break;
		case 'select':
?>

				<select name="<?php echo $field['id']; ?>" id="<?php echo $field['id']; ?>">

<?php
			foreach ($field['options'] as $option) {
				$meta_ = $meta == $option['value'] ? ' selected="selected"' : '';
?>

<option value="<?php echo $option['value']; ?>"<?php echo $meta_; ?>><?php echo $option['name']; ?></option>

<?
			};
?>

				</select>
				<span class="description"><?php echo $field['desc']; ?></span>

<?php
			break;
	};
?>

			</td>
		</tr>
<?php
		};
?>

	</tbody>
</table>

<?php
		};
	}

	/* save data from user meta box */
	function save_user_meta_box($user_id) {
		if (!current_user_can('edit_user', $user_id)) return false;
		foreach ($this->user_meta_boxes as $user_meta_box) {
			foreach ($user_meta_box['fields'] as $field) {
				$old = get_user_meta($user_id, $field['id'], true);
				$new = $_POST[$field['id']];
				if ($new && $new != $old) {
					update_user_meta($user_id, $field['id'], $new);
				} elseif ($new == '' && $old) {
					delete_user_meta($user_id, $field['id'], $old);
				};
			};
		};
	}
}
