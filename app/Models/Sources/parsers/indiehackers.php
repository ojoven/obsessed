<?php

/**=====================================
 * POSTS
 *=====================================**/

// HTML
function indiehackers_post_html($value) {

	$value = rtrim($value);
	$value = substr($value, 0, strrpos($value, "\n"));
	return $value;
}

// FIELDS
function indiehackers_post_external_id($value) {

	$valueAux = explode('-', $value);
	$value = end($valueAux);
	return $value;
}

function indiehackers_post_created_at($value) {

	$value = str_replace('(', '', str_replace(')', '', $value));
	$value = date('Y-m-d H:i:s', strtotime($value));
	return $value;
}

function indiehackers_post_url($value) {

	if (strpos($value, 'indiehackers.com') === false) {
		$urlBase = 'https://www.indiehackers.com';
		$value = $urlBase . $value;
	}

	return $value;
}

function indiehackers_post_num_comments($value) {

	$value = (int) str_replace('comments', '', str_replace('comment', '', trim($value)));
	return $value;
}

/**=====================================
 * COMMENTS
 *=====================================**/

function indiehackers_comment_created_at($value) {

	$value = str_replace('(', '', str_replace(')', '', $value));
	$value = date('Y-m-d H:i:s', strtotime($value));
	return $value;
}

function indiehackers_comment_url($value) {

	if (strpos($value, 'indiehackers.com') === false) {
		$urlBase = 'https://www.indiehackers.com';
		$value = $urlBase . $value;
	}

	return $value;
}

function indiehackers_comment_external_id($value) {

	$valueAux = explode('-', $value);
	$value = end($valueAux);
	return $value;
}