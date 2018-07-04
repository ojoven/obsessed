<?php

return array(

	// Configuration
	'parameters' => array(

		// URL
		'url' => 'https://www.indiehackers.com/forum/newest/page/[page]',

		// Parse?
		'parseHTML' => true,

		// Post in List
		'postInList' => '.forum-list__thread-list > .forum-list__thread',
	),

	// Config Options
	'fieldsPost' => array(

		'external_id' => array(
			'available' => true,
			'pathList' => '.thread__details > a',
			'attribute' => 'href',
			'parse' => true
		),

		'title' => array(
			'available' => true,
			'pathList' => '.thread__details > a',
			'attribute' => 'plaintext'
		),

		'thumbnail' => array(
			'available' => false,
		),

		'image' => array(
			'available' => false,
		),

		'rating' => array(
			'available' => true,
			'pathList' => '.thread-voter__count',
			'attribute' => 'plaintext'
		),

		'date' => array(
			'available' => true,
			'pathList' => '.thread__date',
			'attribute' => 'title',
			'parse' => true
		),

		'link' => array(
			'available' => true,
			'pathList' => '.thread__details > a',
			'attribute' => 'href',
			'parse' => true
		),

		'text' => array(
			'available' => true,
			'pathSingle' => '.thread__content',
			'attribute' => 'innertext',
		),
	)

);