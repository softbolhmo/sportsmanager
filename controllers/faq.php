<?php

$questions = $this->query_faq();
$faq = array (
	(object) array (
		'slug' => 'get_started',
		'name' => 'Get started',
		'questions' => isset($questions['get_started']) ? $questions['get_started'] : array ()
	),
	(object) array (
		'slug' => 'backend',
		'name' => 'Backend',
		'questions' => isset($questions['backend']) ? $questions['backend'] : array ()
	),
	(object) array (
		'slug' => 'frontend',
		'name' => 'Frontend',
		'questions' => isset($questions['frontend']) ? $questions['frontend'] : array ()
	)
);
