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
	protected $sourceKey;
	protected $maxNumberDbPostsToFetch = 100;
	protected $apiUrlSearch = 'https://www.googleapis.com/youtube/v3/search?part=id&maxResults=50&type=video';
	protected $apiUrlVideos = 'https://www.googleapis.com/youtube/v3/videos?part=snippet,statistics&id=';
	protected $apiUrlComments = 'https://www.googleapis.com/youtube/v3/commentThreads?part=snippet,replies&maxResults=100&order=time&videoId=';
	protected $urlBase = 'https://www.youtube.com/';

	public function addNewContent($sourceKey, $subtype) {

		$this->initialize($sourceKey, $subtype);
		$this->addNewPosts();
		$this->addNewComments();
	}

	public function initialize($sourceKey, $subtype) {

		$this->sourceKey = $sourceKey;
		$this->apiKey = config('youtube.apiKey');
		$this->subtype = $subtype;
	}

	/**=====================================
	 * POSTS
	 *=====================================**/

	public function addNewPosts() {

		// Get the latest posts
		$postsYouTube = $this->getLatestPosts();

		// Get the saved posts
		$postsDb = $this->getSavedPostsDb();

		// Filter just the new ones
		$posts = $this->getPostsNotOnDb($postsYouTube, $postsDb);

		// With the new ones, we parse them
		$posts = $this->parsePostsToDb($posts);

		// Once parsed, we save them
		$result = $this->savePostsToDb($posts);

	}

	public function getLatestPosts() {

		// First we get the IDs of the videos
		$postIds = $this->getLatestPostsIds();

		// Now we get the whole posts with postIds
		$posts = $this->getPostsFromPostIds($postIds);

		return $posts;
	}

	public function getLatestPostsIds() {

		$paramName = ($this->subtype === 'channel') ? 'channelId' : 's';
		$url = $this->apiUrlSearch . '&' . $paramName . '=' . $this->sourceKey . '&key=' . $this->apiKey;
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

	public function getSavedPostsDb() {

		$posts = Post::where('source_type', 'youtube')
			->where('source_key', $this->sourceKey)
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

	public function parsePostsToDb($posts) {

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
			$postDb['source_key'] = $this->sourceKey;
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

	public function addNewComments() {

		$posts = $this->getPostsForWhichWeWillGetComments();

		foreach ($posts as $post) {

			$this->addNewCommentsPost($post);
		}

		return $posts;

	}

	public function getPostsForWhichWeWillGetComments() {

		$postsDb = $this->getPostsToBeCheckedFromDB();
		$postsYouTube = $this->getPostsUpToDateYouTube($postsDb);
		$postsWithNewComments = $this->getPostsWithNewComments($postsYouTube, $postsDb);

		return $postsWithNewComments;
	}

	public function getPostsToBeCheckedFromDB() {

		$postsToBeChecked = Post::where('source_type', $this->sourceType)
			->where('source_key', $this->sourceKey)->orderBy('num_comments', 'desc')->get()->toArray();
		return $postsToBeChecked;
	}

	public function getPostsUpToDateYouTube($posts) {

		$postIds = Functions::getArrayValuesFromArrayIndex($posts, 'external_id');
		$posts = $this->getPostsFromPostIds($postIds);

		return $posts;
	}

	public function getPostsWithNewComments($postsYouTube, $postsDb) {

		$postsWithNewComments = [];
		foreach ($postsDb as $postDb) {

			foreach ($postsYouTube as $postYouTube) {

				if ($postDb['external_id'] === $postYouTube['id']) {

					if ((int) $postDb['num_comments'] !== (int) $postYouTube['statistics']['commentCount']) {
						$postDb['num_comments_new'] = $postYouTube['statistics']['commentCount'];
						$postsWithNewComments[] = $postDb;
					}

				}
			}

		}

		return $postsWithNewComments;
	}

	public function addNewCommentsPost($post) {

		$commentsPost = $this->getCommentsPost($post);
		if (!$commentsPost) return;

		$commentsPostDB = $this->getCommentsDB($post);
		$comments = $this->getCommentsNotOnDB($commentsPost, $commentsPostDB);

		if (!$comments) return;
		$response = $this->saveCommentsToDB($comments, $post);
	}

	public function getCommentsPost($post) {

		$url = $this->apiUrlComments . $post['external_id'] . '&key=' . $this->apiKey;
		$json = file_get_contents($url);
		$postsObject = json_decode($json, true);
		$threads = $postsObject['items'];
		$comments = $this->parseThreadsToComments($threads);

		return $comments;
	}

	public function parseThreadsToComments($threads) {

		$comments = [];

		foreach ($threads as $thread) {

			$comment = [];
			$comment['external_id'] = $thread['id'];
			$comment['reply_to_post_id'] = $thread['snippet']['videoId'];
			$comment['reply_to_comment_id'] = null;
			$comment['text'] = $thread['snippet']['textDisplay'];
			$comment['url'] = $this->urlBase . 'watch?v=' . $comment['reply_to_post_id'];
			$comment['author'] = $comment['snippet']['authorDisplayName'];
			$comment['rating'] = $comment['snippet']['likeCount'];
			$comment['source_type'] = $this->sourceType;
			$comment['source_key'] = $this->sourceKey;
			$comment['created_at'] = date("Y-m-d H:i:s", strtotime($comment['snippet']['publishedAt']));

			$comments[] = $comment;

			// Let's save the replies
			if (isset($thread['replies']['comments']) && $thread['replies']['comments']) {

				foreach ($thread['replies']['comments'] as $reply) {

					$comment = [];
					$comment['external_id'] = $reply['id'];
					$comment['reply_to_post_id'] = $reply['snippet']['videoId'];
					$comment['reply_to_comment_id'] = $comment['external_id'];
					$comment['text'] = $reply['snippet']['textDisplay'];
					$comment['url'] = $this->urlBase . 'watch?v=' . $comment['reply_to_post_id'];
					$comment['author'] = $reply['snippet']['authorDisplayName'];
					$comment['rating'] = $reply['snippet']['likeCount'];
					$comment['source_type'] = $this->sourceType;
					$comment['source_key'] = $this->sourceKey;
					$comment['created_at'] = date("Y-m-d H:i:s", strtotime($reply['snippet']['publishedAt']));

					$comments[] = $comment;
				}

			}

		}

		return $comments;
	}

	public function getCommentsDB($post) {

		$comments = Comment::where('reply_to_post_id', $post['external_id'])->get()->toArray();
		return $comments;

	}

	public function getCommentsNotOnDb($commentsYouTube, $commentsDb) {

		$comments = [];

		$idCommentsDb = Functions::getArrayValuesFromArrayIndex($commentsDb, 'external_id');

		foreach ($commentsYouTube as $commentYouTube) {

			$externalId = $commentYouTube['id'];
			if (!in_array($externalId, $idCommentsDb)) {
				$comments[] = $commentYouTube;
			}

		}

		return $comments;

	}

	public function saveCommentsToDb($comments) {

		$result = Comment::insert($comments);
		return $result;
	}
}