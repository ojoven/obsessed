<?php

namespace App\Models\Sources;

use App\Lib\Functions;
use App\Lib\SimpleHtmlDom;
use App\Models\Comment;
use App\Models\Post;
use App\Models\SourceInterface;

class Scraper {

	protected $pathToConfigFiles = '/Models/Sources/scrapers/';
	protected $pathToParserFiles = '/Models/Sources/parsers/';

	protected $config;
	protected $page;
	protected $sourceKey;
	protected $sourceType = 'scraper';
	protected $obsessionId;

	protected $maxNumberDbPostsToFetch = 100;

	public function addNewContent($sourceKey, $obsessionId) {

		$this->initialize($sourceKey, $obsessionId);
		$this->addNewPosts();
		$this->addNewComments();

	}

	public function initialize($sourceKey) {

		$this->sourceKey = $sourceKey;
		$this->obsessionId = $obsessionId;

		// Load configuration file
		$pathToConfigFile = app_path() . $this->pathToConfigFiles . $this->sourceKey . '.php';
		if (!file_exists($pathToConfigFile)) return false;
		$this->config = include($pathToConfigFile);

		// Load parser file (if it exists)
		$pathToParserFile = app_path() . $this->pathToParserFiles . $this->sourceKey . '.php';
		if (file_exists($pathToParserFile)) {
			require_once $pathToParserFile;
		}

	}

	/**=====================================
	 * POSTS
	 *=====================================**/

