<?php
namespace App\Http\Controllers;

use Reddit\Api\Client;
use App\Lib\DateFunctions;
use App\Lib\Functions;
use App\Lib\SimpleHtmlDom;
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
		$linkToSinglePost = '.enlaceNaranja';

		$contentInSinglePost = '#contenidoSinglePost .entry-content';
		$datePublished = '#contenidoSinglePost .spanFooterPost time';

		$page = 1;

		$loop = true;
		while ($loop) {

			$url = $urlBase . $page;
			$html = SimpleHtmlDom::fileGetHtml($url);

			foreach ($html->find($elementInList) as $element) {

				$title = SimpleHtmlDom::find($element, $titleInElement, 0, 'plaintext');
				$excerpt = SimpleHtmlDom::find($element, $excerptInElement, 0, 'plaintext');
				$image = SimpleHtmlDom::find($element, $imgInElement, 0, 'src');
				$link = SimpleHtmlDom::find($element, $linkToSinglePost, 0, 'href');

				// Single post
				$htmlSingle = SimpleHtmlDom::fileGetHtml($link);
				$content = SimpleHtmlDom::find($htmlSingle, $contentInSinglePost, 0, 'innertext');
				$date = SimpleHtmlDom::find($htmlSingle, $datePublished, 0, 'datetime');

			}

			$page++;
			$loop = false;

		}

		$data = [];
		return view('index', $data);
	}

	public function reddit() {

		$jsonPosts = 'https://www.reddit.com/r/SideProject/new.json?sort=new';
		$jsonComments = 'https://www.reddit.com/r/SideProject/comments.json?sort=new';

	}

}
