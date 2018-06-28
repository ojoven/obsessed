<?php
namespace App\Http\Controllers;

use Reddit\Api\Client;

// Models

class IndexController extends Controller {

	/** NOT LOGGED IN **/
	public function index() {

		$data = [];
		return view('index', $data);
	}

	public function update() {

		// Get obsessions

		// For each obsession

		// Get sources

		// For each source, check if new posts

		// If new posts, add them to database

	}

}
