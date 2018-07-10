<?php
namespace App\Http\Controllers;

use App\Models\Obsession;
use Reddit\Api\Client;

// Models

class IndexController extends Controller {

	/** NOT LOGGED IN **/
	public function index() {

		$data = [];
		return view('index', $data);
	}

	public function update() {

		$obsessionModel = new Obsession();
		$obsessionModel->addDataObsessions();

	}

	/** LOGGED IN **/
	public function timeline() {

		$data = [];
		return view('timeline', $data);
	}

	public function notifications() {

		$data = [];
		return view('notifications', $data);
	}

	public function profile() {

		$data = [];
		return view('profile', $data);
	}

}