	public function addNewPosts() {

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
			$posts = $this->handleInfoAndCommentsOnSinglePages($posts);

			// Save to DB
			$result = $this->savePostsToDb($posts);

			$this->page++;
			$loop = false;

		}

	}

	/** GET INFO LIST **/
	public function getLatestPostsList() {

		$posts = [];

		$url = $this->getUrl($this->config['parameters']['url'], $this->page);
		$html = $this->getHtmlDom($url);

		if (!$html) return $posts;

		foreach ($html->find($this->config['parameters']['postInList']) as $postDom) {

			$post = $this->addFieldsInfoToPost($postDom, 'pathList');

			$post['source_type'] = $this->sourceType;
			$post['source_key'] = $this->sourceKey;
			$post['obsession_id'] = $this->obsessionId;

			$posts[] = $post;
		}

		return $posts;
	}

	/** GET INFO SINGLE PAGE **/
	public function handleInfoAndCommentsOnSinglePages($posts) {

		$posts = array_slice($posts, 0, 5);

		foreach ($posts as &$post) {

			$htmlDom = SimpleHtmlDom::fileGetHtml($post['url']);

			// Get additional info
			$post = $this->addFieldsInfoToPost($htmlDom, 'pathSingle', $post);

			// Add new comments
			if ($post['num_comments'] !== 0) {
				$this->addNewCommentsPost($post, $htmlDom);
			}
		}

		return $posts;
	}

	public function addFieldsInfoToPost($dom, $path, &$post = false, $arrayFields = false) {

		if (!$post) $post = [];

		foreach ($this->config['fieldsPost'] as $fieldName => $fieldAttributes) {

			if ($arrayFields && !in_array($fieldName, $arrayFields)) continue; // We can get just a subarray of fields

			if ($fieldAttributes['available'] && isset($fieldAttributes[$path])) {

				$position = isset($fieldAttributes['position']) ? $fieldAttributes['position'] : 0;
				$post[$fieldName] = SimpleHtmlDom::find($dom, $fieldAttributes[$path], $position, $fieldAttributes['attribute']);
				if ($post[$fieldName]) $post[$fieldName] = trim($post[$fieldName]);

				if (isset($fieldAttributes['parse']) && $fieldAttributes['parse']) {

					// Call to parser function
					$functionName = $this->sourceKey . '_post_' . $fieldName;
					if (function_exists($functionName)) {
						$post[$fieldName] = $functionName($post[$fieldName]);
					}
				}

			}
		}

		return $post;
	}

	public function getHtmlDom($url) {

		$html = file_get_contents($url);

		if (isset($this->config['parameters']['parseHTML'])
			&& $this->config['parameters']['parseHTML']) {

			$functionName = $this->sourceKey . '_post_html';
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

		$posts = Post::where('source_type', $this->sourceType)
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

	/**=====================================
	 * COMMENTS
	 *=====================================**/

	public function addNewComments() {

		$posts = $this->getPostsForWhichWeWillGetComments();

		foreach ($posts as $post) {

			$this->addNewCommentsPost($post);
		}

		return $posts;

	}

	public function getPostsForWhichWeWillGetComments() {

		$postsScraping = $this->getPostsToBeCheckedFromScraping();
		$postsDb = $this->getPostsToBeCheckedFromDB($postsScraping);
		$postsWithNewComments = $this->getPostsWithNewComments($postsScraping, $postsDb);

		return $postsWithNewComments;
	}

	public function getPostsToBeCheckedFromScraping() {

		$posts = [];
		$externalIds = [];

		$urls = $this->config['parameters']['urlsComments'];
		foreach ($urls as $url) {

			$html = $this->getHtmlDom($url);
			if (!$html) return $posts;

			foreach ($html->find($this->config['parameters']['postInList']) as $postDom) {

				$post = [];
				$fields = ['external_id', 'num_comments'];
				$post = $this->addFieldsInfoToPost($postDom, 'pathList', $post, $fields);

				if (!in_array($post['external_id'], $externalIds)) { // Avoid duplicates
					$externalIds[] = $post['external_id'];
					$posts[] = $post;
				}
			}

		}

		return $posts;
	}

	public function getPostsToBeCheckedFromDB($postsScraping) {

		$idPostsDb = Functions::getArrayValuesFromArrayIndex($postsScraping, 'external_id');
		$postsToBeChecked = Post::whereIn('external_id', $idPostsDb)->get()->toArray();
		return $postsToBeChecked;
	}

	public function getPostsWithNewComments($postsScraping, $postsDb) {

		$postsWithNewComments = [];
		foreach ($postsDb as $postDb) {

			foreach ($postsScraping as $postScraping) {

				if ($postDb['external_id'] === $postScraping['external_id']) {

					if ($postDb['num_comments'] !== $postScraping['num_comments']) {
						$postDb['num_comments_new'] = $postScraping['num_comments'];
						$postsWithNewComments[] = $postDb;
					}

				}
			}

		}

		return $postsWithNewComments;
	}

	public function addNewCommentsPost($post, $htmlDom = false) {

		$commentsPost = $this->getNewCommentsPost($post, $htmlDom);
		if (!$commentsPost) return;

		$commentsPostDB = $this->getCommentsDB($post);
		$comments = $this->getNewCommentsNotOnDB($commentsPost, $commentsPostDB);

		if (!$comments) return;
		$response = $this->saveCommentsToDB($comments, $post);
	}

	public function getNewCommentsPost($post, $html = false) {

		if (!$html) $html = $this->getHtmlDom($post['url']);
		if (!$html) return false;

		$comments = [];

		foreach ($html->find($this->config['parameters']['commentInList']) as $commentDom) {
			$comment = $this->addFieldsInfoToComment($commentDom);

			$comment['reply_to_post_id'] = $post['external_id'];
			// comment['reply_to_comment_id'] = TODO THIS.
			$comment['source_type'] = $this->sourceType;
			$comment['source_key'] = $this->sourceKey;
			$comment['obsession_id'] = $this->obsessionId;

			$comments[] = $comment;
		}

		return $comments;
	}

	public function addFieldsInfoToComment($dom) {

		$comment = [];

		foreach ($this->config['fieldsComment'] as $fieldName => $fieldAttributes) {

			if ($fieldAttributes['available']) {

				$position = isset($fieldAttributes['position']) ? $fieldAttributes['position'] : 0;
				$comment[$fieldName] = SimpleHtmlDom::find($dom, $fieldAttributes['path'], $position, $fieldAttributes['attribute']);
				if (isset($comment[$fieldName])) $comment[$fieldName] = trim($comment[$fieldName]);

				if (isset($fieldAttributes['parse']) && $fieldAttributes['parse']) {

					// Call to parser function
					$functionName = $this->sourceKey . '_comment_' . $fieldName;
					if (function_exists($functionName)) {
						$comment[$fieldName] = $functionName($comment[$fieldName]);
					}
				}

			}
		}

		return $comment;
	}

	public function getCommentsDB($post) {

		$comments = Comment::where('reply_to_post_id', $post['external_id'])->get()->toArray();
		return $comments;

	}

	public function getNewCommentsNotOnDB($commentsScraper, $commentsPostDB) {

		$comments = [];
		$idCommentsDb = Functions::getArrayValuesFromArrayIndex($commentsPostDB, 'external_id');

		foreach ($commentsScraper as $commentScraper) {

			$externalId = $commentScraper['external_id'];
			if (!in_array($externalId, $idCommentsDb)) {
				$comments[] = $commentScraper;
			}

		}

		return $comments;
	}

	public function saveCommentsToDB($comments, $post) {

		// Save comments in its table
		$result = Comment::insert($comments);
		if (!$result) return false;

		// Save num_comments in post table
		$result = Post::where('external_id', $post['external_id'])
			->where('source_type', $this->sourceType)
			->where('source_key', $this->sourceKey)
			->update(array('num_comments' => $post['num_comments']));

		return $result;
	}

}