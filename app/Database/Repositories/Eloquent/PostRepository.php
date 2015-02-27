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

	/**
	 * @param Post             $postModel The model to use for posts.
	 * @param Guard            $guard     Laravel guard instance, used to get user ID.
	 * @param MessageFormatter $formatter Post formatter instance.
	 */
	public function __construct(
		Post $postModel,
		Guard $guard,
		MessageFormatter $formatter
	) // TODO: Inject permissions container? So we can check post permissions before querying?
	{
		$this->postModel = $postModel;
		$this->guard = $guard;
		$this->formatter = $formatter;
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
	 * @param Topic $topic The thread to fetch the posts for.
	 * @param bool $withTrashed Find trashed posts?
	 *
	 * @return mixed
	 */
	public function allForTopic(Topic $topic, $withTrashed = false)
	{
		if (!$this->guard->check()) {
			// Todo: default to board setting
			$ppp = 10;
		} else {
			$ppp = $this->guard->user()->settings->posts_per_page;
		}

		if($withTrashed)
		{
			return $this->postModel->withTrashed()->with(['author'])->where('topic_id', '=', $topic->id)->paginate($ppp);
		}
		else
		{
			return $this->postModel->with(['author'])->where('topic_id', '=', $topic->id)->paginate($ppp);
		}
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
			// TODO: Auto-populate parsed content with parser once parser is written.
		], $postDetails);

		$postDetails['content_parsed'] = $this->formatter->parse($postDetails['content'], [
			MessageFormatter::ME_USERNAME => $this->guard->user()->name,
		]); // TODO: Parser options...

		if($postDetails['user_id'] > 0)
		{
			$postDetails['username'] = User::find($postDetails['user_id'])->name;
		}
		else
		{
			$postDetails['user_id'] = null;
			if($postDetails['username'] == trans('general.guest'))
			{
				$postDetails['username'] = null;
			}
		}

		$post = $topic->posts()->save(new Post($postDetails));

		if ($post !== false) {
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

		if ($postDetails['content']) {
			$postDetails['content_parsed'] = $this->formatter->parse($postDetails['content'], [
				MessageFormatter::ME_USERNAME => $post->author->name,
			]); // TODO: Parser options...
		}

		$post->update($postDetails);

		return $post;
	}

	/**
	 * Delete posts of topic
	 *
	 * @param Topic $topic The topic that you want to delete its posts
	 *
	 * @return mixed
	 */

	public function deletePostsForTopic(Topic $topic, $force = false)
	{
		if($force)
		{
			return $this->postModel->where('topic_id', '=', $topic->id)->forceDelete();
		}
		else
		{
			return $this->postModel->where('topic_id', '=', $topic->id)->delete();
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
		}
		else
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
			'last_post_id' => $this->postModel->where('topic_id', '=', $topic->id)->orderBy('id', 'desc')->first()->id
		]);
		return $topic;
	}
}
