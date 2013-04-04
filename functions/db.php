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
