<?php

namespace App\Models\Sources;

use App\Lib\Functions;
use App\Models\Comment;
use App\Models\Post;
use App\Models\SourceInterface;
use Illuminate\Database\Eloquent\Model;

// https://hn.algolia.com/api
// Rate limit: 10,000 requests / hour (same IP) 160 per minute

class HackerNews extends Model {

	protected $sourceType = 'hackernews';
	protected $maxNumberDbPostsToFetch = 100;
	protected $urlBase = 'https://news.ycombinator.com';
	protected $apiUrlBase = 'http://hn.algolia.com/api/v1/search_by_date?tags=story&query=';

	public function addNewContent($query) {

		$this->addNewPosts($query);
		//$this->addNewComments($query);
	}

	/**=====================================
	 * POSTS
	 *=====================================**/

	public function addNewPosts($query) {

		// Get the latest posts
		$postsHN = $this->getLatestPosts($query);

		// Get the saved posts
		$postsDb = $this->getSavedPostsDb($query);

		// Filter just the new ones
		$posts = $this->getPostsNotOnDb($postsHN, $postsDb);

		// With the new ones, we parse them
		$posts = $this->parsePostsToDb($posts, $query);

		// Once parsed, we save them
		$result = $this->savePostsToDb($posts);

	}

	public function getLatestPosts($query) {

		$url = $this->apiUrlBase . $query;
		$json = file_get_contents($url);
		$postsObject = json_decode($json, true);

		$posts = $postsObject['hits'];

		return $posts;
	}

	public function getSavedPostsDb($query) {

		$posts = Post::where('source_type', 'hackernews')
			->where('source_key', $query)
			->skip(0)->take($this->maxNumberDbPostsToFetch)
			->get()->toArray();

		return $posts;

	}

	public function getPostsNotOnDb($postsHN, $postsDb) {

		$posts = [];

		$idPostsDb = Functions::getArrayValuesFromArrayIndex($postsDb, 'external_id');

		foreach ($postsHN as $postHN) {

			$externalId = $postHN['objectID'];
			if (!in_array($externalId, $idPostsDb)) {
				$posts[] = $postHN;
			}

		}

		return $posts;
	}

	public function parsePostsToDb($posts, $query) {

		$postsDb = array();

		foreach ($posts as $post) {

			$postDb['external_id'] = $post['objectID'];
			$postDb['title'] = $post['title'];
			$postDb['text'] = trim($post['url'] . ' ' . $post['story_text']);
			$postDb['url'] = $this->urlBase . '/item?id=' . $post['objectID'];
			$postDb['num_comments'] = $post['num_comments'];
			$postDb['author'] = $post['author'];
			$postDb['rating'] = $post['points'];
			$postDb['source_type'] = $this->sourceType;
			$postDb['source_key'] = $query;
			$postDb['created_at'] = date("Y-m-d H:i:s", $post["created_at_i"]);

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

	public function addNewComments($query) {

		// Get the latest comments
		$commentsHN = $this->getLatestComments($query);

		// Get the saved comments
		$commentsDb = $this->getSavedCommentsDb($query);

		// Filter just the new ones
		$comments = $this->getCommentsNotOnDb($commentsHN, $commentsDb);

		// With the new ones, we parse them
		$comments = $this->parseCommentsToDb($comments, $query);

		// Once parsed, we save them
		$result = $this->saveCommentsToDb($comments);
	}

	public function getLatestComments($query) {

		$url = $this->urlBase . '/r/' . $query . '/comments.json?sort=new';
		$json = file_get_contents($url);
		$commentsObject = json_decode($json, true);

		$comments = $commentsObject['data']['children'];

		return $comments;
	}

	public function getSavedCommentsDb($query) {

		$comments = Comment::where('source_type', 'hackernews')
			->where('source_key', $query)
			->skip(0)->take($this->maxNumberDbPostsToFetch)
			->get()->toArray();

		return $comments;

	}

	public function getCommentsNotOnDb($commentsHN, $commentsDb) {

		$comments = [];

		$idCommentsDb = Functions::getArrayValuesFromArrayIndex($commentsDb, 'external_id');

		foreach ($commentsHN as $commentHN) {

			$externalId = $commentHN['data']['id'];
			if (!in_array($externalId, $idCommentsDb)) {
				$comments[] = $commentHN;
			}

		}

		return $comments;

	}

	public function parseCommentsToDb($comments, $query) {

		$commentsDb = array();

		foreach ($comments as $comment) {

			$comment = $comment['data'];

			$commentDb['external_id'] = $comment['id'];
			$commentDb['reply_to_post_id'] = $this->parseReplyToPostFromLinkId($comment['link_id']);
			$commentDb['reply_to_comment_id'] = $this->parseReplyToCommentHNFromParentId($comment['parent_id']);

			$commentDb['text'] = $comment['body'];
			$commentDb['url'] = $this->urlBase . $comment['permalink'];

			$commentDb['rating'] = $comment['score'];
			$commentDb['source_type'] = $this->sourceType;
			$commentDb['source_key'] = $query;
			$commentDb['created_at'] = date("Y-m-d H:i:s", $comment["created_utc"]);

			$commentsDb[] = $commentDb;
		}

		return $commentsDb;
	}

	public function parseReplyToPostFromLinkId($commentLinkId) {

		$replyToPostId = str_replace('t3_', '', $commentLinkId);
		return $replyToPostId;
	}

	public function parseReplyToCommentHNFromParentId($commentParentId) {

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