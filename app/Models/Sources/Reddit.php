<?php

namespace App\Models\Sources;

use App\Lib\Functions;
use App\Models\Post;
use App\Models\SourceInterface;
use Illuminate\Database\Eloquent\Model;

class Reddit extends Model implements SourceInterface {

	protected $sourceType = 'reddit';
	protected $maxNumberDbPostsToFetch = 100;
	protected $urlBase = 'https://www.reddit.com/';

	public function addNewContent($subreddit) {

		$this->addNewPosts($subreddit);
		$this->addNewComments($subreddit);
	}

	/**=====================================
	 * POSTS
	 *=====================================**/

	public function addNewPosts($subreddit) {

		// Get the latest posts
		$postsReddit = $this->getLatestPosts($subreddit);

		// Get the saved posts
		$postsDb = $this->getSavedPostsDb($subreddit);

		// Filter just the new ones
		$posts = $this->getPostsNotOnDb($postsReddit, $postsDb);

		// With the new ones, we parse them
		$posts = $this->parsePostsToDb($posts, $subreddit);

		// Once parsed, we save them
		$result = $this->savePostsToDb($posts);

	}

	public function getLatestPosts($subreddit) {

		$url = $this->urlBase . 'r/' . $subreddit . '/new.json?sort=new';
		$json = file_get_contents($url);
		$postsObject = json_decode($json, true);

		$posts = $postsObject['data']['children'];

		return $posts;
	}

	public function getSavedPostsDb($subreddit) {

		$posts = Post::where('source_type', 'reddit')
			->where('source_id', $subreddit)
			->skip(0)->take($this->maxNumberDbPostsToFetch)
			->get()->toArray();

		return $posts;

	}

	public function getPostsNotOnDb($postsReddit, $postsDb) {

		$posts = [];

		$idPostsDb = Functions::getArrayValuesFromArrayIndex($postsDb, 'external_id');

		foreach ($postsReddit as $postReddit) {

			$externalId = $postReddit['data']['id'];
			if (!in_array($externalId, $idPostsDb)) {
				$posts[] = $postReddit;
			}

		}

		return $posts;

	}

	public function parsePostsToDb($posts, $subreddit) {

		$postsDb = array();

		foreach ($posts as $post) {

			$post = $post['data'];

			$postDb['external_id'] = $post['id'];
			$postDb['title'] = $post['title'];
			$postDb['text'] = $post['selftext'] . ' ' . $post['url'];
			$postDb['url'] = $this->urlBase . $post['permalink'];
			$postDb['thumbnail'] = $post['thumbnail'];

			if (isset($post['preview']['images'][0]['source']['url'])) {
				$postDb['image'] = $post['preview']['images'][0]['source']['url'];
			}

			$postDb['rating'] = $post['score'];
			$postDb['source_type'] = $this->sourceType;
			$postDb['source_id'] = $subreddit;
			$postDb['created_at'] = date("Y-m-d H:i:s", $post["created_utc"]);

			$postsDb[] = $postDb;
		}

		return $postsDb;
	}

	public function savePostsToDb($posts) {

		$result = Post::insert($posts);
		return $result;
	}


	/**=====================================
	 * COMMENTS
	 *=====================================**/

	public function addNewComments($subreddit) {

		$url = $this->urlBase . 'r/' . $subreddit . '/comments.json?sort=new';
		//$json = file_get_contents($url);
		//$comments = json_decode($json, true);
	}

	private function parseComments($posts) {

	}
}