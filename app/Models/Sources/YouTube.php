<?php

namespace App\Models\Sources;

use App\Lib\Functions;
use App\Models\Comment;
use App\Models\Post;
use Illuminate\Database\Eloquent\Model;

class YouTube extends Model {

	protected $sourceType = 'youtube';
	protected $subtype = 'channel'; // default, can be also 'search'
	protected $apiKey;
	protected $maxNumberDbPostsToFetch = 100;
	protected $apiUrlSearch = 'https://www.googleapis.com/youtube/v3/search?part=id&maxResults=50&type=video';
	protected $apiUrlVideos = 'https://www.googleapis.com/youtube/v3/videos?part=snippet,statistics&id=';
	protected $urlBase = 'https://www.youtube.com/';

	public function addNewContent($query, $subtype) {

		$this->initialize($subtype);
		$this->addNewPosts($query);
		//$this->addNewComments($query);
	}

	public function initialize($subtype) {

		$this->apiKey = config('youtube.apiKey');
		$this->subtype = $subtype;
	}

	/**=====================================
	 * POSTS
	 *=====================================**/

	public function addNewPosts($query) {

		// Get the latest posts
		$postsYouTube = $this->getLatestPosts($query);

		// Get the saved posts
		$postsDb = $this->getSavedPostsDb($query);

		// Filter just the new ones
		$posts = $this->getPostsNotOnDb($postsYouTube, $postsDb);

		// With the new ones, we parse them
		$posts = $this->parsePostsToDb($posts, $query);

		// Once parsed, we save them
		$result = $this->savePostsToDb($posts);

	}

	public function getLatestPosts($query) {

		// First we get the IDs of the videos
		$postIds = $this->getLatestPostsIds($query);

		// Now we get the whole posts with postIds
		$posts = $this->getPostsFromPostIds($postIds);

		return $posts;
	}

	public function getLatestPostsIds($query) {

		$paramName = ($this->subtype === 'channel') ? 'channelId' : 's';
		$url = $this->apiUrlSearch . '&' . $paramName . '=' . $query . '&key=' . $this->apiKey;
		$json = file_get_contents($url);
		$postsObject = json_decode($json, true);
		$posts = $postsObject['items'];

		$postIds =  [];
		foreach ($posts as $post) {
			if (isset($post['id']))
			$postIds[] = $post['id']['videoId'];
		}

		return $postIds;
	}

	public function getPostsFromPostIds($postIds) {

		$url = $this->apiUrlVideos . implode(',', $postIds) . '&key=' . $this->apiKey;
		$json = file_get_contents($url);
		$postsObject = json_decode($json, true);
		$posts = $postsObject['items'];

		return $posts;
	}

	public function getSavedPostsDb($query) {

		$posts = Post::where('source_type', 'youtube')
			->where('source_key', $query)
			->skip(0)->take($this->maxNumberDbPostsToFetch)
			->get()->toArray();

		return $posts;

	}

	public function getPostsNotOnDb($postsYouTube, $postsDb) {

		$posts = [];

		$idPostsDb = Functions::getArrayValuesFromArrayIndex($postsDb, 'external_id');

		foreach ($postsYouTube as $postYouTube) {

			$externalId = $postYouTube['id'];
			if (!in_array($externalId, $idPostsDb)) {
				$posts[] = $postYouTube;
			}

		}

		return $posts;
	}

	public function parsePostsToDb($posts, $query) {

		$postsDb = array();

		foreach ($posts as $post) {

			$snippet = $post['snippet'];
			$postDb['external_id'] = $post['id'];
			$postDb['title'] = $snippet['title'];
			$postDb['text'] = $snippet['description'];
			$postDb['url'] = $this->urlBase . 'watch?v=' . $post['id'];
			$postDb['num_comments'] = $post['statistics']['commentCount'];
			$postDb['author'] = $snippet['channelTitle'];

			$postDb['thumbnail'] = isset($snippet['thumbnails']['high']['url']) ? $snippet['thumbnails']['high']['url'] : null;
			$postDb['image'] = isset($snippet['thumbnails']['maxres']['url']) ? $snippet['thumbnails']['maxres']['url'] : $postDb['thumbnail'];

			$postDb['rating'] = $post['statistics']['likeCount'];
			$postDb['source_type'] = $this->sourceType;
			$postDb['source_key'] = $query;
			$postDb['created_at'] = date("Y-m-d H:i:s", strtotime($snippet["publishedAt"]));

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