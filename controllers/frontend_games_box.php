<?php

$filter = $this->args->display;
$rows = sm_order_array_objects_by('time', $this->rows);
foreach ($this->rows as $i => $row) {
	if (isset($row->time) && $row->time >= time()) {
		$next_game = $i;
		break;
	};
};
