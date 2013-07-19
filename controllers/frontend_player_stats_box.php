<?php

$tabs = $this->args->seasons_array;
if (!is_array($tabs) && is_string($tabs)) $tabs = (array) $tabs;
$league_id = $this->args->league_id;
$sport = $this->args->sport;
$player_id = $this->args->player_id;
