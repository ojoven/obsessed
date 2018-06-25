<?php

namespace App\Http\Controllers;

use App\Lib\DateFunctions;
use App\Lib\Functions;
use App\Lib\SimpleHtmlDom;
use App\Models\Category;
use App\Models\Event;
use App\Models\Place;
use App\Models\Scraper;
use App\Models\Twitter;
use Illuminate\Http\Request;
use App\Http\Requests;

// Models

class IndexController extends Controller {

	public function index() {

		$data = [];
		return view('index', $data);
	}

	public function playground() {

		// Literautas
		$urlBase = 'https://www.literautas.com/es/blog/page/';
		$elementInList = '#contenidoPosts > article';
		$titleInElement = '.enlaceNaranja';
		$excerptInElement = '> .contenidoPost .entryContent > p';
		$imgInElement = '> .contenidoPost .entryContent .imgContenidoPost';

		$page = 132;

		$loop = true;
		while ($loop) {

			$url = $urlBase . $page;
			$html = SimpleHtmlDom::fileGetHtml($url);

			foreach ($html->find($elementInList) as $element) {

				$title = $element->find($titleInElement, 0);
				$excerpt = $element->find($excerptInElement, 0);
				$image = $element->find($imgInElement, 0);

			}

			$page++;
			$loop = false;

		}

		$data = [];
		return view('index', $data);
	}

}
