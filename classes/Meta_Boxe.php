<?php

/* CUSTOM META BOXES */
class LBO_meta_boxes {
	function __construct() {
		$this->setup();
		add_action('admin_menu', array (&$this, 'add_meta_boxes'));
		add_action('save_post', array (&$this, 'save_meta_box'));
	}

	function setup() {
		$this->meta_boxes = array (
			'meta_box_info' => array (
				'id' => 'meta_box_info',
				'title' => 'Post Info',
				'post_types' => array (
					'post'
				),
				'context' => 'normal',
				'priority' => 'high',
				'fields' => array (
					array (
						'name' => 'Image info',
						'desc' => "Image details",
						'id' => 'image_info',
						'type' => 'text',
						'default' => "Photo prise par..."
					),
					array (
						'name' => 'SEO description',
						'desc' => "Page description",
						'id' => 'seo_description',
						'type' => 'text',
						'default' => ""
					)
				)
			),
			'meta_box_teams' => array (
				'id' => 'meta_box_teams',
				'title' => 'Lecture/Lab Info',
				'post_types' => array (
					'teams'
				),
				'context' => 'normal',
				'priority' => 'high',
				'fields' => array (
					array (
						'name' => 'Date/Time',
						'desc' => "Enter the date & time of the lecture (YYYY-MM-DD HH:MM)",
						'id' => 'datetime',
						'type' => 'text',
						'default' => date('Y-m-d H:i')
					),
					array (
						'name' => 'Extras',
						'desc' => "Enter the CSV (*, *) list of all the extras",
						'id' => 'extras',
						'type' => 'text',
						'default' => ""
					),
					array (
						'name' => 'Lecture/Lab Number',
						'desc' => "Enter the lecture/lab's #",
						'id' => 'number',
						'type' => 'text',
						'default' => ""
					),
					array (
						'name' => 'Teacher',
						'desc' => "Select the professor/teacher's name",
						'id' => 'teacher',
						'type' => 'select',
						'options' => get_user_list('administrator')
					),
					array (
						'name' => 'Video Recording',
						'desc' => 'Enter the url of the recorded lecture',
						'id' => 'video_url',
						'type' => 'text',
						'default' => ''
					),
					/*
					array (
						'name' => 'Text',
						'desc' => 'Enter string here',
						'id' => 'text',
						'type' => 'text',
						'default' => "Default text"
					),
					array (
						'name' => 'Textarea',
						'desc' => 'Enter big text here',
						'id' => 'textarea',
						'type' => 'textarea',
						'default' => "Default textarea"
					),
					array (
						'name' => 'Select box',
						'desc' => 'Select option here',
						'id' => 'select',
						'type' => 'select',
						'options' => array ('Option 1', 'Option 2', 'Option 3')
					),
					array (
						'name' => 'Radio',
						'desc' => 'Choose option here',
						'id' => 'radio',
						'type' => 'radio',
						'options' => array (
							array ('name' => 'Name 1', 'value' => 'Value 1'),
							array ('name' => 'Name 2', 'value' => 'Value 2')
						)
					),
					array (
						'name' => 'Checkbox',
						'desc' => 'Check options here',
						'id' => 'checkbox',
						'type' => 'checkbox' //missing stuff?
					),
					array (
						'name' => 'File Upload',
						'desc' => "Upload a file (enter it's url)",
						'id' => 'file_upload',
						'type' => 'upload',
						'default' => ''
					),
					*/
				)
			),
		);
	}

	/* create functions for each meta box (not very handy...) */
	function meta_box_info() {
		$this->display_meta_box($this->meta_boxes['meta_box_info']);
	}

	function meta_box_teams() {
		$this->display_meta_box($this->meta_boxes['meta_box_teams']);
	}

	/* add meta box */
	function add_meta_boxes() {
		foreach ($this->meta_boxes as $meta_box) {
			foreach ($meta_box['post_types'] as $post_type) {
				add_meta_box(
					$meta_box['id'],
					$meta_box['title'],
					array( &$this, $meta_box['id']),
					$post_type,
					$meta_box['context'],
					$meta_box['priority']
				);
			};
		};
	}

