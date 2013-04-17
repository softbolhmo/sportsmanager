<?php
/**
 * Backend role class
 * @package SportsManager
 * @subpackage classes
 */

/**
 * Class for backend role object
 * @package SportsManager
 */
class SportsManager_Role extends SportsManager_Backend_Default {
	function __construct($data) {
		parent::__construct($data, 'role');
	}

	function add_role() {
		if (isset($this->slug, $this->name, $this->capabilities) && $this->slug != '' && $this->name != '') {
			add_role($this->slug, $this->name, $this->capabilities);
		};
	}

	function add_capability($capability) {
		$role = get_role($this->slug);
		if (is_array($capability)) {
			foreach ($capability as $k) {
				$role->add_cap($k);
			};
		} else {
			$role->add_cap($capability);
		};
	}

	function remove_capability($capability) {
		$role = get_role($this->slug);
		if (is_array($capability)) {
			foreach ($capability as $k) {
				$role->remove_cap($k);
			};
		} else {
			$role->remove_cap($capability);
		};
	}
}
