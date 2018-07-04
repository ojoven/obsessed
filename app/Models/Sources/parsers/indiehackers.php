<?php

// HTML
function indiehackers_html($value) {

	$value = rtrim($value);
	$value = substr($value, 0, strrpos($value, "\n"));
	return $value;
}

// FIELDS
function indiehackers_external_id($value) {

	$valueAux = explode('-', $value);
	$value = end($valueAux);
	return $value;
}

function indiehackers_date($value) {

	$value = str_replace('(', '', str_replace(')', '', $value));
	$value = date('Y-m-d H:i:s', strtotime($value));
	return $value;
}

function indiehackers_link($value) {

	$urlBase = 'https://www.indiehackers.com';
	$value = $urlBase . $value;
	return $value;
}