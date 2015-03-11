<?php
/**
 * Post repository implementation, using Eloquent ORM.
 *
 * @version 2.0.0
 * @author  MyBB Group
 * @license LGPL v3
 */

namespace MyBB\Core\Database\Repositories\Eloquent;

use Illuminate\Contracts\Auth\Guard;
use MyBB\Core\Database\Models\Post;
use MyBB\Core\Database\Models\Topic;
use MyBB\Core\Database\Models\User;
use MyBB\Core\Database\Repositories\IPostRepository;
use MyBB\Parser\MessageFormatter;
use MyBB\Settings\Store;

class PostRepository implements IPostRepository
{
	/**
	 * @var Post $postModel
	 * @access protected
	 */
	protected $postModel;
	/**
	 * @var Guard $guard
	 * @access protected
	 */
	protected $guard;
	/**
	 * @var MessageFormatter $formatter
	 * @access protected
	 */
	protected $formatter;

	/** @var  Store $settings */
	private $settings;

	/**
	 * @param Post             $postModel The model to use for posts.
	 * @param Guard            $guard     Laravel guard instance, used to get user ID.
	 * @param MessageFormatter $formatter Post formatter instance.
	 * @param Store            $settings  The settings container
	 */
	public function __construct(
		Post $postModel,
		Guard $guard,
		MessageFormatter $formatter,
		Store $settings
	) // TODO: Inject permissions container? So we can check post permissions before querying?
	{
		$this->postModel = $postModel;
		$this->guard = $guard;
		$this->formatter = $formatter;
		$this->settings = $settings;
	}

	/**
	 * Find all posts created by a user.
	 *
	 * @param int $userId The ID of the user to get the posts for.
	 *
	 * @return mixed
	 */
	public function allForUser($userId = 0)
	{
		return $this->postModel->where('user_id', '=', $userId)->get();
	}

	public function getNewest($num = 20)
	{
		return $this->postModel->orderBy('created_at', 'desc')->with([
			                                                             'topic',
			                                                             'topic.forum',
			                                                             'author'
		                                                             ])->take($num)->get();
	}

	/**
	 * Find a single post by its ID.
	 *
	 * @param int $id The ID of the post to find.
	 *
	 * @return mixed
	 */
	public function find($id = 0)
	{
		return $this->postModel->withTrashed()->find($id);
	}

	/**
	 * Get all posts for a thread.
	 *
	 * @param Topic $topic       The thread to fetch the posts for.
	 * @param bool  $withTrashed Find trashed posts?
	 *
	 * @return mixed
	 */
	public function allForTopic(Topic $topic, $withTrashed = false)
	{
		$postsPerPage = $this->settings->get('user.posts_per_page', 10);

		$baseQuery = $this->postModel->with(['author'])->where('topic_id', '=', $topic->id);

		if($withTrashed)
		{
			$baseQuery = $baseQuery->withTrashed();
		}

		return $baseQuery->paginate($postsPerPage);
	}

	/**
	 * Get's the number of this post in the thread. Eg '1' for the first, '2' for the second etc
	 *
	 * @param Post $post        The post we want the number for
	 * @param bool $withTrashed Count trashed posts?
	 *
	 * @return int
	 */
	public function getNumForPost(Post $post, $withTrashed = false)
	{
		// Get all posts in this thread created before this one...
		$baseQuery = $this->postModel->where('topic_id', '=', $post->topic_id)
		                             ->where('created_at', '<', $post->created_at);

		if($withTrashed)
		{
			$baseQuery = $baseQuery->withTrashed();
		}

		// ... and add 1 (the first post doesn't have one created before it etc)
		return $baseQuery->count() + 1;
	}

	/**
	 * Add a post to a topic.
	 *
	 * @param Topic $topic       The topic to add a post to.
	 * @param array $postDetails The details of the post to add.
	 *
	 * @return mixed
	 */
	public function addPostToTopic(Topic $topic, array $postDetails)
	{

		$postDetails = array_merge([
			                           'user_id' => $this->guard->user()->id,
			                           'username' => null,
			                           'content' => '',
			                           'content_parsed' => '',
		                           ], $postDetails);

		$postDetails['content_parsed'] = $this->formatter->parse($postDetails['content'], [
			MessageFormatter::ME_USERNAME => $this->guard->user()->name,
		]); // TODO: Parser options...

		if($postDetails['user_id'] > 0)
		{
			$postDetails['username'] = User::find($postDetails['user_id'])->name;
		} else
		{
			$postDetails['user_id'] = null;
			if($postDetails['username'] == trans('general.guest'))
			{
				$postDetails['username'] = null;
			}
		}

		$post = $topic->posts()->create($postDetails);

		if($post !== false)
		{
			$topic->increment('num_posts');
			$topic->forum->increment('num_posts');
			$topic->update([
				               'last_post_id' => $post['id']
			               ]);
		}

		if($post->user_id > 0)
		{
			$post->author->increment('num_posts');
		}

		return $post;
	}

	/**
	 * Edit a post
	 *
	 * @param Post  $post        The post to edit
	 * @param array $postDetails The details of the post to add.
	 *
	 * @return mixed
	 */
	public function editPost(Post $post, array $postDetails)
	{
		if($postDetails['content'])
		{
			$options = [];
			if($post->user_id > 0)
			{
				$options[MessageFormatter::ME_USERNAME] = $post->author->name;
			} else
			{
				$options[MessageFormatter::ME_USERNAME] = trans('general.guest');
			}

			$postDetails['content_parsed'] = $this->formatter->parse($postDetails['content'],
			                                                         $options); // TODO: Parser options...
		}

		$post->update($postDetails);

		return $post;
	}

	/**
	 * Delete posts of topic
	 *
	 * @param Topic $topic The topic that you want to delete its posts
	 * @param bool  $force Whether to force a hard delete of the post.
	 *
	 * @return mixed
	 */

	public function deletePostsForTopic(Topic $topic, $force = false)
	{
		$baseQuery = $this->postModel->where('topic_id', '=', $topic->id);

		if($force)
		{
			return $baseQuery->forceDelete();
		} else
		{
			return $baseQuery->delete();
		}
	}

	/**
	 * Delete a post
	 *
	 * @param Post $post The post to delete
	 *
	 * @return mixed
	 */

	public function deletePost(Post $post)
	{
		if($post['deleted_at'] == null)
		{
			$post->topic->decrement('num_posts');
			$post->author->decrement('num_posts');

			return $post->delete();
		} else
		{
			return $post->forceDelete();
		}
	}

	/**
	 * Restore a post
	 *
	 * @param Post $post The post to restore
	 *
	 * @return mixed
	 */

	public function restorePost(Post $post)
	{
		$post->topic->increment('num_posts');
		$post->author->increment('num_posts');

		return $post->restore();
	}

	/**
	 * Update the last post of the topic
	 *
	 * @param Topic $topic The topic to update
	 *
	 * @return mixed
	 */

	public function updateLastPost(Topic $topic)
	{
		$topic->update([
			               'last_post_id' => $this->postModel->where('topic_id', '=', $topic->id)->orderBy('id', 'desc')
			                                                 ->first()->id
		               ]);

		return $topic;
	}
}
