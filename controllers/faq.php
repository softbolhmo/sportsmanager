<?php

$faq = array (
	(object) array (
		'slug' => 'get_started',
		'name' => 'Get started',
		'questions' => array (
			array (
				"How to display the intro pane?",
				"Click on the 'Show Intro' link in the footer."
			),
			array (
				"Why should I donate?",
				"Because a full-featured plugin like this takes <b>a lot</b> of time to build."
			)
		)
	),
	(object) array (
		'slug' => 'backend',
		'name' => 'Backend',
		'questions' => array (
			array (
				"Why can't I edit the ID cell?",
				"Making sure rows don't share the same ID is very important. Remember that it doesn't matter if IDs don't follow each other, the ID will never be displayed in the frontend."
			),
			array (
				"Can I recover a row that was deleted?",
				"No. Not directly. You could alway use the importer to recover a previous backup made."
			)
		),
	),
	(object) array (
		'slug' => 'frontend',
		'name' => 'Frontend',
		'questions' => array (
			array (
				"Is the frontend ready yet?",
				"No, not yet. But it will be!"
			)
		)
	)
);
