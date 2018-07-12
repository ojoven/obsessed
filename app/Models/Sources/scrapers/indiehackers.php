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

		// Comment in List
		'commentInList' => '.comment',

		// URLs for comments
		'urlsComments' => array(
			'https://www.indiehackers.com/forum/newest/page/1', // Latest
			'https://www.indiehackers.com/' // Top
		),

		// Update fields
		'updateFields' => array(
			'rating'
		)
	),

	// Config Options
	'fieldsPost' => array(

		'external_id' => array(
			'available' => true,
			'pathList' => '.thread__details .thread__reply-count',
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

		'author' => array(
			'available' => true,
			'pathList' => '.user-link__username',
			'attribute' => 'plaintext'
		),

		'author_image'  => array(
			'available' => true,
			'pathList' => '.user-avatar__img',
			'attribute' => 'src'
		),

		'created_at' => array(
			'available' => true,
			'pathList' => '.thread__date',
			'attribute' => 'title',
			'parse' => true
		),

		'url' => array(
			'available' => true,
			'pathList' => '.thread__details .thread__reply-count',
			'attribute' => 'href',
			'parse' => true
		),

		'text' => array(
			'available' => true,
			'pathSingle' => '.thread__content',
			'attribute' => 'innertext',
		),

		'num_comments' => array(
			'available' => true,
			'pathList' => '.thread__reply-count',
			'attribute' => 'plaintext',
			'parse' => true
		)
	),

	'fieldsComment' => array(

		'external_id' => array(
			'available' => true,
			'path' => '.footer__date',
			'attribute' => 'href',
			'parse' => true
		),

		// We already have the information from the post from which we're retrieving the comments
		'reply_to_post_id' => array(
			'available' => false,
		),

		'reply_to_comment_id' => array(
			'available' => false,
		),

		'text' => array(
			'available' => true,
			'path' => '.comment__content',
			'attribute' => 'innertext'
		),

		'url' => array(
			'available' => true,
			'path' => '.footer__date',
			'attribute' => 'href',
			'parse' => true
		),

		'rating' => array(
			'available' => true,
			'path' => '.comment-voter__score',
			'attribute' => 'plaintext'
		),

		'author' => array(
			'available' => true,
			'path' => '.user-link__username',
			'attribute' => 'plaintext'
		),

		'author_image' => array(
			'available' => true,
			'path' => '.user-avatar__img',
			'attribute' => 'plaintext'
		),

		'created_at' => array(
			'available' => true,
			'path' => '.footer__date',
			'attribute' => 'title',
			'parse' => true
		),
	)

);