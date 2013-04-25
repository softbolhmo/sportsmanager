<?php

function sm_where_string($wheres) {
	if (!empty($wheres)) {
		$q = 'WHERE ';
		foreach ($wheres as $where) {
			$q .= $where.' AND ';
		};
		$q = rtrim($q, 'AND ').' ';
		return $q;
	};
}

function sm_group_array_objects_by($key, $objects) {
	$array = array ();
	foreach ($objects as $i => $object) {
		if (isset($object->$key)) $array[$object->$key][] = $object;
	};
	return $array;
}

function sm_order_array_objects_by($keys, $objects, $reverse = false) {
	if ($keys == '') return $objects;
	if (!is_array($keys)) $keys = (array) $keys;
	foreach (array_reverse($keys) as $key) {
		$order = array ();
		foreach ($objects as $i => $object) {
			$order[$i] = isset($object->$key) ? $object->$key : '';
		};
		asort($order);
		$in_order = array ();
		foreach ($order as $k => $v) {
			$in_order[] = $objects[$k];
		};
		if ($reverse) $in_order = array_reverse($in_order);
		$objects = $in_order;
	};
	return $objects;
}

function sm_search_array_objects_for($key, $value, $objects, $property = '') {
	$found = false;
	foreach ($objects as $i => $object) {
		if (isset($object->$key) && $object->$key == $value) {
			$found = $object;
			break;
		}
	};
	$found = isset($found->$property) ? $found->$property : $found;
	return $found;
}