	/* callback function to show fields in meta box */
	function display_meta_box($meta_box) {
		global $post;
		echo '<input type="hidden" name="'.$meta_box['id'].'_nonce" value="'.wp_create_nonce(basename(__FILE__)).'" />';
		echo '<table class="form-table">';
		foreach ($meta_box['fields'] as $field) {
			$meta = get_post_meta($post->ID, $field['id'], true);
?>

	<tr>
		<th style="width:20%"><label for="<?php echo $field['id']; ?>"><?php echo $field['name']; ?></label></th>
	<td>

<?php
		//convert the rest into html!
		switch ($field['type']) {
			case 'text':
				$meta_ = $meta ? $meta : $field['default'];
				echo '<input id="'.$field['id'].'" type="text" name="'.$field['id'].'" value="'.$meta_.'" size="30" style="margin:0 auto; width:100%" />'.
					'<br />'.$field['desc'];
				break;
			case 'textarea':
				$meta_ = $meta ? $meta : $field['default'];
				echo '<textarea id="'.$field['id'].'" name="'.$field['id'].'" rows="4" style="margin:0 auto; width:100%">'.$meta_.'</textarea>'.
					'<br />'.$field['desc'];
				break;
			case 'select':
				echo '<select id="'.$field['id'].'" name="'.$field['id'].'" style="margin:0 auto; width:100%">';
				echo '<option value=""'.$meta_.'></option>';
				foreach ($field['options'] as $option) {
					$meta_ = $meta == $option['value'] ? ' selected="selected"' : '';
					echo '<option value="'.$option['value'].'"'.$meta_.'>'.$option['name'].'</option>';
				};
				echo '</select>'.
					'<br />'.$field['desc'];
				break;
			case 'radio':
				foreach ($field['options'] as $option) {
					$meta_ = $meta == $option['value'] ? ' checked="checked"' : '';
					echo '<input type="radio" name="'.$field['id'].'" value="'.$option['value'].'"'.$meta_.' />'.$option['name'];
				};
				echo '<br />'.$field['desc'];
				break;
			case 'checkbox':
				$meta_ = $meta ? ' checked="checked"' : '';
?>

<input id="<?php echo $field['id']; ?>" type="checkbox" name="<?php echo $field['id']; ?>"<?php echo $meta_; ?> />
<br /><?php echo $field['desc']; ?>

<?php
				break;
			case 'upload':
				$meta_ = $meta ? $meta : $field['default'];
?>

<script type="text/javascript">
	jQuery(document).ready(function() {
		jQuery("#file_upload_button_<?php echo $meta_box['id']; ?>").click(function() {
			var formfield = jQuery("#file_upload_<?php echo $meta_box['id']; ?>").attr("name");
			tb_show("", "media-upload.php?post_id=&TB_iframe=true");
			return false;
		});
		window.send_to_editor = function(html) {
			html = "<div>" + html + "</div>";
			var img = jQuery("img", html).attr("src");
			var file = jQuery("a", html).attr("href");
			var url = "";
			if (typeof img !== "undefined") {
				url = img;
			} else if (typeof file !== "undefined") {
				url = file;
			};
			jQuery("#file_upload_<?php echo $meta_box['id']; ?>").val(url);
			tb_remove();
		};
	});
</script>

<input id="file_upload_<?php echo $meta_box['id']; ?>" type="text" name="<?php echo $field['id']; ?>" value="<?php echo $meta_; ?>" style="margin:0 auto; width:50%" />
<input id="file_upload_button_<?php echo $meta_box['id']; ?>" type="button" value="Upload File" />
<br /><?php $field['desc']; ?>

<?php
				break; 
		};
?>

		</td>
	</tr>

<?php
	};
?>

</table>

<?php
	}

	/* save data from meta box */
	function save_meta_box($post_id) {
		foreach ($this->meta_boxes as $meta_box) {
			if (isset($_POST[$meta_box['id'].'_nonce'])) {
				if (!wp_verify_nonce($_POST[$meta_box['id'].'_nonce'], basename(__FILE__))) {
					return $post_id;
				};
				if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
					return $post_id;
				};
				if ('page' == $_POST['post_type']) {
					if (!current_user_can('edit_page', $post_id)) {
						return $post_id;
					};
				} elseif (!current_user_can('edit_post', $post_id)) {
					return $post_id;
				};
				foreach ($meta_box['fields'] as $field) {
					$old = get_post_meta($post_id, $field['id'], true);
					$new = $_POST[$field['id']];
					if ($new && $new != $old) {
						update_post_meta($post_id, $field['id'], $new);
					} elseif ($new == '' && $old) {
						delete_post_meta($post_id, $field['id'], $old);
					};
				};
			};
		};
	}
}
