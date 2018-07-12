<?php

namespace App\Models\Sources;

use App\Lib\Functions;
use App\Models\Comment;
use App\Models\Post;
use App\Models\SourceInterface;
use Illuminate\Database\Eloquent\Model;

class Reddit extends Model {

	protected $sourceType = 'reddit';
	protected $sourceKey; // subreddit
	protected $obsessionId;
	protected $maxNumberDbPostsToFetch = 100;
	protected $urlBase = 'https://www.reddit.com';

	public function addNewContent($sourceKey, $obsessionId) {

		$this->initialize($sourceKey, $obsessionId);
		$this->addNewPosts();
		$this->addNewComments();
	}

	public function initialize($sourceKey, $obsessionId) {

		$this->sourceKey = $sourceKey;
		$this->obsessionId = $obsessionId;
	}

	/**=====================================
	 * POSTS
	 *=====================================**/

	public function addNewPosts() {

		// Get the latest posts
		$postsReddit = $this->getLatestPosts();

		// Get the saved posts
		$postsDb = $this->getSavedPostsDb();

		// Filter just the new ones
		$posts = $this->getPostsNotOnDb($postsReddit, $postsDb);

		// With the new ones, we parse them
		$posts = $this->parsePostsToDb($posts);

		// Once parsed, we save them
		$result = $this->savePostsToDb($posts);

	}

	public function getLatestPosts() {

		$url = $this->urlBase . '/r/' . $this->sourceKey . '/new.json?sort=new';
		$json = file_get_contents($url);
		$postsObject = json_decode($json, true);

		$posts = $postsObject['data']['children'];

		return $posts;
	}

	public function getSavedPostsDb() {

		$posts = Post::where('source_type', 'reddit')
			->where('source_key', $this->sourceKey)
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

	public function parsePostsToDb($posts) {

		$postsDb = array();

		foreach ($posts as $post) {

			$post = $post['data'];

			$postDb['external_id'] = $post['id'];
			$postDb['title'] = $post['title'];
			$postDb['text'] = trim($post['url'] . ' ' . $post['selftext']);
			$postDb['url'] = $this->urlBase . $post['permalink'];
			$postDb['thumbnail'] = $post['thumbnail'];

			if (isset($post['preview']['images'][0]['source']['url'])) {
				$postDb['image'] = $post['preview']['images'][0]['source']['url'];
			}

			$postDb['author'] = $post['author'];
			$postDb['rating'] = $post['score'];
			$postDb['source_type'] = $this->sourceType;
			$postDb['source_key'] = $this->sourceKey;
			$postDb['obsession_id'] = $this->obsessionId;
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

	public function addNewComments() {

		// Get the latest comments
		$commentsReddit = $this->getLatestComments();

		// Get the saved comments
		$commentsDb = $this->getSavedCommentsDb();

		// Filter just the new ones
		$comments = $this->getCommentsNotOnDb($commentsReddit, $commentsDb);

		// With the new ones, we parse them
		$comments = $this->parseCommentsToDb($comments);

		// Once parsed, we save them
		$result = $this->saveCommentsToDb($comments);
	}

	public function getLatestComments() {

		$url = $this->urlBase . '/r/' . $this->sourceKey . '/comments.json?sort=new';
		$json = file_get_contents($url);
		$commentsObject = json_decode($json, true);

		$comments = $commentsObject['data']['children'];

		return $comments;
	}

	public function getSavedCommentsDb() {

		$comments = Comment::where('source_type', 'reddit')
			->where('source_key', $this->sourceKey)
			->skip(0)->take($this->maxNumberDbPostsToFetch)
			->get()->toArray();

		return $comments;

	}

	public function getCommentsNotOnDb($commentsReddit, $commentsDb) {

		$comments = [];

		$idCommentsDb = Functions::getArrayValuesFromArrayIndex($commentsDb, 'external_id');

		foreach ($commentsReddit as $commentReddit) {

			$externalId = $commentReddit['data']['id'];
			if (!in_array($externalId, $idCommentsDb)) {
				$comments[] = $commentReddit;
			}

		}

		return $comments;

	}

	public function parseCommentsToDb($comments) {

		$commentsDb = array();

		foreach ($comments as $comment) {

			$comment = $comment['data'];

			$commentDb['external_id'] = $comment['id'];
			$commentDb['reply_to_post_id'] = $this->parseReplyToPostFromLinkId($comment['link_id']);
			$commentDb['reply_to_comment_id'] = $this->parseReplyToCommentRedditFromParentId($comment['parent_id']);

			$commentDb['text'] = $comment['body'];
			$commentDb['url'] = $this->urlBase . $comment['permalink'];

			$commentDb['author'] = $comment['author'];
			$commentDb['rating'] = $comment['score'];
			$commentDb['source_type'] = $this->sourceType;
			$commentDb['source_key'] = $this->sourceKey;
			$commentDb['obsession_id'] = $this->obsessionId;
			$commentDb['created_at'] = date("Y-m-d H:i:s", $comment["created_utc"]);

			$commentsDb[] = $commentDb;
		}

		return $commentsDb;
	}

	public function parseReplyToPostFromLinkId($commentLinkId) {

		$replyToPostId = str_replace('t3_', '', $commentLinkId);
		return $replyToPostId;
	}

	public function parseReplyToCommentRedditFromParentId($commentParentId) {

		$replyToCommentId = null;
		if (strpos($commentParentId, 't3_') !== false) { // If parent ID is the root post
			return $replyToCommentId;
		} else {
			return str_replace('t1_', '', $commentParentId);
		}

	}

	public function saveCommentsToDb($comments) {

		$result = Comment::insert($comments);
		return $result;
	}
}