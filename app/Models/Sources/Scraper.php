<?php

namespace App\Models\Sources;

use App\Lib\Functions;
use App\Lib\SimpleHtmlDom;
use App\Models\Post;
use App\Models\SourceInterface;

class Scraper implements SourceInterface {

	protected $pathToConfigFiles = '/Models/Sources/scrapers/';
	protected $pathToParserFiles = '/Models/Sources/parsers/';

	protected $config;
	protected $page;
	protected $sourceKey;

	protected $maxNumberDbPostsToFetch = 100;

	public function addNewContent($sourceKey) {
		
		$this->sourceKey = $sourceKey;
		$this->addNewPostsAndComments($sourceKey);

	}

	public function addNewPostsAndComments($sourceKey) {

		// Load configuration file
		$pathToConfigFile = app_path() . $this->pathToConfigFiles . $sourceKey . '.php';
		if (!file_exists($pathToConfigFile)) return false;
		$this->config = include($pathToConfigFile);

		// Load parser file (if it exists)
		$pathToParserFile = app_path() . $this->pathToParserFiles . $sourceKey . '.php';
		if (file_exists($pathToParserFile)) {
			require_once $pathToParserFile;
		}

		$this->page = 1;

		$loop = true;
		while ($loop) {

			// Get the latest posts
			$postsScraping = $this->getLatestPostsList();

			// Get the saved posts
			$postsDb = $this->getSavedPostsDb();

			// Filter just the new ones
			$posts = $this->getPostsNotOnDb($postsScraping, $postsDb);

			// Get additional information for each new post
			$posts = $this->getAdditionalInfoPostsOnSinglePages($posts);

			// Save to DB
			$result = $this->savePostsToDb($posts);

			$this->page++;
			$loop = false;

		}

	}

	/** GET LATEST POSTS LIST **/
	public function getLatestPostsList() {

		$posts = [];

		$html = $this->getHtmlDom($this->config);
		if (!$html) return $posts;

		foreach ($html->find($this->config['parameters']['postInList']) as $postDom) {

			$post = $this->addFieldsInfoToPost($postDom, 'pathList');

			$post['source_type'] = 'scraper';
			$post['source_key'] = $this->sourceKey;

			$posts[] = $post;
		}

		return $posts;
	}

	public function getAdditionalInfoPostsOnSinglePages($posts) {

		$posts = array_slice($posts, 0, 5);

		foreach ($posts as &$post) {

			$htmlDom = SimpleHtmlDom::fileGetHtml($post['url']);
			$post = $this->addFieldsInfoToPost($htmlDom, 'pathSingle', $post);
		}

		return $posts;
	}

	public function addFieldsInfoToPost($dom, $path, &$post = false) {

		if (!$post) $post = [];

		foreach ($this->config['fieldsPost'] as $fieldName => $fieldAttributes) {

			if ($fieldAttributes['available'] && isset($fieldAttributes[$path])) {

				$position = isset($fieldAttributes['position']) ? $fieldAttributes['position'] : 0;
				$post[$fieldName] = SimpleHtmlDom::find($dom, $fieldAttributes[$path], $position, $fieldAttributes['attribute']);
				if (isset($fieldAttributes['parse']) && $fieldAttributes['parse']) {

					// Call to parser function
					$functionName = $this->sourceKey . '_' . $fieldName;
					if (function_exists($functionName)) {
						$post[$fieldName] = $functionName($post[$fieldName]);
					}
				}

			}
		}

		return $post;
	}

	public function getHtmlDom($config) {

		$url = $this->getUrl($config['parameters']['url'], $this->page);
		$html = file_get_contents($url);

		if (isset($config['parameters']['parseHTML'])
			&& $config['parameters']['parseHTML']) {

			$functionName = $this->sourceKey . '_html';
			if (function_exists($functionName)) {
				$html = $functionName($html);
			}

		}

		$htmlDom = SimpleHtmlDom::strGetHtml($html);

		return $htmlDom;
	}

	public function getUrl($url, $page) {

		$url = str_replace('[page]', $page, $url);
		return $url;
	}

	public function getSavedPostsDb() {

		$posts = Post::where('source_type', 'scraper')
			->where('source_key', $this->sourceKey)
			->skip(0)->take($this->maxNumberDbPostsToFetch)
			->get()->toArray();

		return $posts;
	}

	public function getPostsNotOnDb($postsScraper, $postsDb) {

		$posts = [];

		$idPostsDb = Functions::getArrayValuesFromArrayIndex($postsDb, 'external_id');

		foreach ($postsScraper as $postScraper) {

			$externalId = $postScraper['external_id'];
			if (!in_array($externalId, $idPostsDb)) {
				$posts[] = $postScraper;
			}

		}

		return $posts;
	}

	public function savePostsToDb($posts) {

		$result = Post::insert($posts);
		return $result;
	}

}