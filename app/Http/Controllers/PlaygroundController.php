<?php
namespace App\Http\Controllers;

use App\Models\Sources\YouTube;
use Reddit\Api\Client;
use App\Lib\DateFunctions;
use App\Lib\Functions;
use App\Lib\SimpleHtmlDom;
use Illuminate\Http\Request;
use App\Http\Requests;

// Models

class PlaygroundController extends Controller {

	public function playground() {

		/**
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

		$html = file_get_contents('https://medium.com/topic/entrepreneurship');
		$htmlDom = SimpleHtmlDom::strGetHtml($html);
		foreach ($htmlDom->find('.u-borderBox') as $post) {
			$title = SimpleHtmlDom::find($post, '.ui-h3', 0, 'plaintext');
			echo $title . '<br>';
		}
		 * 		 * **/

		//$result = file_put_contents(app_path() . '/tmp/indiehackers.html', $html);

		$youTubeModel = new YouTube();
		$youTubeModel->initialize('channel');
		$postsWithComments = $youTubeModel->getPostsForWhichWeWillGetComments('UCSw5U1MuzDED2Nvlx9m65-Q');

		$data = [];
		return view('index', $data);
	}

	public function reddit() {

		$jsonPosts = 'https://www.reddit.com/r/SideProject/new.json?sort=new';
		$jsonComments = 'https://www.reddit.com/r/SideProject/comments.json?sort=new';

	}

	public function play1() {


	}

}
