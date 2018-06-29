<?php

namespace App\Models\Sources;

use App\Models\Post;
use App\Models\Source;
use Illuminate\Database\Eloquent\Model;

class Reddit extends Model implements Source {

	protected $maxNumberDbPostsToFetch = 100;
	protected $urlBase = 'https://www.reddit.com/r/';

	/**=====================================
	 * POSTS
	 *=====================================**/

	public function addNewPosts($subreddit) {

		// Get the latest posts
		$postsReddit = $this->getLatestPosts($subreddit);

		// Get the saved posts
		$postsDb = $this->getSavedPostsDb($subreddit);

		// Filter just the new ones
		$posts = $this->intersectPosts($postsReddit, $postsDb);

		// With the new ones, we parse them
		$posts = $this->parsePostsToDb($posts);

		// Once parsed, we save them
		$result = $this->savePostsToDb($posts);

	}

	public function getLatestPosts($subreddit) {

		$url = $this->urlBase . $subreddit . '/new.json?sort=new';
		$json = file_get_contents($url);
		$posts = json_decode($json, true);

		return $posts;
	}

	public function getSavedPostsDb($subreddit) {

		$posts = Post::where('source_type', 'reddit')
			->where('source_id', $subreddit)
			->skip(0)->take($this->maxNumberDbPostsToFetch)
			->get()->toArray();

		return $posts;

	}

	public function intersectPosts($postsReddit, $postsDb) {

		$posts = array();
		return $posts;

	}

	public function parsePostsToDb($posts) {

		return $posts;
	}

	public function savePostsToDb($posts) {

		return true;
	}


	/**=====================================
	 * COMMENTS
	 *=====================================**/

	public function addNewComments($subreddit) {

		$url = $this->urlBase . $subreddit . '/comments.json?sort=new';
		$json = file_get_contents($url);
		$comments = json_decode($json, true);
	}

	private function parsePosts($posts) {

	}

	private function parseComments($posts) {

	}
}