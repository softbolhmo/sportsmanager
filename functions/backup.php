<?php

function sm_backup($file, $host, $user, $pass, $name, $tables = '*') {
	$link = mysql_connect($host, $user, $pass);
	mysql_select_db($name, $link);
	if ($tables == '*') {
		$tables = array ();
		$result = mysql_query('SHOW TABLES');
		while ($row = mysql_fetch_row($result)) {
			$tables[] = $row[0];
		};
	} else {
		$tables = is_array($tables) ? $tables : explode(',', $tables);
	};
	$query = '';
	foreach ($tables as $table) {
		$result = mysql_query('SELECT * FROM '.$table);
		$num_fields = mysql_num_fields($result);
		$query .= 'DROP TABLE '.$table.';';
		$row2 = mysql_fetch_row(mysql_query('SHOW CREATE TABLE '.$table));
		$query .= "\n\n".$row2[1].";\n\n";
		for ($i = 0; $i < $num_fields; $i++) {
			while ($row = mysql_fetch_row($result)) {
				$query.= 'INSERT INTO '.$table.' VALUES(';
				for ($j = 0; $j < $num_fields; $j++) {
					$row[$j] = addslashes($row[$j]);
					$row[$j] = ereg_replace("\n", "\\n", $row[$j]);
					if (isset($row[$j])) {
						$query.= '"'.$row[$j].'"' ;
					} else {
						$query.= '""';
					};
					if ($j < ($num_fields-1) ) $query.= ',';
				};
				$query.= ");\n";
			};
		};
		$query.="\n\n";
	};
	$handle = fopen($file, 'w+');
	if ($handle) {
		fwrite($handle, $query);
		fclose($handle);
		return true;
	} else {
		return false;
	};
}
