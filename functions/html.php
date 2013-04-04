<?php

function sm_autocomplete_labels($data = '') {
	if ($data != '') {
		$items = array ();
		foreach ($data as $object) {
			$items[] = (object) array (
				'label' => $object->name.(isset($object->season) ? ' '.$object->season : '').' ('.$object->id.')',
				'value' => $object->id
			);
		};
		return json_encode($items);
	} else {
		return '[]';
	};
}
